<?php

// Birthday Package with new code sending to Server
// Sending Data from Gravity Form to Galaxy Right now
// Its for Birthday

//add_filter('gform_pre_submission_filter_7', 'change_form_7', 10, 3);
add_filter('gform_after_submission_7', 'change_form_7', 10, 3);
function change_form_7($form)
{

    $iac_order_id = rgpost('input_26');
    $post_url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/BirthdayPackageAddOrder';

    $total_price_dollar_remove = ltrim(rgpost('input_25'), '$');
    // Removing (,) too
    $total_price_without_dollar = str_replace(',', '', $total_price_dollar_remove);

    $aditional_general = ltrim(rgpost('input_9_2'), '$');
    $nacho_platter_lg = ltrim(rgpost('input_10_2'), '$');
    $nacho_platter_sm = ltrim(rgpost('input_41_2'), '$');
    $carn_asada_taco_platter = ltrim(rgpost('input_11_2'), '$');
    $mini_corn_dogs = ltrim(rgpost('input_12_2'), '$');
    $birthday_cake = ltrim(rgpost('input_42_2'), '$');
    $ceasar_salad_chiken = ltrim(rgpost('input_44_2'), '$');
    $ceasar_salad_no_chiken = ltrim(rgpost('input_43_2'), '$');
    $chiken_tender_sm = ltrim(rgpost('input_46_2'), '$');
    $chiken_tender_lg = ltrim(rgpost('input_45_2'), '$');
    $chiken_wing_sm = ltrim(rgpost('input_48_2'), '$');
    $chiken_wing_lg = ltrim(rgpost('input_47_2'), '$');
    $cookie_platter = ltrim(rgpost('input_49_2'), '$');
    $south_west_salad = ltrim(rgpost('input_50_2'), '$');
    $fruit_platter_sm = ltrim(rgpost('input_54_2'), '$');
    $fruit_platter_lg = ltrim(rgpost('input_55_2'), '$');

    //$tax_dollar_remove   = ltrim(rgpost('input_55'), '$');
    //$tax_without_dollar = str_replace(',', '', $tax_dollar_remove);
    // $tax_without_dollar = 0;

    //$service_dollar_remove   = ltrim(rgpost('input_56'), '$');
    //$service_without_dollar = str_replace(',', '', $service_dollar_remove);
    //$service_without_dollar = 0

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
            "ticketType": "General",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_33') . '",
            "amount": 0
        },
        {
            "ticketType": "Nacho Platter Large",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_10_3') . '",
            "amount": ' . $nacho_platter_lg . '
        },
        {
            "ticketType": "Nacho Platter Small",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_41_3') . '",
            "amount": ' . $nacho_platter_sm . '
        },
        {
            "ticketType": "Carne Asada Taco Platter",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_11_3') . '",
            "amount": ' . $carn_asada_taco_platter . '
        },
        {
            "ticketType": "Mini Corn Dogs",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_12_3') . '",
            "amount": ' . $mini_corn_dogs . '
        },
        {
            "ticketType": "Birthday Cake",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_42_3') . '",
            "amount": ' . $birthday_cake . '
        },
        {
            "ticketType": "Caesar Salad W/Chk",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_44_3') . '",
            "amount": ' . $ceasar_salad_chiken . '
        },
        {
            "ticketType": "Caesar Salad W/O Chk",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_43_3') . '",
            "amount": ' . $ceasar_salad_no_chiken . '
        },
        {
            "ticketType": "Chicken Tender w/FF Lg",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_45_3') . '",
            "amount": ' . $chiken_tender_lg . '
        },
        {
            "ticketType": "Chicken Tender w/FF Sm",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_46_3') . '",
            "amount": ' . $chiken_tender_sm . '
        },
        {
            "ticketType": "Chicken Wing Platter Lg",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_47_3') . '",
            "amount": ' . $chiken_wing_lg . '
        },
        {
            "ticketType": "Chicken Wing Platter Sm",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_48_3') . '",
            "amount": ' . $chiken_wing_sm . '
        },
        {
            "ticketType": "Cookie Platter",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_49_3') . '",
            "amount": ' . $cookie_platter . '
        },
        {
            "ticketType": "Southwest Salad w/Chk",
            "sectionId": "' . rgpost('input_28') . '",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_50_3') . '",
            "amount": ' . $south_west_salad . '
        },
        {
            "ticketType": "' . rgpost('input_1') . '",
            "sectionId": "' . rgpost('input_31') . '",
            "capacityId": "' . rgpost('input_30') . '",
            "quantity": "' . rgpost('input_6_3') . '",
            "amount": 0
        },
        {
            "ticketType": "Fruit Platter Small",
            "sectionId": "0",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_54_3') . '",
            "amount": ' . $fruit_platter_sm . '
        },
        {
            "ticketType": "Fruit Platter Large",
            "sectionId": "0",
            "capacityId": "' . rgpost('input_29') . '",
            "quantity": "' . rgpost('input_55_3') . '",
            "amount": ' . $fruit_platter_lg . '
        }
    ],
    "payment": {
        "cardholerName": "Omitted",
        "billingStreet": "' . rgpost('input_52_1') . ', ' . rgpost('input_52_2') . ', ' . rgpost('input_52_3') . ', ' . rgpost('input_52_4') . '",
        "billingZipCode": "' . rgpost('input_52_5') . '",
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
            $payment_verify_url = 'https://staging13.tickets.wildrivers.com/wp-json/gravityForms/getPaymentStatus?order_id=' . rgpost('input_26');
            $payment_verify_response = wp_remote_get($payment_verify_url);
            $payment_verify_response_body = json_decode(wp_remote_retrieve_body($payment_verify_response), true);
            ?>

            <?php

            if (isset($payment_verify_response_body['error_code']) && $payment_verify_response_body['error_code'] === 0) {
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
                                    <td style="padding:5px">Birthday Packages</td>
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
                                <tr>
                                    <td style="padding:5px">General Tickets</td>
                                    <td style="padding:5px"><?php echo rgpost('input_6_3') * 8; ?></td>
                                    <td style="padding:5px">Included</td>
                                </tr>

                                <tr>
                                    <td style="padding:5px">Extra General Tickets</td>
                                    <td style="padding:5px"><?php echo rgpost('input_9_3'); ?></td>
                                    <td style="padding:5px"><?php echo rgpost('input_9_2'); ?></td>
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
                                    <td colspan="2" style="padding:5px">Service Fee:</td>
                                    <td style="padding:5px">$0</td>
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
                                    <?php echo rgpost('input_17'); ?>                 <?php echo rgpost('input_18'); ?> <br />
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
            } // 3ds Email Checker

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