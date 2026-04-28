<?php

defined('ABSPATH') || exit;

// Posts: shift display dates/times to viewer timezone.
add_filter('get_the_date',          'owbn_tz_filter_post_date',          10, 3);
add_filter('get_the_modified_date', 'owbn_tz_filter_post_modified_date', 10, 3);
add_filter('get_the_time',          'owbn_tz_filter_post_time',          10, 3);
add_filter('get_the_modified_time', 'owbn_tz_filter_post_modified_time', 10, 3);

function owbn_tz_filter_post_date($the_date, $format, $post) {
    return owbn_tz_shift_post($the_date, $format, $post, 'date', 'date_format');
}
function owbn_tz_filter_post_modified_date($the_date, $format, $post) {
    return owbn_tz_shift_post($the_date, $format, $post, 'modified', 'date_format');
}
function owbn_tz_filter_post_time($the_time, $format, $post) {
    return owbn_tz_shift_post($the_time, $format, $post, 'date', 'time_format');
}
function owbn_tz_filter_post_modified_time($the_time, $format, $post) {
    return owbn_tz_shift_post($the_time, $format, $post, 'modified', 'time_format');
}

function owbn_tz_shift_post($fallback, $format, $post, $field, $default_option) {
    $post = get_post($post);
    if (!$post) return $fallback;
    $ts = function_exists('get_post_timestamp') ? get_post_timestamp($post, $field) : false;
    if (!$ts) {
        $gmt = $field === 'modified' ? $post->post_modified_gmt : $post->post_date_gmt;
        if (!$gmt || $gmt === '0000-00-00 00:00:00') return $fallback;
        $ts = strtotime($gmt . ' UTC');
    }
    if (!$ts) return $fallback;
    if (!$format) $format = get_option($default_option);
    return wp_date($format, $ts, owbn_tz_get_timezone());
}

// Comments
add_filter('get_comment_date', 'owbn_tz_filter_comment_date', 10, 3);
add_filter('get_comment_time', 'owbn_tz_filter_comment_time', 10, 5);

function owbn_tz_filter_comment_date($date, $format, $comment) {
    if (!$comment || empty($comment->comment_date_gmt)) return $date;
    $ts = strtotime($comment->comment_date_gmt . ' UTC');
    if (!$ts) return $date;
    if (!$format) $format = get_option('date_format');
    return wp_date($format, $ts, owbn_tz_get_timezone());
}

function owbn_tz_filter_comment_time($time, $format, $gmt, $translate, $comment) {
    if ($gmt) return $time;
    if (!$comment || empty($comment->comment_date_gmt)) return $time;
    $ts = strtotime($comment->comment_date_gmt . ' UTC');
    if (!$ts) return $time;
    if (!$format) $format = get_option('time_format');
    return wp_date($format, $ts, owbn_tz_get_timezone());
}
