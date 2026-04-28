=== OWBN Timezone Plugin ===
Contributors: greghacke
Tags: timezone, datetime, user-profile
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 0.1.0
License: GPL-2.0-or-later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Auto-detects the viewer's timezone, persists it on user profiles, and shifts every displayed date/time into the viewer's local zone.

== Description ==

* Auto-detects browser timezone via `Intl.DateTimeFormat`.
* Persists the detected zone on logged-in users (user meta `owbn_user_timezone`); visitors get a cookie (`owbn_tz`).
* User profile timezone selector + frontend `[owbn_tz_picker]` shortcode.
* Filters `get_the_date`, `get_the_modified_date`, `get_the_time`, `get_the_modified_time`, `get_comment_date`, `get_comment_time` to render in the viewer's zone.
* Resolution order: user meta → cookie → site default.

== Changelog ==

= 0.1.0 =
* Initial release.
