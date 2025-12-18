<?php

// Submitting to API and For Ticketing Platform
//add_filter('gform_entry_post_save_17', 'change_form_17', 10, 3); 
add_filter('gform_after_submission_17', 'change_form_17', 10, 3); 
function change_form_17($form) 
{
    $order_id = rgpost('input_57');
    $post_url = 'https://dynamicpricing-api.dynamicpricingbuilder.com/SeasonPassDashboardAPIs/AddWavierForm?authCode=0a27e421-e1e7-4530-80f8-fca5789b79be';
    
    $current_date = date('Y-m-d');
    
    // Waiver Payload
    $body = array(
        'organization' => array(
            'formName' => rgpost('input_6'),
            'organizationName' => 'BOLDER ADVENTURE PARK',
            'qrCode' => rgpost('input_1'),
            'name' => rgpost('input_4'),
            'dob' => rgpost('input_11'),
            'parentName' => rgpost('input_14'),
            'parentAddress' => rgpost('input_15'),
            'streetAddress' => rgpost('input_16'),
            'city' => rgpost('input_17'),
            'state' => rgpost('input_18'),
            'zipCode' => rgpost('input_19'),
            'parentPhone' => rgpost('input_9'),
            'emergencyNumber' => rgpost('input_23'),
            'untitled' => '',
            'permission' => 0,
            'assumptionandacknowledgmentofallrisks' => 0,
            'releaseandwaiverofallclaims' => 0,
            'indemnity' => 0,
            'recreationalservices' => 0,
            'postedsignsandsafetyrules' => 0,
            'waiverandrelease' => 0,
            'date' => $current_date
        ),
        'signatureImage' => base64_encode(rgpost('input_4'))
    );
    

    // Sending Data Condition Ends here
    $headers = array(
        'Content-Type' => 'application/json'
    );
    /*echo '<pre>';
    var_dump($body);
    echo '</pre>'; */

    $body = json_encode($body);
    $response = wp_remote_post($post_url, array(
        'headers' => $headers,
        'method' => 'POST',
        'body' => $body
    ));

  /*  echo '<pre>';
    var_dump($response);
    echo '</pre>';
*/
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    // GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $body, true ) );

    if ($response_body != '') { 
           
        if ($response_body['status']['errorCode'] != 0) {
            
            $email_body = 'Hi, We may have issue with this order, Error Code Was Not Zero. Error Code:' . $response_body['status']['errorCode'] . ' & Error Message: ' . $response_body['status']['errorMessage'] . ' Please Check Further Details';
            //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
            $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com';
            
            //$subject = 'Thank You For Purchasing - Your Order Number is #' . rgpost('input_26');
            $subject = 'Problem For Signing the Bolder Waiver for Ticket QR' . rgpost('input_1') . ' for ' . rgpost('input_3');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');

            $form['is_active'] = false;
            //return $form;   // to see the var_dump output
            return 0;       // to prevent form submission
        } else {
            
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

            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4">
                <tr>
                    <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;">
                        
                        <!-- Logo -->
                        <tr>
                        <td align="center" style="padding:20px 0;background-color:#ffffff;">
                            <img src="http://tickets.bolderadventurepark.com/wp-content/uploads/2025/01/email-header-email-Bolder.jpg" alt="Bolder Adventure Park" width="" style="display:block;" />
                        </td>
                        </tr>

                        <!-- Header -->
                        <tr>
                        <td bgcolor="#004388" style="padding:30px;text-align:center;">
                            <h1 style="color:#ffffff;font-family:Arial,sans-serif;font-size:24px;margin:0;">✅ You're All Set!</h1>
                            <p style="color:#bbbbbb;font-family:Arial,sans-serif;font-size:16px;margin-top:10px;">Thank You for Signing the Waiver for <?php echo rgpost('input_1'); ?></p>
                        </td>
                        </tr>

                        <!-- Body -->
                        <tr>
                        <td style="padding:30px;font-family:Arial,sans-serif;color:#333333;font-size:16px;line-height:1.6;">
                            <p>Hi <?php echo rgpost('input_4'); ?></p>
                            <p>We’ve received your waiver and you're officially good to go! We truly appreciate you taking a moment to complete it.</p>
                            <p>Our team is excited to welcome you. If you have any questions before your visit, we’re here to help.</p>
                            <p>See you soon!</p>

                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:25px;">
                            <tr>
                                <td align="center">
                                <a href="https://www.bolderadventurepark.com/" style="background-color:#004388;color:#ffffff;text-decoration:none;padding:12px 25px;font-size:16px;border-radius:5px;display:inline-block;">Visit Our Website</a>
                                </td>
                            </tr>
                            </table>
                        </td>
                        </tr>

                        <!-- Social Icons -->
                        <tr>
                        <td style="padding:20px;text-align:center;background-color:#f9f9f9;">
                            <p style="font-size:14px;color:#999999;margin-bottom:10px;">Stay connected:</p>
                            <a href="https://www.facebook.com/BolderAdventurePark" style="margin:0 10px;" target="_blank"><img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" border="0" /></a>
                            <a href="https://www.instagram.com/BolderAdventurePark" style="margin:0 10px;" target="_blank"><img src="https://cdn-icons-png.flaticon.com/24/733/733558.png" alt="Instagram" border="0" /></a>
                        </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                        <td style="background-color:#eeeeee;padding:15px;text-align:center;font-size:12px;color:#888888;">
                            &copy; 2025 Bolder Adventure Park. All rights reserved.
                        </td>
                        </tr>

                    </table>
                    </td>
                </tr>
            </table>
           
            <?php

            $email_body = ob_get_clean();
            //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
            $to = rgpost('input_5');
            $subject = 'Thank You For Signing the Waiver for Ticket QR ' . rgpost('input_1') . ' for ' . rgpost('input_3');

            //    This is going to sent the email-----------------------------------------------------------------------------------
            wp_mail($to, $subject, $email_body, 'Content-type: text/html');

            return $form;
        } // Error Code Else

        // Checking Null Value in Return Bracket
    } else { 

        $email_body = 'Hi, Customer tried but got error, we might have to look into this.';
        //    Developing the email system with wordpress builtin function ---- wp_mail($to, $subject, $email_body, $headers)
        $to = 'shawn.bowman@ideaseat.com, design.turtles700@gmail.com, huzaifa@idreamtechnology.com';
        $subject = 'Problem For Signing BOLDER Waiver for Ticket QR' . rgpost('input_1') . ' for ' . rgpost('input_3');

        //    This is going to sent the email-----------------------------------------------------------------------------------
        wp_mail($to, $subject, $email_body, 'Content-type: text/html');

    }
}; // Function Bracket


add_filter('gform_confirmation_17', 'redirect_to_current_url_17', 10, 4);
function redirect_to_current_url_17($confirmation, $form, $entry, $ajax) {
    // Get the current URL
    $current_url = (is_ssl() ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Redirect to same URL
    return array('redirect' => $current_url);
}