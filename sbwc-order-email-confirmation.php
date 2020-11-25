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
    require_once(SBWC_OEC_PATH . 'functions/sbwc-oec-settings.php');
    require_once(SBWC_OEC_PATH . 'functions/sbwc-oec-add-field.php');

    // register pll strings
    if (function_exists('pll_register_string')) :
        pll_register_string('sbwc_oec_1', 'Confirm email');
        pll_register_string('sbwc_oec_2', 'Email addresses do not match');
        pll_register_string('sbwc_oec_3', 'Email addresses match');
        pll_register_string('sbwc_oec_4', 'Email address does not seem to be valid');
        pll_register_string('sbwc_oec_5', 'There are errors with your email address which needs to be fixed before you will be able to check out.');
        pll_register_string('sbwc_oec_6', 'Checkout Email Confirmation Settings');
        pll_register_string('sbwc_oec_7', 'Currently defined file: ');
        pll_register_string('sbwc_oec_8', 'Upload Sparkpost CSV file:*');
        pll_register_string('sbwc_oec_9', 'Upload Sparkpost CSV file here. This file is required and will be used to check for non-existent and invalid domains during email submission on the checkout page.');
        pll_register_string('sbwc_oec_10', 'File successfully uploaded.');
    endif;
}
