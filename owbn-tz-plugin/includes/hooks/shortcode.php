<?php

defined('ABSPATH') || exit;

add_shortcode('owbn_tz_date', function ($atts) {
    $atts = shortcode_atts([
        'date'   => '',
        'format' => '',
    ], $atts, 'owbn_tz_date');

    if ($atts['date'] === '' || strtolower($atts['date']) === 'now') {
        $ts = time();
    } else {
        $ts = $atts['date'];
    }
    return esc_html(owbn_tz_format($ts, $atts['format']));
});

add_shortcode('owbn_tz_picker', function () {
    $current = '';
    if (is_user_logged_in()) {
        $current = (string) get_user_meta(get_current_user_id(), OWBN_TZ_META_KEY, true);
    }
    if ($current === '' && !empty($_COOKIE[OWBN_TZ_COOKIE])) {
        $current = (string) wp_unslash($_COOKIE[OWBN_TZ_COOKIE]);
    }

    ob_start();
    ?>
    <form class="owbn-tz-picker" data-owbn-tz-picker>
        <label>
            <?php esc_html_e('Timezone:', OWBN_TZ_TEXT_DOMAIN); ?>
            <select name="owbn_tz">
                <option value=""><?php esc_html_e('— Auto-detect —', OWBN_TZ_TEXT_DOMAIN); ?></option>
                <?php echo wp_timezone_choice($current); ?>
            </select>
        </label>
        <button type="submit"><?php esc_html_e('Save', OWBN_TZ_TEXT_DOMAIN); ?></button>
        <span class="owbn-tz-picker-status" aria-live="polite"></span>
    </form>
    <?php
    return ob_get_clean();
});
