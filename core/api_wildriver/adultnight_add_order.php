<?php 

// ADULT NIGHT PLATFORM ADD ORDER API
//add_filter('gform_pre_submission_filter_6', 'change_form', 10, 3);
add_filter('gform_after_submission_14', 'change_form_14', 10, 3);
//add_filter( 'gform_authorizenet_post_capture', 'change_form', 10, 3 );
function change_form_14($form)
{

    $bolder_order_id = rgpost('input_124');
    $post_url = 'https://dynamicpricing-api.dynamicpricingbuilder.com/Pricing/AddOrder/';

    $ropecourse_sec_id = !empty(rgpost('input_150'))?rgpost('input_150'):'0';
    $ropecourse_cap_id = !empty(rgpost('input_151'))?rgpost('input_151'):'0';

    $mountain_sec_id = !empty(rgpost('input_154'))?rgpost('input_154'):'0';
    $mountain_cap_id = !empty(rgpost('input_153'))?rgpost('input_153'):'0';

    $freefall_sec_id = !empty(rgpost('input_157'))?rgpost('input_157'):'0';
    $freefall_cap_id = !empty(rgpost('input_156'))?rgpost('input_156'):'0';

    $archery_sec_id = !empty(rgpost('input_160'))?rgpost('input_160'):'0';
    $archery_cap_id = !empty(rgpost('input_159'))?rgpost('input_159'):'0';

    $dartsee_sec_id = !empty(rgpost('input_163'))?rgpost('input_163'):'0';
    $dartsee_cap_id = !empty(rgpost('input_162'))?rgpost('input_162'):'0';

    $total_price_dollar_remove   = ltrim(rgpost('input_73'), '$');
    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);

    $tax_dollar_remove   = ltrim(rgpost('input_179'), '$');
    $tax_without_dollar = str_replace(',', '', $tax_dollar_remove);

    $service_dollar_remove   = ltrim(rgpost('input_180'), '$');
    $service_without_dollar = str_replace(',', '', $service_dollar_remove);

    $advance_blk_quantity = rgpost('input_92');

    $advance_blue_quantity = rgpost('input_86');

    $coupon_code = rgpost('input_173');
    $discount_type = null;
    $discount_value = 0;

    // Clean up prices (remove $ and convert to float)
    $general_price = (float) ltrim(rgpost('input_28'), '$');
    $green_price   = (float) ltrim(rgpost('input_84'), '$');
    $blue_price    = (float) ltrim(rgpost('input_87'), '$');
    $black_price   = (float) ltrim(rgpost('input_100'), '$');

    // Additional prices
    $ropecourse_price = (float) ltrim(rgpost('input_125_2'), '$');
    $mountain_price   = (float) ltrim(rgpost('input_126_2'), '$');
    $freefall_price   = (float) ltrim(rgpost('input_127_2'), '$');
    $archery_price    = (float) ltrim(rgpost('input_128_2'), '$');
    $dartsee_price    = (float) ltrim(rgpost('input_129_2'), '$');
    $socks_price      = (float) ltrim(rgpost('input_172_2'), '$');

    // Default prices
    $final_general_price = $general_price;
    $final_green_price   = $green_price;
    $final_blue_price    = $blue_price;
    $final_black_price   = $black_price;
    $final_ropecourse_price = $ropecourse_price;
    $final_mountain_price   = $mountain_price;
    $final_freefall_price   = $freefall_price;
    $final_archery_price    = $archery_price;
    $final_dartsee_price    = $dartsee_price;
    $final_socks_price      = $socks_price;

    // Get the quantities for each product (replace these with actual quantities if they are available)
    $general_quantity = rgpost('input_27');
    $green_quantity   = rgpost('input_83');
    $blue_quantity    = rgpost('input_86');
    $black_quantity   = rgpost('input_92');
    $ropecourse_quantity = rgpost('input_125_3');
    $mountain_quantity = rgpost('input_126_3');
    $freefall_quantity = rgpost('input_127_3');
    $archery_quantity  = rgpost('input_128_3');
    $dartsee_quantity  = rgpost('input_129_3');
    $socks_quantity    = rgpost('input_172_3');

    // Check and apply promo
    if (!empty($coupon_code)) {
        $coupon_code = strtolower(trim($coupon_code));

        if (preg_match('/(\d+)([pf])$/', $coupon_code, $matches)) {
            $discount_value = intval($matches[1]);
            $discount_type  = $matches[2];

            if ($discount_type === 'p') {
                // Percentage discount (no change here)
                $final_general_price -= ($general_price * $discount_value / 100);
                $final_green_price   -= ($green_price * $discount_value / 100);
                $final_blue_price    -= ($blue_price * $discount_value / 100);
                $final_black_price   -= ($black_price * $discount_value / 100);
                $final_ropecourse_price -= ($ropecourse_price * $discount_value / 100);
                $final_mountain_price   -= ($mountain_price * $discount_value / 100);
                $final_freefall_price   -= ($freefall_price * $discount_value / 100);
                $final_archery_price    -= ($archery_price * $discount_value / 100);
                $final_dartsee_price    -= ($dartsee_price * $discount_value / 100);
                $final_socks_price      -= ($socks_price * $discount_value / 100);
            } elseif ($discount_type === 'f') {
                // Flat discount, divide it proportionally

                // Calculate the total price of all products
                $total_price = ($general_price * $general_quantity) + 
                            ($green_price * $green_quantity) + 
                            ($blue_price * $blue_quantity) + 
                            ($black_price * $black_quantity) + 
                            ($ropecourse_price * $ropecourse_quantity) + 
                            ($mountain_price * $mountain_quantity) + 
                            ($freefall_price * $freefall_quantity) + 
                            ($archery_price * $archery_quantity) + 
                            ($dartsee_price * $dartsee_quantity) + 
                            ($socks_price * $socks_quantity);

                // Apply the discount proportionally to each product
                $final_general_price -= (($general_price * $general_quantity) / $total_price) * $discount_value;
                $final_green_price   -= (($green_price * $green_quantity) / $total_price) * $discount_value;
                $final_blue_price    -= (($blue_price * $blue_quantity) / $total_price) * $discount_value;
                $final_black_price   -= (($black_price * $black_quantity) / $total_price) * $discount_value;
                $final_ropecourse_price -= (($ropecourse_price * $ropecourse_quantity) / $total_price) * $discount_value;
                $final_mountain_price   -= (($mountain_price * $mountain_quantity) / $total_price) * $discount_value;
                $final_freefall_price   -= (($freefall_price * $freefall_quantity) / $total_price) * $discount_value;
                $final_archery_price    -= (($archery_price * $archery_quantity) / $total_price) * $discount_value;
                $final_dartsee_price    -= (($dartsee_price * $dartsee_quantity) / $total_price) * $discount_value;
                $final_socks_price      -= (($socks_price * $socks_quantity) / $total_price) * $discount_value;
            }
        }
    }

    // Round all prices to 2 decimal places
    $final_general_price = round($final_general_price, 2);
    $final_green_price   = round($final_green_price, 2);
    $final_blue_price    = round($final_blue_price, 2);
    $final_black_price   = round($final_black_price, 2);
    $final_ropecourse_price = round($final_ropecourse_price, 2);
    $final_mountain_price   = round($final_mountain_price, 2);
    $final_freefall_price   = round($final_freefall_price, 2);
    $final_archery_price    = round($final_archery_price, 2);
    $final_dartsee_price    = round($final_dartsee_price, 2);
    $final_socks_price      = round($final_socks_price, 2);

    $body = array(
        'authCode' => '0a27e421-e1e7-4530-80f8-fca5789b79be',
        'isOfficeUse' => false,
        'sessionId' => rgpost('input_51'),
        'orderId' => rgpost('input_124'),
        'customer' => array(
            'firstName' => rgpost('input_17'),
            'lastName' => rgpost('input_59'),
            'phone' => rgpost('input_19'),
            'email' => rgpost('input_18')
        ),
        'purchases' => [
            array(
                'ticketType' => 'Blue Cabana',
                'sectionId' => "0",
                'capacityId' => rgpost('input_144'),
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_86'),
                'amount' => $final_blue_price
            ), 
            array(
                'ticketType' => 'Black Cabana',
                'sectionId' => "0",
                'capacityId' => rgpost('input_145'),
                'VisualId' => "",
                'VisualIdStockCount' => 0, 
                'quantity' => rgpost('input_92'),
                'amount' => $final_black_price
            ), 
            array(
                'ticketType' => 'RopesCourseAndZipLines',
                'sectionId' => $ropecourse_sec_id,
                'capacityId' => $ropecourse_cap_id,
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_125_3'),
                'amount' => $final_ropecourse_price
            ), 
            array(
                'ticketType' => 'MountainExperienceViaFerrata',
                'sectionId' => $mountain_sec_id,
                'capacityId' => $mountain_cap_id,
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_126_3'),
                'amount' => $final_mountain_price
            ), 
            array(
                'ticketType' => 'FreeFallExperience',
                'sectionId' => $freefall_sec_id,
                'capacityId' => $freefall_cap_id,
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_127_3'),
                'amount' => $final_freefall_price
            ),
            array(
                'ticketType' => 'ArcheryLanes',
                'sectionId' => $archery_sec_id,
                'capacityId' => $archery_cap_id,
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_128_3'),
                'amount' => $final_archery_price
            ),
            array(
                'ticketType' => 'DartseeInteractiveDarts',
                'sectionId' => $dartsee_sec_id,
                'capacityId' => $dartsee_cap_id,
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_129_3'),
                'amount' => $final_dartsee_price
            ),
            array( 
                'ticketType' => 'Socks',
                'sectionId' => "0",
                'capacityId' => rgpost('input_143'),
                'VisualId' => "",
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_172_3'),
                'amount' => $final_socks_price
            )
        ],
        'payment' => array(
            'cardholderName' => 'Omitted',
            'billingStreet' => rgpost('input_174_1') . ' , ' . rgpost('input_174_2') . ' , ' .  rgpost('input_174_3') . ' , ' . rgpost('input_174_4'),
            'billingZipCode' => rgpost('input_174_5'),
            //'billingStreet' => rgpost('input_55'),
            //'billingZipCode' => rgpost('input_56'),
            'cvn' => 'Omitted',
            'expDate' => 'Omitted',
            'ccNumber' => 'Omitted',
            'paymentCode' => '32',
            'amount' => $total_price_without_dollar,
            'staffTip'=> 0,
            'tax' => isset($request->tax_amount) ? $request->tax_amount : '0',
            'serviceCharges' => isset($request->tax_amount) ? $request->tax_amount : '0',
        )

    );


    // Define the Black Option 1
    $advance_option_1 = array(
        'ticketType' => rgpost('input_97'),
        'sectionId' => rgpost('input_146'),
        'capacityId' => rgpost('input_147'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Black Option 2
    $advance_option_2 = array(
        'ticketType' => rgpost('input_98'),
        'sectionId' => rgpost('input_148'),
        'capacityId' => rgpost('input_149'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Black Option 3
    $advance_option_3 = array(
        'ticketType' => rgpost('input_99'),
        'sectionId' => rgpost('input_165'),
        'capacityId' => rgpost('input_166'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Black Option 4
    $advance_option_4 = array(
        'ticketType' => rgpost('input_102'),
        'sectionId' => rgpost('input_167'),
        'capacityId' => rgpost('input_168'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Blue Option 1
    $blue_advance_option_1 = array(
        'ticketType' => rgpost('input_187'),
        'sectionId' => rgpost('input_203'),
        'capacityId' => rgpost('input_204'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Blue Option 2
    $blue_advance_option_2 = array(
        'ticketType' => rgpost('input_191'),
        'sectionId' => rgpost('input_205'),
        'capacityId' => rgpost('input_206'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Blue Option 3
    $blue_advance_option_3 = array(
        'ticketType' => rgpost('input_195'),
        'sectionId' => rgpost('input_207'),
        'capacityId' => rgpost('input_208'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    // Define the Blue Option 4
    $blue_advance_option_4 = array(
        'ticketType' => rgpost('input_199'),
        'sectionId' => rgpost('input_209'),
        'capacityId' => rgpost('input_210'),
        'VisualId' => "",
        'VisualIdStockCount' => 0,
        'quantity' => "1",
        'amount' => '0.00'
    );

    if ($advance_blk_quantity > 0 ) {
        $body['purchases'][] = $advance_option_1; // Append Black Option 1
    }
    if ($advance_blk_quantity > 1) {
        $body['purchases'][] = $advance_option_2; // Append Black Option 2
    }
    if ($advance_blk_quantity > 2 ) {
        $body['purchases'][] = $advance_option_3; // Append Black Option 3
    }
    if ($advance_blk_quantity > 3 ) {
        $body['purchases'][] = $advance_option_4; // Append Black Option 4
    }

    if ($advance_blue_quantity > 0 ) {
        $body['purchases'][] = $blue_advance_option_1; // Append Black Option 1
    }
    if ($advance_blue_quantity > 1) {
        $body['purchases'][] = $blue_advance_option_2; // Append Black Option 2
    }
    if ($advance_blue_quantity > 2 ) {
        $body['purchases'][] = $blue_advance_option_3; // Append Black Option 3
    }
    if ($advance_blue_quantity > 3 ) {
        $body['purchases'][] = $blue_advance_option_4; // Append Black Option 4
    }
    
    /*echo '<pre>';
    var_dump($body);
    echo '</pre>';*/

   // Sending Data Condition Ends here
    $headers = array(
        'Content-Type' => 'application/json'
    );
    /*echo '<pre>';
    var_dump($body);
    echo '</pre>';*/
    $body = json_encode($body);
    $response = wp_remote_post($post_url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    // GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );

    if ($response_body != '') { ?>
        <script>
            //alert('We have something in return');
        </script>

        <?php if ($response_body['status']['errorCode'] != 0) {
            echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>Error Code:' . $response_body['status']['errorCode'] . ', Error Message:' . filter_var($response_body['status']['errorMessage'], FILTER_SANITIZE_STRING) . ', There is an issue with your order, you may contact us if we do not get back to you shortly.</p><a href="/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';

            $email_body = 'Hi, We may have issue with this order, Error Code Was Not Zero. Error Code:' .$response_body['status']['errorCode']. ' & Error Message: '.$response_body['status']['errorMessage']. ' Please Check Further Details';
            //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
            $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com'; 
            //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
            $subject = 'Problem with Bolder Adventure Order Number is #' . rgpost('input_124') . ' for ' . rgpost('input_1');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');
            
        ?>
            <script>
                alert('Error Code: <?php echo $response_body['status']['errorCode']; ?>, Error Message: <?php echo filter_var($response_body['status']['errorMessage'], FILTER_SANITIZE_STRING); ?>, There is an issue with your order, you may try again.');
            </script>

        <?php

            $form['is_active'] = false;
            //return $form;   // to see the var_dump output
            return 0;       // to prevent form submission
        } else {
            /*$response = wp_remote_post( $post_url, array( 'body' => $body ) );
            GFCommon::log_debug( 'gform_confirmation: response => ' . print_r( $response, true ) );
            echo '<pre>';
            var_dump($response_body);
            echo '</pre>'; */
        ?>

        <?php
        $payment_verify_url = 'https://tickets.bolderadventurepark.com/wp-json/gravityForms/getPaymentStatus?order_id=' . rgpost('input_124');
        $payment_verify_response = wp_remote_get($payment_verify_url);
        $payment_verify_response_body = json_decode(wp_remote_retrieve_body($payment_verify_response), true);

        if (isset($payment_verify_response_body['error_code']) && $payment_verify_response_body['error_code'] === 0) { ?>

            <?php
            // Getting visual id from API -----------------------------------------------
            $post_url = 'https://dynamicpricing-api.dynamicpricingbuilder.com/Pricing/QueryOrder2?orderId=' . rgpost('input_124') . '&authCode=0a27e421-e1e7-4530-80f8-fca5789b79be';
            $response = wp_remote_get($post_url);
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $cabanaVisualId = $response_body['data']['tickets'][0]['visualId'];
            $ticketsIds = array();

            //for ($i = 0; $i < count($response_body['data'][0]) - 1; $i++) {
            //$ticketsIds[] = $response_body['data'][$i + 1]['visualId'];
            //}
            ob_start();
            ?>

            
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
            <style>
                ._main_table * {
                    font-family: 'Montserrat', sans-serif;
                }
            </style>

            <table cellpadding="0" cellspacing="0" width="600" align="center" class="_main_table">
                <tr>
                    <td>
                        <img src="https://tickets.bolderadventurepark.com/wp-content/uploads/2025/01/email-header-email-Bolder.jpg" alt="" />
                    </td>
                </tr>
                <tr style="background-color: #173767;">
                    <td style="padding: 30px;">
                        <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You For
                            Purchasing - Your Order Number is #<?php echo rgpost('input_124'); ?></h1>
                    </td>
                </tr>
                <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                    <td style="padding:20px">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="padding-bottom: 30px;">
                                    <p style="line-height: 16px;">
                                        Hi <?php echo rgpost('input_17'); ?>,<br />
                                        We've received your order #<?php echo rgpost('input_124'); ?>
                                        for <?php echo rgpost('input_130'); ?>. We look forward to seeing you.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb">
                            <tr>
                                <td style="padding:5px">Product(s)</td>
                                <td style="padding:5px">Quantity</td>
                                <td style="padding:5px">Price</td>
                            </tr>
                            <?php $blue_ticket_quantity = rgpost('input_86'); ?>
                            <?php if ($blue_ticket_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Intermediate Blue Pass</td>
                                    <td style="padding:5px"><?php echo rgpost('input_86'); ?></td>
                                    <td style="padding:5px">$<?php echo rgpost('input_87'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $black_ticket_quantity = rgpost('input_92'); ?>
                            <?php if ($black_ticket_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Advanced – Black Diamond</td>
                                    <td style="padding:5px"><?php echo rgpost('input_92'); ?></td>
                                    <td style="padding:5px">$<?php echo rgpost('input_100'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $ropecourse_quantity = rgpost('input_125_3'); ?>
                            <?php if ($ropecourse_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Ropes Course & Zip Lines</td>
                                    <td style="padding:5px"><?php echo rgpost('input_125_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_125_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $mountain_quantity = rgpost('input_126_3'); ?>
                            <?php if ($mountain_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Mountain Experience - Via Ferrata</td>
                                    <td style="padding:5px"><?php echo rgpost('input_126_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_126_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $freefall_quantity = rgpost('input_127_3'); ?>
                            <?php if ($freefall_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Free Fall Experience</td>
                                    <td style="padding:5px"><?php echo rgpost('input_127_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_127_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $archery_quantity = rgpost('input_128_3'); ?>
                            <?php if ($archery_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Archery Lanes</td>
                                    <td style="padding:5px"><?php echo rgpost('input_128_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_128_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $dartsee_quantity = rgpost('input_129_3'); ?>
                            <?php if ($dartsee_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Dartsee Interactive Darts</td>
                                    <td style="padding:5px"><?php echo rgpost('input_129_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_129_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php $socks_quantity = rgpost('input_172_3'); ?>
                            <?php if ($socks_quantity > 0) { ?>
                                <tr>
                                    <td style="padding:5px">Socks</td>
                                    <td style="padding:5px"><?php echo rgpost('input_172_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_172_2'); ?></td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <td colspan="2" style="padding:5px">
                                    <strong>Tax 8.25%</strong>
                                </td>
                                <td style="padding:5px">
                                    $<?php echo rgpost('input_179'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding:5px">
                                    <strong>Service Fee 2.75%</strong>
                                </td>
                                <td style="padding:5px">
                                   $<?php echo rgpost('input_180'); ?>
                                </td>
                            </tr>
 
                            <tr>
                                <td colspan="2" style="padding:5px">
                                    <strong>Total:</strong>
                                </td>
                                <td style="padding:5px">
                                    <?php echo rgpost('input_73'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:5px" colspan="3">
                                    <h4>See / Modify Tickets</h4>
                                    <p style="padding:0; margin:0">To modify the date of your visit, please use the link
                                        provided on your emailed receipt. Must change more than 24 hours in advance. tickets
                                        purchased within 24 hours will not be able to be rescheduled.</p>
                                    <a href="https://tickets.bolderadventurepark.com/update-order/?order=<?php echo rgpost('input_124'); ?>" style="margin-bottom: 10px;">Click here to Modify</a>
                                    <ul class="_ticket_code" style="text-align:center;padding: 0;list-style: none;">
                                        <?php
                                        for ($j = 0; $j < count($response_body['data']['tickets']); $j++) {
                                        ?>
                                            <li style="margin: 0; border-bottom: 1px solid #b2b2b2; padding-bottom: 5px;">
                                                <br />
                                                <br />
                                                <br />
                                                <br />
                                                <h5>Ticket #<?php echo $j + 1; ?>
                                                    - <?php echo $response_body['data']['tickets'][$j]['ticketType'] ?>
                                                </h5>
                                                <img src="https://quickchart.io/qr?text=<?php echo $response_body['data']['tickets'][$j]['visualId'] ?>&margin=2&size=150" alt="" />
                                                <p style="margin: 0;"><?php echo $response_body['data']['tickets'][$j]['visualId'] ?> - <?php echo rgpost('input_130'); ?></p>
                                                
                                                    <?php echo $response_body['data']['tickets'][$j]['ticketType'] ?> - <?php echo $response_body['data']['tickets'][$j]['slotTime'] ?>

                                                <br />
                                                <br />
                                                <br />
                                                <br />
                                            </li>
                                        <?php
                                        } ?>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <table border="0" style="border: 1px solid #ececec;" width="100%">
                            <h4>Billing Information</h4>
                            <p>
                                <?php echo rgpost('input_17'); ?> <?php echo rgpost('input_59'); ?> <br />
                                <?php echo rgpost('input_55'); ?> <br />
                                <?php echo rgpost('input_56'); ?> <br />
                                <?php echo rgpost('input_19'); ?> <br />
                                <?php echo rgpost('input_18'); ?> <br />
                            </p>
                        </table>

                        <table border="0" style="border: 1px solid #ececec;" width="100%">
                            <tr>
                                <td>
                                    <h3>Terms & Conditions</h3>
                                    <p style="font-size: 10px;">
                                        1. No Refunds. You fully ASSUME ALL RISK whatsoever in relation to your park activities and experience. By entering Park, you automatically and fully waive any and all claims of any nature whatsoever, including without limitation for personal injuries, inadequate security or warnings, theft/damage of property, weather-related claims, acts of 3rd parties, and illnesses (air, food, water-borne etc…)
                                    </p>
                                    <p style="font-size: 10px;">
                                        2. You (including anyone under your care/supervision) must comply with all safety rules, warning signs and instructions. Parents/Guardians are solely responsible for supervision of minors or others needing special care. No resale, ”rain checks”, exchanges or refunds (including for loss, theft or expired date). SAFETY 1st! Act responsibly.
                                    </p>
                                    <p style="font-size: 10px;">
                                        3. Days/hours and ride/attraction availability are subject to change without notice. Certain rides, shows, attractions and special events, may require an additional charge or be subject to heigh, weight, or other restrictions at Park’s discretion. Park may deny admission or expel anyone, without refund, for: illegality, aggressive/disorderly conduct or threats, Park rule violation, drugs/alcohol (including marijuana), offensive conduct (e.g., inappropriate or inadequate clothing, markings/tattoos, gestures, language, etc…), possession of a firearm or any weapon-like device, line-cutting, stealing, property damage, or anything else which the park deems a threat to the welfare/safety of people, wildlife/animals or Park property.
                                    </p>
                                    <p style="font-size: 10px;">
                                        4. This ticket is valid in 2025 only.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
        <?php

            $email_body = ob_get_clean();


            //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
            $to = rgpost('input_18');
            $subject = 'Thank You For Purchasing - Your Bolder Adventure Order Number is #' . rgpost('input_124') . ' for ' . rgpost('input_130');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');


            return $form;
        } // Error Code Else

        } // Payment Verification
        
    // Checking Null Value in Return Bracket
    } else { ?>


        <?php

        echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>We are sorry, Your order is taking longer to process, We will send you an email with your ticket IDs shortly. </p><a href="/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';


        $email_body = 'Hi, We may have issue with this order, might be we have not send him qr code or something else. please investigate.';
        //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
        $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com';
        //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
        $subject = 'NULL Response. Problem with Bolder Adventure Order Number is #' . rgpost('input_124') . ' for ' . rgpost('input_130');

        //    This is going to sent the email-----------------------------------------------------------------------------------
        wp_mail($to, $subject, $email_body, 'Content-type: text/html');

        ?>
        <script>
            alert('We are sorry, Your order is taking longer to process, We will send you an email with your ticket IDs shortly.');
        </script>

    <?php
    }
}; // Function Bracket



add_filter('gform_confirmation_14', 'redirect_after_adult_night_submission', 10, 4);
function redirect_after_adult_night_submission($confirmation, $form, $entry, $ajax) {
    
    // Extract values from the entry (replace field IDs with your GF field IDs)
    $order_id   = rgar($entry, '124'); // Order ID field
    $order_date = rgar($entry, '186'); // Date field
    $email      = rgar($entry, '18');  // Email field
    $first_name = rgar($entry, '17');  // First Name
    $last_name  = rgar($entry, '59');  // Last Name
    $phone      = rgar($entry, '19');  // Phone
    $total      = rgar($entry, '73');  // Total

    // Build the redirect URL
    $redirect_url = add_query_arg(array(
        'order'     => $order_id,
        'orderdate' => $order_date,
        'email'     => $email,
        'firstname' => $first_name,
        'lastname'  => $last_name,
        'phone'     => $phone,
        'total'     => $total
    ), 'https://tickets.bolderadventurepark.com/adult-night/thank-you/');

    return array('redirect' => $redirect_url);
}
