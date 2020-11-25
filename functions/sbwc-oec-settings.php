<?php

/**
 * Admin settings page
 */



// settings page for uploading CSV file to use for checks
add_menu_page('Checkout Email Confirmation Settings', 'Order Email Confirmation', 'manage_options', 'sbwc-oec-settings', 'sbwc_oec_settings', 'dashicons-email-alt2');

// render settings page
function sbwc_oec_settings()
{ ?>
    <div id="sbwc_oec_settings">
        <h2><?php pll_e('Checkout Email Confirmation Settings'); ?></h2>
        <hr>

        <!-- csv file upload -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="sbwc_oec_settings_inner">

                <?php if (get_option('sbwc_oec_file_link')) : ?>
                    <label for="sbwc_oec_csv_file_upload"><?php pll_e('Currently defined file: '); echo get_option('sbwc_oec_file_link'); ?></label>
                <?php else : ?>
                    <label for="sbwc_oec_csv_file_upload"><?php pll_e('Upload Sparkpost CSV file:*'); ?></label>
                <?php endif; ?>

                <input type="file" name="sbwc_oec_csv_file_upload" id="sbwc_oec_csv_file_upload" required>
                <span><?php pll_e('Upload Sparkpost CSV file here. This file is required and will be used to check for non-existent and invalid domains during email submission on the checkout page.'); ?></span>
            </div>
            <input id="sbwc_oec_settings_submit" name="sbwc_oec_settings_submit" type="submit" value="<?php pll_e('Submit'); ?>">
        </form>

    </div>

    <?php
    // upload csv file
    if (isset($_POST['sbwc_oec_settings_submit'])) :

        // make sure wp_handle_upload is loaded
        if (!function_exists('wp_handle_upload')) :
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        endif;

        // get file
        $uploadedfile = $_FILES['sbwc_oec_csv_file_upload'];

        // upload overrides
        $upload_overrides = [
            'test_form' => false
        ];

        // move file to uploads directory
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        // if successful, display success message, else display error message
        if ($movefile && !isset($movefile['error'])) :
            if(add_option('sbwc_oec_file_link', $movefile['url'])): ?>
            <span id="sbwc_oec_upload_success">
                <?php pll_e('File successfully uploaded.'); ?>
            </span>
            <?php endif;
        else :
            /*
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            echo $movefile['error'];
        endif;

    endif;

    ?>

    <!-- css -->
    <style>
        div#sbwc_oec_settings {
            overflow: auto;
            width: 50%;
            min-width: 360px;
        }

        .sbwc_oec_settings_inner label {
            display: block;
            line-height: 2;
            font-weight: 600;
            font-size: 14px;
        }

        .sbwc_oec_settings_inner input {
            display: block;
            line-height: 2;
        }

        .sbwc_oec_settings_inner span {
            font-weight: 500;
            font-style: italic;
        }

        #sbwc_oec_settings_submit {
            background: #0073aa;
            color: white;
            padding: 9px 15px;
            display: inline-block;
            text-align: center;
            text-transform: uppercase;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            margin-top: 15px;
            width: 150px;
            border-radius: 2px;
            border: none;
            cursor: pointer;
        }

        span#sbwc_oec_upload_success {
            font-weight: 500;
            line-height: 3;
            border-left: 5px solid green;
            padding: 5px;
            position: relative;
            top: 10px;
            border-bottom: 1px solid green;
            color: green;
        }

        span#sbwc_oec_upload_fail {
            font-weight: 500;
            line-height: 3;
            border-left: 5px solid red;
            padding: 5px;
            position: relative;
            top: 10px;
            border-bottom: 1px solid red;
            color: red;
        }
    </style>
<?php }

?>