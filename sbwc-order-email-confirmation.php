<?php

/**
 * Plugin Name: SBWC Order Email Confirmation
 * Description: Adds email confirmation input to checkout form. Also corrects incorrectly entered email addresses.
 * Author: WC Bessinger
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) :
    exit();
endif;

// init plugin functions etc
add_action('plugins_loaded', 'sbwc_oec_init');

function sbwc_oec_init()
{

    // constants
    define('SBWC_OEC_PATH', plugin_dir_path(__FILE__));
    define('SBWC_OEC_URL', plugin_dir_url(__FILE__));

    // requires
    require_once(SBWC_OEC_PATH.'functions/sbwc-oec-settings.php');
    require_once(SBWC_OEC_PATH.'functions/sbwc-oec-add-field.php');

}
