<?php
/**
 * Plugin Name: OWBN Timezone Plugin
 * Plugin URI: https://github.com/One-World-By-Night/owbn-tz-plugin
 * Description: Auto-detects visitor timezone, stores it on user profiles, and shifts all displayed dates/times into the viewer's local timezone.
 * Version: 0.2.2
 * Author: greghacke
 * Author URI: https://www.owbn.net
 * License: GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: owbn-tz
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

define('OWBN_TZ_VERSION', '0.2.2');
define('OWBN_TZ_PATH', plugin_dir_path(__FILE__));
define('OWBN_TZ_URL', plugin_dir_url(__FILE__));
define('OWBN_TZ_BASENAME', plugin_basename(__FILE__));

require_once OWBN_TZ_PATH . 'includes/init.php';

register_activation_hook(__FILE__, function () {
    update_option('owbn_tz_version', OWBN_TZ_VERSION);
});
