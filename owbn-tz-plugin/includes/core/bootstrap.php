<?php

defined('ABSPATH') || exit;

define('OWBN_TZ_TEXT_DOMAIN', 'owbn-tz');
define('OWBN_TZ_META_KEY', 'owbn_user_timezone');
define('OWBN_TZ_COOKIE', 'owbn_tz');
define('OWBN_TZ_REST_NS', 'owbn-tz/v1');
define('OWBN_TZ_COOKIE_DAYS', 365);

add_action('init', function () {
    load_plugin_textdomain(OWBN_TZ_TEXT_DOMAIN, false, dirname(OWBN_TZ_BASENAME) . '/languages');
});
