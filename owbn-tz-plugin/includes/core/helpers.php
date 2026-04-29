<?php

defined('ABSPATH') || exit;

function owbn_tz_validate($tz) {
    if (!is_string($tz) || $tz === '') return null;
    try { new DateTimeZone($tz); return $tz; }
    catch (Exception $e) { return null; }
}

function owbn_tz_get_user_timezone($user_id = 0) {
    $user_id = $user_id ?: get_current_user_id();
    if (!$user_id) return null;
    return owbn_tz_validate(get_user_meta($user_id, OWBN_TZ_META_KEY, true));
}

function owbn_tz_get_visitor_timezone() {
    if (empty($_COOKIE[OWBN_TZ_COOKIE])) return null;
    return owbn_tz_validate(wp_unslash($_COOKIE[OWBN_TZ_COOKIE]));
}

function owbn_tz_get_timezone_string() {
    // Only resolve viewer-specific TZ for logged-in users. Anonymous visitors
    // always get site TZ so page caches (WPSC, SG edge) stay coherent — anon
    // requests with arbitrary owbn_tz cookies must not vary cached HTML.
    if (is_user_logged_in()) {
        $tz = owbn_tz_get_user_timezone();
        if ($tz) return $tz;
        $tz = owbn_tz_get_visitor_timezone();
        if ($tz) return $tz;
    }
    $site = wp_timezone_string();
    return $site ?: 'UTC';
}

function owbn_tz_get_timezone() {
    return new DateTimeZone(owbn_tz_get_timezone_string());
}

function owbn_tz_format($input, $format = '') {
    if ($format === '') {
        $format = get_option('date_format') . ' ' . get_option('time_format');
    }
    if (is_numeric($input)) {
        $ts = (int) $input;
    } elseif ($input instanceof DateTimeInterface) {
        $ts = $input->getTimestamp();
    } elseif (is_string($input) && $input !== '') {
        $ts = strtotime($input . (preg_match('/(Z|[+-]\d{2}:?\d{2}|UTC|GMT)$/i', $input) ? '' : ' UTC'));
    } else {
        return '';
    }
    if (!$ts) return '';
    return wp_date($format, $ts, owbn_tz_get_timezone());
}
