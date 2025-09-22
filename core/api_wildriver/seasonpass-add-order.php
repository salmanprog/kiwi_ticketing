<?php

// SEASON PASS
//add_filter('gform_pre_submission_filter_6', 'change_form', 10, 3);
//add_filter( 'gform_authorizenet_transaction_pre_capture', 'change_form_14', 10, 3 );
//add_filter( 'gform_pre_submission_filter_14', 'change_form_14', 10, 3 );
//add_filter( 'gform_authorizenet_post_capture', 'change_form_14', 10, 3 );
//add_filter('gform_post_payment_completed_14', 'change_form_14', 10, 3);
add_filter('gform_after_submission_14', 'change_form_14', 10, 3);
//add_filter('gform_stripe_fulfillment', 'change_form_14', 10, 3);
function change_form_14($form, $entry)
{

    // Get the payment status
    // $payment_status = rgar($entry, 'payment_status');

    // For Failed Payment Emails
    // $custom_email = 'shawnbowman6@gmail.com';

    //if ($payment_status === 'Paid') {

    $payment_status = rgar($entry, 'payment_status');

    //print_r($payment_status);

    /*if ($payment_status !== 'Paid') {
        // Delete the entry if payment failed
        //GFAPI::delete_entry($entry['id']);
        //GFAPI::spam_entry($entry['id']);

        // Redirect back with error message (via query param)
       // wp_redirect(add_query_arg('payment_error', '1', $_SERVER['HTTP_REFERER']));
        //exit;
    }*/

    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/SeasonPassAddOrder';

    $silver_price_without_dollar = ltrim(rgpost('input_19'), '$');
    $gold_price_without_dollar = ltrim(rgpost('input_24'), '$');
    $platinum_price_without_dollar = ltrim(rgpost('input_27'), '$');
    $senior_price_without_dollar = ltrim(rgpost('input_53'), '$');
    //$parking_price_without_dollar = ltrim(rgpost('input_32_2'), '$');
    $parking_price_without_dollar = ltrim(rgpost('input_44'), '$');

    //$seniorseasonpass = ltrim(rgpost('input_48'), '$');

    $platinum_pass_quantity = intval(rgpost('input_28'));

    $total_price_dollar_remove = ltrim(rgpost('input_4'), '$');

    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);


    if ($platinum_pass_quantity > 100) {
        $body = array(
            'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
            'orderId' => rgpost('input_13'),
            'promoCode' => rgpost('input_34'),
            'customer' => array(
                'firstName' => rgpost('input_5'),
                'lastName' => rgpost('input_6'),
                'phone' => rgpost('input_8'),
                'email' => rgpost('input_7')
            ),
            'purchases' => [
                array(
                    'ticketType' => 'Silver Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_18'),
                    'amount' => $silver_price_without_dollar
                ),
                array(
                    'ticketType' => 'Gold Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_25'),
                    'amount' => $gold_price_without_dollar
                ),
                array(
                    'ticketType' => 'Platinum Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_28'),
                    'amount' => $platinum_price_without_dollar
                ),
                array(
                    'ticketType' => 'Parking Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_43'),
                    'amount' => $parking_price_without_dollar
                ),
                array(
                    'ticketType' => 'Parking Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => 1,
                    'amount' => 0
                )
            ],
            'payment' => array(
                'cardholderName' => $payment_status . ' Status',
                'billingStreet' => rgpost('input_42_1') . ' , ' . rgpost('input_42_2') . ' , ' . rgpost('input_42_3') . ' , ' . rgpost('input_42_4'),
                'billingZipCode' => rgpost('input_42_5'),
                //'billingZipCode' => '00000-' . $payment_status,
                'cvn' => 'Omitted',
                'expDate' => 'Omitted',
                'ccNumber' => 'Omitted',
                'paymentCode' => '32',
                'amount' => $total_price_without_dollar
            )

        );
    } else {

        $body = array(
            'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
            'orderId' => rgpost('input_13'),
            'promoCode' => rgpost('input_34'),
            'customer' => array(
                'firstName' => rgpost('input_5'),
                'lastName' => rgpost('input_6'),
                'phone' => rgpost('input_8'),
                'email' => rgpost('input_7')
            ),
            'purchases' => [
                array(
                    'ticketType' => 'Silver Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_18'),
                    'amount' => $silver_price_without_dollar
                ),
                array(
                    'ticketType' => 'Gold Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_25'),
                    'amount' => $gold_price_without_dollar
                ),
                array(
                    'ticketType' => 'Platinum Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_28'),
                    'amount' => $platinum_price_without_dollar
                ),
                array(
                    'ticketType' => 'Parking Season Pass',
                    'sectionId' => '0',
                    'capacityId' => '0',
                    'VisualId' => null,
                    'VisualIdStockCount' => 0,
                    'quantity' => rgpost('input_43'),
                    'amount' => $parking_price_without_dollar
                )
            ],
            'payment' => array(
                'cardholderName' => $payment_status . ' Status',
                'billingStreet' => rgpost('input_42_1') . ' , ' . rgpost('input_42_2') . ' , ' . rgpost('input_42_3') . ' , ' . rgpost('input_42_4'),
                'billingZipCode' => rgpost('input_42_5'),
                //'billingZipCode' => '00000-' . $payment_status,
                'cvn' => 'Omitted',
                'expDate' => 'Omitted',
                'ccNumber' => 'Omitted',
                'paymentCode' => '32',
                'amount' => $total_price_without_dollar
            )

        );

    }


    /*
    $body = array(
        'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
        'orderId' => rgpost('input_13'),
        'promoCode' => rgpost('input_34'),
        'customer' => array(
            'firstName' => rgpost('input_5'),
            'lastName' => rgpost('input_6'),
            'phone' => rgpost('input_8'),
            'email' => rgpost('input_7')
        ),
        'purchases' => [
            array(
                'ticketType' => 'Silver Season Pass',
                'sectionId' => '0',
                'capacityId' => '0',
                'VisualId' => null,
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_18'),
                'amount' => $silver_price_without_dollar
            ),
            array(
                'ticketType' => 'Gold Season Pass',
                'sectionId' => '0',
                'capacityId' => '0',
                'VisualId' => null,
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_25'),
                'amount' => $gold_price_without_dollar
            ),
            array(
                'ticketType' => 'Platinum Season Pass',
                'sectionId' => '0',
                'capacityId' => '0',
                'VisualId' => null,
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_28'),
                'amount' => $platinum_price_without_dollar
            ),
            array(
                'ticketType' => 'Parking Season Pass',
                'sectionId' => '0',
                'capacityId' => '0',
                'VisualId' => null,
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_43'),
                'amount' => $parking_price_without_dollar
            )
        ],
        'payment' => array(
            'cardholderName' => 'Omitted',
            'billingStreet' => '',
            'billingZipCode' => '',
            'cvn' => 'Omitted',
            'expDate' => 'Omitted',
            'ccNumber' => 'Omitted',
            'paymentCode' => '32',
            'amount' => $total_price_without_dollar
        )

    ); */


    // Sending Data Condition Ends here
    $headers = array(
        'Content-Type' => 'application/json'
    );

    $body = json_encode($body);

    /*echo '<pre>';
    var_dump($body);
    echo '</pre>';*/


    $response = wp_remote_post($post_url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    /*echo '<pre>';
    var_dump($response_body);
    echo '</pre>';*/
    //GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );

    if ($response_body != '') { ?>
        <script>
            //alert('We have something in return');
        </script>

        <?php if ($response_body['status']['errorCode'] != 0) {
            echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>Error Code:' . $response_body['status']['errorCode'] . ', Error Message:' . filter_var($response_body['status']['errorMessage'], FILTER_SANITIZE_STRING) . ', There is an issue with your order, you may contact us if we do not get back to you shortly.</p><a href="/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';

            $email_body = 'Hi, We may have issue with this order, Error Code Was Not Zero. Error Code:' . $response_body['status']['errorCode'] . ' & Error Message: ' . $response_body['status']['errorMessage'] . ' Please Check Further Details';
            //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
            $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com';
            //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
            $subject = 'Problem with Wild Rivers Order Number is #' . rgpost('input_13');

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
            $payment_verify_url = 'https://staging13.tickets.wildrivers.com/wp-json/gravityForms/getPaymentStatus?order_id=' . rgpost('input_13');
            $payment_verify_response = wp_remote_get($payment_verify_url);
            $payment_verify_response_body = json_decode(wp_remote_retrieve_body($payment_verify_response), true);
            ?>

            <?php

            if (isset($payment_verify_response_body['error_code']) && $payment_verify_response_body['error_code'] === 0) {
                // Getting visual id from API -----------------------------------------------
                $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=' . rgpost('input_13') . '&authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
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
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/05/email-header-email.jpg" alt="" />
                        </td>
                    </tr>
                    <tr style="background-color: #173767;">
                        <td style="padding: 30px;">
                            <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You For
                                Purchasing - Your Order Number is #<?php echo rgpost('input_13'); ?></h1>
                        </td>
                    </tr>
                    <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                        <td style="padding:20px">
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding-bottom: 30px;">
                                        <p style="line-height: 16px;">
                                            Hi <?php echo rgpost('input_5'); ?>                 <?php echo rgpost('input_6'); ?>,<br />
                                            We've received your order #<?php echo rgpost('input_13'); ?>, A separate email will be sent
                                            to you prior to the beginning of the season to log in to your customer portal and add
                                            identification photos for each season passholder to activate your season passes.
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
                                <?php if ($platinum_pass_quantity > 3) { ?>
                                    <tr>
                                        <td style="padding:5px">Free Parking Season Pass</td>
                                        <td style="padding:5px">1</td>
                                        <td style="padding:5px">FREE</td>
                                    </tr>
                                <?php } ?>
                                <?php if ($silver_price_without_dollar != "0") { ?>
                                    <tr>
                                        <td style="padding:5px">Silver Season Pass</td>
                                        <td style="padding:5px"><?php echo rgpost('input_18'); ?></td>
                                        <td style="padding:5px"><?php echo rgpost('input_19'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($gold_price_without_dollar != "0") { ?>
                                    <tr>
                                        <td style="padding:5px">Gold Season Pass</td>
                                        <td style="padding:5px"><?php echo rgpost('input_25'); ?></td>
                                        <td style="padding:5px"><?php echo rgpost('input_24'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($platinum_price_without_dollar != "0") { ?>
                                    <tr>
                                        <td style="padding:5px">Platinum Season Pass</td>
                                        <td style="padding:5px"><?php echo rgpost('input_28'); ?></td>
                                        <td style="padding:5px"><?php echo rgpost('input_27'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($parking_price_without_dollar != "0") { ?>
                                    <tr>
                                        <td style="padding:5px">Parking Season Pass</td>
                                        <td style="padding:5px"><?php echo rgpost('input_43'); ?></td>
                                        <td style="padding:5px"><?php echo rgpost('input_44'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php // if ($seniorseasonpass != "0") { ?>
                                <!--tr>
                                    <td style="padding:5px">Senior Season Pass</td>
                                    <td style="padding:5px"><?php //echo rgpost('input_49'); ?></td>
                                    <td style="padding:5px"><?php // echo rgpost('input_48'); ?></td>
                                </tr-->
                                <?php //} ?>
                                <tr>
                                    <td colspan="2" style="padding:5px">
                                        <strong>Total:</strong>
                                    </td>
                                    <td style="padding:5px">
                                        <?php echo rgpost('input_4'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:5px" colspan="3">
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
                                                    <img src="https://quickchart.io/qr?text=<?php echo $response_body['data']['tickets'][$j]['visualId'] ?>&margin=2&size=150"
                                                        alt="" />
                                                    <p style="margin: 0;"><?php echo $response_body['data']['tickets'][$j]['visualId'] ?> -
                                                        <?php echo rgpost('input_1'); ?>
                                                    </p>

                                                    <?php echo $response_body['data'][$a]['ticketType'] ?>

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
                                    <?php echo rgpost('input_5'); ?>                 <?php echo rgpost('input_6'); ?> <br />
                                    <?php echo rgpost('input_7'); ?> <br />
                                    <?php echo rgpost('input_8'); ?> <br />
                                    <?php echo rgpost('input_9'); ?>                 <?php echo rgpost('input_10'); ?> <br />
                                    <?php echo rgpost('input_11'); ?>                 <?php echo rgpost('input_12'); ?> <br />
                                </p>
                            </table>

                            <table border="0" style="border: 1px solid #ececec;" width="100%">
                                <tr>
                                    <td>
                                        <h3>Terms & Conditions</h3>
                                        <p style="font-size: 10px;">
                                            1. No Refunds. You fully ASSUME ALL RISK whatsoever in relation to your park activities and
                                            experience. By entering Park, you automatically and fully waive any and all claims of any
                                            nature whatsoever, including without limitation for personal injuries, inadequate security
                                            or warnings, theft/damage of property, weather-related claims, acts of 3rd parties, and
                                            illnesses (air, food, water-borne etc…)
                                        </p>
                                        <p style="font-size: 10px;">
                                            2. You (including anyone under your care/supervision) must comply with all safety rules,
                                            warning signs and instructions. Parents/Guardians are solely responsible for supervision of
                                            minors or others needing special care. No resale, ”rain checks”, exchanges or refunds
                                            (including for loss, theft or expired date). SAFETY 1st! Act responsibly.
                                        </p>
                                        <p style="font-size: 10px;">
                                            3. Days/hours and ride/attraction availability are subject to change without notice. Certain
                                            rides, shows, attractions and special events, may require an additional charge or be subject
                                            to heigh, weight, or other restrictions at Park’s discretion. Park may deny admission or
                                            expel anyone, without refund, for: illegality, aggressive/disorderly conduct or threats,
                                            Park rule violation, drugs/alcohol (including marijuana), offensive conduct (e.g.,
                                            inappropriate or inadequate clothing, markings/tattoos, gestures, language, etc…),
                                            possession of a firearm or any weapon-like device, line-cutting, stealing, property damage,
                                            or anything else which the park deems a threat to the welfare/safety of people,
                                            wildlife/animals or Park property.
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
                $to = rgpost('input_7');
                $subject = 'Thank You For Purchasing - Your Wild Rivers Order Number is #' . rgpost('input_13');

                //    This is going to sent the email-----------------------------------------------------------------------------------
                wp_mail($to, $subject, $email_body, 'Content-type: text/html'); ?>

                <?php  // Starting seperate Email for Season Pass for Portal Signup
                                ob_start(); ?>

                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
                <style>
                    ._sp_portal_main_table * {
                        font-family: 'Montserrat', sans-serif;
                    }
                </style>


                <table cellpadding="0" cellspacing="0" width="600" align="center" class="_sp_portal_main_table">
                    <tr>
                        <td colspan="2">
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2025/05/sp_email_header-1.jpg" alt="" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2025/05/sp_email_header_2-1.jpg" alt="" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center">
                            <p style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:17px;">
                                <strong>Hi <?php echo rgpost('input_5'); ?>,</strong>
                                <br /><br />
                                We're excited to introduce an entirely new way for you to process your Wild Rivers 2025 Season Pass!
                                Unlike last year, you won't need to stand in line at the park. Instead, you can take care of everything
                                from your mobile device, tablet, or desktop whether you're at home, at the office, or lounging on your
                                couch.
                                <br />
                                <br />
                                Our all-new Passholder Portal will securely store your season pass and your family's passes making them
                                easily accessible from your mobile device.
                                <br />
                                <br />
                                Wristbands are now optional but still available for passholders who want the convenience of making
                                cashless purchases in the park.
                            </p>
                            <a href="https://seasonpasses.wildrivers.com/create-an-account/" target="_blank"
                                style="background-color:#cf3537;color:#fff;Text-transform:uppercase;font-family:'Montserrat',sans-serif;font-size:18px;padding: 10px 25px;text-decoration: none;border-radius: 25px;">Activate
                                your Season Pass</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; text-align:center">
                            <h3 style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:24px;">HOW TO CREATE YOUR
                                PORTAL ACCOUNT</h3>
                            <p style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:17px;">
                                Each passholder can have their own portal account, or you can assign multiple Wild Rivers Season Passes
                                to a single account perfect for families.
                                <br />
                                <br />
                                To create your account, follow this link or the set-up link button below, enter the email address you
                                wish to use for your account. You will be required to verify it and a link will be sent to finish
                                creating your account.
                            </p>

                        </td>
                        <td style="padding:15px; text-align:center">
                            <h3 style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:24px;">HOW TO ADD YOUR SEASON
                                PASS</h3>
                            <p style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:17px;">
                                Once you're logged into your Passholder Portal click the orange Add Season Pass button and enter the QR
                                code number from the receipt you've recently received with your purchased season passes.
                            </p>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center">
                            <p style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:17px;">
                                These are the season pass and season parking pass ticket ID's from your order you will need to add to
                                your account:
                            </p>
                            <a href="https://seasonpasses.wildrivers.com/create-an-account/" target="_blank"
                                style="background-color:#cf3537;color:#fff;Text-transform:uppercase;font-family:'Montserrat',sans-serif;font-size:18px;padding: 10px 25px;text-decoration: none;border-radius: 25px;">Activate
                                your Season Pass</a>
                            <ul class="_ticket_code" style="text-align:center;padding: 0;list-style: none;">
                                <?php
                                for ($j = 0; $j < count($response_body['data']['tickets']); $j++) {
                                    ?>
                                    <li style="margin: 0; border-bottom: 1px solid #b2b2b2; padding-bottom: 5px;">
                                        <br />
                                        <h5>Ticket #<?php echo $j + 1; ?>
                                            - <?php echo $response_body['data']['tickets'][$j]['ticketType'] ?>
                                        </h5>
                                        <img src="https://quickchart.io/qr?text=<?php echo $response_body['data']['tickets'][$j]['visualId'] ?>&margin=2&size=150"
                                            alt="" />
                                        <p style="margin: 0;"><?php echo $response_body['data']['tickets'][$j]['visualId'] ?> -
                                            <?php echo rgpost('input_1'); ?>
                                        </p>
                                        <?php echo $response_body['data'][$a]['ticketType'] ?>
                                        <br />
                                    </li>
                                    <?php
                                } ?>
                            </ul>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2025/05/sp_email_3.jpg" alt="" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center">
                            <p style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:17px;">
                                You'll need to complete this process for each Season Pass and Season Parking Pass you'd like to link to
                                your account. 2025 Season Parking Passes are assignable to one season pass only and valid for redemption
                                by that passholder only, who must be present to redeem.
                                <br />
                                <br />
                                Once you're finished, you're all set! Just log in to your portal account when you visit Wild Rivers and
                                show your season pass on your mobile device for park entry.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2025/05/sp_email_4.jpg" alt="" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p style="text-align:center; font-family: 'Montserrat', sans-serif; font-size:17px;">
                                Wild Rivers Wristbands are available at Guest Relations for passholders who wish to use one for entry
                                into the park or to make purchases throughout the park.
                                <br />
                                If you have any questions, please e-mail info@wildrivers.com
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2025/05/sp_email_footer.jpg" alt="" />
                        </td>
                    </tr>
                </table>

                <?php $email_body_seasonpass = ob_get_clean();

                $seasonTo = rgpost('input_7');
                $subject_seasonpass = 'Activate your season pass - Wild Rivers Order Number is #' . rgpost('input_13');


                //    This is going to sent the email-----------------------------------------------------------------------------------
                wp_mail($seasonTo, $subject_seasonpass, $email_body_seasonpass, 'Content-type: text/html');


                return $form;




            } // Error Code Else

        } // Payment Verificaion Checker

        // Checking Null Value in Return Bracket
    } else { ?>


        <?php

        echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>We are sorry, Your order is taking longer to process, We will send you an email with your ticket IDs shortly. </p><a href="/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';


        $email_body = 'Hi, We may have issue with this order, might be we have not send him qr code or something else. please investigate.';
        //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
        $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com';
        //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
        $subject = 'NULL Response. Problem with Wild Rivers Order Number is #' . rgpost('input_13');

        //    This is going to sent the email-----------------------------------------------------------------------------------
        wp_mail($to, $subject, $email_body, 'Content-type: text/html');

        ?>
        <script>
            alert('We are sorry, Your order is taking longer to process, We will send you an email with your ticket IDs shortly.');
        </script>

        <?php
    }


    /* } else {
        // Payment failed or still pending, notify the custom email
        send_email_to_custom_email($entry, $payment_status, $custom_email, $form);
        error_log('Payment failed for entry ID: ' . $entry['13'] . '. No email sent to customer.');
    } */


}
; // Function Bracket


function send_email_to_custom_email($entry, $payment_status, $custom_email, $form)
{
    // Customize the subject and message with custom fields
    $subject = "Payment Failed for Order # " . rgar($entry, '13');
    $message = "The payment for entry ID " . rgar($entry, '13') . " has failed. Payment Status: " . $payment_status . ".\n\n";

    // Add custom fields to the message (replace with actual field IDs)
    $message .= "First Name: " . rgpost('input_5') . "\n"; // Example: Field ID 1 for name
    $message .= "Last Name: " . rgpost('input_6') . "\n"; // Example: Field ID 1 for name
    $message .= "Order Total: " . rgpost('input_4') . "\n"; // Example: Field ID 2 for total amount
    $message .= "Email: " . rgpost('input_7') . "\n"; // Replace with email field ID
    $message .= "Payment Status: " . $payment_status . "\n";

    // You can include more custom fields here by adding more `rgar($entry, 'field_id')` calls

    // Send email to the custom email address
    wp_mail($custom_email, $subject, $message); // Send email to custom email
}


add_filter('gform_confirmation_14', 'redirect_after_successful_stripe_payment_14', 10, 4);
function redirect_after_successful_stripe_payment_14($confirmation, $form, $entry, $ajax)
{
    if (rgar($entry, 'payment_status') === 'Paid') {
        // Redirect to thank you page
        $confirmation = [
            'redirect' => 'https://staging13.tickets.wildrivers.com/sp-thank-you/?utm_user_email=' . rgpost('input_7') . '&utm_silver=' . rgpost('input_18') . '&utm_gold=' . rgpost('input_25') . '&utm_platinum=' . rgpost('input_28') . '&utm_parking=' . rgpost('input_43') . '&utm_total=' . rgpost('input_4') . '&orderID=' . rgpost('input_13') . '&fname=' . rgpost('input_5') . '&lname=' . rgpost('input_6') . '&phone=' . rgpost('input_8') . '&utm_silver_price=' . rgpost('input_19') . '&utm_gold_price=' . rgpost('input_24') . '&utm_platinum_price=' . rgpost('input_27') . '&utm_parking_price=' . rgpost('input_44')
        ];
    }
    return $confirmation;
}