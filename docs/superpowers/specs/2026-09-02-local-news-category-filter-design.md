# Local News Category Filter Design

## Goal

Make the Local News archive consistent: the parent and `Semua` filter show posts from the parent plus all descendants, while a child filter shows only posts assigned to that child. The post card category label follows the active filter.

## Data flow

The existing category archive route remains the page entry point. The category-posts endpoint receives the page's parent category plus an optional selected category ID. `all=1` expands the parent to all active descendants recursively; a selected child ID queries only that category. The selected ID is validated as the parent or one of its active descendants.

The archive template sends each filter's ID in `data-category-id`. The runtime sends that ID for every filter request and sends `all=1` only for `Semua`. The endpoint renders the list with the selected category as the card label.

## Loading behavior

Only the news-list region gets loading placeholders. Filter changes replace the list with a fixed number of skeleton rows. Load-more keeps existing rows and appends skeleton rows below them until the response arrives. Other page regions remain untouched. Failed requests restore the prior list and leave the load-more control usable.

## Verification

Run the application at `http://localhost:8000` and verify parent, `Semua`, child-only filters, card labels, filter skeletons, and appended load-more skeletons. Add a focused regression test for category ID selection and recursive parent aggregation where the existing test harness supports it.
