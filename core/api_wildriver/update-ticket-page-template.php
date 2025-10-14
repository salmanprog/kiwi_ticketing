<?php
/* Template Name: Update Ticket Page Template  */

get_header(); ?>
<?php if (post_password_required()): ?>

    <h1 style="text-align:center;"> Maintainaince Mode</h1>
    <?php echo get_the_password_form(); ?>

<?php else: ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">

    <section class="_update_ticket_title">
        <div class="_update_ticket_title_inner">
            <h1><?php the_title(); ?></h1>
            <p>Instructions:</p>
            <ol>
                <li>Click the calendar on the ticket you would like to update and select the new date.</li>
                <li>Click Check Availability to see if that date is available.</li>
                <li>Click Update Date.</li>
                <li>Click Send Order Update Email to receive the updated email of your order.</li>
            </ol>
            <p>**Weekend Cabanas may be updated to other weekends, Weekday Cabanas may be updated to other weekdays.</p>
            <p style="color: #F15656; font-weight: 700;">Important: When updating the cabana date, please ensure that any
                food items in the order are also updated separately to reflect the same date.</p>
        </div>
    </section>

    <section class="_update_tickets">
        <div class="_success_message"></div>
        <div class="_error_message"></div>
        <div class="_update_tickets_inner"></div>
    </section>

    <section class="btn_update_sec" style="padding-bottom:70px">
        <a href="javascript:void(0);" class="_btn_send_order_update_email">Send Order Update Email </a>
    </section>

    <div class="_update_email_popup">
        <div class="_update_email_popup_wrap">
            <div class="_update_email_popup_inner">
                <p>Your update email has been sent!</p>
                <a href="javascript:void(0);" class="_btn_close_email_popop">Close</a>
            </div>
        </div>
    </div>

    <section class="_ticket_update_checkout_form" style="display:none">
        <div class="_ticket_update_checkout_form_inner">
            <a href="javascript:void(0)" class="_close_ticket_update_form">
                <img src="https://wildrivers.com/wp-content/uploads/2024/03/x-mark.jpg" alt="Close">
            </a>
            <?php echo do_shortcode('[gravityform id="13" title="false" ajax="true"]'); ?>
        </div>
    </section>
    <style type="text/css">
        ._update_email_popup {
            position: fixed;
            top: 0;
            z-index: 999;
            background-color: rgb(0, 0, 0, 0.5);
            width: 100%;
            height: 100vh;
            display: none;
        }

        ._update_email_popup_wrap {
            text-align: center;
            background: #fff;
            padding: 25px;
            display: table;
            width: 100%;
            position: relative;
            max-width: 520px;
            margin: 0 auto;
            margin-top: 40vh;
        }

        ._update_email_popup_inner a {
            background: #213664;
            display: table;
            margin: 0 auto;
            padding: 10px 45px;
            color: #fff !important;
            text-decoration: none;
            border-radius: 70px;
        }

        ._update_email_popup_inner {
            font-size: 15px;
            font-family: montserrat;
        }

        ._update_ticket_title {
            padding: 50px 0 0px 0;
            text-align: left;
        }

        ._update_ticket_title_inner {
            padding: 0 15px;
            max-width: 830px;
            margin: 0 auto;
        }

        ._update_ticket_title_inner h1 {
            font-size: 35px;
            font-family: Montserrat;
            font-weight: 300;
            margin: 0;
            color: #183769;
        }

        ._update_tickets {
            padding-bottom: 10px;
        }

        ._update_tickets_inner {
            margin: 0 auto;
            display: table;
            width: 100%;
            max-width: 1170px;
            background: #f2f2f2;
            padding: 15px;
            border-radius: 10px;
        }

        .idu_single_ticket_section {
            padding: 20px 15px;
            background: #76b4ec !important;
            border-radius: 10px;
            width: 33%;
            float: left;
            margin: 0.16%;
        }

        ._ticket_type {
            font-size: 16px;
            color: #fff;
            font-family: montserrat;
            font-weight: 500;
            line-height: 1.8;
        }

        ._ticket_desc {
            font-size: 12px;
            color: #fff;
            font-family: montserrat;
            font-weight: 400;
        }

        ._ticket_date {
            display: table;
            font-family: montserrat;
            font-weight: 500;
            font-size: 14px;
            color: #fff;
        }

        ._ticket_date_update_wrapper {
            padding: 10px;
            background: #709fca;
            margin-top: 10px;
            border-radius: 10px;
        }

        .idu_ticket_date {
            width: 100%;
            height: 35px;
            font-family: montserrat;
            font-weight: 500;
            background: transparent;
            border: 1px solid #fff;
            font-size: 12px;
            color: #fff !important;
            border-radius: 6px;
        }

        ._btn_hold_to_change_date {
            width: 49%;
            margin: 0 !important;
            background: #f44932 !important;
            color: #fff !important;
            border-radius: 50px !important;
            padding: 11px 20px !important;
            border: 0 !important;
            font-family: Montserrat !important;
            font-size: 10px !important;
            letter-spacing: 0px !important;
            margin-top: 5px !important;
            margin-right: 0% !important;
        }

        .idu_submit_button {
            background: #183769 !important;
            color: #fff !important;
            width: 49%;
            border-radius: 50px !important;
            padding: 11px 20px !important;
            border: 0 !important;
            font-family: Montserrat !important;
            font-size: 10px !important;
            letter-spacing: 0px !important;
            margin-top: 5px !important;
            margin-left: 0.45% !important;
        }

        ._success_message {
            margin: 0 auto;
            background: #007400;
            color: #fff;
            text-align: left;
            font-family: montserrat;
            line-height: 1.2;
            font-size: 15px;
            font-weight: 400;
            position: fixed;
            top: 20%;
            right: -320px;
            box-shadow: 0px 0px 10px 0px #c0c0c0;
            z-index: 999;
            padding: 15px 20px;
            width: 100%;
            max-width: 320px;
            transition: 0.5s all;
        }

        ._success_message._is_active {
            right: 0px;
        }

        /* ._error_message {
                max-width: 800px;
                margin: 0 auto;
                background: #e30000;
                color: #fff;
                text-align: center;
                font-family: montserrat;
                line-height: 2.2;
                font-size: 16px;
                font-weight: 400;
            }*/

        ._error_message {
            margin: 0 auto;
            background: #e30000;
            color: #fff;
            text-align: left;
            font-family: montserrat;
            line-height: 1.2;
            font-size: 15px;
            font-weight: 400;
            position: fixed;
            top: 20%;
            right: -320px;
            box-shadow: 0px 0px 10px 0px #c0c0c0;
            z-index: 999;
            padding: 15px 20px;
            width: 100%;
            max-width: 320px;
            transition: 0.5s all;
        }

        ._error_message._is_active {
            right: 0px;
        }


        .btn_update_sec {
            text-align: left;
            padding: 10px 10px;
            max-width: 820px;
            margin: 0 auto;
        }

        ._btn_send_order_update_email {
            background: #183769 !important;
            color: #fff !important;
            border-radius: 50px !important;
            padding: 14px 40px !important;
            border: 0 !important;
            font-family: Montserrat !important;
            font-size: 15px;
            text-transform: uppercase;
            margin: 0 auto !important;
            display: table;
        }

        ._update_ticket_title_inner ol {
            list-style: decimal;
            padding-left: 15px;
        }

        ._update_ticket_title_inner h1 {
            text-align: center;
        }

        ._update_ticket_title_inner p,
        ._update_ticket_title_inner li {
            font-size: 14px;
            font-family: Montserrat;
            margin-bottom: 10px;
        }

        ._ticket_update_checkout_form {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.8);
            padding: 10%;
        }

        ._ticket_update_checkout_form_inner {
            max-width: 550px;
            margin: 0 auto;
            background: #f5f5f5;
            padding: 10px 20px;
            position: relative;
        }

        ._ticket_update_confirmation_gf {
            padding: 10px 0;
        }

        ._ticket_update_confirmation_gf h4 {
            margin: 0;
            font-size: 20px;
            font-family: montserrat;
            font-weight: 400;
            text-align: center;
        }

        ._ticket_update_confirmation_gf p {
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
            font-family: montserrat;
        }

        ._close_ticket_update_form img {
            max-width: 40px;
            border-radius: 50%;
        }

        ._close_ticket_update_form {
            position: absolute;
            top: -15px;
            right: -15px;
        }

        ._ticket_type img {
            max-width: 50px;
            float: right;
        }

        ._cabana_listingfor_update {
            display: inline-block;
            background: #183769;
            width: 40px;
            height: 40px;
            line-height: 35px;
            text-align: center;
            border: 3px solid #709fca;
            font-size: 14px;
            color: #fff;
            font-family: montserrat;
            cursor: pointer;
        }

        ._update_cabana_div {
            width: 50px;
            height: 30px;
            display: inline-block;
            margin-top: 10px;
            border: 1px solid #ececec;
            margin-right: 4px;
            line-height: 30px;
            text-align: center;
            font-size: 12px;
        }

        ._update_cabana_div span {
            padding-left: 5px;
        }

        ._slct_option_ticket_Type {
            border: 1px solid #fff;
            border-radius: 4px;
            background: transparent;
            color: #fff !important;
        }

        ._cabana_type_label {
            color: #fff !important;
            font-family: montserrat;
            font-weight: 500;
            font-size: 14px !important;
        }

        ._slct_option_ticket_Type option {
            color: #000;
        }

        .idu_ticket_date::-webkit-inner-spin-button,
        .idu_ticket_date::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none !important;
        }


        @media screen and (max-width: 999px) {
            .idu_single_ticket_section {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>


    <div class="order_id_display" style="display: none !important;"><?php echo $_REQUEST['order']; ?></div>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            var ajaxurl = 'https://staging13.tickets.wildrivers.com/wp-admin/admin-ajax.php';
            var orderID = $('.order_id_display').text();
            let url = "http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/QueryOrder2?orderId=" + orderID + '&authCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';

            let value = {
                'action': 'cabana_order_loader',
                'url': url
            };

            jQuery.post(ajaxurl, value, function (response) {
                let value = jQuery.parseJSON(response);
                // Display all tickets in loop
                //console.log(value);
                $('._update_tickets_inner').html(value);

                jQuery('._btn_hold_to_change_date').click(function () {
                    var hold_date = $(this).closest('.idu_single_ticket_section').find('.idu_ticket_date').val();
                    console.log(hold_date);
                    var ticket_type = $(this).closest('.idu_single_ticket_section').find('._slct_option_ticket_Type').val();
                    console.log(ticket_type);
                    var visual_ID = $(this).closest('.idu_single_ticket_section').find('._ticket_visual_id').val();

                    var Order_ID = $('._order_number').val();

                    //var cabana_number = $(this).closest('.idu_single_ticket_section').find('._cabana_number').val();
                    var cabana_number = jQuery('._cabana_checkbox:checked').val();
                    console.log(cabana_number);


                    var current_ticket_price = $(this).closest('.idu_single_ticket_section').find('._ticket_price').val();

                    //console.log(current_ticket_price);
                    // alert(hold_date);
                    // alert(ticket_type);
                    // alert(cabana_number);

                    $('.idu_single_ticket_section').removeClass('edit_mode');

                    $(this).closest('.idu_single_ticket_section').addClass('edit_mode');

                    let url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/TicketHold?date=' + hold_date + '&AuthCode=d063d05b-fbb1-4bf0-b4d8-b2f603454858';

                    let ticketTypeGeneral = ticket_type;

                    // let ticketTypeCabana  = cabana_type;  // get the ticket type here ----------------------
                    //let quantityCabana    = 1;            // get the quantity here -------------------------
                    //let seatCabana        = cabana_number_for_hold;            // get the seats ---------------------------------

                    let data = {
                        'action': 'seat_hold_for_change',
                        'url': url,
                        'ticketTypeGeneral': ticketTypeGeneral,
                        'quantityTickets': 1,
                        'cabanaSeatNumber': cabana_number
                    };



                    jQuery.post(ajaxurl, data, function (response) {
                        let data = jQuery.parseJSON(response);
                        console.log(data);

                        var new_ticket_price = data.data[0].price;
                        var check_price_for_update = new_ticket_price - current_ticket_price;
                        jQuery('._txt_price_difference #input_13_1').val(check_price_for_update);
                        jQuery('._txt_session_id_update input').val(data.sessionId);
                        jQuery('._txt_order_id_update input').val(Order_ID);
                        jQuery('._txt_visual_id_update input').val(visual_ID);
                        jQuery('._txt_section_id_update input').val(data.data[0].sectionId);
                        jQuery('._txt_capacity_id_update input').val(data.data[0].capacityId);
                        jQuery('._txt_ticket_type_update input').val(data.data[0].eventName);
                        jQuery('._txt_date_update input').val(hold_date);
                        jQuery('._txt_total_price_update input').val(data.data[0].price);


                        // alert(check_price_for_update);

                        if (check_price_for_update > 0) {

                            let new_date_OrderID = new Date();
                            let new_dynamic_OrderID = new_date_OrderID.getTime();
                            let newOrderNumber = 'MOD-' + new_dynamic_OrderID;
                            jQuery('._txt_order_id_new_generated input').val(newOrderNumber);
                            jQuery('._ticket_update_checkout_form').fadeIn();



                        } else {

                            jQuery('.idu_single_ticket_section.edit_mode ._ticket_session_id').val(data.sessionId);
                            jQuery('.idu_single_ticket_section.edit_mode ._ticket_section_id').val(data.data[0].sectionId);
                            jQuery('.idu_single_ticket_section.edit_mode ._ticket_capacity_id').val(data.data[0].capacityId);
                            jQuery('.idu_single_ticket_section.edit_mode ._new_update_ticket_price').val(data.data[0].price);
                            jQuery('.idu_single_ticket_section.edit_mode .idu_submit_button').fadeIn();



                            if (data.status.errorMessage == '') {
                                $('._error_message').removeClass('_is_active');
                                $('._error_message').text('');
                                $('._success_message').text('Your ticket for selected date is available.');
                                $('._success_message').addClass('_is_active');
                                setTimeout(function () {
                                    $('._success_message').removeClass('_is_active');
                                }, 5000);
                                /* $("html, body").animate({
                                    scrollTop: 0
                                }, 1000);*/

                            } else {

                                //alert('there is an error in availablity for your ticket. Error code' + data.status.errorMessage);
                                $('._success_message').text('');
                                $('._error_message').text('There is an error in availablity for your ticket.');
                                $('._error_message').addClass('_is_active');
                                $('._success_message').removeClass('_is_active');

                                setTimeout(function () {
                                    $('._error_message').removeClass('_is_active');
                                }, 5000);

                                /*$("html, body").animate({
                                    scrollTop: 0
                                }, 1000);*/

                                // Adding Checkout Page for Ticket Update
                                /* jQuery('._session_id input').val(data.sessionId);
                                jQuery('._section_id_ticket input').val(data.data[0].sectionId);
                                jQuery('._capacity_id_ticket input').val(data.data[0].capacityId);
                                jQuery('._section_id_cabana input').val(data.data[1].sectionId);
                                jQuery('._capacity_id_cabana input').val(data.data[1].capacityId);
                                jQuery('._cabana_type_number input').val(data.data[1].eventName);         */
                                /*console.log('Event Name Ticket');
                                console.log(data.data[0].eventName);
                                console.log(data.sessionId);*/

                            }
                        }





                    });

                });



                $('.idu_submit_button').click(function () {
                    $(this).hide();
                    var sessionID = $('.idu_single_ticket_section.edit_mode ._ticket_session_id').val();
                    var sectionID = $('.idu_single_ticket_section.edit_mode ._ticket_section_id').val();
                    var capacityID = $('.idu_single_ticket_section.edit_mode ._ticket_capacity_id').val();
                    var visualID = $('.idu_single_ticket_section.edit_mode ._ticket_visual_id').val();
                    var ticketPrice = $('.idu_single_ticket_section.edit_mode ._ticket_price').val();
                    var ticket_type = $('.idu_single_ticket_section.edit_mode ._slct_option_ticket_Type').val();
                    //_slct_option_ticket_Type

                    var dateInputValue = $(this).closest('._ticket_date_update_wrapper').find('input[type="date"]').val();
                    //alert(dateInputValue);
                    var dateObject = new Date(dateInputValue);
                    var dateValue = dateObject.toISOString();
                    //alert(dateValue);

                    let new_date_OrderID = new Date();
                    let new_dynamic_OrderID = new_date_OrderID.getTime();
                    let newOrderNumber = 'MOD-' + new_dynamic_OrderID;

                    //alert(newOrderNumber);


                    // var dateValue = '2024-03-25T00:00:00.000Z';

                    var prevOrderNumber = $('._order_number').val();
                    var OrderTotal = $('._order_total').val();
                    var OrderDate = $('._order_date').val();

                    /*  console.log('Session ID' + sessionID);
                      console.log('Section ID' + sectionID);
                      console.log('Capacity ID' + capacityID);
                      console.log('Visual ID' + visualID);
                      console.log('dateValue: ' + dateValue);
                      console.log('Ticket Price: ' + ticketPrice);
                      console.log('Ticket Type: ' + ticket_type);*/

                    let url = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/UpdateOrder';

                    let session_ID = sessionID;
                    let section_ID = sectionID;
                    let capacity_ID = capacityID;
                    let visual_ID = visualID;
                    let ticket_PRICE = ticketPrice;




                    let data = {
                        'action': 'update_ticket_date',
                        'url': url,
                        'sessionId': session_ID,
                        'visualId': visual_ID,
                        'sectionId': sectionID,
                        'capacityId': capacityID,
                        'previousOrderNumber': prevOrderNumber,
                        'newOrderNumber': newOrderNumber,
                        'ticketAmount': ticket_PRICE,
                        'ticketType': ticket_type,
                        'amount': OrderTotal,
                        'date': dateValue
                    };

                    //console.log(data);

                    jQuery.post(ajaxurl, data, function (response) {
                        console.log(response);
                        let data = jQuery.parseJSON(response);
                        //console.log(data);
                        if (data.status.errorMessage == '') {
                            $('._error_message').text('');
                            $('._error_message').removeClass('_is_active');
                            $('._success_message').text('Your ticket has been updated successfully. This page will reload.');
                            $('._success_message').addClass('_is_active');
                            $('.idu_submit_button').fadeOut();
                            setTimeout(function () {
                                $('._success_message').removeClass('_is_active');
                                location.reload(true);
                            }, 3000);

                            /*$("html, body").animate({
                                scrollTop: 0
                            }, 1000);*/

                        } else {

                            $('._success_message').text('');
                            $('._success_message').removeClass('_is_active');
                            $('._error_message').text('There is an error updating your ticket. Error code' + data.status.errorMessage);
                            $('._error_message').addClass('_is_active');
                            $('.idu_submit_button').fadeOut();

                            setTimeout(function () {
                                $('._error_message').removeClass('_is_active');
                            }, 5000);

                            $('.idu_single_ticket_section.edit_mode .idu_submit_button').fadeIn();

                            /*$("html, body").animate({
                                scrollTop: 0
                            }, 1000);*/
                        }
                    });
                });


                $(function () {

                    var dtToday = new Date();

                    //var todaydate = ("0" + (dtToday.getDate()+2)).slice(-2);
                    var todaydate = ("0" + (dtToday.getDate())).slice(-2);
                    var current_month = ("0" + (dtToday.getMonth() + 1)).slice(-2);

                    //Min Date
                    var strDate = dtToday.getFullYear() + "-" + current_month + "-" + todaydate;

                    /*var month = dtToday.getMonth();
                    var day = dtToday.getDay();*/
                    var year = dtToday.getFullYear();
                    /*if(month < 10)
                    month = '0' + month.toString();
                    if(day < 10)
                    day = '0' + day.toString();*/

                    //Max Date
                    var max_month = 10;
                    var max_day = 13;
                    if (max_month < 10)
                        max_month = '0' + max_month.toString();
                    if (max_day < 10)
                        max_day = '0' + max_day.toString();
                    //var minDate = year + '-' + month + '-' + day;
                    var maxDate = year + '-' + max_month + '-' + max_day;
                    $('.idu_ticket_date').attr('min', strDate);
                    $('.idu_ticket_date').attr('max', maxDate);
                });

            });


            $('._btn_send_order_update_email').click(function () {
                var OrderID_jquery = $('.order_id_display').text();
                //alert(orderID);
                let data = {
                    'action': 'update_order_email_date',
                    'Order_ID': OrderID_jquery
                };

                jQuery.post(ajaxurl, data, function (response) {
                    //console.log(response);
                    let data = jQuery.parseJSON(response);
                    console.log(data);
                    if (data == 0) {
                        $('._update_email_popup').fadeIn();
                    }

                    /* if (data.status.errorMessage == ''){
                            $('._success_message').text('Your ticket has been updated successfully.');
                            $("html, body").animate({scrollTop: 0}, 1000);
                        }else{
                            $('._error_message').text('There is an error updating your ticket. Error code' + data.status.errorMessage);
                        $("html, body").animate({scrollTop: 0}, 1000);

                        }*/
                });
            });

            // Close Email Popup Window

            $('._btn_close_email_popop').click(function () {
                $('._update_email_popup').fadeOut();
            });

            $('._close_ticket_update_form').click(function () {
                $('._ticket_update_checkout_form').fadeOut();
            });

            $('._txt_price_difference input').attr('readonly', '');




        });
    </script>

<?php endif; ?>
<?php get_footer(); ?>