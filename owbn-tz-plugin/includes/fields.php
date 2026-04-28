<?php

defined('ABSPATH') || exit;

add_action('show_user_profile', 'owbn_tz_render_profile_field');
add_action('edit_user_profile', 'owbn_tz_render_profile_field');

function owbn_tz_render_profile_field($user) {
    $current = get_user_meta($user->ID, OWBN_TZ_META_KEY, true);
    ?>
    <h3><?php esc_html_e('Timezone', OWBN_TZ_TEXT_DOMAIN); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="owbn_tz"><?php esc_html_e('Timezone', OWBN_TZ_TEXT_DOMAIN); ?></label></th>
            <td>
                <select name="owbn_tz" id="owbn_tz">
                    <option value=""><?php esc_html_e('— Auto-detect from browser —', OWBN_TZ_TEXT_DOMAIN); ?></option>
                    <?php echo wp_timezone_choice($current, get_user_locale($user->ID)); ?>
                </select>
                <p class="description">
                    <?php esc_html_e('All dates and times shown to you across the site will use this timezone. Leave blank to auto-detect from the browser.', OWBN_TZ_TEXT_DOMAIN); ?>
                </p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'owbn_tz_save_profile_field');
add_action('edit_user_profile_update', 'owbn_tz_save_profile_field');

function owbn_tz_save_profile_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) return;
    if (!isset($_POST['owbn_tz'])) return;

    $value = sanitize_text_field(wp_unslash($_POST['owbn_tz']));
    if ($value === '') {
        delete_user_meta($user_id, OWBN_TZ_META_KEY);
        return;
    }
    $valid = owbn_tz_validate($value);
    if ($valid) {
        update_user_meta($user_id, OWBN_TZ_META_KEY, $valid);
    }
}
