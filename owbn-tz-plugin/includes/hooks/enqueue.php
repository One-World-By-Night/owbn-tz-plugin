<?php

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'owbn_tz_enqueue_detect');
add_action('admin_enqueue_scripts', 'owbn_tz_enqueue_detect');

function owbn_tz_enqueue_detect() {
    wp_enqueue_script(
        'owbn-tz-detect',
        OWBN_TZ_URL . 'includes/assets/detect.js',
        [],
        OWBN_TZ_VERSION,
        true
    );

    $user_id = get_current_user_id();
    $has_user_meta = $user_id ? (bool) get_user_meta($user_id, OWBN_TZ_META_KEY, true) : false;

    wp_localize_script('owbn-tz-detect', 'owbnTzConfig', [
        'restUrl'     => esc_url_raw(rest_url(OWBN_TZ_REST_NS . '/set')),
        'nonce'       => wp_create_nonce('wp_rest'),
        'isLoggedIn'  => (bool) $user_id,
        'hasUserMeta' => $has_user_meta,
        'cookieName'  => OWBN_TZ_COOKIE,
        'cookieDays'  => OWBN_TZ_COOKIE_DAYS,
    ]);
}
