<?php

defined('ABSPATH') || exit;

require_once __DIR__ . '/core/bootstrap.php';
require_once __DIR__ . '/core/helpers.php';

require_once __DIR__ . '/fields.php';

require_once __DIR__ . '/hooks/enqueue.php';
require_once __DIR__ . '/hooks/rest-api.php';
require_once __DIR__ . '/hooks/filters.php';
require_once __DIR__ . '/hooks/shortcode.php';

do_action('owbn_tz_loaded');
