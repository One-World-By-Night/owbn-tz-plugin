# OWBN Timezone Plugin

Auto-detects the viewer's timezone and shifts every displayed date/time on the site into their local zone.

## What it does

1. **Auto-detect** — JS reads `Intl.DateTimeFormat().resolvedOptions().timeZone` on every page and writes it to the `owbn_tz` cookie (1 year).
2. **Persist on profile** — For logged-in users, the first detected zone is saved to user meta `owbn_user_timezone` (only if not already set, so manual overrides win).
3. **Set/change** — User profile screen has a Timezone field (full WP `wp_timezone_choice()` list). Frontend shortcode `[owbn_tz_picker]` lets visitors and users pick a zone.
4. **Display shift** — All post / comment dates and times are filtered through the viewer's zone (resolution order: user meta → cookie → site default).

## Resolution order per request

| Source        | Used when                                       |
|---------------|-------------------------------------------------|
| User meta     | Logged-in user has set a value                  |
| Cookie        | Visitor (or logged-in user without user meta)   |
| Site default  | No detection yet (`wp_timezone_string()` → UTC) |

All OWBN servers store dates in UTC, so this is a clean UTC → local shift with no double-conversion risk.

## Helpers (PHP)

```php
owbn_tz_get_timezone_string();   // e.g. "America/New_York"
owbn_tz_get_timezone();          // DateTimeZone instance
owbn_tz_format($ts_or_str, $fmt); // wp_date()-formatted string in viewer TZ
```

## Shortcodes

```
[owbn_tz_date date="2026-04-28 18:00:00" format="F j, Y g:i a"]
[owbn_tz_date date="now"]
[owbn_tz_picker]
```

Date strings without an explicit offset are assumed UTC.

## REST endpoint

`POST /wp-json/owbn-tz/v1/set` — body `{ "timezone": "America/Chicago", "auto": false }`.
Requires `X-WP-Nonce`. `auto: true` only writes user meta if it is currently empty.

## Filters covered

- `get_the_date`, `get_the_modified_date`
- `get_the_time`, `get_the_modified_time`
- `get_comment_date`, `get_comment_time`

Filter scope is **global** — every theme/plugin that pulls dates through these filters automatically renders in the viewer's zone.
