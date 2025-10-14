<?php
/**
 * OceanWP Child Theme Functions
 *
 * When running a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions will be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function oceanwp_child_enqueue_parent_style()
{

    // Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update the theme).
    $theme = wp_get_theme('OceanWP');
    $version = $theme->get('Version');

    // Load the stylesheet.
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('oceanwp-style'), $version);

}

add_action('wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style');


define('CHILD_THEME_DIR', get_stylesheet_directory());

// Sending Data from Gravity Form to Galaxy Right now

// Its for Ticket

//add_filter('gform_pre_submission_filter_6', 'change_form', 10, 3);
add_filter('gform_after_submission_6', 'change_form', 10, 3);
//add_filter( 'gform_authorizenet_transaction_pre_capture', 'change_form', 10, 3 );
//add_filter( 'gform_authorizenet_post_capture', 'change_form', 10, 3 );
function change_form($form)
{

    $iac_order_id = rgpost('input_57');
    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/AddOrder';

    $ticket_price_without_dollar = ltrim(rgpost('input_28'), '$');
    $child_ticket_without_dollar = ltrim(rgpost('input_67_2'), '$');
    /*$pizza_ticket_without_dollar = ltrim(rgpost('input_69_2'), '$');*/
    $parking_ticket_without_dollar = ltrim(rgpost('input_70_2'), '$');
    //$senior_ticket_without_dollar = ltrim(rgpost('input_37_2'), '$');

    $ticket_quantity = rgpost('input_27');
    $child_quantity = rgpost('input_67_3');
    //$senior                 = rgpost( 'input_37_3' );

    //$total_price_without_dollar = ltrim(rgpost( 'input_15' ), '$');
    $total_price_dollar_remove = ltrim(rgpost('input_73'), '$');
    // $early_entry_ticket_without_dollar = ltrim(rgpost('input_78_2'), '$');
    // Removing (,) too
    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);


    $cabana_price_without_dollar = ltrim(rgpost('input_34'), '$');

    if ($cabana_price_without_dollar == 0.00) {
        $cabana_price_without_dollar = null;
    }

    // This is for all General, Junior and Cabana
    if (!empty($cabana_price_without_dollar)) {
        $body = array(
            'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
            'isOfficeUse' => false,
            'sessionId' => rgpost('input_49'),
            'orderId' => rgpost('input_57'),
            'customer' => array(
                'firstName' => rgpost('input_17'),
                'lastName' => rgpost('input_59'),
                'phone' => rgpost('input_19'),
                'email' => rgpost('input_18')
            ),
            'purchases' => [
                array(
                    'ticketType' => 'General',
                    'sectionId' => rgpost('input_48'),
                    'capacityId' => rgpost('input_50'),
                    'quantity' => rgpost('input_27'),
                    'amount' => $ticket_price_without_dollar
                ),
                array(
                    'ticketType' => 'Junior',
                    'sectionId' => rgpost('input_48'),
                    'capacityId' => rgpost('input_50'),
                    'quantity' => rgpost('input_67_3'),
                    'amount' => $child_ticket_without_dollar
                ),
                /*array(
                    'ticketType' => 'Pizza Pack',
                    'sectionId' => rgpost( 'input_48' ),
                    'capacityId' => rgpost( 'input_50' ),
                    'quantity' => rgpost( 'input_69_3' ),
                    'amount' => $pizza_ticket_without_dollar
                ),   */
                array(
                    'ticketType' => 'Parking Pass',
                    'sectionId' => rgpost('input_48'),
                    'capacityId' => rgpost('input_50'),
                    'quantity' => rgpost('input_70_3'),
                    'amount' => $parking_ticket_without_dollar
                ),
                array(
                    'ticketType' => rgpost('input_47'),
                    'sectionId' => rgpost('input_51'),
                    'capacityId' => rgpost('input_52'),
                    'quantity' => 1,
                    'amount' => $cabana_price_without_dollar
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
    } else {
        $body = array(
            'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
            'sessionId' => rgpost('input_49'),
            'orderId' => rgpost('input_57'),
            'customer' => array(
                'firstName' => rgpost('input_17'),
                'lastName' => rgpost('input_59'),
                'phone' => rgpost('input_19'),
                'email' => rgpost('input_18')
            ),
            'purchases' => [
                array(
                    'ticketType' => 'General',
                    'sectionId' => rgpost('input_48'),
                    'capacityId' => rgpost('input_50'),
                    'quantity' => rgpost('input_27'),
                    'amount' => $ticket_price_without_dollar
                ),
                array(
                    'ticketType' => 'Junior',
                    'sectionId' => rgpost('input_48'),
                    'capacityId' => rgpost('input_50'),
                    'quantity' => rgpost('input_67_3'),
                    'amount' => $child_ticket_without_dollar
                ),/*
              array(
              'ticketType' => 'Pizza Pack',
              'sectionId' => rgpost( 'input_48' ),
              'capacityId' => rgpost( 'input_50' ),
              'quantity' => rgpost( 'input_69_3' ),
              'amount' => $pizza_ticket_without_dollar
              ),   */
                array(
                    'ticketType' => 'Parking Pass',
                    'sectionId' => rgpost('input_48'),
                    'capacityId' => rgpost('input_50'),
                    'quantity' => rgpost('input_70_3'),
                    'amount' => $parking_ticket_without_dollar
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

    }

    // Sending Data Condition Ends here
    $headers = array(
        'Content-Type' => 'application/json'
    );
    //echo '<pre>';
    //var_dump($body);
    //echo '</pre>';
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

            $email_body = 'Hi, We may have issue with this order, Error Code Was Not Zero. Error Code:' . $response_body['status']['errorCode'] . ' & Error Message: ' . $response_body['status']['errorMessage'] . ' Please Check Further Details';
            //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
            $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com';
            //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
            $subject = 'Problem with Wild Rivers Order Number is #' . rgpost('input_57') . ' for ' . rgpost('input_1');

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

            $payment_verify_url = 'https://staging13.tickets.wildrivers.com/wp-json/gravityForms/getPaymentStatus?order_id=' . rgpost('input_57');
            $payment_verify_response = wp_remote_get($payment_verify_url);
            $payment_verify_response_body = json_decode(wp_remote_retrieve_body($payment_verify_response), true);

            ?>

            <?php

            if (isset($payment_verify_response_body['error_code']) && $payment_verify_response_body['error_code'] === 0) {
                // Getting visual id from API -----------------------------------------------
                $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=' . rgpost('input_57') . '&authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
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

                <?php // Email conditions With Cabana and Tickets
                                if ($cabana_price_without_dollar != null && !empty($cabana_price_without_dollar)) { ?>
                    <table cellpadding="0" cellspacing="0" width="600" align="center" class="_main_table">
                        <tr>
                            <td>
                                <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/05/email-header-email.jpg" alt="" />
                            </td>
                        </tr>
                        <tr style="background-color: #173767;">
                            <td style="padding: 30px;">
                                <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You For
                                    Purchasing - Your Order Number is #<?php echo rgpost('input_57'); ?></h1>
                            </td>
                        </tr>
                        <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                            <td style="padding:20px">
                                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="padding-bottom: 30px;">
                                            <p style="line-height: 16px;">
                                                Hi <?php echo rgpost('input_17'); ?>                     <?php echo rgpost('input_59'); ?>,<br />
                                                We've received your order #<?php echo rgpost('input_57'); ?>
                                                for <?php echo rgpost('input_1'); ?>. We look forward to seeing you.
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
                                    <?php if (!empty($ticket_price_without_dollar)) { ?>
                                        <tr>
                                            <td style="padding:5px">General Admission Tickets</td>
                                            <td style="padding:5px"><?php echo rgpost('input_27'); ?></td>
                                            <td style="padding:5px"><?php echo rgpost('input_28'); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (!empty($child_ticket_without_dollar)) { ?>
                                        <tr>
                                            <td style="padding:5px">Junior Tickets</td>
                                            <td style="padding:5px"><?php echo rgpost('input_67_3'); ?></td>
                                            <td style="padding:5px"><?php echo rgpost('input_67_2'); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (!empty($parking_ticket_without_dollar)) { ?>
                                        <tr>
                                            <td style="padding:5px">Parking Pass</td>
                                            <td style="padding:5px"><?php echo rgpost('input_70_3'); ?></td>
                                            <td style="padding:5px"><?php echo rgpost('input_70_2'); ?></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if (rgpost('input_29') == 'Yes') { ?>

                                        <tr>
                                            <td style="padding:5px">
                                                Cabana - <?php echo rgpost('input_47'); ?> (<?php echo rgpost('input_46'); ?>)
                                            </td>
                                            <td style="padding:5px">
                                                1
                                            </td>
                                            <td style="padding:5px">
                                                <?php echo rgpost('input_34'); ?>
                                            </td>
                                        </tr>

                                    <?php } ?>


                                    <tr>
                                        <td colspan="2" style="padding:5px">
                                            <strong>Service Fee:</strong>
                                        </td>
                                        <td style="padding:5px">
                                            $0
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
                                            <a href="https://staging13.tickets.wildrivers.com/update-order/?order=<?php echo rgpost('input_57'); ?>"
                                                style="margin-bottom: 10px;">Click here to Modify</a>
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
                                        <?php echo rgpost('input_17'); ?>                     <?php echo rgpost('input_59'); ?> <br />
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
                <?php // Email with only Tickets (NO Cabana)
                                } else { ?>
                    <table cellpadding="0" cellspacing="0" width="600" align="center" class="_main_table">
                        <tr>
                            <td>
                                <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/05/email-header-email.jpg" alt="" />
                            </td>
                        </tr>
                        <tr style="background-color: #173767;">
                            <td style="padding: 30px;">
                                <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You For
                                    Purchasing - Your Order Number is #<?php echo rgpost('input_57'); ?></h1>
                            </td>
                        </tr>
                        <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                            <td style="padding:20px">
                                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="padding-bottom: 30px;">
                                            <p style="line-height: 16px;">
                                                Hi <?php echo rgpost('input_17'); ?>                     <?php echo rgpost('input_59'); ?>,<br />
                                                We've received your order #<?php echo rgpost('input_57'); ?>
                                                for <?php echo rgpost('input_1'); ?>. We look forward to seeing you.
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
                                    <?php if (!empty($ticket_price_without_dollar)) { ?>
                                        <tr>
                                            <td style="padding:5px">General Admission Tickets</td>
                                            <td style="padding:5px"><?php echo rgpost('input_27'); ?></td>
                                            <td style="padding:5px"><?php echo rgpost('input_28'); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (!empty($child_ticket_without_dollar)) { ?>
                                        <tr>
                                            <td style="padding:5px">Junior Tickets</td>
                                            <td style="padding:5px"><?php echo rgpost('input_67_3'); ?></td>
                                            <td style="padding:5px"><?php echo rgpost('input_67_2'); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (!empty($parking_ticket_without_dollar)) { ?>
                                        <tr>
                                            <td style="padding:5px">Parking Pass</td>
                                            <td style="padding:5px"><?php echo rgpost('input_70_3'); ?></td>
                                            <td style="padding:5px"><?php echo rgpost('input_70_2'); ?></td>
                                        </tr>
                                    <?php } ?>



                                    <tr>
                                        <td colspan="2" style="padding:5px">
                                            <strong>Service Fee:</strong>
                                        </td>
                                        <td style="padding:5px">
                                            $0
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
                                            <a href="https://staging13.tickets.wildrivers.com/update-order/?order=<?php echo rgpost('input_57'); ?>"
                                                style="margin-bottom: 10px;">Click here to Modify</a>
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
                                        <?php echo rgpost('input_17'); ?>                     <?php echo rgpost('input_59'); ?> <br />
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
                                }
                                ?>



                <?php

                $email_body = ob_get_clean();


                //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
                $to = rgpost('input_18');
                $subject = 'Thank You For Purchasing - Your Wild Rivers Order Number is #' . rgpost('input_57') . ' for ' . rgpost('input_1');

                //    This is going to sent the email-----------------------------------------------------------------------------------
                wp_mail($to, $subject, $email_body, 'Content-type: text/html');


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
        $subject = 'NULL Response. Problem with Wild Rivers Order Number is #' . rgpost('input_17') . ' for ' . rgpost('input_1');

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

// TICKET BOOKING ADD ORDER - OFFICE ONLY
require_once CHILD_THEME_DIR . '/API_libs/ticket-booking-add-order-office-only.php';

// Twilight TICKET BOOKING ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/twilight-ticket-booking-add-order.php';

// SUPER SLIDE TICKET BOOKING ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/super-slide-ticket-booking-add-order.php';

// DELETE ORDER WITH 3DS ISSUE
//require_once CHILD_THEME_DIR . '/API_libs/delete-order-with-3ds-issue.php';


// Loading the Tickets from the ORDER so the user can edit.
// This functions is bieng called from ajax
add_action('wp_ajax_cabana_order_loader', 'cabana_order_loader_function');
add_action('wp_ajax_nopriv_cabana_order_loader', 'cabana_order_loader_function');
function cabana_order_loader_function()
{
    $url = $_POST['url'];
    $response = wp_remote_get($url);

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    // HTML is built here -----------------------------------------------------------
    //
    ob_start();


    ?>


    <script type="text/javascript">
        jQuery(document).ready(function () {

            var disableddates = [
                "19-05-2025", "20-05-2025", "21-05-2025", "22-05-2025", "23-05-2025", "27-05-2025",
                "28-05-2025", "29-05-2025", "30-05-2025", "26-08-2025", "27-08-2025", "02-09-2025",
                "03-09-2025", "04-09-2025", "05-09-2025", "08-09-2025", "09-09-2025", "10-09-2025",
                "11-09-2025", "12-09-2025", "15-09-2025", "16-09-2025", "17-09-2025", "18-09-2025",
                "19-09-2025", "22-09-2025", "23-09-2025", "24-09-2025", "25-09-2025", "26-09-2025",
                "29-09-2025", "30-09-2025", "01-10-2025", "02-10-2025", "03-10-2025", "06-10-2025",
                "07-10-2025", "08-10-2025", "09-10-2025", "10-10-2025"
            ];

            function DisableSpecificDates(date) {
                var string = jQuery.datepicker.formatDate('dd-mm-yy', date);
                return [disableddates.indexOf(string) == -1];
            }

            jQuery(function () {
                // Shared values
                const absoluteMin = new Date('2025-05-17');
                const today = new Date();
                const dynamicMin = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 2);
                const finalMinDate = dynamicMin > absoluteMin ? dynamicMin : absoluteMin;

                // 1. Default for all non-food items
                jQuery(".idu_ticket_date").not('.food_datepicker').datepicker({
                    yearRange: '2025:c',
                    minDate: absoluteMin,
                    beforeShowDay: DisableSpecificDates,
                    dateFormat: "yy-mm-dd",
                    maxDate: '2025-10-12'
                });

                // 2. Special config for food item fields
                jQuery(".idu_ticket_date.food_datepicker").datepicker({
                    yearRange: '2025:c',
                    minDate: finalMinDate,
                    beforeShowDay: DisableSpecificDates,
                    dateFormat: "yy-mm-dd",
                    maxDate: '2025-10-12'
                });
            });

        });
    </script>



    <div class="idu_main_container">

        <?php if ($response_body['data']['isDeleted'] != 0) {
            echo '<p style="color: red;text-align: center;font-size: 19px;font-family: montserrat;">Order Not Found!</p>';
        } else { ?>


            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderNumber']; ?>"
                class="_order_number" />
            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderTotal']; ?>"
                class="_order_total" />
            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderDisplayDate']; ?>"
                class="_order_date" />

            <?php
            for ($i = 0; $i < count($response_body['data']['tickets']); $i++) {

                $visualID = $response_body['data']['tickets'][$i]['visualId'];
                $ticket_type = $response_body['data']['tickets'][$i]['ticketType'];
                $ticket_price = $response_body['data']['tickets'][$i]['price'];
                $ticket_details = $response_body['data']['tickets'][$i]['description'];

                $ticket_details_array = explode(' ', $response_body['data']['tickets'][$i]['description']);
                //$cabana_number = $ticket_details_array[3];
                $cabana_number = $response_body['data']['tickets'][$i]['seat'];

                $ticket_date = explode("T", $response_body['data']['tickets'][$i]['ticketDate']);
                $ticket_date = $ticket_date[0];
                $order_date = explode("T", $response_body['data']['tickets'][$i]['orderDate']);
                $order_date = $order_date[0];

                ?>

                <?php if ($response_body['data']['tickets'][$i]['isQrCodeBurn'] == 0 && $response_body['data']['tickets'][$i]['isOrderDelete'] == 0) { ?>
                    <div class="idu_single_ticket_section">
                        <div class="_ticket_type">Ticket Type: <span class="cka_ticket_type"><?php echo $ticket_type; ?></span>
                            <img src="https://quickchart.io/qr?text=<?php echo $visualID; ?>&margin=2&size=100" alt="Test" />
                        </div>
                        <div class="_ticket_desc">Ticket Details: <?php echo $ticket_details; ?>                 <?php echo $cabana_number; ?></div>
                        <div class="_ticket_date_update_wrapper">

                            <?php

                            $ticket_date_formatd = strtotime(date('Y-m-d', strtotime($ticket_date)));
                            $current_date = strtotime(date('Y-m-d'));

                            if ($ticket_date_formatd <= $current_date) {
                                echo 'You can not edit';
                            } else {

                                ?>

                                <span class="_ticket_date">Ticket Date: </span>
                                <input type="hidden" value="<?php echo $visualID; ?>" class="_ticket_visual_id" />
                                <input type="hidden" value="" class="_ticket_session_id" />
                                <input type="hidden" value="" class="_ticket_section_id" />
                                <input type="hidden" value="" class="_ticket_capacity_id" />
                                <input type="hidden" value="<?php echo $ticket_price; ?>" class="_ticket_price" />
                                <input type="hidden" value="" class="_new_update_ticket_price" />
                                <input type="hidden" value="<?php echo $cabana_number; ?>" class="_cabana_number">

                                <?php if ($ticket_date <= date('Y-m-d')) {
                                    echo "<p style='color: #fff;font-weight: 600;font-family: montserrat;margin: 0;background: #d60000;font-size: 12px;padding: 5px;'>This Ticket Can't be modified</p>";
                                } else { ?>



                                    <?php // DATE PICKER ?>
                                    <?php if ($ticket_type == 'Birthday Cake' || $ticket_type == 'Nacho Platter Large' || $ticket_type == 'Nacho Platter Small' || $ticket_type == 'Carne Asada Taco Platter' || $ticket_type == 'Mini Corn Dogs' || $ticket_type == 'Caesar Salad W/Chk' || $ticket_type == 'Caesar Salad W/O Chk' || $ticket_type == 'Chicken Tender w/FF Lg' || $ticket_type == 'Chicken Tender w/FF Sm' || $ticket_type == 'Chicken Wing Platter Lg' || $ticket_type == 'Chicken Wing Platter Sm' || $ticket_type == 'Cookie Platter' || $ticket_type == 'Southwest Salad w/Chk' || $ticket_type == 'Fruit Platter Small' || $ticket_type == 'Fruit Platter Large' || $ticket_type == ' Mini Corn Dog Platter') { ?>
                                        <!--FOOD Items only-->
                                        <input type="date" value="<?php echo $ticket_date ?>"
                                            class="idu_ticket_date food_datepicker cka_<?php echo $i + 1; ?>" name="idu_ticket_date" />
                                    <?php } else { ?>
                                        <!--ALL TICKETS-->
                                        <input type="date" value="<?php echo $ticket_date ?>" class="idu_ticket_date cka_<?php echo $i + 1; ?>"
                                            name="idu_ticket_date" />
                                    <?php } ?>


                                    <?php if ($ticket_type == 'Kontiki Yellow Cabanas') { ?>
                                        <span class="_cabana_type_label">Cabana Type:</span>
                                        <select name="change-cabana-type" class="_slct_option_ticket_Type _slct_change_cabanan" aria-invalid="false"
                                            data-conditional-logic="visible">
                                            <option value="Kontiki Yellow Cabanas">Kontiki Yellow Cabanas</option>
                                            <option value="Cooks Cove Orange Cabanas">Cooks Cove Orange Cabanas</option>
                                            <option value="River Green Cabanas">River Green Cabanas</option>
                                            <option value="Wave Pool Aqua Cabanas">Wave Pool Aqua Cabanas</option>
                                            <option value="Wave Pool Blue Cabanas">Wave Pool Blue Cabanas</option>
                                        </select>
                                        <div class="_update_display_cabana"></div>
                                    <?php } elseif ($ticket_type == 'Cooks Cove Orange Cabanas') { ?>
                                        <span class="_cabana_type_label">Cabana Type:</span>
                                        <select name="change-cabana-type" class="_slct_option_ticket_Type _slct_change_cabanan" aria-invalid="false"
                                            data-conditional-logic="visible">
                                            <option value="Cooks Cove Orange Cabanas">Cooks Cove Orange Cabanas</option>
                                            <option value="Kontiki Yellow Cabanas">Kontiki Yellow Cabanas</option>
                                            <option value="River Green Cabanas">River Green Cabanas</option>
                                            <option value="Wave Pool Aqua Cabanas">Wave Pool Aqua Cabanas</option>
                                            <option value="Wave Pool Blue Cabanas">Wave Pool Blue Cabanas</option>
                                        </select>
                                        <div class="_update_display_cabana"></div>
                                    <?php } elseif ($ticket_type == 'River Green Cabanas') { ?>
                                        <span class="_cabana_type_label">Cabana Type:</span>
                                        <select name="change-cabana-type" class="_slct_option_ticket_Type _slct_change_cabanan" aria-invalid="false"
                                            data-conditional-logic="visible">
                                            <option value="River Green Cabanas">River Green Cabanas</option>
                                            <option value="Cooks Cove Orange Cabanas">Cooks Cove Orange Cabanas</option>
                                            <option value="Kontiki Yellow Cabanas">Kontiki Yellow Cabanas</option>
                                            <option value="Wave Pool Aqua Cabanas">Wave Pool Aqua Cabanas</option>
                                            <option value="Wave Pool Blue Cabanas">Wave Pool Blue Cabanas</option>
                                        </select>
                                        <div class="_update_display_cabana"></div>
                                    <?php } elseif ($ticket_type == 'Wave Pool Aqua Cabanas') { ?>
                                        <span class="_cabana_type_label">Cabana Type:</span>
                                        <select name="change-cabana-type" class="_slct_option_ticket_Type _slct_change_cabanan" aria-invalid="false"
                                            data-conditional-logic="visible">
                                            <option value="Wave Pool Aqua Cabanas">Wave Pool Aqua Cabanas</option>
                                            <option value="Cooks Cove Orange Cabanas">Cooks Cove Orange Cabanas</option>
                                            <option value="Kontiki Yellow Cabanas">Kontiki Yellow Cabanas</option>
                                            <option value="River Green Cabanas">River Green Cabanas</option>
                                            <option value="Wave Pool Blue Cabanas">Wave Pool Blue Cabanas</option>
                                        </select>
                                        <div class="_update_display_cabana"></div>
                                    <?php } elseif ($ticket_type == 'Wave Pool Blue Cabanas') { ?>
                                        <span class="_cabana_type_label">Cabana Type:</span>
                                        <select name="change-cabana-type" class="_slct_option_ticket_Type _slct_change_cabanan" aria-invalid="false"
                                            data-conditional-logic="visible">
                                            <option value="Wave Pool Blue Cabanas">Wave Pool Blue Cabanas</option>
                                            <option value="Cooks Cove Orange Cabanas">Cooks Cove Orange Cabanas</option>
                                            <option value="Kontiki Yellow Cabanas">Kontiki Yellow Cabanas</option>
                                            <option value="River Green Cabanas">River Green Cabanas</option>
                                            <option value="Wave Pool Aqua Cabanas">Wave Pool Aqua Cabanas</option>
                                        </select>
                                        <div class="_update_display_cabana"></div>
                                    <?php } else { ?>
                                        <span class="_cabana_type_label">Ticket Type:</span>
                                        <select name="change-cabana-type" class="_slct_option_ticket_Type" aria-invalid="false"
                                            data-conditional-logic="visible" readonly>
                                            <option value="<?php echo $ticket_type; ?>"><?php echo $ticket_type; ?></option>
                                        </select>

                                    <?php } ?>

                                    <input type="submit" class="_btn_hold_to_change_date cka_<?php echo $i + 1; ?>" name="hold_to_change_date"
                                        value="Check availability" />
                                    <input type="submit" class="idu_submit_button" name="idu_submit_button" style="display:none"
                                        value="Update Date" />

                                <?php } ?>

                                <?php // echo date('Y-m-d') . "<br>"; ?>
                                <?php //echo $ticket_date ?>





                                <?php
                            }

                            ?>

                        </div>


                    </div>

                <?php } ?>



                <?php
            }
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {



                    jQuery('.idu_ticket_date').on('change', function () {
                        var selectedText = jQuery(this).next('span').next('select').val();
                        // alert(selectedText);
                        if (selectedText === "Wave Pool Blue Cabanas" || selectedText === "Cooks Cove Orange Cabanas" || selectedText === "Kontiki Yellow Cabanas" || selectedText === "River Green Cabanas" || selectedText === "Wave Pool Aqua Cabanas") {
                            var $select = jQuery(this).next('span').next('select');

                            if ($select.length) {
                                // Remove existing one to avoid duplicates
                                $select.find('option[value="select_cabana"]').remove();

                                // Prepend and auto-select it
                                $select.prepend('<option value="select_cabana" selected>Select Cabana</option>');
                                jQuery('._update_display_cabana').empty();
                                // Optional: trigger change if needed
                                $select.trigger('change');
                            }
                        }
                    });

                    jQuery('._slct_option_ticket_Type._slct_change_cabanan').on('change', function () {


                        var cabana_type = jQuery(this).val();
                        var selectDateForCabana = jQuery(this).closest('.idu_single_ticket_section').find('.idu_ticket_date').val();

                        console.log(cabana_type);
                        console.log(selectDateForCabana);

                        // If we need Cabana Price
                        //selected_cabana_price_for_date = jQuery(this).attr('cabana-price');
                        //let url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/GetCabanaOccupancy?cabanaType=South%20Beach&date=2024-01-31";

                        let url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/GetCabanaOccupancy?cabanaType=" + cabana_type + "&date=" + selectDateForCabana + "&Authcode=d063d05b-fbb1-4bf0-b4d8-b2f603454858";

                        let value = {
                            'action': 'cabana_seats_loader',
                            'url': url
                        };

                        jQuery.post('https://staging13.tickets.wildrivers.com/wp-admin/admin-ajax.php', value, function (response) {

                            let value = jQuery.parseJSON(response);
                            console.log(value);

                            if (value.status.errorCode == '200') {
                                alert('We are Sorry, Date you have selected for "' + cabana_type + '" is not Available!');
                                // jQuery('._not_available_popup').fadeIn();

                            } else {

                                var html_to_append = '';
                                //console.log(value.data.availableSeats[0].seatNo[0].seatNo);
                                var array = value.data.availableSeats[0].seatNo[0].seatNo.split(",");
                                for (i = 0; i < array.length; i++) {
                                    //html_to_append += '<a href="javascript:void(0)" class="_available_cabana" cabana-price="'+selected_cabana_price_for_date+'">' + array[i] + '</a>';
                                    //html_to_append += '<a href="javascript:void(0)" class="_available_cabana" cabana-price="">' + array[i] + '</a>';
                                    html_to_append += '<div class="_update_cabana_div"><input type="radio" class="_cabana_checkbox" name="cabana[]" value="' + array[i] + '" ><span>' + array[i] + '</span></div>';
                                }
                                jQuery('._update_display_cabana').html(html_to_append);

                            }

                        });

                    });



                });

            </script>

        <?php } ?>

    </div>




    <?php
    $html = ob_get_clean();
    echo json_encode($html);
    die();
}

add_action('wp_ajax_seat_hold_for_change', 'seat_hold_for_change_function');
add_action('wp_ajax_nopriv_seat_hold_for_change', 'seat_hold_for_change_function');
function seat_hold_for_change_function()
{
    $url = $_POST['url'];
    $ticketTypeGeneral = $_POST['ticketTypeGeneral'];
    $quantityTickets = $_POST['quantityTickets'];

    $cabanaSeatNumber = $_POST['cabanaSeatNumber'];

    //  Creating cabanas in the remote server ------------ to get cabana id ---- id will be necessary later ----$url = "http://dynamicpricing.us-east-1.elasticbeanstalk.com/Pricing/AddVenue?authCode=QwLrBmyA";

    $headers = array(
        'Content-Type' => 'application/json'
    );

    if ($cabanaSeatNumber != null && !empty($cabanaSeatNumber)) {
        $body = array(
            'sessionId' => '0',
            'ticketHoldItem' => [
                array(
                    'ticketType' => $ticketTypeGeneral,
                    'quantity' => $quantityTickets,
                    'seat' => $cabanaSeatNumber

                )
            ]
        );
    } else {
        $body = array(
            'sessionId' => '0',
            'ticketHoldItem' => [
                array(
                    'ticketType' => $ticketTypeGeneral,
                    'quantity' => $quantityTickets,
                    'seat' => ''

                )
            ]
        );
    }


    $body = json_encode($body);

    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    echo json_encode($response_body);

    die();
}



add_action('wp_ajax_update_ticket_date', 'update_ticket_date_function');
add_action('wp_ajax_nopriv_update_ticket_date', 'update_ticket_date_function');

function update_ticket_date_function()
{
    $url = $_POST['url'];
    $sessionId = $_POST['sessionId'];
    $visualId = $_POST['visualId'];
    $sectionId = $_POST['sectionId'];
    $capacityId = $_POST['capacityId'];
    $prevOrderNumber = $_POST['previousOrderNumber'];
    $newOrderNumber = $_POST['newOrderNumber'];
    $ticketAmount = $_POST['ticketAmount'];
    $OrderTotal = $_POST['amount'];
    $dateValue = $_POST['date'];
    $ticket_type = $_POST['ticketType'];

    $headers = array(
        'accept' => '*/*',
        'Content-Type' => 'application/json'
    );

    $body = array(
        'sessionId' => $sessionId,
        'previousOrderNumber' => $prevOrderNumber,
        'orderNumber' => $newOrderNumber,
        'transactionId' => '',
        'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
        'date' => $dateValue,
        'ticketChanges' => [
            array(
                'ticketType' => $ticket_type,
                'visualId' => $visualId,
                'sectionId' => $sectionId,
                'capacityId' => $capacityId,
                'date' => $dateValue,
                //'amount' => $ticketAmount
                'amount' => 0
            )
        ],
        'payment' => array(
            'cardholerName' => 'Omitted',
            'billingStreet' => '',
            'billingZipCode' => '',
            'cvn' => 'Omitted',
            'expDate' => 'Omitted',
            'ccNumber' => 'Omitted',
            'paymentCode' => '32',
            'amount' => 0
        )
    );

    $body = json_encode($body);

    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    echo json_encode($response_body);
    //echo $body;
    die();
}


// Send Order Update Email

add_action('wp_ajax_update_order_email_date', 'update_order_email_date');
add_action('wp_ajax_nopriv_update_order_email_date', 'update_order_email_date');
function update_order_email_date()
{

    $OrderID = $_POST['Order_ID'];


    //Getting visual id from API -----------------------------------------------
    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=' . $OrderID . '&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';

    $response = wp_remote_get($post_url);

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $cabanaVisualId = $response_body['data']['tickets'][0]['visualId'];
    $cabanaDescription = $response_body['data']['tickets'][0]['description'];
    $cabanaDate = $response_body['data']['tickets'][0]['ticketDisplayDate'];

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
                <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Your Order Has
                    Been Successully Updated. Your Order is Number#<?php echo $OrderID ?></h1>
            </td>
        </tr>
        <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
            <td style="padding:20px">
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding-bottom: 30px;">
                            <p style="line-height: 16px;">
                                Hi <?php echo $response_body['data']['firstName'] ?>,<br />
                                We have updated dates in your order (<?php echo $OrderID ?>). We look forward to seeing you.
                            </p>
                        </td>
                    </tr>
                </table>
                <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb">
                    <tr>
                        <td style="padding:5px" colspan="3">
                            <h4>See / Modify Tickets</h4>
                            <p style="padding:0; margin:0">To modify the date of your visit, please use the link
                                provided on your emailed receipt. Must change more than 24 hours in advance. tickets
                                purchased within 24 hours will not be able to be rescheduled.</p>
                            <a href="https://staging13.tickets.wildrivers.com/update-order/?order=<?php echo $OrderID ?>"
                                target="_blank" style="margin-bottom: 10px;">Click here to Modify</a>
                            <ul class="_ticket_code" style="text-align:center;padding: 0;list-style: none;">
                                <?php
                                for ($k = 0; $k < count($response_body['data']['tickets']); $k++) {
                                    ?>
                                    <li style="margin: 0; border-bottom: 1px solid #b2b2b2; padding-bottom: 5px;">
                                        <br />
                                        <br />
                                        <br />
                                        <br />

                                        <h5>Ticket #<?php echo $k + 1; ?>
                                            - <?php echo $response_body['data']['tickets'][$k]['description'] ?>
                                        </h5>
                                        <img src="https://quickchart.io/qr?text=<?php echo $response_body['data']['tickets'][$k]['visualId'] ?>&margin=2&size=150"
                                            alt="" />
                                        <p style="margin:0;"><?php echo $response_body['data']['tickets'][$k]['visualId'] ?> -
                                            <?php echo $response_body['data']['tickets'][$k]['ticketDisplayDate'] ?>
                                        </p>
                                        <br />
                                        <br />
                                        <br />
                                        <br>
                                    </li>
                                    <?php
                                } ?>
                            </ul>
                        </td>
                    </tr>



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
    $to = $response_body['data']['email'];
    $subject = 'Your Order Date Has Been Updated Successfully For Order #' . $OrderID;

    //    This is going to sent the email-----------------------------------------------------------------------------------
    wp_mail($to, $subject, $email_body, 'Content-type: text/html');
}
;


// Send PDF every hour for sales report
// The schedule filter hook

/*
add_filter( 'cron_schedules', 'isa_add_every_hour' );
function isa_add_every_hour( $schedules ) {
    $schedules['every_hour'] = array(
            'interval'  => 3600,
            'display'   => __( 'Every 60 Minutes', 'textdomain' )
    );
    return $schedules;
}
if ( ! wp_next_scheduled( 'isa_add_every_hour' ) ) {
    wp_schedule_event( time(), 'every_hour', 'isa_add_every_hour' );
}

*/


/*
add_action( 'isa_add_every_hour', 'every_hour_event_func' );

// The WP Cron event callback function
function every_hour_event_func() {

    global $wpdb;
    $entry_table_name = $wpdb->prefix . 'gf_entry';
    $tz_offset = get_option( 'gmt_offset' ). ':00';
    $form_id = 6;

    //per hour
    $summary_per_hour = $wpdb->get_results(
                $wpdb->prepare(
                    "
                        select transaction.date, leads.orders, leads.subscriptions, transaction.revenue, transaction.refund, transaction.refunds FROM ( SELECT CONVERT_TZ(payment_date, '+00:00', '-7:00') as date, sum( if(transaction_type = 1,1,0) ) as orders, sum( if(transaction_type = 2,1,0) ) as subscriptions, TIMESTAMPDIFF(MINUTE, payment_date, now()) as time1 FROM fvi_gf_entry WHERE status='active' AND form_id = %d AND TIMESTAMPDIFF(MINUTE, payment_date, now()) < 60 GROUP BY date ) AS leads RIGHT OUTER JOIN( SELECT CONVERT_TZ(t.date_created, '+00:00', '-7:00') as date, sum( if(t.transaction_type = 'refund', abs(t.amount) * -1, t.amount) ) as revenue,
                           sum( if(t.transaction_type = 'refund', t.amount, 0) ) as refund,
                           sum( if(t.transaction_type = 'refund', 1,0) ) as refunds FROM fvi_gf_addon_payment_transaction t INNER JOIN fvi_gf_entry l ON l.id = t.lead_id WHERE l.form_id=%d AND l.status='active' AND TIMESTAMPDIFF(MINUTE, t.date_created, now()) < 60 GROUP BY date ) AS transaction on leads.date = transaction.date ORDER BY date desc", $form_id, $form_id
                ), ARRAY_A
            );

    //todays, yesterday, 30 days
    $summary = $wpdb->get_results(
                $wpdb->prepare(
                    "
                        SELECT transaction.date, leads.orders, leads.subscriptions, transaction.revenue, transaction.refund, transaction.refunds
                        FROM (
                           SELECT  date( CONVERT_TZ(payment_date, '+00:00', '" . $tz_offset . "') ) as date,
                                   sum( if(transaction_type = 1,1,0) ) as orders,
                                   sum( if(transaction_type = 2,1,0) ) as subscriptions
                           FROM {$entry_table_name}
                           WHERE status='active' AND form_id = %d AND datediff(now(), CONVERT_TZ(payment_date, '+00:00', '" . $tz_offset . "') ) <= 30
                           GROUP BY date
                         ) AS leads

                         RIGHT OUTER JOIN(
                           SELECT  date( CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "') ) as date,
                                   sum( if(t.transaction_type = 'refund', abs(t.amount) * -1, t.amount) ) as revenue,
                                   sum( if(t.transaction_type = 'refund', t.amount, 0) ) as refund,
                                   sum( if(t.transaction_type = 'refund', 1,0) ) as refunds
                           FROM {$wpdb->prefix}gf_addon_payment_transaction t
                             INNER JOIN {$entry_table_name} l ON l.id = t.lead_id
                           WHERE l.form_id=%d AND l.status='active'
                           GROUP BY date
                         ) AS transaction on leads.date = transaction.date
                        ORDER BY date desc", $form_id, $form_id
                ), ARRAY_A
            );

    $total_summary = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT sum( if(transaction_type = 1,1,0) ) as orders,
                       sum( if(transaction_type = 2,1,0) ) as subscriptions
                FROM {$entry_table_name}
                WHERE form_id=%d AND status='active'", $form_id
        ), ARRAY_A
    );

    $total_revenue = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT sum( if(t.transaction_type = 'refund', abs(t.amount) * -1, t.amount) ) as revenue,
                   sum( if(t.transaction_type = 'refund', t.amount, 0) ) as refund,
                   sum( if(t.transaction_type = 'refund', 1,0) ) as refunds
                FROM {$wpdb->prefix}gf_addon_payment_transaction t
                INNER JOIN {$entry_table_name} l ON l.id = t.lead_id
                WHERE l.form_id=%d AND status='active'", $form_id
        ), ARRAY_A
    );

    $result = array(
        'OneHour' => array( 'revenue' => GFCommon::to_money( 0 ), 'orders' => 0, 'subscriptions' => 0 ),
        'today'     => array( 'revenue' => GFCommon::to_money( 0 ), 'orders' => 0, 'subscriptions' => 0 ),
        'yesterday' => array( 'revenue' => GFCommon::to_money( 0 ), 'orders' => 0, 'subscriptions' => 0 ),
        'last30'    => array( 'revenue' => 0, 'orders' => 0, 'subscriptions' => 0 ),
        'total'     => array(
            'revenue'       => GFCommon::to_money( $total_revenue[0]['revenue']),
            'orders'        => $total_summary[0]['orders'],
            'subscriptions' => $total_summary[0]['subscriptions'],
            'refund'       => GFCommon::to_money( $total_revenue[0]['refund'] ),
            'refunds'       => GFCommon::to_money( $total_revenue[0]['refunds'] )

        )
    );

    $local_time = GFCommon::get_local_timestamp();
    $today      = gmdate( 'Y-m-d', $local_time );
    $yesterday  = gmdate( 'Y-m-d', strtotime( '-1 day', $local_time ) );

    $summary_get_general_tickets = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT wp.id, entry_id, wp.date_created, wm.meta_key, wm.meta_value FROM `fvi_gf_entry` wp
INNER JOIN `fvi_gf_entry_meta` wm ON (wm.`entry_id` = wp.`id` AND wm.`meta_key`='68.1' || wm.`entry_id` = wp.`id` AND wm.`meta_key`='68.2' || wm.`entry_id` = wp.`id` AND wm.`meta_key`='68.3')
ORDER BY wp.date_created DESC"
                ), ARRAY_A
            );

    foreach ( $summary_get_general_tickets as $summary_get_id ) {

        $date_loop = date("Y-m-d", strtotime($summary_get_id['date_created']) );

        if ( $date_loop == $today ) {
            if($summary_get_id['meta_key'] == "68.2"){
                $price=str_replace('$','', $summary_get_id['meta_value'] );
                $result['today']['general_tickets']['price'] += $price;
            }if($summary_get_id['meta_key'] == "68.3"){
                $result['today']['general_tickets']['quantity']  += $summary_get_id['meta_value'];
            }
        } elseif ( $date_loop == $yesterday ) {
            if($summary_get_id['meta_key'] == "68.2"){
                $price=str_replace('$','', $summary_get_id['meta_value'] );
                $result['yesterday']['general_tickets']['price']  += $price;
            }if($summary_get_id['meta_key'] == "68.3"){
                $result['yesterday']['general_tickets']['quantity']  += $summary_get_id['meta_value'] ;
            }
        }

        $is_within_30_days = strtotime( $date_loop ) >= strtotime( '-30 days', $local_time );
        if ( $is_within_30_days ) {
            if($summary_get_id['meta_key'] == "68.2"){
                $price=str_replace('$','', $summary_get_id['meta_value'] );
                $result['last30']['general_tickets']['price']  += $price;
            }if($summary_get_id['meta_key'] == "68.3"){
                $result['last30']['general_tickets']['quantity']  += $summary_get_id['meta_value'] ;
            }
        }
    }

    $summary_get_service_fees = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT wp.id, entry_id, wp.date_created, wm.meta_key, wm.meta_value FROM `fvi_gf_entry` wp
INNER JOIN `fvi_gf_entry_meta` wm ON (wm.`entry_id` = wp.`id` AND wm.`meta_key`='58.1' || wm.`entry_id` = wp.`id` AND wm.`meta_key`='58.2' || wm.`entry_id` = wp.`id` AND wm.`meta_key`='58.3')
ORDER BY wp.date_created DESC"
                ), ARRAY_A
            );

    foreach ( $summary_get_service_fees as $summary_get_id ) {

        $date_loop = date("Y-m-d", strtotime($summary_get_id['date_created']) );

        if ( $date_loop == $today ) {
            if($summary_get_id['meta_key'] == "58.2"){
                $price=str_replace('$','', $summary_get_id['meta_value'] );
                $result['today']['service_fees']['price'] += $price;
            }if($summary_get_id['meta_key'] == "58.3"){
                $result['today']['service_fees']['quantity']  += $summary_get_id['meta_value'];
            }
        } elseif ( $date_loop == $yesterday ) {
            if($summary_get_id['meta_key'] == "58.2"){
                $price=str_replace('$','', $summary_get_id['meta_value'] );
                $result['yesterday']['service_fees']['price']  += $price;
            }if($summary_get_id['meta_key'] == "58.3"){
                $result['yesterday']['service_fees']['quantity']  += $summary_get_id['meta_value'] ;
            }
        }

        $is_within_30_days = strtotime( $date_loop ) >= strtotime( '-30 days', $local_time );
        if ( $is_within_30_days ) {
            if($summary_get_id['meta_key'] == "58.2"){
                $price=str_replace('$','', $summary_get_id['meta_value'] );
                $result['last30']['service_fees']['price']  += $price;
            }if($summary_get_id['meta_key'] == "58.3"){
                $result['last30']['service_fees']['quantity']  += $summary_get_id['meta_value'] ;
            }
        }
    }

    foreach ( $summary as $day ) {

        if ( $day['date'] == $today ) {
            $result['today']['revenue']       = GFCommon::to_money( $day['revenue'] );
            $result['today']['orders']        = $day['orders'];
            $result['today']['subscriptions'] = $day['subscriptions'];
            $result['today']['refunds'] = $day['refunds'];
            $result['today']['refund'] = GFCommon::to_money($day['refund']);
        } elseif ( $day['date'] == $yesterday ) {
            $result['yesterday']['revenue']       = GFCommon::to_money( $day['revenue'] );
            $result['yesterday']['orders']        = $day['orders'];
            $result['yesterday']['subscriptions'] = $day['subscriptions'];
            $result['yesterday']['refunds'] = $day['refunds'];
            $result['yesterday']['refund'] = GFCommon::to_money($day['refund']);
        }

        $is_within_30_days = strtotime( $day['date'] ) >= strtotime( '-30 days', $local_time );
        if ( $is_within_30_days ) {
            $result['last30']['revenue'] += floatval( $day['revenue'] );
            $result['last30']['orders'] += floatval( $day['orders'] );
            $result['last30']['subscriptions'] += floatval( $day['subscriptions'] );
            $result['last30']['refund'] += floatval( $day['refund'] );
            $result['last30']['refunds'] += $day['refunds'];
        }
    }
    $result['last30']['revenue'] = GFCommon::to_money( $result['last30']['revenue'] );
    $result['last30']['refund'] = GFCommon::to_money( $result['last30']['refund'] );
    $summary_per_hour_count=0;
    $summary_per_hour_revenue = 0;
    $summary_per_hour_subscriptions = 0;
    foreach ( $summary_per_hour as $day ) {
        $summary_per_hour_count++;
        $summary_per_hour_revenue += floatval( $day['revenue'] );
        $summary_per_hour_subscriptions +=  $day['subscriptions'];
        $result['OneHour']['refund'] += floatval( $day['refund'] );
        $result['OneHour']['refunds'] += $day['refunds'];
    }
    $result['OneHour']['revenue'] = GFCommon::to_money($summary_per_hour_revenue);
    $result['OneHour']['orders'] = $summary_per_hour_count;
    $result['OneHour']['subscriptions'] = $summary_per_hour_subscriptions;

    $result['today']['general_tickets']['price'] = GFCommon::to_money($result['today']['general_tickets']['price']);
    $result['yesterday']['general_tickets']['price'] = GFCommon::to_money($result['yesterday']['general_tickets']['price']);
    $result['last30']['general_tickets']['price'] = GFCommon::to_money($result['last30']['general_tickets']['price']);
    $result['today']['service_fees']['price'] = GFCommon::to_money($result['today']['service_fees']['price']);
    $result['yesterday']['service_fees']['price'] = GFCommon::to_money($result['yesterday']['service_fees']['price']);
    $result['last30']['service_fees']['price'] = GFCommon::to_money($result['last30']['service_fees']['price']);

    // Get day wise records
    $select        = "transaction.date, date_format(transaction.date, '%%c') as month, day(transaction.date) as day, dayname(transaction.date) as day_of_week, '' as month_day";
                $select_inner1 = "date(CONVERT_TZ(payment_date, '+00:00', '" . $tz_offset . "')) as date";
                $select_inner2 = "date(CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "')) as date";
                $group_by      = 'date';
                $order_by      = 'date desc';
                $join          = 'leads.date = transaction.date';

                $current_period_format = 'Y-m-d';
                $decrement_period = 'day';
                $result_period = 'date';
                $current_date = gmdate( 'Y-m-d' );
    $lead_date_filter        = '';
        $transaction_date_filter = '';
        if ( isset( $search['start_date'] ) ) {
            $lead_date_filter        = $wpdb->prepare( " AND timestampdiff(SECOND, %s, CONVERT_TZ(l.payment_date, '+00:00', '" . $tz_offset . "')) >= 0", $search['start_date'] );
            $transaction_date_filter = $wpdb->prepare( " AND timestampdiff(SECOND, %s, CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "')) >= 0", $search['start_date'] );
        }

        if ( isset( $search['end_date'] ) ) {
            $search['end_date']      .= ' 23:59:59';
            $lead_date_filter        .= $wpdb->prepare( " AND timestampdiff(SECOND, %s, CONVERT_TZ(l.payment_date, '+00:00', '" . $tz_offset . "')) <= 0", $search['end_date'] );
            $transaction_date_filter .= $wpdb->prepare( " AND timestampdiff(SECOND, %s, CONVERT_TZ(t.date_created, '+00:00', '" . $tz_offset . "')) <= 0", $search['end_date'] );
        }

        $payment_method_filter = '';
        if ( ! empty( $payment_method ) ) {
            $payment_method_filter = $wpdb->prepare( ' AND l.payment_method=%s', $payment_method );
        }
        $page_size = 15;
        $current_page = 1;

        $offset           = $page_size * ( $current_page - 1 );

        $sql = $wpdb->prepare(
            " SELECT SQL_CALC_FOUND_ROWS {$select}, leads.orders, leads.subscriptions, transaction.refunds, transaction.recurring_payments, transaction.revenue
                                FROM (
                                  SELECT  {$select_inner1},
                                          sum( if(transaction_type = 1,1,0) ) as orders,
                                          sum( if(transaction_type = 2,1,0) ) as subscriptions
                                  FROM {$entry_table_name} l
                                  WHERE l.status='active' AND form_id=%d {$lead_date_filter} {$payment_method_filter}
                                  GROUP BY {$group_by}
                                ) AS leads

                                RIGHT OUTER JOIN(
                                  SELECT  {$select_inner2},
                                          sum( if(t.transaction_type = 'refund', abs(t.amount) * -1, t.amount) ) as revenue,
                                          sum( if(t.transaction_type = 'refund', 1, 0) ) as refunds,
                                          sum( if(t.transaction_type = 'payment' AND t.is_recurring = 1, 1, 0) ) as recurring_payments
                                  FROM {$wpdb->prefix}gf_addon_payment_transaction t
                                  INNER JOIN {$entry_table_name} l ON l.id = t.lead_id
                                  WHERE l.status='active' AND l.form_id=%d {$lead_date_filter} {$transaction_date_filter} {$payment_method_filter}
                                  GROUP BY {$group_by}

                                ) AS transaction on {$join}
                                ORDER BY {$order_by}
                                LIMIT $page_size OFFSET $offset
                                ", $form_id, $form_id
        );
        $results_day_wise_table = $wpdb->get_results( $sql, ARRAY_A );
        //print_r($results_day_wise_table);
        $table_day_wise_html = "";
        foreach($results_day_wise_table as $results_day_wise){
            $table_day_wise_html .= '<tr>
                                      <td style="padding:5px; font-size:14px">'.$results_day_wise['date'].'</td>
                                      <td style="padding:5px; font-size:14px">'.$results_day_wise['day_of_week'].'</td>
                                      <td style="padding:5px; font-size:14px">'.$results_day_wise['orders'].'</td>
                                      <td style="padding:5px; font-size:14px">'.$results_day_wise['refunds'].'</td>
                                      <td style="padding:5px; font-size:14px">$'.$results_day_wise['revenue'].'</td>
                                    </tr>';
        }

        // Include the main TCPDF library (search for installation path).
        //echo $_SERVER['DOCUMENT_ROOT'].'/TCPDF-main/examples/tcpdf_include_wild.php';
        require_once($_SERVER['DOCUMENT_ROOT'].'/TCPDF-main/examples/tcpdf_include_wild.php');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->setCreator(PDF_CREATOR);
        //$pdf->setAuthor('Nicola Asuni');
        $pdf->setTitle('Custom reporting options');
        //$pdf->setSubject('TCPDF Tutorial');
        //$pdf->setKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->setFont('dejavusans', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        // Set some content to print
        $html = '
        <h1 align="center">Wild Rivers Water Park<br /><small>Irvine, Ca</small></h1>
        <table>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="1" border="1" style="border-color:gray;" style="margin-bottom:15px">
                    <tr style="background-color:#06b9ca;color:white;" align="center">
                        <td colspan="2" style="padding:5px; font-size:16px">Hourly Sales Report</td>
                    </tr>
                    <tr>
                      <td style="padding:5px; font-size:14px">Total Orders</td>
                      <td style="padding:5px; font-size:14px">'.$result['OneHour']['orders'].'</td>
                    </tr>
                    <tr>
                      <td style="padding:5px; font-size:14px">Total Sales</td>
                      <td style="padding:5px; font-size:14px">'.$result['OneHour']['revenue'].'</td>
                    </tr>
                </table><br />
        </td>
        <td>
                <table cellspacing="0" cellpadding="1" border="1" style="border-color:gray;" style="margin-bottom:15px">
                    <tr style="background-color:#06b9ca;color:white;" align="center">
                        <td colspan="2" style="padding:5px; font-size:16px">Today Sales Report</td>
                    </tr><tr>
                      <td style="padding:5px; font-size:14px">Total Orders</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['orders'].'</td>
                </tr><tr>
                      <td style="padding:5px; font-size:14px">Total Sales</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['revenue'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Tickets Total</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['general_tickets']['price'].'</td>
                </tr><tr>
                      <td style="padding:5px; font-size:14px">Tickets Quantity</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['general_tickets']['quantity'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Service Fees</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['service_fees']['price'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Refund Orders</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['refunds'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Refund Amount</td>
                      <td style="padding:5px; font-size:14px">'.$result['today']['refund'].'</td>
                </tr>
                </table><br />
        </td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="1" border="1" style="border-color:gray;" style="margin-bottom:15px">
                <tr style="background-color:#06b9ca;color:white;" align="center">
                    <td colspan="2" style="padding:5px; font-size:16px">Yesterday Sales Report</td>
                </tr><tr>
                  <td style="padding:5px; font-size:14px">Total Orders</td>
                  <td style="padding:5px; font-size:14px">'.$result['yesterday']['orders'].'</td>
                </tr><tr>
                  <td style="padding:5px; font-size:14px">Total Sales</td>
                  <td style="padding:5px; font-size:14px">'.$result['yesterday']['revenue'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Tickets Total</td>
                      <td style="padding:5px; font-size:14px">'.$result['yesterday']['general_tickets']['price'].'</td>
                </tr><tr>
                      <td style="padding:5px; font-size:14px">Tickets Quantity</td>
                      <td style="padding:5px; font-size:14px">'.$result['yesterday']['general_tickets']['quantity'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Service Fees</td>
                      <td style="padding:5px; font-size:14px">'.$result['yesterday']['service_fees']['price'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Refund Orders</td>
                      <td style="padding:5px; font-size:14px">'.$result['yesterday']['refunds'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Refund Amount</td>
                      <td style="padding:5px; font-size:14px">'.$result['yesterday']['refund'].'</td>
                </tr>
            </table><br />
        </td>
        <td>
        <table cellspacing="0" cellpadding="1" border="1" style="border-color:gray;" style="margin-bottom:15px">
                <tr style="background-color:#06b9ca;color:white;" align="center">
                    <td colspan="2" style="padding:5px; font-size:16px">Last 30 Days Sales Report</td>
                </tr><tr>
                  <td style="padding:5px; font-size:14px">Total Orders</td>
                  <td style="padding:5px; font-size:14px">'.$result['last30']['orders'].'</td>
            </tr><tr>
                  <td style="padding:5px; font-size:14px">Total Sales</td>
                  <td style="padding:5px; font-size:14px">'.$result['last30']['revenue'].'</td>
            </tr>
            <tr>
                      <td style="padding:5px; font-size:14px">Tickets Total</td>
                      <td style="padding:5px; font-size:14px">'.$result['last30']['general_tickets']['price'].'</td>
            </tr><tr>
                  <td style="padding:5px; font-size:14px">Tickets Quantity</td>
                  <td style="padding:5px; font-size:14px">'.$result['last30']['general_tickets']['quantity'].'</td>
            </tr>
            <tr>
                      <td style="padding:5px; font-size:14px">Service Fees</td>
                      <td style="padding:5px; font-size:14px">'.$result['last30']['service_fees']['price'].'</td>
                </tr>
            <tr>
                      <td style="padding:5px; font-size:14px">Refund Orders</td>
                      <td style="padding:5px; font-size:14px">'.$result['last30']['refunds'].'</td>
                </tr>
                <tr>
                      <td style="padding:5px; font-size:14px">Refund Amount</td>
                      <td style="padding:5px; font-size:14px">'.$result['last30']['refund'].'</td>
                </tr>
        </table><br />
        </td>
    </tr>
</table>
<table>
<tr>
        <td>
            <table cellspacing="0" cellpadding="1" border="1" style="border-color:gray;" style="margin-bottom:15px">
                <tr style="background-color:#06b9ca;color:white;" align="center">
                    <td colspan="5" style="padding:5px; font-size:16px">Day Wise Sales Report</td>
                </tr>
                <tr>
                  <td style="padding:5px; font-size:14px">Date</td>
                  <td style="padding:5px; font-size:14px">Day</td>
                  <td style="padding:5px; font-size:14px">Orders</td>
                  <td style="padding:5px; font-size:14px">Refunds</td>
                  <td style="padding:5px; font-size:14px">Revenue</td>
                </tr>'.$table_day_wise_html.'
            </table>
        </td>
    </tr>
</table>
        ';

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        //$pdf->Output('example_001.pdf', 'I');
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf/report.pdf', 'F');

        $to          = "shawn.bowman@ideaseat.com"; // addresses to email pdf to
        //$to          = "boldertechno@gmail.com"; // addresses to email pdf to
        $from        = "carlos.roman@wildrivers.com "; // address message is sent from
        $subject     = "Wild Rivers Hourly Sales Report"; // email subject
        $body        = "<p>Hello Admin, <br/><br/>Please find the PDF report attached.</p>"; // email body
        $pdfLocation = $_SERVER['DOCUMENT_ROOT'].'/pdf/report.pdf'; // file location
        $pdfName     = "report.pdf"; // pdf file name recipient will get
        $filetype    = "application/pdf"; // type

        // create headers and mime boundry
        $eol = PHP_EOL;
        $semi_rand     = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        $headers       = "From: $from$eol" .
          "MIME-Version: 1.0$eol" .
          "Content-Type: multipart/mixed;$eol" .
          " boundary=\"$mime_boundary\"";

        // add html message body
          $message = "--$mime_boundary$eol" .
          "Content-Type: text/html; charset=\"iso-8859-1\"$eol" .
          "Content-Transfer-Encoding: 7bit$eol$eol" .
          $body . $eol;

        // fetch pdf
        $file = fopen($pdfLocation, 'rb');
        $data = fread($file, filesize($pdfLocation));
        fclose($file);
        $pdf = chunk_split(base64_encode($data));

        // attach pdf to email
        $message .= "--$mime_boundary$eol" .
          "Content-Type: $filetype;$eol" .
          " name=\"$pdfName\"$eol" .
          "Content-Disposition: attachment;$eol" .
          " filename=\"$pdfName\"$eol" .
          "Content-Transfer-Encoding: base64$eol$eol" .
          $pdf . $eol .
          "--$mime_boundary--";

        // Send the email
        if(mail($to, $subject, $message, $headers)) {
          //echo "The main email was sent.";
        }
        else {
          //echo "There was an error sending the mail.";
        }
}
*/

// Birthday ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/birthday-add-order.php';


// Cabana Reservation with sending to Server
// Sending Data from Gravity Form to Server Right now
// Its for Cabana Reservation

add_filter('gform_pre_submission_filter_10', 'change_form_10', 10, 3);
function change_form_10($form)
{

    $section_explode = explode(",", rgar($entry, '31'));
    $cabana_sectionID = implode(",", $section_explode);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/BirthdayPackageAddOrder',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
    "BirthdayPackage": "' . rgpost('input_36_3') . '",
    "notes": "' . rgpost('input_38') . '",
    "isOfficeUse": true,
    "authCode": "d063d05b-fbb1-4bf0-b4d8-b2f603454858",
    "sessionId": "' . rgpost('input_27') . '",
    "orderId": "' . rgpost('input_26') . '",
    "customer": {
        "firstName": "' . rgpost('input_17') . '",
        "lastName": "' . rgpost('input_18') . '",
        "email": "' . rgpost('input_19') . '",
        "phone": "' . rgpost('input_20') . '"
    },
    "purchases": [
        {
            "ticketType": "' . rgpost('input_1') . '",
            "sectionId": "' . rgpost('input_31') . '",
            "capacityId": "' . rgpost('input_30') . '",
            "quantity": "' . rgpost('input_36_3') . '",
            "amount": 0
        }
    ],
    "payment": {
        "cardholerName": "Omitted",
        "billingStreet": "",
        "billingZipCode": "",
        "cvn": "Omitted",
        "expDate": "Omitted",
        "ccNumber": "Omitted",
        "paymentCode": "32",
        "amount": "0"
    }
    }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    // echo $curl;
    curl_close($curl);
    // echo $response;

    $response_body = json_decode($response, true);

    // GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );

    if ($response_body != '') { ?>
        <script>
            //alert('We have something in return');
        </script>

        <?php if ($response_body["status"]["errorCode"] != 0) {
            echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>Error Code:' . $response_body["status"]["errorCode"] . ', Error Message:' . filter_var($response_body["status"]["errorMessage"], FILTER_SANITIZE_STRING) . ', Please refresh your page to attempt your order again.</p><a href="/tickets-2/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';
            ?>
            <script>
                alert('Error Code: <?php echo $response_body["status"]["errorCode"]; ?>, Error Message: <?php echo filter_var($response_body["status"]["errorMessage"], FILTER_SANITIZE_STRING); ?>, Please refresh your page to attempt your order again');
                alert('<?php echo rgpost('input_31'); ?> ');
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

            <!--Printing the Order-->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
            <table cellpadding="0" cellspacing="0" width="100%" align="center" class="_main_table"
                style="font-size: 14px;line-height: 1.8 !important;">
                <style type="text/css" re>
                    ._main_table * {
                        font-family: montserrat;
                    }

                    ._list_of_item_cart {
                        display: none !important;
                    }
                </style>
                <tr>
                    <td style="padding:0 !important">
                        <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/05/email-header-email.jpg" alt=""
                            style="width: 100%;" />
                    </td>
                </tr>
                <tr style="background-color: #173767;">
                    <td style="padding: 30px;">
                        <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You For
                            Reservation - Your Order Number is #<?php echo rgpost('input_26'); ?></h1>
                    </td>
                </tr>
                <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                    <td style="padding:20px">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 0;">
                            <tr>
                                <td style="padding-bottom: 10px;">
                                    <p>
                                        Hi <?php echo rgpost('input_17'); ?>,<br />
                                        We've received your order #<?php echo rgpost('input_26'); ?>
                                        for <?php echo rgpost('input_5'); ?>. We look forward to seeing you.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb"
                            class="_table_with_border">
                            <tr>
                                <td style="padding:5px">Product(s)</td>
                                <td style="padding:5px">Quantity</td>
                                <td style="padding:5px">Price</td>
                            </tr>

                            <tr>
                                <td style="padding:5px">
                                    Cabana - <?php echo rgpost('input_1'); ?> (<?php echo rgpost('input_16'); ?>)
                                </td>
                                <td style="padding:5px">
                                    <?php echo rgpost('input_6_3'); ?>
                                </td>
                                <td style="padding:5px">
                                    Reservation
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding:5px">Total:</td>
                                <td style="padding:5px"><?php echo rgpost('input_25'); ?></td>
                            </tr>


                            <tr>
                                <td style="padding:5px" colspan="3">
                                    <h4>See / Modify Tickets</h4>
                                    <p style="padding:0; margin:0">To modify the date of your visit, please use the link
                                        provided on your emailed receipt. Must change more than 24 hours in advance. tickets
                                        purchased within 24 hours will not be able to be rescheduled.</p>
                                    <a href="https://staging13.tickets.wildrivers.com/update-order/?order=<?php echo rgpost('input_57'); ?>"
                                        style="margin-bottom: 10px;">Click here to Modify</a>
                                    <ul class="_ticket_code" style="margin-top: 10px;padding: 0px 15px;">
                                        <?php
                                        for ($a = 0; $a < count($response_body['data']); $a++) {
                                            ?>
                                            <li
                                                style="margin: 0;border-bottom: 1px solid #b2b2b2;padding-bottom: 5px;margin-bottom: 5px;">
                                                <h5>Ticket #<?php echo $a + 1; ?>
                                                    - <?php echo $response_body['data'][$a]['ticketType'] ?></h5>
                                                <img src="https://quickchart.io/qr?text=<?php echo $response_body['data'][$a]['visualId'] ?>&margin=2&size=150"
                                                    title="WildRiver Ticket QR Code" class="cabana_qr_code" />
                                                <p style="margin:0; padding:0"><?php echo $response_body['data'][$a]['visualId'] ?> -
                                                    <?php echo rgpost('input_1'); ?>
                                                </p>

                                            </li>

                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <table border="0" style="border: 1px solid #ececec;" width="100%">
                            <h4 style="margin-top: 10px;">Billing Information</h4>
                            <p>
                                <?php echo rgpost('input_17'); ?>             <?php echo rgpost('input_18'); ?> <br />
                                <?php echo rgpost('input_20'); ?> <br />
                                <?php echo rgpost('input_19'); ?> <br />
                                <?php echo rgpost('input_21'); ?> <br />
                                <?php echo rgpost('input_23'); ?> - <?php echo rgpost('input_22'); ?> -
                                <?php echo rgpost('input_23'); ?> <br />
                            </p>
                        </table>

                        <table border="0" style="border: 1px solid #ececec;" width="100%">
                            <tr>
                                <td>
                                    <p style="text-align: center; margin-top:10px;"><a
                                            href="https://wildrivers.com/terms-conditions/" target="_blank">Terms & Conditions</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <?php
            // Getting visual id from API -----------------------------------------------
            $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=' . rgpost('input_26') . '&authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
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

            <table cellpadding="0" cellspacing="0" width="660px" align="center" class="_main_table"
                style="font-size: 14px;line-height: 1.8 !important;">
                <style type="text/css" re>
                    ._main_table * {
                        font-family: montserrat;
                    }

                    ._list_of_item_cart {
                        display: none !important;
                    }
                </style>
                <tr>
                    <td style="padding:0 !important">
                        <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/05/email-header-email.jpg" alt=""
                            style="width: 100%;" />
                    </td>
                </tr>
                <tr style="background-color: #173767;">
                    <td style="padding: 30px;">
                        <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You For
                            Reservation - Your Order Number is #<?php echo rgpost('input_26'); ?></h1>
                    </td>
                </tr>
                <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                    <td style="padding:20px">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 0;">
                            <tr>
                                <td style="padding-bottom: 10px;">
                                    <p>
                                        Hi <?php echo rgpost('input_17'); ?>,<br />
                                        We've received your order #<?php echo rgpost('input_26'); ?>
                                        for <?php echo rgpost('input_5'); ?>. We look forward to seeing you.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb"
                            class="_table_with_border">
                            <tr>
                                <td style="padding:5px">Product(s)</td>
                                <td style="padding:5px">Quantity</td>
                                <td style="padding:5px">Price</td>
                            </tr>

                            <tr>
                                <td style="padding:5px">
                                    Cabana - <?php echo rgpost('input_1'); ?> (<?php echo rgpost('input_16'); ?>)
                                </td>
                                <td style="padding:5px">
                                    <?php echo rgpost('input_6_3'); ?>
                                </td>
                                <td style="padding:5px">
                                    Reservation
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding:5px">Total:</td>
                                <td style="padding:5px"><?php echo rgpost('input_25'); ?></td>
                            </tr>


                            <tr>
                                <td style="padding:5px" colspan="3">
                                    <h4>See / Modify Tickets</h4>
                                    <p style="padding:0; margin:0">To modify the date of your visit, please use the link
                                        provided on your emailed receipt. Must change more than 24 hours in advance. tickets
                                        purchased within 24 hours will not be able to be rescheduled.</p>
                                    <a href="https://staging13.tickets.wildrivers.com/update-order/?order=<?php echo rgpost('input_26'); ?>"
                                        style="margin-bottom: 10px;">Click here to Modify</a>
                                    <ul class="_ticket_code" style="margin-top: 10px;padding: 0px 15px;">
                                        <?php
                                        for ($bd = 0; $bd < count($response_body['data']['tickets']); $bd++) {
                                            ?>
                                            <li
                                                style="margin: 0;border-bottom: 1px solid #b2b2b2;padding-bottom: 5px;margin-bottom: 5px;">
                                                <h5>Ticket #<?php echo $bd + 1; ?>
                                                    - <?php echo $response_body['data']['tickets'][$bd]['ticketType'] ?></h5>
                                                <img src="https://quickchart.io/qr?text=<?php echo $response_body['data']['tickets'][$bd]['visualId'] ?>&margin=2&size=150"
                                                    title="WildRiver Ticket QR Code" class="cabana_qr_code" />
                                                <p style="margin:0; padding:0">
                                                    <?php echo $response_body['data']['tickets'][$bd]['visualId'] ?> -
                                                    <?php echo rgpost('input_1'); ?>
                                                </p>

                                            </li>

                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <table border="0" style="border: 1px solid #ececec;" width="100%">
                            <h4 style="margin-top: 10px;">Billing Information</h4>
                            <p>
                                <?php echo rgpost('input_17'); ?>             <?php echo rgpost('input_18'); ?> <br />
                                <?php echo rgpost('input_20'); ?> <br />
                                <?php echo rgpost('input_19'); ?> <br />
                                <?php echo rgpost('input_21'); ?> <br />
                                <?php echo rgpost('input_23'); ?> - <?php echo rgpost('input_22'); ?> -
                                <?php echo rgpost('input_23'); ?> <br />
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
            $to = rgpost('input_19');
            //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
            $subject = 'Thank You For Reservation - Your Wild Rivers Order Number is #' . rgpost('input_26') . ' for ' . rgpost('input_5');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');

            return $form;
        } // Error Code Else

        // Checking Null Value in Return Bracket
    } else { ?>


        <?php

        echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>We are sorry, we cannot process your order at this time. Please try again later. Error: Server not responsive.</p><a href="/tickets-2/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';

        ?>
        <script>
            alert('We are sorry, we cannot process your order at this time. Please try again later. Error: Server not responsive.');
        </script>

        <?php
    }
}
; // Function Bracket


/**
 * The snippets below use 30 seconds for testing. If they help, try lowering the value.
 * If you are still getting cURL timeouts after raising the timeout to 30 seconds, your host must investigate the issue further.
 */

// Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
add_action('http_api_curl', 'sar_custom_curl_timeout', 9999, 1);
function sar_custom_curl_timeout($handle)
{
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 60); // 30 seconds.
    curl_setopt($handle, CURLOPT_TIMEOUT, 60); // 30 seconds.
}

// Setting custom timeout for the HTTP request
add_filter('http_request_timeout', 'sar_custom_http_request_timeout', 9999);
function sar_custom_http_request_timeout($timeout_value)
{
    return 60; // 30 seconds.
}

// Setting custom timeout in HTTP request args
add_filter('http_request_args', 'sar_custom_http_request_args', 9999, 1);
function sar_custom_http_request_args($r)
{
    $r['timeout'] = 60; // 30 seconds.
    return $r;
}


// Loading Emails for 10% sesaon pass
add_action('wp_ajax_seasonpass_email_loader', 'seasonpass_email_loader_function');
add_action('wp_ajax_nopriv_seasonpass_email_loader', 'seasonpass_email_loader_function');
function seasonpass_email_loader_function()
{
    $email = $_POST['email'];
    $url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/HasSeasonPass?Email=" . $email;

    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/DashboardReports?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&startdate=2024-05-28&enddate=2024-05-28";

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}

// Loading Miniumum Cabana Price
add_action('wp_ajax_minimum_cabana_price_loader', 'minimum_cabana_price_loader_function');
add_action('wp_ajax_nopriv_minimum_cabana_price_loader', 'minimum_cabana_price_loader_function');
function minimum_cabana_price_loader_function()
{
    $date = $_POST['date'];

    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/GetMinimumCabanaPrice?TicketDate=2024-06-20&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858";

    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/GetMinimumCabanaPrice?TicketDate=".$date."&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858";
    $url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/getAllCabanasPrice?authcode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&date=" . $date;


    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}

add_action('gform_enqueue_scripts', 'your_prefix_add_scripts');
function your_prefix_add_scripts($form)
{
    echo '<script>
        document.addEventListener( "DOMContentLoaded", function( e ) {
            if ( typeof jQuery !== "undefined" ) {
                jQuery(document).on( "click", "#gform_' . $form['id'] . ' [type=submit]", function(e) {
                    if( jQuery(this).data("isclicked" ) === true ) {
                        e.preventDefault();
                        return false;
                    }

                    jQuery(this).css("opacity", 0.2).data("isclicked", true);
                });
            }
        });
    </script>';
}

// Change Gravity Form Message to something that will blend with site
add_filter('gform_validation_message', 'change_message', 10, 2);
function change_message($message, $form)
{
    return "<div class='validation_error' style='font-size: 15px;font-weight: 500;'>There is a problem booking your ticket(s), please reload the page and try again.</div>";
}
;



// ANY DAY SALE ADD ORDER OFFICE ONLY
require_once CHILD_THEME_DIR . '/API_libs/birthday-add-order-office-only.php';



// Update ticket with Extra Charge
// Sending Data from Gravity Form to Server Right now
// Its for Update Ticket

//add_filter('gform_post_submission_13', 'change_form_13', 10, 3);
add_filter('gform_after_submission_13', 'change_form_13', 10, 3);
function change_form_13($form)
{

    $ticket_price_without_dollar = ltrim(rgpost('input_1'), '$');
    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/UpdateOrder';

    $headers = array(
        'accept' => '*/*',
        'Content-Type' => 'application/json'
    );

    $body = array(
        'sessionId' => rgpost('input_6'),
        'previousOrderNumber' => rgpost('input_5'),
        'orderNumber' => rgpost('input_15'),
        'transactionId' => '',
        'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
        'date' => rgpost('input_10'),
        'ticketChanges' => [
            array(
                'ticketType' => rgpost('input_13'),
                'visualId' => rgpost('input_7'),
                'sectionId' => rgpost('input_8'),
                'capacityId' => rgpost('input_9'),
                'date' => rgpost('input_10'),
                //'amount' => rgpost('input_11')
                'amount' => $ticket_price_without_dollar

            )
        ],
        'payment' => array(
            'cardholerName' => 'Omitted',
            'billingStreet' => '',
            'billingZipCode' => '',
            'cvn' => 'Omitted',
            'expDate' => 'Omitted',
            'ccNumber' => 'Omitted',
            'paymentCode' => '32',
            'amount' => $ticket_price_without_dollar
        )
    );

    /*echo '<pre>';
    var_dump($body);
    echo '</pre>'; */

    $body = json_encode($body);


    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    /* echo '<pre>';
    var_dump($response_body);
    echo '</pre>';  */
    //echo json_encode($response_body);
    //echo $body;

    // GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );

    if ($response_body != '') { ?>
        <script>
            //alert('We have something in return');
        </script>

        <?php if ($response_body["status"]["errorCode"] != 0) {
            echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>Error Code:' . $response_body["status"]["errorCode"] . ', Error Message:' . filter_var($response_body["status"]["errorMessage"], FILTER_SANITIZE_STRING) . ', Please refresh your page to attempt your order again.</p><a href="/tickets-2/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';
            ?>

            <?php
            /*<script>
                alert('Error Code: <?php echo $response_body["status"]["errorCode"]; ?>, Error Message: <?php echo filter_var($response_body["status"]["errorMessage"], FILTER_SANITIZE_STRING); ?>, Please refresh your page to attempt your order again');
                alert('<?php echo rgpost('input_31'); ?> ');
            </script>
            */ ?>

            <?php

            $form['is_active'] = false;
            //return $form;   // to see the var_dump output
            return 0;       // to prevent form submission
        } else { ?>

            <div class="_ticket_update_confirmation_gf">
                <h4>Your Ticket Has Been Successfully Updated.</h1>
                    <p>If you need an updated email you can send the email by clicking button below</p>
            </div>

        <?php } // Error Code Else

        // Checking Null Value in Return Bracket
    } else { ?>


        <?php

        echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>We are sorry, we cannot process your order at this time. Please try again later. Error: Server not responsive.</p><a href="/tickets-2/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';

        ?>
        <!--script>
            alert('We are sorry, we cannot process your order at this time. Please try again later. Error: Server not responsive.');
        </script-->

        <?php
    }
}
; // Function Bracket

// Setting Order Number as Invoice in Authorize.Net is a Pre Defined Hook from Gravity Form
add_filter('gform_authorizenet_transaction_pre_capture', function ($transaction, $form_data, $config, $form, $entry) {
    if ($form['id'] == 6) { // Ticket Form ID
        $transaction->invoice_num = rgar($entry, '57'); // Order ID field name as invoice in Auth.net
    } elseif ($form['id'] == 7) { // Birthday Form ID
        $transaction->invoice_num = rgar($entry, '26'); // Order ID field name as invoice in Auth.net
    } elseif ($form['id'] == 16) { // Any Day
        $transaction->invoice_num = rgar($entry, '13'); // Order ID field name as invoice in Auth.net
    } elseif ($form['id'] == 14) { // Season Pass
        $transaction->invoice_num = rgar($entry, '13'); // Order ID field name as invoice in Auth.net
    } elseif ($form['id'] == 20) { // Cabana Dynamic Sale
        $transaction->invoice_num = rgar($entry, '17'); // Order ID field name as invoice in Auth.net
    } elseif ($form['id'] == 19) { // Corporate
        $transaction->invoice_num = rgar($entry, '13'); // Order ID field name as invoice in Auth.net
    } elseif ($form['id'] == 24) { // Extra Saving
        $transaction->invoice_num = rgar($entry, '13'); // Order ID field name as invoice in Auth.net
    }

    return $transaction;
}, 10, 5);


//Thank You page Order Loader
//Loading Order on Thank You After Creating in database

add_action('wp_ajax_ThankYou_order_loader', 'ThankYou_order_loader_function');
add_action('wp_ajax_nopriv_ThankYou_order_loader', 'ThankYou_order_loader_function');
function ThankYou_order_loader_function()
{
    $url = $_POST['url'];
    $response = wp_remote_get($url);

    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    // HTML is built here -----------------------------------------------------------
    //
    ob_start(); ?>

    <div class="idu_main_container">

        <?php if ($response_body['data']['isDeleted'] != 0) {
            echo '<p style="color: red;text-align: center;font-size: 19px;font-family: montserrat;">Order Not Found!</p>';
        } else { ?>


            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderNumber']; ?>"
                class="_order_number" />
            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderTotal']; ?>"
                class="_order_total" />
            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderDisplayDate']; ?>"
                class="_order_date" />
            <?php
            for ($i = 0; $i < count($response_body['data']['tickets']); $i++) {
                $visualID = $response_body['data']['tickets'][$i]['visualId'];
                $ticket_type = $response_body['data']['tickets'][$i]['ticketType'];
                $ticket_price = $response_body['data']['tickets'][$i]['price'];
                $ticket_details = $response_body['data']['tickets'][$i]['description'];
                $ticket_details_array = explode(' ', $response_body['data']['tickets'][$i]['description']);
                //$cabana_number = $ticket_details_array[3];
                $cabana_number = $response_body['data']['tickets'][$i]['seat'];
                $ticket_date = explode("T", $response_body['data']['tickets'][$i]['ticketDate']);
                $ticket_date = $ticket_date[0];
                $order_date = explode("T", $response_body['data']['tickets'][$i]['orderDate']);
                $order_date = $order_date[0];
                ?>


                <?php if ($response_body['data']['tickets'][$i]['isQrCodeBurn'] == 0 && $response_body['data']['tickets'][$i]['isOrderDelete'] == 0) { ?>

                    <div class="idu_single_ticket_section">
                        <div class="_ticket_type">Ticket Type: <span class="cka_ticket_type"><?php echo $ticket_type; ?></span>
                            <img src="https://quickchart.io/qr?text=<?php echo $visualID; ?>&margin=2&size=100" alt="Test" />
                        </div>
                        <div class="_ticket_desc">Ticket Details: <?php echo $ticket_details; ?>                 <?php echo $cabana_number; ?></div>
                    </div>
                <?php } ?>

                <?php
            }

        }   // Is Deleted Else Condition
        ?>

    </div>



    <script type="text/javascript">
        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "purchase",
            ecommerce: {
                transaction_id: "<?php echo $response_body['data']['tickets'][0]['orderNumber']; ?>",
                value: <?php echo $response_body['data']['tickets'][0]['orderTotal']; ?>,
                tax: 0,
                shipping: 0,
                email: "<?php echo $response_body['data']['email']; ?>",
                currency: "USD", //or other applicable currencies
                coupon: "",
                items: [
                    <?php
                    for ($t2 = 0; $t2 < count($response_body['data']['tickets']); $t2++) { ?>
                                                                                                                                                                                                                                                <?php
                                                                                                                                                                                                                                                $visualID1 = $response_body['data']['tickets'][$t2]['visualId'];
                                                                                                                                                                                                                                                $ticket_type1 = $response_body['data']['tickets'][$t2]['ticketType'];
                                                                                                                                                                                                                                                $ticket_price1 = $response_body['data']['tickets'][$t2]['price'];
                                                                                                                                                                                                                                                $ticket_details1 = $response_body['data']['tickets'][$t2]['quantity'];
                                                                                                                                                                                                                                                ?>
                                                                                                                                                                                                                                                    {
                            item_id: '<?php echo $visualID1; ?>',
                            item_name: '<?php echo $ticket_type1; ?>',
                            affiliation: "",
                            coupon: "",
                            discount: 0,
                            price: <?php echo $ticket_price1; ?>,
                            quantity: 1
                        },
                    <?php } ?>]
            }
        });
    </script>

    <?php
    $html = ob_get_clean();
    echo json_encode($html);
    die();
}


// Enabling Total Conditional Logic
add_action('admin_enqueue_scripts', function () {
    $pages = array('confirmation', 'notification_edit');
    if (!class_exists('GFForms') || !in_array(GFForms::get_page(), $pages)) {
        return;
    }

    $script =
        "gform.addFilter('gform_is_conditional_logic_field', function (isConditionalLogicField, field) {" .
        "return field.type == 'total' ? true : isConditionalLogicField;" .
        '});' .
        "gform.addFilter('gform_conditional_logic_operators', function (operators, objectType, fieldId) {" .
        'var targetField = GetFieldById(fieldId);' .
        "if (targetField && targetField['type'] == 'total') {" .
        "operators = {'>': 'greaterThan', '<': 'lessThan'};" .
        '}' .
        'return operators;' .
        '});';

    wp_add_inline_script('gform_form_admin', $script);
}, 11);


new GWEmailDomainControl(array(
    'form_id' => 7,
    'field_id' => 19,
    'domains' => array('duck.com', 'test.com'),
));

new GWEmailDomainControl(array(
    'form_id' => 6,
    'field_id' => 18,
    'domains' => array('duck.com', 'test.com'),
));



// api for booked date
function booked_date_api_route()
{
    register_rest_route('booked_date/v1', '/data', array(
        'methods' => 'GET',
        'callback' => 'booked_date_api_callback',
    ));
}
add_action('rest_api_init', 'booked_date_api_route');

function booked_date_api_callback($data)
{
    global $wpdb;

    // Define form IDs and meta_keys based on form names
    $form_details = array(
        'Birthday_Ticketing_System' => array('form_id' => 7, 'order_meta_key' => '26'),
        'Birthday_Ticketing_System_Office' => array('form_id' => 12, 'order_meta_key' => '26'),
        'CRS_Office' => array('form_id' => 10, 'order_meta_key' => '26'),
        'Ticket_Booking_System' => array('form_id' => 6, 'order_meta_key' => '57'),
        'Ticket_Booking_System_Office' => array('form_id' => 11, 'order_meta_key' => '57'),
    );

    // Get form name from the request parameters
    $form_name = $data->get_param('form_name');

    // Check if form name exists and get form ID and meta_key
    if (!array_key_exists($form_name, $form_details)) {
        return new WP_Error('invalid_form_name', 'Invalid form name provided', array('status' => 400));
    }

    $form_id = $form_details[$form_name]['form_id'];
    $order_meta_key = $form_details[$form_name]['order_meta_key'];

    // Determine the meta_keys based on the form ID
    if (in_array($form_id, [7, 12, 10])) {
        $booked_date_key = '5';
        $cabana_name_key = '1';
        $booked_seat_key = '16';
    } else {
        $booked_date_key = '1';
        $cabana_name_key = '47';
        $booked_seat_key = '46';
    }

    // Get optional filter parameters
    $start_date = $data->get_param('start_date');
    $end_date = $data->get_param('end_date');
    $payment_status = $data->get_param('payment_status');

    // Construct the SQL query with optional filters
    $query = "
        SELECT e.date_created,
               e.payment_status,
               MAX(CASE WHEN em.meta_key = %s THEN em.meta_value END) AS booked_date,
               MAX(CASE WHEN em.meta_key = %s THEN em.meta_value END) AS order_id,
               MAX(CASE WHEN em.meta_key = %s THEN em.meta_value END) AS cabana_name,
               MAX(CASE WHEN em.meta_key = %s THEN em.meta_value END) AS booked_seat
        FROM {$wpdb->prefix}gf_entry e
        INNER JOIN {$wpdb->prefix}gf_entry_meta em
        ON e.id = em.entry_id
        WHERE e.form_id = %d
    ";

    // Add conditions based on optional parameters
    $conditions = array();

    if ($payment_status) {
        $conditions[] = $wpdb->prepare("e.payment_status = %s", $payment_status);
    }

    // Append conditions to the query if any
    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    // Date filtering happens outside the main query
    $query .= "
    GROUP BY e.id
    HAVING 1=1
";

    // Add date filtering using HAVING clause
    if ($start_date) {
        $query .= $wpdb->prepare("
        AND (
            STR_TO_DATE(booked_date, '%%Y-%%m-%%d') >= %s OR
            STR_TO_DATE(booked_date, '%%m/%%d/%%Y') >= %s
        )
    ", $start_date, $start_date);
    }

    if ($end_date) {
        $query .= $wpdb->prepare("
        AND (
            STR_TO_DATE(booked_date, '%%Y-%%m-%%d') <= %s OR
            STR_TO_DATE(booked_date, '%%m/%%d/%%Y') <= %s
        )
    ", $end_date, $end_date);
    }

    // Prepare and execute the query
    $results = $wpdb->get_results($wpdb->prepare($query, $booked_date_key, $order_meta_key, $cabana_name_key, $booked_seat_key, $form_id));

    // Prepare the response array
    $response = array(
        'status' => 'success',
        'total_count' => count($results),
        'data' => array()
    );

    // Check if any results are returned
    if (count($results) > 0) {
        foreach ($results as $result) {
            // Convert booked_date to yyyy-mm-dd format
            $booked_date = $result->booked_date;

            // Try to parse the date in different formats
            $date_formats = ['Y-m-d', 'm/d/Y'];
            $formatted_date = false;

            foreach ($date_formats as $format) {
                $parsed_date = DateTime::createFromFormat($format, $booked_date);
                if ($parsed_date) {
                    $formatted_date = $parsed_date->format('Y-m-d');
                    break;
                }
            }

            $response['data'][] = array(
                'date_created' => $result->date_created,
                'payment_status' => $result->payment_status,
                'booked_date' => $formatted_date ? $formatted_date : $booked_date,
                'order_id' => $result->order_id,
                'cabana_name' => $result->cabana_name,
                'booked_seat' => $result->booked_seat
            );
        }
    } else {
        $response['status'] = 'error';
        $response['data'] = [];
    }

    // Return the formatted response as JSON
    return rest_ensure_response($response);
}



// Creating Post Type for Saving Tickets Logs
function tickets_logs_custom_post_type()
{
    $labels = array(
        'name' => __('Logs'),
        'singular_name' => __('Log'),
        'menu_name' => __('Logs'),
        'parent_item_colon' => __('Parent Log'),
        'all_items' => __('All Logs'),
        'view_item' => __('View Log'),
        'add_new_item' => __('Add New Log'),
        'add_new' => __('Add New'),
        'edit_item' => __('Edit Log'),
        'update_item' => __('Update Log'),
        'search_items' => __('Search Log'),
        'not_found' => __('Not Found'),
        'not_found_in_trash' => __('Not found in Trash')
    );
    $args = array(
        'label' => __('Logs'),
        'description' => __('Ticket Logs'),
        'labels' => $labels,
        'supports' => array('title', 'custom-fields'),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'has_archive' => true,
        'can_export' => true,
        'exclude_from_search' => false,
        'yarpp_support' => true,
        'taxonomies' => array('post_tag'),
        'publicly_queryable' => true,
        'capability_type' => 'page'
    );
    register_post_type('Logs', $args);
}
add_action('init', 'tickets_logs_custom_post_type', 0);

// Saving Add order Request and its return in logs
add_action('gform_advancedpostcreation_post_after_creation', 'update_product_information', 10, 4);
function update_product_information($post_id, $feed, $entry, $form)
{

    $ticket_price_without_dollar = ltrim($entry['28'], '$');
    $child_ticket_without_dollar = ltrim($entry['67.2'], '$');
    $parking_ticket_without_dollar = ltrim($entry['70.2'], '$');
    $ticket_quantity = $entry['27'];
    $child_quantity = $entry['67.3'];
    $total_price_dollar_remove = ltrim($entry['73'], '$');
    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);
    $cabana_price_without_dollar = ltrim($entry['34'], '$');

    if ($cabana_price_without_dollar == 0.00) {
        $cabana_price_without_dollar = null;
    }
    if (!empty($cabana_price_without_dollar)) {
        $body = array(
            'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
            'sessionId' => $entry['49'],
            'orderId' => $entry['57'],
            'customer' => array(
                'firstName' => $entry['17'],
                'lastName' => $entry['59'],
                'phone' => $entry['19'],
                'email' => $entry['18']
            ),
            'purchases' => [
                array(
                    'ticketType' => 'General',
                    'sectionId' => $entry['48'],
                    'capacityId' => $entry['50'],
                    'quantity' => $entry['27'],
                    'amount' => $ticket_price_without_dollar
                ),
                array(
                    'ticketType' => 'Junior',
                    'sectionId' => $entry['48'],
                    'capacityId' => $entry['50'],
                    'quantity' => $entry['67.3'],
                    'amount' => $child_ticket_without_dollar
                ),
                /*array(
                    'ticketType' => 'Pizza Pack',
                    'sectionId' => rgpost( 'input_48' ),
                    'capacityId' => rgpost( 'input_50' ),
                    'quantity' => rgpost( 'input_69_3' ),
                    'amount' => $pizza_ticket_without_dollar
                ),   */
                array(
                    'ticketType' => 'Parking Pass',
                    'sectionId' => $entry['48'],
                    'capacityId' => $entry['50'],
                    'quantity' => $entry['70.3'],
                    'amount' => $parking_ticket_without_dollar
                ),
                array(
                    'ticketType' => $entry['47'],
                    'sectionId' => $entry['51'],
                    'capacityId' => $entry['52'],
                    'quantity' => 1,
                    'amount' => $cabana_price_without_dollar
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
    } else {
        $body = array(
            'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858',
            'sessionId' => $entry['49'],
            'orderId' => $entry['57'],
            'customer' => array(
                'firstName' => $entry['17'],
                'lastName' => $entry['59'],
                'phone' => $entry['19'],
                'email' => $entry['18']
            ),
            'purchases' => [
                array(
                    'ticketType' => 'General',
                    'sectionId' => $entry['48'],
                    'capacityId' => $entry['50'],
                    'quantity' => $entry['27'],
                    'amount' => $ticket_price_without_dollar
                ),
                array(
                    'ticketType' => 'Junior',
                    'sectionId' => $entry['48'],
                    'capacityId' => $entry['50'],
                    'quantity' => $entry['67.3'],
                    'amount' => $child_ticket_without_dollar
                ),
                /*array(
                    'ticketType' => 'Pizza Pack',
                    'sectionId' => rgpost( 'input_48' ),
                    'capacityId' => rgpost( 'input_50' ),
                    'quantity' => rgpost( 'input_69_3' ),
                    'amount' => $pizza_ticket_without_dollar
                ),   */
                array(
                    'ticketType' => 'Parking Pass',
                    'sectionId' => $entry['48'],
                    'capacityId' => $entry['50'],
                    'quantity' => $entry['70.3'],
                    'amount' => $parking_ticket_without_dollar
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

    }

    // Getting visual id from API -----------------------------------------------
    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=' . $entry['57'] . '&authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
    $response = wp_remote_get($post_url);

    $response_body = json_encode($response);

    $body1 = json_encode($body);
    //update the prices set for the product
    update_post_meta($post_id, 'add_order_request', $body1);
    update_post_meta($post_id, 'add_order_return', $response_body);


}


// Checking Session Validity
add_action('wp_ajax_check_session_validity', 'check_session_validity_function');
add_action('wp_ajax_nopriv_check_session_validity', 'check_session_validity_function');
function check_session_validity_function()
{
    $session = $_POST['session'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/CheckSessionStatus?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&SessionId=' . $session,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response_body = json_decode($response, true);

    $json_response = json_encode($response_body, true);

    echo $json_response;

    //var_dump($json_response);
    die();

    //echo $response;


}
/*
// Season Pass Style Sheet Link
function my_child_theme_enqueue_styles() {
    wp_enqueue_style('parent-theme', get_template_directory_uri() . '/season-pass-style.css');
    wp_enqueue_style('child-theme', get_stylesheet_directory_uri() . '/season-pass-style.css', array('parent-theme'));
}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');
// Season Pass Js Link
function your_child_theme_enqueue_scripts() {
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/seaason-pass-template.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'your_child_theme_enqueue_scripts');

*/

add_action('wp_ajax_get_promo_code_for_email', 'get_promo_code_for_email_function');
add_action('wp_ajax_nopriv_get_promo_code_for_email', 'get_promo_code_for_email_function');
function get_promo_code_for_email_function()
{
    $email = $_POST['email'];

    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/SeasonPassesRecord?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=".$email;
    $url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/SeasonPassesRecord?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=" . $email . "&Is_Season_Pass=0";


    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/SeasonPassesRecord?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=shawn@ideaseat.com";

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}


// ANY DAY SALE ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/any-day-add-order-api.php';
// ANY DAY SALE ADD ORDER OFFICE ONLY
require_once CHILD_THEME_DIR . '/API_libs/any-day-add-order-office-only.php';

// AFTER WAVE ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/after-wave-add-order-api-request.php';



add_action('wp_ajax_get_promo_code_for_email_verification', 'get_promo_code_for_email_verification_function');
add_action('wp_ajax_nopriv_get_promo_code_for_email_verification', 'get_promo_code_for_email_verification_function');
function get_promo_code_for_email_verification_function()
{
    $email = $_POST['email'];
    $promo = $_POST['promo'];

    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/UpdateSeasonPassesStatus?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=sb80@ideaseat.com&PromoCode=WRIW8FLO3X;
    $url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/UpdateSeasonPassesStatus?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=" . $email . "&PromoCode=" . $promo;

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}

// Cabana Sales Coded in 2025

//add_filter('gform_pre_submission_filter_7', 'change_form_7', 10, 3);
add_filter('gform_after_submission_18', 'change_form_18', 10, 3);
function change_form_18($form)
{

    $iac_order_id = rgpost('input_26');
    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/BirthdayPackageAddOrder';

    $total_price_dollar_remove = ltrim(rgpost('input_25'), '$');
    // Removing (,) too
    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);

    $section_explode = explode(",", rgar($entry, '31'));
    $cabana_sectionID = implode(",", $section_explode);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/BirthdayPackageAddOrder',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
    "BirthdayPackage": "' . rgpost('input_6_3') . '",
    "isOfficeUse" : false,
    "notes": "' . rgpost('input_36') . '",
    "authCode": "d063d05b-fbb1-4bf0-b4d8-b2f603454858",
    "sessionId": "' . rgpost('input_27') . '",
    "orderId": "' . rgpost('input_26') . '",
    "customer": {
        "firstName": "' . rgpost('input_17') . '",
        "lastName": "' . rgpost('input_18') . '",
        "email": "' . rgpost('input_19') . '",
        "phone": "' . rgpost('input_20') . '"
    },
    "purchases": [
        {
            "ticketType": "Nacho platter",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_10_3') . '",
            "amount": 0
        },
        {
            "ticketType": "Carne Asada Taco Platter",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_11_3') . '",
            "amount": 0
        },
        {
            "ticketType": "Mini Corn Dogs",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_12_3') . '",
            "amount": 0
        },
        {
            "ticketType": "' . rgpost('input_1') . '",
            "sectionId": "' . rgpost('input_31') . '",
            "capacityId": "' . rgpost('input_30') . '",
            "quantity": "' . rgpost('input_6_3') . '",
            "amount": 0
        }
    ],
    "payment": {
        "cardholerName": "Omitted",
        "billingStreet": "' . rgpost('input_21') . '",
        "billingZipCode": "' . rgpost('input_24') . '",
        "cvn": "Omitted",
        "expDate": "Omitted",
        "ccNumber": "Omitted",
        "paymentCode": "32",
        "amount": "' . $total_price_without_dollar . '"
    }
    }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    // echo $curl;
    curl_close($curl);
    //echo $response;

    $response_body = json_decode($response, true);

    // GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );

    if ($response_body != '') { ?>
        <script>
            //alert('We have something in return');
        </script>

        <?php if ($response_body["status"]["errorCode"] != 0) {
            echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>Error Code:' . $response_body["status"]["errorCode"] . ', Error Message:' . filter_var($response_body["status"]["errorMessage"], FILTER_SANITIZE_STRING) . ', Please refresh your page to attempt your order again.</p><a href="/tickets-2/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';
            ?>
            <script>
                alert('Error Code: <?php echo $response_body["status"]["errorCode"]; ?>, Error Message: <?php echo filter_var($response_body["status"]["errorMessage"], FILTER_SANITIZE_STRING); ?>, Please refresh your page to attempt your order again');
                alert('<?php echo rgpost('input_31'); ?> ');
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
            // Getting visual id from API -----------------------------------------------
            $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=' . rgpost('input_26') . '&authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
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

            <table cellpadding="0" cellspacing="0" width="660px" align="center" class="_main_table"
                style="font-size: 14px;line-height: 1.8 !important;">
                <style type="text/css" re>
                    ._main_table * {
                        font-family: montserrat;
                    }

                    ._list_of_item_cart {
                        display: none !important;
                    }
                </style>
                <tr>
                    <td style="padding:0 !important">
                        <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/05/email-header-email.jpg" alt=""
                            style="width: 100%;" />
                    </td>
                </tr>
                <tr style="background-color: #173767;">
                    <td style="padding: 30px;">
                        <h1 style="color: #fff; text-align: center; font-weight: 400; font-size: 26px; margin: 0;">Thank You
                            For Purchasing - Your Order Number is #<?php echo rgpost('input_26'); ?></h1>
                    </td>
                </tr>
                <tr style="background-color: #fff;outline: 1px solid #cbcbcb;display: table;width: 100%;outline-offset: -1px;">
                    <td style="padding:20px">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 0;">
                            <tr>
                                <td style="padding-bottom: 10px;">
                                    <p>
                                        Hi <?php echo rgpost('input_17'); ?>,<br />
                                        We've received your order #<?php echo rgpost('input_26'); ?>
                                        for <?php echo rgpost('input_5'); ?>. We look forward to seeing you.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb"
                            class="_table_with_border">
                            <tr>
                                <td style="padding:5px">Product(s)</td>
                                <td style="padding:5px">Quantity</td>
                                <td style="padding:5px">Price</td>
                            </tr>

                            <tr>
                                <td style="padding:5px">Cabana</td>
                                <td style="padding:5px"><?php echo rgpost('input_6_3'); ?></td>
                                <td style="padding:5px"><?php echo rgpost('input_6_2'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:5px">
                                    Cabana - <?php echo rgpost('input_1'); ?> (<?php echo rgpost('input_16'); ?>)
                                </td>
                                <td style="padding:5px">
                                    <?php echo rgpost('input_6_3'); ?>
                                </td>
                                <td style="padding:5px">
                                    Included
                                </td>
                            </tr>

                            <?php if (!empty(rgpost('input_10_3'))) { ?>
                                <tr>
                                    <td style="padding:5px">Nacho Platter</td>
                                    <td style="padding:5px"><?php echo rgpost('input_10_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_10_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (!empty(rgpost('input_11_3'))) { ?>
                                <tr>
                                    <td style="padding:5px">Carne Asada Taco Platter</td>
                                    <td style="padding:5px"><?php echo rgpost('input_11_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_11_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (!empty(rgpost('input_12_3'))) { ?>
                                <tr>
                                    <td style="padding:5px">Mini Corn Dogs</td>
                                    <td style="padding:5px"><?php echo rgpost('input_12_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_12_2'); ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2" style="padding:5px">Total:</td>
                                <td style="padding:5px"><?php echo rgpost('input_25'); ?></td>
                            </tr>


                            <tr>
                                <td style="padding:5px" colspan="3">
                                    <h4>See / Modify Tickets</h4>
                                    <p style="padding:0; margin:0">To modify the date of your visit, please use the link
                                        provided on your emailed receipt. Must change more than 24 hours in advance. tickets
                                        purchased within 24 hours will not be able to be rescheduled.</p>
                                    <a href="https://staging13.tickets.wildrivers.com/update-order/?order=<?php echo rgpost('input_26'); ?>"
                                        style="margin-bottom: 10px;">Click here to Modify</a>
                                    <ul class="_ticket_code" style="margin-top: 10px;padding: 0px 15px;">
                                        <?php
                                        for ($bd = 0; $bd < count($response_body['data']['tickets']); $bd++) {
                                            ?>
                                            <li
                                                style="margin: 0;border-bottom: 1px solid #b2b2b2;padding-bottom: 5px;margin-bottom: 5px;">
                                                <h5>Ticket #<?php echo $bd + 1; ?>
                                                    - <?php echo $response_body['data']['tickets'][$bd]['ticketType'] ?></h5>
                                                <img src="https://quickchart.io/qr?text=<?php echo $response_body['data']['tickets'][$bd]['visualId'] ?>&margin=2&size=150"
                                                    title="WildRiver Ticket QR Code" class="cabana_qr_code" />
                                                <p style="margin:0; padding:0">
                                                    <?php echo $response_body['data']['tickets'][$bd]['visualId'] ?> -
                                                    <?php echo rgpost('input_5'); ?>
                                                </p>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <table border="0" style="border: 1px solid #ececec;" width="100%">
                            <h4 style="margin-top: 10px;">Billing Information</h4>
                            <p>
                                <?php echo rgpost('input_17'); ?>             <?php echo rgpost('input_18'); ?> <br />
                                <?php echo rgpost('input_20'); ?> <br />
                                <?php echo rgpost('input_19'); ?> <br />
                                <?php echo rgpost('input_21'); ?> <br />
                                <?php echo rgpost('input_23'); ?> - <?php echo rgpost('input_22'); ?> -
                                <?php echo rgpost('input_23'); ?> <br />
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
            $to = rgpost('input_19');
            //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
            $subject = 'Thank You For Purchasing - Your Wild Rivers Order Number is #' . rgpost('input_26') . ' for ' . rgpost('input_5');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');

            return $form;
        } // Error Code Else

        // Checking Null Value in Return Bracket
    } else { ?>


        <?php

        echo '<div class="_not_available_popup _ticket_function_php_file_error" style="display:table !important; left:0 !important"><div class="not_available_popup_wrap"><div class="_not_available_popup_inner"><p>We are sorry, we cannot process your order at this time. Please try again later. Error: Server not responsive.</p><a href="/" class="_btn_close_not_avaialble_ticket">Referesh Page</a></div></div></div>';

        ?>
        <script>
            alert('We are sorry, we cannot process your order at this time. Please try again later. Error: Server not responsive.');
        </script>

        <?php
    }
}
; // Function Bracket


// CORP Add ORDER
require_once CHILD_THEME_DIR . '/API_libs/corp-any-day-ticket-add-order.php';


add_action('wp_ajax_get_promo_code_for_email_verification1', 'get_promo_code_for_email_verification1_function');
add_action('wp_ajax_nopriv_get_promo_code_for_email_verification1', 'get_promo_code_for_email_verification1_function');
function get_promo_code_for_email_verification1_function()
{
    $email = $_POST['email'];
    $promo = $_POST['promo'];

    //$url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/UpdateSeasonPassesStatus?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=sb80@ideaseat.com&PromoCode=WRIW8FLO3X;
    $url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/UpdateSeasonPassesStatus?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&Email=" . $email . "&PromoCode=" . $promo;

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}
/* DOMAIN VERIFICATION FUNCTION FOR CORPORATE
add_action('wp_ajax_verify_email_corporate_domain', 'verify_email_corporate_domain_function');
add_action('wp_ajax_nopriv_verify_email_corporate_domain', 'verify_email_corporate_domain_function');
function verify_email_corporate_domain_function()
{
    $email = $_POST['email'];
    $postID = $_POST['postID'];

    //https://wildrivers.com/wp-json/custom-api/v1/check-domain-name?email=test@in-n-out.com&id=5628&authcode=aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uVwXyZ;
    //$url = 'https://wildrivers.com/wp-json/custom-api/v1/check-domain-name?email='.$email.'&id='.$postID.'&authcode=aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uVwXyZ';

    $url = 'https://wildrivers.com/wp-json/custom-api/v1/check-domain-name?email='.$email.'&id='.$postID.'&authcode=aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uVwXyZ';

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}*/

// CABANA DYNAMIC SALE ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/cabana_dynamic_add_order.php';

// CABANA DYNAMIC SALE ADD ORDER - OFFICE ONLY
require_once CHILD_THEME_DIR . '/API_libs/cabana_dynamic_add_order_office_only.php';

// SEASON PASS - ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/seasonpass-add-order.php';

// SEASON PASS OFFICE ONLY - ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/seasonpass-add-order-office-only.php';

// SEASON PASS OFFICE ONLY - ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/24hours-upgrade-seasonpass-addorder-api-request.php';

// CHECK STRIPE READER BEFORE PAYMENT ATTEMPT
require_once CHILD_THEME_DIR . '/API_libs/check-reader-before-attemp-frontgate.php';


//add_filter( 'gform_pre_submission_filter_16', 'change_form_16', 10, 3 );
add_filter('gform_after_submission_24', 'change_form_24', 10, 3);
//add_filter( 'gform_authorizenet_post_capture_16', 'change_form_16', 10, 3 );
function change_form_24($form)
{

    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/AnyDayAddOrder';

    $platinum_price_without_dollar = ltrim(rgpost('input_27'), '$');

    $platinum_pass_quantity = intval(rgpost('input_44'));
    $parking_pass_quantity = intval(rgpost('input_45'));

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
                'quantity' => rgpost('input_44'),
                'amount' => '49.99'
            ),
            array(
                'ticketType' => 'Daily Parking Pass',
                'sectionId' => '0',
                'capacityId' => '0',
                'VisualId' => null,
                'VisualIdStockCount' => 0,
                'quantity' => rgpost('input_45'),
                'amount' => '0'
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
    /*echo '<pre>';
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
                                        Hi <?php echo rgpost('input_5'); ?>             <?php echo rgpost('input_6'); ?>,<br />
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
                                <td style="padding:5px">Extra Saving Pack (4 Anyday Tickets + 1 Daily Parking Pass)</td>
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
                                <?php echo rgpost('input_5'); ?>             <?php echo rgpost('input_6'); ?> <br />
                                <?php echo rgpost('input_7'); ?> <br />
                                <?php echo rgpost('input_8'); ?> <br />
                                <?php echo rgpost('input_9'); ?>             <?php echo rgpost('input_10'); ?> <br />
                                <?php echo rgpost('input_11'); ?>             <?php echo rgpost('input_12'); ?> <br />
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
            $subject = 'Thank You For Purchasing - Your Wild Rivers Order Number is #' . rgpost('input_13') . ' for ' . rgpost('input_1');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');


            return $form;
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

// EXTRA SAVING ADD ORDER OFFICE ONLY
require_once CHILD_THEME_DIR . '/API_libs/extra-saving-add-order-office-only.php';




// Hold On Button Open

// This Function is beign used to place hold for ticket
add_action('wp_ajax_wildrivers_ticket_seat_hold_for_pos', 'wildrivers_ticket_seat_hold_for_pos_function');
add_action('wp_ajax_nopriv_wildrivers_ticket_seat_hold_for_pos', 'wildrivers_ticket_seat_hold_for_pos_function');
function wildrivers_ticket_seat_hold_for_pos_function()
{

    //$url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/TicketHold?date=2025-12-30&AuthCode=rivwil3';

    //$non_participant_type = $_POST['non_participant_type'];
    $general_admission_type = $_POST['general_admission_type'];
    $general_admission_quantity_pos = $_POST['general_admission_quantity_pos'];

    $cabana_yes_or_no = $_POST['cabana_yes_or_no'];
    $cabana_type = $_POST['ticketTypeCabana'];
    $cabana_quantity = $_POST['quantityCabana'];
    $cabana_seat = $_POST['seatCabana'];

    // $junior_ticket_type = $_POST['junior_ticket_type'];
    // $junior_ticket_quantity = $_POST['junior_ticket_quantity'];

    // $parking_pass_type = $_POST['parking_pass_type'];
    // $parking_pass_quantity = $_POST['parking_pass_quantity'];

    // $black_pass_type = $_POST['black_pass_type'];
    // $black_pass_quantity = $_POST['black_pass_quantity'];

    // $general_admission_twilight_ticket_type = "general_admission_twilight_ticket_type";
    // $general_admission_twilight_ticket_quantity = $_POST['general_admission_twilight_ticket_quantity'];

    // $twilight_ticket_type = $_POST['twilight_ticket_type'];
    // $twilight_ticket_quantity = $_POST['twilight_ticket_quantity'];



    $date = $_POST['date'];
    $orderID = $_POST['orderID'];


    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/TicketHold?date=' . $date . '&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';

    // http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/TicketHold?date=2025-02-28&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858


    $headers = array(
        'Content-Type' => 'application/json'
    );

    if ($cabana_yes_or_no == 'Yes') {

        $body = array(
            'sessionId' => '0',
            'OrderId' => $orderID,
            'ticketHoldItem' => [
                array(
                    'ticketType' => $general_admission_type,
                    'quantity' => $general_admission_quantity_pos,
                    'seat' => ''
                ),
                array(
                    'ticketType' => $ticketTypeCabana,
                    'quantity' => $quantityCabana,
                    'seat' => $seatCabana
                )
            ]
        );

    } else {
        $body = array(
            'sessionId' => '0',
            'OrderId' => $orderID,
            'ticketHoldItem' => [
                array(
                    'ticketType' => $general_admission_type,
                    'quantity' => $general_admission_quantity_pos,
                    'seat' => ''
                )
            ]
        );
    }


    /*
    $body = array(
        'sessionId' => '',
        'OrderId' => $orderID,
        'ticketHoldItem' => [
        array(
            'ticketType' => $general_admission_type,
            'quantity' => $general_admission_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $beginner_pass_type,
            'quantity' => $beginner_pass_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $intermediate_pass_type,
            'quantity' => $intermediate_pass_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $black_pass_type,
            'quantity' => $black_pass_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $non_participant_twilight_type,
            'quantity' => $non_participant_twilight_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $beginner_twilight_pass_type,
            'quantity' => $beginner_twilight_pass_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $intermediate_twilight_pass_type,
            'quantity' => $intermediate_twilight_pass_quantity,
            'seat' => ""
        ),
        array(
            'ticketType' => $black_twilight_pass_type,
            'quantity' => $black_twilight_pass_quantity,
            'seat' => ""
        )
        ]
    ); */

    //var_dump($body);
    $body = json_encode($body);
    //var_dump($body);
    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    if ($response_body['status']['errorCode'] == 300) {
        echo json_encode(-999);
        //echo $response_body;
        die();
    } else {
        echo json_encode($response_body);

        die();
    }
}


// Hold On Button Close

add_filter('gform_stripe_charge_description', 'gf_replace_charge_description', 10, 5);
function gf_replace_charge_description($description, $strings, $entry, $submission_data, $feed)
{
    GFCommon::log_debug(__METHOD__ . '(): running.');
    $feed_name = rgars($feed, 'meta/feedName');

    // Update the following line to use your feed name.
    if ($feed_name == 'Ticket Booking System 2025') { // Changed to Stripe
        // Change 21 to your field id number.
        $description = 'Kiwi Ticketing Platform Order #' . rgar($entry, '57');
        //return $description;
    } elseif ($feed_name == 'Twilight Ticket Booking System') { // Changed to Stripe
        // Change 21 to your field id number.
        $description = 'Kiwi Ticketing Platform Twilight Order #' . rgar($entry, '57');
        //return $description;
    } elseif ($feed_name == 'Ticket Update Checkout') { // Changed to Stripe

        $description = 'Kiwi Ticketing Update Order #' . rgar($entry, '15');

    } elseif ($feed_name == 'Kiwi Ticketing Season Pass') { // Changed to Stripe

        $description = 'Kiwi Ticketing Season Pass Order #' . rgar($entry, '13');

    } elseif ($feed_name == 'Kiwi Ticketing Extra Saving') {

        $description = 'Kiwi Ticketing Extra Saving Order #' . rgar($entry, '13');

    } elseif ($feed_name == 'Kiwi Ticketing Corporate Any Day Ticket') { // Changed to Stripe

        $description = 'Kiwi Ticketing Corporate Any Day Ticket Order #' . rgar($entry, '13');

    } elseif ($feed_name == 'Kiwi Ticketing Cabana Dynamic Sale') { // Changed to Stripe

        $description = 'Kiwi Ticketing Cabana DPB Order #' . rgar($entry, '17');

    } elseif ($feed_name == 'Kiwi Ticketing Birthday Pack') { // Changed to Stripe

        $description = 'Kiwi Ticketing Birthday Pack Order #' . rgar($entry, '26');

    } elseif ($feed_name == 'Kiwi Ticketing Anyday Ticket') { // Changed to Stripe

        $description = 'Kiwi Ticketing Anyday Ticket Order #' . rgar($entry, '13');

    } elseif ($feed_name == 'Wildriver Front Gate') { // Changed to Stripe

        $description = 'Kiwi FrontGate Order #' . rgar($entry, '7');

    } elseif ($feed_name == 'Tidal Wave Sale') { // Changed to Stripe

        $description = 'Kiwi Ticketing - Tidal Wave Sale Order #' . rgar($entry, '13');

    } elseif ($feed_name == 'Group Ticket Feed') { // Changed to Stripe

        $description = 'Kiwi Ticketing - Group Ticket Sale Order #' . rgar($entry, '13');

    } elseif ($feed_name == 'After Wave Ticket Sale') { // Changed to Stripe

        $description = 'Kiwi Ticketing - After Wave Ticket Order #' . rgar($entry, '13');

    } elseif ($feed_name == '24 Hours Upgrade - Season Pass') { // Changed to Stripe

        $description = 'Kiwi Ticketing - 24 Hours Upgrade Offer #' . rgar($entry, '13');

    } elseif ($feed_name == 'Super Sale Ticketing Booking System 2025') { // Changed to Stripe
        // Change 21 to your field id number.
        $description = 'Kiwi Ticketing Platform Super Slider Order #' . rgar($entry, '57');
        //return $description;
    }

    GFCommon::log_debug(__METHOD__ . '(): running for feed ' . $feed_name);

    GFCommon::log_debug(__METHOD__ . '(): Custom Description: ' . $description);
    return $description;
}


// including get_transaction_id_api.php for transaction id api
$transaction_id_api_file_path = get_stylesheet_directory() . '/includes/get_transaction_id_api.php';
if (file_exists($transaction_id_api_file_path)) {
    require_once $transaction_id_api_file_path;
}

$get_payment_status_api_file_path = get_stylesheet_directory() . '/includes/get_payment_status_api.php';
if (file_exists($get_payment_status_api_file_path)) {
    require_once $get_payment_status_api_file_path;
}


add_action('wp_ajax_nopriv_check_strip_terminal_hold', 'check_strip_terminal_hold_function');
add_action('wp_ajax_check_strip_terminal_hold', 'check_strip_terminal_hold_function');
function check_strip_terminal_hold_function()
{

    $staffIdentity = $_POST['staffIdentity'];

    // http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/GetStripeDevicesHold?AuthCode=0a27e421-e1e7-4530-80f8-fca5789b79be&StaffIdentity=Ideaseat
    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/GetStripeDevicesHold?AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&StaffIdentity=' . $staffIdentity;

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}

add_action('wp_ajax_nopriv_GetAllReaders', 'GetAllReaders_function');
add_action('wp_ajax_GetAllReaders', 'GetAllReaders_function');
function GetAllReaders_function()
{


    // http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/GetAllReaders?authCode=0a27e421-e1e7-4530-80f8-fca5789b79be
    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/GetAllReaders?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858&getAllDevices=false';

    $response = wp_remote_get($url);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}

add_action('wp_ajax_nopriv_StripeAddDevicesHold', 'StripeAddDevicesHold_function');
add_action('wp_ajax_StripeAddDevicesHold', 'StripeAddDevicesHold_function');
function StripeAddDevicesHold_function()
{

    // http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/StripeAddDevicesHold?AuthCode=0a27e421-e1e7-4530-80f8-fca5789b79be
    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/StripeAddDevicesHold?AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';

    $deviceName = $_POST['deviceName'];
    $registrationCode = $_POST['registrationCode'];
    $staffIdentity = $_POST['staffIdentity'];

    $headers = array(
        'Content-Type' => 'application/json'
    );

    $body = array(
        'deviceName' => $deviceName,
        'registrationCode' => $registrationCode,
        'staffIdentity' => $staffIdentity
    );

    $body = json_encode($body);

    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    echo json_encode($response_body);
    //echo $body;
    die();

}


add_action('wp_ajax_nopriv_Release_stripe_device', 'Release_stripe_device_function');
add_action('wp_ajax_Release_stripe_device', 'Release_stripe_device_function');
function Release_stripe_device_function()
{

    $staffIdentity = $_POST['staffIdentity'];
    // http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/GetAllReaders?authCode=0a27e421-e1e7-4530-80f8-fca5789b79be
    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/ReleaseDevicesHold?StaffIdentity=' . $staffIdentity . '&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
    //$url = $_POST['url'];

    $headers = array(
        'Content-Type' => 'application/json'
    );

    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST'
    ));

    // $response = wp_remote_get($url);

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    $json_response = json_encode($response_body);

    echo $json_response;

    die();

}


// POS THANK YOU ORDER LOADER TO PRINT SLIP
add_action('wp_ajax_POS_ThankYou_order_loader', 'POS_ThankYou_order_loader_function');
add_action('wp_ajax_nopriv_POS_ThankYou_order_loader', 'POS_ThankYou_order_loader_function');
function POS_ThankYou_order_loader_function()
{
    $url = $_POST['url'];
    $response = wp_remote_get($url);

    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    // HTML is built here -----------------------------------------------------------
    //
    ob_start(); ?>

    <div class="idu_main_container">

        <?php if ($response_body['data']['isDeleted'] != 0) {
            echo '<p style="color: red;text-align: center;font-size: 19px;font-family: montserrat;">Order Not Found!</p>';
        } else { ?>

            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderNumber']; ?>"
                class="_order_number" />
            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderTotal']; ?>"
                class="_order_total" />
            <input type="hidden" value="<?php echo $response_body['data']['tickets'][0]['orderDisplayDate']; ?>"
                class="_order_date" />

            <div class="idu_single_ticket_section" id="contentz0">
                <h3>CUSTOMER RECEIPT</h3>
                <div class="_ticket_logo">
                    <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/02/wildrivers-logo-2x.png"
                        alt="Wild Rivers" />
                </div>
                <div class="_ticket_creation_date">
                    <span>Date Creation: <?php echo $response_body['data']['tickets'][0]['orderDate']; ?></span>
                </div>
                <ul class="_reciept_ul">
                    <li>
                        Order Number: <?php echo $response_body['data']['orderNumber']; ?>
                    </li>
                    <li>
                        Name: <?php echo $response_body['data']['firstName']; ?>
                        <?php echo $response_body['data']['lastName']; ?>
                    </li>
                    <li>
                        Phone: <?php echo $response_body['data']['phone']; ?>
                    </li>
                    <li>
                        Email: <?php echo $response_body['data']['email']; ?>
                    </li>
                    <li>
                        Tax: $<?php echo $response_body['data']['tax']; ?>
                    </li>
                    <li>
                        Service Fee: $<?php echo $response_body['data']['serviceCharges']; ?>
                    </li>
                    <li>
                        Total: $<?php echo $response_body['data']['orderTotal']; ?>
                    </li>
                </ul>
            </div>

            <?php
            for ($i = 0; $i < count($response_body['data']['tickets']); $i++) {
                $visualID = $response_body['data']['tickets'][$i]['visualId'];
                $ticket_type = $response_body['data']['tickets'][$i]['ticketType'];
                $slot_time = $response_body['data']['tickets'][$i]['slotTime'];
                $ticket_price = $response_body['data']['tickets'][$i]['price'];
                $ticket_details = $response_body['data']['tickets'][$i]['description'];
                $ticket_details_array = explode(' ', $response_body['data']['tickets'][$i]['description']);
                //$cabana_number = $ticket_details_array[3];
                $cabana_number = $response_body['data']['tickets'][$i]['seat'];
                $ticket_date = explode("T", $response_body['data']['tickets'][$i]['ticketDate']);
                $ticket_date = $ticket_date[0];
                $order_date = explode("T", $response_body['data']['tickets'][$i]['orderDate']);
                //$order_date     = $order_date[0];
                $pos_staff = $response_body['data']['tickets'][$i]['posStaffIdentity'];
                ?>

                <?php if ($response_body['data']['tickets'][$i]['isQrCodeBurn'] == 0 && $response_body['data']['tickets'][$i]['isOrderDelete'] == 0) { ?>

                    <div class="idu_single_ticket_section">
                        <div class="_ticket_logo">
                            <img src="https://tickets.wildrivers.com/wp-content/uploads/2024/02/wildrivers-logo-2x.png"
                                alt="Wild Rivers" />
                        </div>
                        <div class="_ticket_creation_date">
                            <span>Date Creation: <?php echo $order_date[0]; ?>                 <?php echo $order_date[1]; ?></span>
                        </div>
                        <div class="_ticket_type">
                            <!--Ticket Type: -->
                            <img src="https://quickchart.io/qr?text=<?php echo $visualID; ?>&margin=2&size=100" alt="Bolder" />
                            <span class="_ticket_vid"><?php echo $visualID; ?></span>
                            <span class="cka_ticket_type">
                                <?php echo $ticket_type; ?>
                            </span>
                        </div>
                        <div class="_ticket_desc" style="display:none;">Ticket Details: <?php echo $ticket_details; ?>
                            <?php echo $cabana_number; ?>
                        </div>
                        <div class="_ticket_time_info">
                            <div class="_ticket_date">Date: <?php echo $ticket_date; ?></div>
                            <?php if ($slot_time != '') { ?>
                                <div class="_ticket_slot">Time Slot: <?php echo $slot_time; ?></div>
                            <?php } ?>
                        </div>
                        <div class="_ticket_staff_info">
                            POS Station: <?php echo $pos_staff; ?>
                        </div>
                        <div class="_ticket_precaution">
                            <!--p>
                        IMPORTANT: Please Arrive 5 Minutes prior to Appointment Time-Late Arrivals will be added to our standby line based on availability and are NOT GUARANTEED admision after appointment time has passed. No personal items are allowed in pockets, Please place in a locker prior to arriving for your appointment.
                    </p-->
                        </div>

                    </div>

                <?php } // isQrBurnt check ?>


                <?php
            }

        } // isDELETED check
        ?>

    </div>


    <?php
    $html = ob_get_clean();
    echo json_encode($html);
    die();
}








function update_user_purchase_value($order_id)
{
    if (!$order_id) {
        return;
    }

    // Get order details
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    $order_total = $order->get_total(); // Get total order value

    if ($user_id) {
        // Get previous purchase total
        $previous_total = get_user_meta($user_id, 'total_purchase_value', true);
        $previous_total = $previous_total ? $previous_total : 0;

        // Update total purchase value
        $new_total = $previous_total + $order_total;
        update_user_meta($user_id, 'total_purchase_value', $new_total);
    }
}
add_action('woocommerce_thankyou', 'update_user_purchase_value');



function display_user_total_purchases()
{
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $total_purchase = get_user_meta($user_id, 'total_purchase_value', true);

        // Default value set karein agar data na ho
        $total_purchase = !empty($total_purchase) ? $total_purchase : 0;

        return "<p><strong>Your Total Purchases:</strong> $" . number_format((float) $total_purchase, 2) . "</p>";
    } else {
        return "<p>Please log in to see your total purchases.</p>";
    }
}
add_shortcode('user_total_purchases', 'display_user_total_purchases');




// WILD RIVER POS ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/pos_add_order.php';

// TIDAL WAVE SALE - ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/tidal_wave_add_order.php';

// TIDAL WAVE SALE - ADD ORDER - OFFICE ONLY
require_once CHILD_THEME_DIR . '/API_libs/tidal_wave_add_order_office_only.php';

// Register API route
function register_gravity_forms_date_api()
{
    register_rest_route('custom/v1', '/getGravityFormsEntriesByDate', [
        'methods' => 'GET',
        'callback' => 'get_gravity_forms_entries_by_date',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'register_gravity_forms_date_api');

// GRAVITY FORM PAYMENT STATUS CHECK
require_once CHILD_THEME_DIR . '/API_libs/gravity-form-payment-status-verification.php';





// function get_gravity_forms_entries_by_date(WP_REST_Request $request) {
//     global $wpdb;

//     $start_date = $request->get_param('start_date');
//     $end_date = $request->get_param('end_date');

//     // Validate dates
//     if (empty($start_date) || empty($end_date)) {
//         return new WP_REST_Response(
//             ['success' => false, 'message' => 'Both start_date and end_date are required.'],
//             400
//         );
//     }

//     // Convert dates to UTC for comparison
//     $start_date_utc = get_gmt_from_date($start_date);
//     $end_date_utc = get_gmt_from_date($end_date);

//     // Main entry table
//     $entry_table = $wpdb->prefix . 'gf_entry';

//     // Meta table
//     $meta_table = $wpdb->prefix . 'gf_entry_meta';

//     // Form ID mapping (adjust according to your needs)
//     $form_mapping = [
//         1 => ['order_id_field' => 57],  // Form ID 1 maps to meta key 57 for order ID
//         4 => ['order_id_field' => 13],  // Form ID 4 maps to meta key 13 for order ID
//         2 => ['order_id_field' => 37]   // Form ID 2 maps to meta key 37 for order ID
//     ];

//     // Booking date field mapping
//     $booking_date_fields = [
//         1 => 1  // Form ID 1 has booking date in meta key 1
//     ];

//     // Prepare the SQL query
//     $query = $wpdb->prepare(
//         "SELECT e.*
//         FROM {$entry_table} e
//         WHERE e.status = 'active'
//         AND e.date_created >= %s
//         AND e.date_created <= %s
//         AND e.form_id IN (" . implode(',', array_keys($form_mapping)) . ")",
//         $start_date_utc,
//         $end_date_utc
//     );

//     $entries = $wpdb->get_results($query);

//     if (empty($entries)) {
//         return new WP_REST_Response(
//             ['success' => false, 'message' => 'No transactions found.'],
//             404
//         );
//     }

//     $entries_data = [];

//     foreach ($entries as $entry) {
//         $form_id = $entry->form_id;

//         if (!isset($form_mapping[$form_id])) {
//             continue;
//         }

//         // Get order ID from meta
//         $order_id = $wpdb->get_var(
//             $wpdb->prepare(
//                 "SELECT meta_value FROM {$meta_table}
//                 WHERE entry_id = %d AND meta_key = %d",
//                 $entry->id,
//                 $form_mapping[$form_id]['order_id_field']
//             )
//         );

//         // Get booking date if mapped
//         $booking_date = '';
//         if (isset($booking_date_fields[$form_id])) {
//             $booking_date = $wpdb->get_var(
//                 $wpdb->prepare(
//                     "SELECT meta_value FROM {$meta_table}
//                     WHERE entry_id = %d AND meta_key = %d",
//                     $entry->id,
//                     $booking_date_fields[$form_id]
//                 )
//             );
//         }

//         $entries_data[] = [
//             'order_id' => $order_id ?? '',
//             'transaction_id' => $entry->transaction_id ?? '',
//             'payment_date' => $entry->payment_date ?? '',
//             'booking_date' => $booking_date ?? '',
//             'date_created' => $entry->date_created,
//             'form_id' => $form_id,
//             'entry_id' => $entry->id
//         ];
//     }

//     return new WP_REST_Response(
//         ['success' => true, 'data' => $entries_data],
//         200
//     );
// }

// ================ Raw query function forwildrivers without UTC conversion  ===================

function get_gravity_forms_entries_by_date(WP_REST_Request $request)
{
    global $wpdb;

    // Validate parameters
    $start_date = $request->get_param('start_date');
    $end_date = $request->get_param('end_date');

    if (empty($start_date) || empty($end_date)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Both start_date and end_date are required.'
        ], 400);
    }

    // Convert dates to UTC
    // $start_date_utc = get_gmt_from_date($start_date);
    // $end_date_utc = get_gmt_from_date($end_date);

    $start_date_utc = $start_date;
    $end_date_utc = $end_date;

    // Field mappings (form_id => field configurations)
    $form_mapping = [
        6 => [
            'order_id_field' => 57,
            'booking_date_field' => 1
        ],
        7 => [
            'order_id_field' => 26,
            'booking_date_field' => 5
        ],
        10 => [
            'order_id_field' => 26,
            'booking_date_field' => 5
        ],
        11 => [
            'order_id_field' => 57,
            'booking_date_field' => 1
        ],
        12 => [
            'order_id_field' => 26,
            'booking_date_field' => 5
        ],
        13 => [
            'order_id_field' => 5
        ],
        14 => [
            'order_id_field' => 13
        ],
        15 => [
            'order_id_field' => 13
        ],
        16 => [
            'order_id_field' => 13
        ],
        17 => [
            'order_id_field' => 13
        ],
        18 => [
            'order_id_field' => 26,
            'booking_date_field' => 5
        ],
        19 => [
            'order_id_field' => 13
        ],
        20 => [
            'order_id_field' => 17,
            'booking_date_field' => 1
        ],
        22 => [
            'order_id_field' => 13
        ],
        23 => [
            'order_id_field' => 7
        ],
        24 => [
            'order_id_field' => 13
        ],
        25 => [
            'order_id_field' => 13
        ],
        26 => [
            'order_id_field' => 13,
            'booking_date_field' => 6
        ],
        27 => [
            'order_id_field' => 13
        ],
        // 29 => [
        //     'order_id_field' => 13
        // ]
    ];


    // Get all relevant form IDs
    $form_ids = array_keys($form_mapping);
    $form_ids_placeholders = implode(',', array_fill(0, count($form_ids), '%d'));

    // Main entries query with UTC date comparison
    $entries = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}gf_entry
            WHERE form_id IN ($form_ids_placeholders)
            AND status = 'active'
            AND date_created >= %s
            AND date_created <= %s",
            array_merge($form_ids, [$start_date_utc, $end_date_utc])
        )
    );

    if (empty($entries)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'No transactions found.'
        ], 404);
    }

    // Get all entry IDs for meta query
    $entry_ids = wp_list_pluck($entries, 'id');
    $meta_fields = array_unique(array_merge(
        array_column($form_mapping, 'order_id_field'),
        array_column($form_mapping, 'booking_date_field')
    ));

    // Filter out null values from meta_fields
    $meta_fields = array_filter($meta_fields, function ($v) {
        return !is_null($v); });
    $meta_fields_placeholders = implode(',', array_fill(0, count($meta_fields), '%d'));

    // Single query to get all required meta data
    $meta_data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT entry_id, meta_key, meta_value
            FROM {$wpdb->prefix}gf_entry_meta
            WHERE entry_id IN (" . implode(',', array_fill(0, count($entry_ids), '%d')) . ")
            AND meta_key IN ($meta_fields_placeholders)",
            array_merge($entry_ids, $meta_fields)
        )
    );

    // Organize meta data by entry_id and meta_key
    $meta_by_entry = [];
    foreach ($meta_data as $meta) {
        $meta_by_entry[$meta->entry_id][$meta->meta_key] = $meta->meta_value;
    }

    // Process entries
    $entries_data = [];
    foreach ($entries as $entry) {
        $form_id = $entry->form_id;
        $mapping = $form_mapping[$form_id] ?? null;

        if (!$mapping)
            continue;

        $entries_data[] = [
            'order_id' => $meta_by_entry[$entry->id][$mapping['order_id_field']] ?? '',
            'transaction_id' => $entry->transaction_id ?? '',
            'payment_date' => $entry->payment_date ?? '',
            'booking_date' => isset($mapping['booking_date_field'])
                ? ($meta_by_entry[$entry->id][$mapping['booking_date_field']] ?? '')
                : '',
            // 'date_created' => $entry->date_created,
            'form_id' => $form_id,
            // 'entry_id' => $entry->id,
            // 'start_date' => $start_date_utc,
            // 'end_date' => $end_date_utc
        ];
    }

    return new WP_REST_Response([
        'success' => true,
        'data' => $entries_data
    ], 200);
}


// GET PAYLOAD CONFIRMATION - CHECK IF WE HIT API OR NOT
require_once CHILD_THEME_DIR . '/API_libs/get_payload_confirmation_for_thank_you_check.php';

// GROUP TICKET ADD ORDER
require_once CHILD_THEME_DIR . '/API_libs/group-ticket-add-order.php';
// GROUP TICKET ADD ORDER Office Only
require_once CHILD_THEME_DIR . '/API_libs/group-ticket-add-order-office-only.php';
// ORDER LOOKUP API CALLES
require_once CHILD_THEME_DIR . '/API_libs/order-lookup-api-calls.php';
// Front Gate Season Pass Upgrade Update Order API
require_once CHILD_THEME_DIR . '/API_libs/front-gate-upgrades-api-request.php';



// ============= shortcode for the heder profile actions ===============

function render_user_profile_header()
{
    if (!is_user_logged_in()) {
        return ''; // Return nothing if user not logged in
    }

    ob_start();
    $current_user = wp_get_current_user();
    $current_user_id = get_current_user_id();
    $profile_image = get_user_meta($current_user_id, 'profile_image', true);
    $profile_image_url = $profile_image ? esc_url($profile_image) : esc_url(site_url() . '/wp-content/uploads/2025/03/dummy-profile-pic.jpg');
    ?>
    <div class="user-profile-container d-flex align-items-center gap-2 position-relative">
        <div class="user-profile-image-wrapper">
            <img id="header-profile-image" class="profile-image" src="<?php echo $profile_image_url; ?>" alt="">
        </div>
        <div class="user-profile-name font-size-14 d-flex align-items-center gap-2 text-white fw-medium">
            <span><?php echo esc_html($current_user->display_name); ?></span>
            <i class="fa-solid fa-angle-down"></i>
        </div>
        <div class="user-profile-dropdown d-none flex-column">
            <a href="<?php echo esc_url(site_url('/update-profile')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12 10C14.2091 10 16 8.20914 16 6C16 3.79086 14.2091 2 12 2C9.79086 2 8 3.79086 8 6C8 8.20914 9.79086 10 12 10Z"
                        stroke="#4D5B7C" stroke-width="2" />
                    <path
                        d="M20 17.5C20 19.985 20 22 12 22C4 22 4 19.985 4 17.5C4 15.015 7.582 13 12 13C16.418 13 20 15.015 20 17.5Z"
                        stroke="#4D5B7C" stroke-width="2" />
                </svg>
                <span>Update Profile</span>
            </a>
            <a href="<?php echo esc_url(wp_logout_url()); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M4 18H6V20H18V4H6V6H4V3C4 2.44772 4.44772 2 5 2H19C19.5523 2 20 2.44772 20 3V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V18ZM6 11H13V13H6V16L1 12L6 8V11Z"
                        fill="#4D5B7C"></path>
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('user_profile_header', 'render_user_profile_header');



// RESET TERMINAL
add_action('wp_ajax_nopriv_reset_Readers', 'reset_Readers_function');
add_action('wp_ajax_reset_Readers', 'reset_Readers_function');
function reset_Readers_function()
{


    $url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/cancel?authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';

    // $deviceName = $_POST['deviceName'];
    // $registrationCode = $_POST['registrationCode'];
    // $staffIdentity = $_POST['staffIdentity'];
    $readerId = $_POST['readerId'];

    $headers = array(
        'Content-Type' => 'application/json'
    );

    $body = array(
        'readerId' => $readerId,
        'authCode' => 'd063d05b-fbb1-4bf0-b4d8-b2f603454858'
    );

    $body = json_encode($body);

    $response = wp_remote_post($url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    echo json_encode($response_body);
    //echo $body;
    die();

}


// add loader in admin dashboard
function add_admin_loader()
{
    ?>
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>
    <?php
}
add_action('admin_footer', 'add_admin_loader');


// add stripe readers menu
add_action('admin_menu', function () {
    // Get current user object
    $user = wp_get_current_user();

    // Check if the user has 'administrator' or 'manager' role
    if (in_array('administrator', $user->roles) || in_array('manager', $user->roles)) {
        add_menu_page(
            'Stripe Readers',
            'Stripe Readers',
            'read',
            'stripe-readers',
            'render_stripe_readers_page',
            'dashicons-chart-area',
            6
        );
    }
});


// enqueue admin scripts
add_action('admin_enqueue_scripts', function ($hook) {

    // Enqueue sweet alert library
    wp_enqueue_style(
        'sweetalert2-css',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
        array(),
        null
    );

    wp_enqueue_script(
        'sweetalert2-js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
        array('jquery'),
        null,
    );

    wp_enqueue_style('admin-style', get_stylesheet_directory_uri() . '/assets/css/admin-style.css', NULL, filemtime(get_stylesheet_directory() . '/assets/css/admin-style.css'), 'all');
    wp_enqueue_script('admin-script', get_stylesheet_directory_uri() . '/assets/js/admin-script.js', array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/admin-script.js'), true);
    wp_localize_script('admin-script', 'ReadersDataAjax', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('readers_data_nonce'),
    ]);
});


function render_stripe_readers_page()
{
    ?>
    <div class="wrap">
        <h1>Readers Data</h1>
        <h2>Connected Readers</h2>
        <div id="connected-readers"></div>
    </div>
    <?php
}


add_action('wp_ajax_get_stripe_devices_hold_ajax', 'handle_get_stripe_devices_hold');
function handle_get_stripe_devices_hold()
{
    $api_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/GetStripeDevicesHold?AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';
    $response = wp_remote_get($api_url, [
        'headers' => ['accept' => 'text/plain'],
        'timeout' => 60,
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message(), 500);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['errorCode']) && $data['errorCode'] != 0) {
        wp_send_json_error($data['errorMessage']);
    }

    wp_send_json_success($data['data']);
    wp_die();
}


add_action('wp_ajax_release_stripe_device_hold_ajax', 'handle_release_stripe_device_hold');
function handle_release_stripe_device_hold()
{
    if (empty($_POST['staff_identity'])) {
        wp_send_json_error(['message' => 'Staff identity missing.'], 400);
    }

    $staff_identity = sanitize_text_field($_POST['staff_identity']);

    $auth_code = 'd063d05b-fbb1-4bf0-b4d8-b2f603454858';
    $url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/GetAllReadersApi/ReleaseDevicesHold?StaffIdentity={$staff_identity}&AuthCode={$auth_code}";


    $response = wp_remote_post($url, [
        'headers' => ['accept' => 'text/plain'],
        'timeout' => 60,
    ]);


    if (is_wp_error($response)) {
        wp_send_json_error(['message' => $response->get_error_message()], 500);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    $errorCode = isset($data['status']['errorCode']) ? $data['status']['errorCode'] : null;
    $errorMessage = isset($data['status']['errorMessage']) ? $data['status']['errorMessage'] : '';
    $resultMsg = isset($data['result']) ? $data['result'] : '';

    // if API indicates failure
    if ($errorCode !== '0' && $errorCode !== 0) {
        $msg = $resultMsg ?: $errorMessage ?: 'Unknown API error.';
        wp_send_json_error(['message' => $msg], 200);
    }

    // success
    wp_send_json_success(['message' => $resultMsg ?: 'Device released successfully.'], 200);
    wp_die();
}


// Showing message to Spam Entries
add_filter('gform_confirmation', function ($confirmation, $form, $entry) {
    if (empty($entry) || rgar($entry, 'status') === 'spam') {
        return 'Something went wrong while processing your order. This may happen due to security filters. Please try again, or contact our support team if the problem continues';
    }
    return $confirmation;
}, 11, 3);



/*
 ============== Start-Here ============== 
* REACT STRIPE PAYMENT INTENT API
* REACT Email API
* REACT Error Email API
* REACT Custom Post Type for React Orders
*/

add_action('rest_api_init', function () {
    register_rest_route('custom-stripe/v1', '/create-intent', array(
        'methods' => 'POST',
        'callback' => 'custom_create_stripe_intent',
        'permission_callback' => '__return_true',
    ));
});


function custom_create_stripe_intent($request) {
    
    $amount = $request->get_param('amount');
    $order_id = $request->get_param('order_id');
    $customer_name = $request->get_param('customer_name');
    $ticket_details = $request->get_param('ticket_details');


    $secret_key = '';

    $ch = curl_init();

    
    $order_id = $request->get_param('order_id'); 
    $bookingData = $request->get_param('bookingData'); // bookingData array le lo

    $description = "KiwiTicketing - Order #{$order_id}";

    $postData = array(
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method_types[]' => 'card',
        'description' => $description,
        'metadata' => array(
            'order_id'  => $order_id,
            'website'   => 'FrontGate WildRivers',
            'firstName' => isset($bookingData['firstName']) ? $bookingData['firstName'] : '',
            'email'     => isset($bookingData['email']) ? $bookingData['email'] : '',
            'phone'     => isset($bookingData['phone']) ? $bookingData['phone'] : ''
        )
    );


    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ':');

    $headers = array('Content-Type: application/x-www-form-urlencoded');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return new WP_Error('stripe_error', curl_error($ch), array('status' => 500));
    }
    curl_close($ch);

    $data = json_decode($result, true);

    return $data;
}

// Create API endpoint for sending emails
add_action('rest_api_init', function() {
    register_rest_route('wildrivers/v1', '/send-email/', [
        'methods' => 'POST',
        'callback' => 'handle_send_email',
        'permission_callback' => '__return_true'
    ]);
});

function handle_send_email($request) {
    $data = $request->get_json_params();
    
    // Validate and sanitize data
    $to = sanitize_email($data['email']);
    $subject = sanitize_text_field($data['subject']);
    $body = wp_kses_post($data['body']); // Allows safe HTML
    
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Wild Rivers <no-reply@wildrivers.com>'
    ];
    
    // Send email using WordPress wp_mail()
    $sent = wp_mail($to, $subject, $body, $headers);
    
    return [
        'success' => $sent,
        'message' => $sent ? 'Email sent' : 'Failed to send email'
    ];
}

// Create API endpoint for sending ERROR emails
add_action('rest_api_init', function() {
    register_rest_route('wildrivers/v1', '/send-error-email/', [
        'methods' => 'POST',
        'callback' => 'handle_send_error_email',
        'permission_callback' => '__return_true'
    ]);
});

function handle_send_error_email($request) {
    $data = $request->get_json_params();
    

    $emails = is_array($data['email']) ? array_map('sanitize_email', $data['email']) : [sanitize_email($data['email'])];
    $to = implode(',', $emails);

    $subject = sanitize_text_field($data['subject']);
    $body = wp_kses_post($data['body']); // Allows safe HTML
    
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Wild Rivers <no-reply@wildrivers.com>'
    ];
    
    // Send email using WordPress wp_mail()
    $sent = wp_mail($to, $subject, $body, $headers);
    
    return [
        'success' => $sent,
        'message' => $sent ? 'Error email sent' : 'Failed to send error email'
    ];
}


// Custome Post Type for React Orders 
function wildriver_ticketing_react_order_custom_post_type() {
    $labels = array(
        'name'                => __('Orders', 'wildrivers'),
        'singular_name'       => __('Order', 'wildrivers'),
        'menu_name'           => __('Orders', 'wildrivers'),
        'all_items'           => __('All Orders', 'wildrivers'),
        'view_item'           => __('View Order', 'wildrivers'),
        'add_new_item'        => __('Add New Order', 'wildrivers'),
        'edit_item'           => __('Edit Order', 'wildrivers'),
        'update_item'         => __('Update Order', 'wildrivers'),
        'search_items'        => __('Search Orders', 'wildrivers'),
        'not_found'           => __('No orders found', 'wildrivers'),
        'not_found_in_trash'  => __('No orders found in Trash', 'wildrivers')
    );

    $args = array(
        'label'               => __('Orders', 'wildrivers'),
        'description'         => __('Orders from React Ticketing System', 'wildrivers'),
        'labels'              => $labels,
        'supports'            => array('title', 'custom-fields'),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-tickets-alt',
        'has_archive'         => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
    );

    register_post_type('orders', $args);
}
add_action('init', 'wildriver_ticketing_react_order_custom_post_type');

function register_order_meta_fields() {
    $meta_fields = [
        'id' => [
            'ticketType'     => 'string',
            'ticketSlug'     => 'string',
            'ticketCategory' => 'string',
            'price'          => 'float'
        ],
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'total' => 'number',
        'paymentMethod' => 'string',
        'transaction_id' => 'string',
        'payment_date' => 'string',
        'user_ip' => 'string',
    ];

    foreach ($meta_fields as $key => $type) {
        register_post_meta('orders', $key, [
            'show_in_rest' => true,
            'single' => true,
            'type' => $type,
        ]);
    }
}
add_action('init', 'register_order_meta_fields');

// Enhanced Admin Columns
add_filter('manage_orders_posts_columns', function($columns) {
    $new_columns = [
        'cb' => $columns['cb'],
        'order_title' => __('Order ID', 'wildrivers'),
        'customer_info' => __('Customer', 'wildrivers'),
        // 'ticket_info' => __('Tickets', 'wildrivers'),
        'total' => __('Total', 'wildrivers'),
        'paymentMethod' => __('Payment', 'wildrivers'),
        'transaction_id' => __('Transaction ID', 'wildrivers'),
        'date' => __('Date', 'wildrivers'),
    ];
    return $new_columns;
});

// Custom Column Content
add_action('manage_orders_posts_custom_column', function($column, $post_id) {
    switch ($column) {
        case 'order_title':
            $order_id = get_the_title($post_id);
            echo '<strong>' . esc_html($order_id) . '</strong>';
            break;

        case 'customer_info':
            $first_name = get_post_meta($post_id, 'first_name', true);
            $last_name = get_post_meta($post_id, 'last_name', true);
            $email = get_post_meta($post_id, 'email', true);
            echo '<div class="customer-cell">';
            echo '<div class="customer-name">' . esc_html($first_name . ' ' . $last_name) . '</div>';
            echo '<div class="customer-email"><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></div>';
            echo '</div>';
            break;

        case 'ticket_info':
            $ticket_data = get_post_meta($post_id, 'id', true);
            if (is_array($ticket_data)) {
                echo '<div class="tickets-cell">';
                echo '<span class="ticket-badge">' . esc_html($ticket_data['ticketType']) . '</span>';
                echo '<span class="ticket-price">$' . esc_html($ticket_data['price']) . '</span>';
                echo '</div>';
            }
            break;

        case 'total':
            $total = get_post_meta($post_id, 'total', true);
            echo '<div class="total-cell">';
            echo '<span class="total-amount">$' . esc_html(number_format($total, 2)) . '</span>';
            echo '</div>';
            break;

        case 'paymentMethod':
            $method = get_post_meta($post_id, 'paymentMethod', true);
            echo '<span class="payment-badge ' . esc_attr(strtolower($method)) . '">' . esc_html($method) . '</span>';
            break;

        case 'transaction_id':
            $txn_id = get_post_meta($post_id, 'transaction_id', true);
            echo '<span class="txn-id">' . esc_html($txn_id) . '</span>';
            break;
    }
}, 10, 2);

// Make columns sortable
add_filter('manage_edit-orders_sortable_columns', function($columns) {
    $columns['total'] = 'total';
    $columns['paymentMethod'] = 'paymentMethod';
    return $columns;
});

// Admin Styles and Scripts
add_action('admin_enqueue_scripts', function($hook) {
    global $typenow;
    if ($typenow === 'orders') {
        // Enqueue Google Fonts
        wp_enqueue_style('wildrivers-admin-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
        
        // Custom CSS
        $custom_css = "
        /* Main admin styling */
        body.post-type-orders #wpcontent {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        
        .post-type-orders #wpbody-content .wrap {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        
        .post-type-orders .wp-list-table {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Column specific styles */
        .customer-cell .customer-name {
            font-weight: 500;
            color: #1e293b;
        }
        
        .customer-cell .customer-email {
            font-size: 12px;
            color: #64748b;
        }
        
        .tickets-cell .ticket-badge {
            display: inline-block;
            background: #f1f5f9;
            border-radius: 2px;
            padding: 2px 6px;
            margin-right: 5px;
            font-size: 12px;
            color: #334155;
        }
        
        .tickets-cell .ticket-price {
            font-size: 12px;
            color: #16a34a;
            font-weight: 500;
        }
        
        .total-cell .total-amount {
            font-weight: 600;
            color: #1e293b;
        }
        
        .payment-badge {
            padding: 4px 4px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .payment-badge.card {
            background: #e0f2fe;
            color: #0369a1;
        }
        
        .payment-badge.terminal {
            background: #e1fec3ff;
            color: #18850eff;
        }
        
        .txn-id {
            font-family: monospace;
            font-size: 12px;
            color: #475569;
        }
        
        /* Hover effects */
        .post-type-orders .wp-list-table tbody tr:hover {
            background-color: #f8fafc !important;
        }
        
        /* Responsive adjustments */
        @media screen and (max-width: 782px) {
            .column-order_title, .column-customer_info {
                display: table-cell !important;
            }
        }
        ";
        
        wp_add_inline_style('wp-admin', $custom_css);
    }
});

// Enhanced Filters
add_action('restrict_manage_posts', function($post_type) {
    if ($post_type === 'orders') {
        // Payment Method Filter
        $current_payment = isset($_GET['filter_payment']) ? $_GET['filter_payment'] : '';
        echo '<select name="filter_payment" class="postform">';
        echo '<option value="">All Payment Methods</option>';
        echo '<option value="card"' . selected($current_payment, 'card', false) . '>Card</option>';
        echo '<option value="terminal"' . selected($current_payment, 'terminal', false) . '>Terminal</option>';
        echo '</select>';

        // Date Filter
        echo '<input type="text" name="date_start" placeholder="Start date" class="datepicker" value="' . esc_attr($_GET['date_start'] ?? '') . '">';
        echo '<input type="text" name="date_end" placeholder="End date" class="datepicker" value="' . esc_attr($_GET['date_end'] ?? '') . '">';
    }
});

// Apply Filters
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'orders') return;

    $meta_query = [];

    // Payment Method Filter
    if (!empty($_GET['filter_payment'])) {
        $meta_query[] = [
            'key' => 'paymentMethod',
            'value' => sanitize_text_field($_GET['filter_payment']),
            'compare' => '='
        ];
    }

    // Date Range Filter
    if (!empty($_GET['date_start']) || !empty($_GET['date_end'])) {
        $date_query = [];
        
        if (!empty($_GET['date_start'])) {
            $date_query['after'] = sanitize_text_field($_GET['date_start']);
        }
        
        if (!empty($_GET['date_end'])) {
            $date_query['before'] = sanitize_text_field($_GET['date_end']);
        }
        
        $date_query['inclusive'] = true;
        $query->set('date_query', $date_query);
    }

    if (!empty($meta_query)) {
        $query->set('meta_query', $meta_query);
    }
});

// Add Export Button
add_action('admin_head-edit.php', function() {
    global $typenow;
    if ($typenow === 'orders') {
        echo '<script>
        jQuery(document).ready(function($) {
            $(".wrap h1").after(\'<a href="\' + ajaxurl + \'?action=export_orders&\' + window.location.search.substr(1) + \'" class="page-title-action">Export Orders</a>\');
        });
        </script>';
    }
});



/*
 ============== End-Here ============== 
* REACT STRIPE PAYMENT INTENT API
* REACT Email API
* REACT Error Email API
* REACT Custom Post Type for React Orders
*/