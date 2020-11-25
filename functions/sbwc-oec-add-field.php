<?php

/**
 * Add additional email input field
 */

// add additional email field
add_action('woocommerce_before_order_notes', 'sbwc_oec_input');

function sbwc_oec_input()
{
    // read CSV file
    $csv_file = get_option('sbwc_oec_file_link');

    // if file present, display extra input field, else bail
    if ($csv_file) :

        // read csv data
        $csvFile = file($csv_file);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }

        // if lines found with DNS Failure, add to mail error list which will be used to check email input on checkout page
        foreach ($data as $val) {
            if ($val[0] == 'DNS Failure') {
                $mail_error_list[] = $val[6];
            }
        }

        // encode array for use in our js
        $mail_error_list = json_encode($mail_error_list);

?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // insert email confirmation input
                var input = '<span class="woocommerce-input-wrapper">';
                input += '<div class="fl-wrap fl-wrap-input fl-is-active">';
                input += '<label for="email_confirmation" class="fl-label"><?php pll_e('Confirm email'); ?>&nbsp';
                input += '<abbr class="required" title="required">*</abbr></label>';
                input += '<input type="text" class="input-text fl-input" name="email_confirmation" id="email_confirmation">';
                input += '<span id="sbwc_oec_error" style="display: none;">&#10006; <?php pll_e('Email addresses do not match') ?></span>';
                input += '<span id="sbwc_oec_success" style="display: none;">&#10004; <?php pll_e('Email addresses match') ?></span>';
                input += '<span id="sbwc_oec_validity" style="display: none;">&#10006; <?php pll_e('Email address does not seem to be valid') ?></span>';
                input += '</div></span>';
                var target = $('p#billing_email_field');
                $(target).append(input);

                // check match
                $('#email_confirmation').on('keyup keydown keypress', function() {

                    // get initiallly defined email value
                    var init_val = $('input#billing_email').val();

                    // get confirmation value
                    var conf_val = $(this).val();

                    // show error if email addresses do not match
                    if (conf_val != init_val) {
                        $('#sbwc_oec_error').show();
                        $('#sbwc_oec_success').hide();
                        $('button#place_order').attr('disabled', true);
                        $('button#place_order').css('cursor', 'no-drop');
                        $('button#place_order').attr('title', '<?php pll_e('There are errors with your email address which needs to be fixed before you will be able to check out.') ?>');
                    } else {
                        $('#sbwc_oec_error').hide();
                        $('#sbwc_oec_success').show();
                        $('button#place_order').attr('disabled', false);
                        $('button#place_order').css('cursor', 'pointer');
                        $('button#place_order').attr('title', '');
                    }
                    
                });
                
                // check for known email domain errors (extracted from uploaded CSV file)
                var domain_errs = '<?php echo $mail_error_list; ?>';
                
                // get entered email dom
                $('#email_confirmation, #billing_email').on('blur', function() {
                    var entered_email = $(this).val();
                    var split_email = entered_email.split("@");
                    var email_dom = split_email[1];
                    
                    // check for presense in domain_errs array
                    if (domain_errs.includes(email_dom)) {
                        $('#sbwc_oec_validity').show();
                        $('button#place_order').attr('disabled', true);
                        $('button#place_order').css('cursor', 'no-drop');
                        $('button#place_order').attr('title', '<?php pll_e('There are errors with your email address which needs to be fixed before you will be able to check out.') ?>');
                    } else {
                        $('#sbwc_oec_validity').hide();
                        $('button#place_order').attr('disabled', false);
                        $('button#place_order').css('cursor', 'pointer');
                        $('button#place_order').attr('title', '');
                    }
                });
                
            });
            </script>

        <style>
            input#email_confirmation {
                height: 3.0084em;
                transition: padding .3s;
                padding-top: 1.4em;
            }

            span#sbwc_oec_error, span#sbwc_oec_validity {
                color: #b20000;
                font-size: 14px;
                position: relative;
                bottom: 9px;
                left: 15px;
                font-weight: 500;
                letter-spacing: 0.5px;
                display: block;
            }

            span#sbwc_oec_success {
                position: relative;
                bottom: 9px;
                left: 15px;
                font-size: 14px;
                color: green;
                font-weight: 500;
                letter-spacing: 0.5px;
                display: block;
            }

            span#sbwc_oec_error>img, span#sbwc_oec_validity>img {
                width: 11px;
            }

            span#sbwc_oec_success>img {
                width: 11px;
            }
        </style>
<?php endif;
}
