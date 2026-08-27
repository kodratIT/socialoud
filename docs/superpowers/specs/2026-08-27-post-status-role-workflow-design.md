# Post Status Role Workflow Design

## Goal

Limit selectable and submitted post statuses by newsroom role without changing existing permissions or status storage.

## Rules

- `wartawan`: `draft`
- `redaktur`: `draft`, `pending`
- `pimred`: `draft`, `pending`, `published`
- Other roles, including `Admin`: retain all existing statuses.
- If a user has multiple newsroom roles, the highest workflow role wins: `pimred` > `redaktur` > `wartawan`.

Role matching uses the role name, not the database slug, because the current `redaktur` row has slug `wartawan-1`.

## Design

Add one blog status workflow helper that returns allowed status values for the authenticated user. `PostForm` uses it to build the status select choices. `PostRequest` uses the same helper to reject forged or manually submitted values outside the user's allowed set.

The existing `BaseStatusEnum` remains the source of status constants and labels. Admin and unknown roles keep the current unrestricted behavior for backward compatibility.

## Error handling

Invalid statuses continue to fail normal Laravel validation. A valid global status submitted by a restricted newsroom role fails validation when it is outside that role's allowed set.

## Verification

Run PHP syntax checks and a focused CLI smoke check for the three newsroom roles plus Admin. Confirm both generated form choices and request rule behavior use the same allowed values.
