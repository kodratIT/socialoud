# Dashboard Management Access Design

## Goal

Restrict administrative management areas so only `kodratcoc@gmail.com` can use Tools, Settings, Appearance, and Plugins. Other authenticated dashboard users must be denied even when they open a restricted URL directly.

## Scope

Restricted dashboard route prefixes:

- `tools.*`
- `settings.*`
- `theme.*`
- `plugins.*`
- `sitemap.settings*`, `slug.settings*`, and `optimize.settings*` (settings pages registered outside the core `settings.*` namespace)

Restricted dashboard menu IDs include the four top-level groups and their registered children:

- `cms-core-tools`
- `cms-core-settings`
- `cms-core-appearance`
- `cms-core-plugins`
- Appearance children: `cms-core-menu`, `cms-core-widget`, `cms-core-theme`, `cms-core-theme-option`, `cms-core-appearance-custom-css`, `cms-core-appearance-custom-js`, `cms-core-appearance-custom-html`, and `cms-core-appearance-robots-txt`
- Plugin children: `cms-core-plugins-installed` and `cms-core-plugins-marketplace`

All other dashboard routes and permissions remain unchanged.

## Design

Add one application access policy containing the allowlisted email, restricted route prefixes, and restricted menu IDs. Apply it in two places:

1. An admin route middleware attached through `AdminHelper`, after authentication, returns HTTP 403 for non-allowlisted users on restricted route names.
2. Dashboard menu and system-panel filters remove the four restricted menu groups for non-allowlisted users.

The email comparison is case-insensitive and trims whitespace. The allowlist is intentionally explicit because this is an owner-only access rule, not a replacement for the existing role/permission system.

## Error handling

Unauthenticated requests continue through the existing authentication flow. Authenticated non-allowlisted users receive the existing Laravel 403 response. The policy does not change permissions for public routes, login routes, or unrelated dashboard features.

## Verification

Add focused unit coverage for allowlisted and non-allowlisted users plus restricted/unrestricted route names. Run the unit test and inspect route/menu behavior with the Laravel application configuration.
