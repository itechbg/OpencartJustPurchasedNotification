# Just Purchased Notification - Complete Remake for OpenCart 3.x and 4.x

This folder contains a full rewrite of the old OpenCart 1.x module with backward-compatible core behavior plus new extension features.

## What Was Preserved from v1.0

- Recent purchased-product notifications sourced from real orders.
- Filtering by order status.
- Optional shuffle mode.
- Configurable message template placeholders.
- Time-ago text with minutes/hours/days templates.
- Hide old time-ago text threshold.
- Popup style controls (colors, image dimensions, timing).
- Optional click-anywhere redirection.
- Cache support and cache clear action.

## New Features Added in the Remake

- Native OpenCart 3.x and 4.x structure and routes.
- Footer auto-inject option (event-driven).
- Cache TTL support.
- Excluded product IDs list.
- Position controls (left/right bottom).
- Motion type controls (slide/fade/none).
- Start delay before first popup.
- Offset controls for mobile/desktop tuning.
- Debug mode for browser console diagnostics.
- Vanilla JavaScript popup queue (no legacy notify dependency).

## Package Layout

- `opencart3.x/upload/...` for OpenCart 3.x.
- `opencart4.x/upload/...` for OpenCart 4.x.
- `opencart4.x/install.json` metadata for OC4 package manager.

## Installation - OpenCart 3.x

1. Create a zip where the root contains the `upload` folder from `remake/opencart3.x`.
2. In admin, go to Extension Installer and upload the zip.
3. Go to Extensions > Extensions > Modules and install `Just Purchased Notification (Remake)`.
4. Open the module, configure settings, enable status, save.
5. If using manual placement, assign the module via Layouts. If using auto-inject, keep `Auto inject in footer` enabled.

## Installation - OpenCart 4.x

1. Create a zip where the root contains:
   - `install.json`
   - `upload/`
2. Upload from Extensions > Installer.
3. Go to Extensions > Extensions > Modules and install `Just Purchased Notification (Remake)`.
4. Open module settings, configure and save.

## Placeholders Supported in Message Template

- `{country}`
- `{zone}`
- `{city}`
- `{quantity}`
- `{product_name}`
- `{product_link}`
- `{product_with_link}`
- `{time_ago}`

## Notes

- The module reads `shipping_country`, `shipping_zone`, and `shipping_city` from order data.
- Notifications are based on order products and visible only for enabled products in the current store and language.
- If your theme has custom footer rendering, keep manual layout placement as fallback.
