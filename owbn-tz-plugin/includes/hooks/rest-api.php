<?php

defined('ABSPATH') || exit;

add_action('rest_api_init', function () {
    register_rest_route(OWBN_TZ_REST_NS, '/set', [
        'methods'             => 'POST',
        'callback'            => 'owbn_tz_rest_set',
        'permission_callback' => '__return_true',
        'args'                => [
            'timezone' => ['required' => true, 'type' => 'string'],
            'auto'     => ['required' => false, 'type' => 'boolean', 'default' => false],
        ],
    ]);
});

function owbn_tz_rest_set($req) {
    $tz = owbn_tz_validate($req->get_param('timezone'));
    if (!$tz) {
        return new WP_Error('owbn_tz_invalid', 'Invalid timezone', ['status' => 400]);
    }

    $user_id = get_current_user_id();
    $stored  = false;

    if ($user_id) {
        $existing = get_user_meta($user_id, OWBN_TZ_META_KEY, true);
        $is_auto  = (bool) $req->get_param('auto');
        if (!$is_auto || empty($existing)) {
            update_user_meta($user_id, OWBN_TZ_META_KEY, $tz);
            $stored = true;
        }
    }

    return [
        'timezone' => $tz,
        'stored'   => $stored,
    ];
}
