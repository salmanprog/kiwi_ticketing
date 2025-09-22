<?php

//add_filter( 'gform_pre_submission_filter_16', 'change_form_16', 10, 3 );
add_filter('gform_after_submission_16', 'change_form_16', 10, 3);
//add_filter( 'gform_authorizenet_post_capture_16', 'change_form_16', 10, 3 );
function change_form_16($form)
{

    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/AnyDayAddOrder';

    $platinum_price_without_dollar = ltrim(rgpost('input_27'), '$');

    $platinum_pass_quantity = intval(rgpost('input_28'));

    $total_price_dollar_remove = ltrim(rgpost('input_4'), '$');

    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);


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
                'ticketType' => 'Any Day Ticket',
                'sectionId' => '0',
                'capacityId' => '0',
                'VisualId' => null,
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_28'),
                'amount' => $platinum_price_without_dollar
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

    );


    // Sending Data Condition Ends here
    $headers = array(
        'Content-Type' => 'application/json'
    );

    $body = json_encode($body);
    /* echo '<pre>';
     var_dump($body);
     echo '</pre>'; */

    $response = wp_remote_post($post_url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    /* echo '<pre>';
     var_dump($response_body);
     echo '</pre>';
     GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );*/

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
                                            We've received your order #<?php echo rgpost('input_13'); ?>
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

                                <tr>
                                    <td style="padding:5px">Any Day Ticket</td>
                                    <td style="padding:5px"><?php echo rgpost('input_28'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_27'); ?></td>
                                </tr>
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
                                                    <p style="margin: 0;"><?php echo $response_body['data']['tickets'][$j]['visualId'] ?>
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
                wp_mail($to, $subject, $email_body, 'Content-type: text/html');


                return $form;
            } // Payment Verificaion Checker

        } // Error Code Else

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
}
; // Function Bracket