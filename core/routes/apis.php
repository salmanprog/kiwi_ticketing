<?php

Route::get('/', 'APIsController@api')->name('apiURL');
// general
Route::get('/website/status', 'APIsController@website_status');
Route::get('/website/info/{lang?}', 'APIsController@website_info');
Route::get('/website/contacts/{lang?}', 'APIsController@website_contacts');
Route::get('/website/style/{lang?}', 'APIsController@website_style');
Route::get('/website/social', 'APIsController@website_social');
Route::get('/website/settings', 'APIsController@website_settings');
Route::get('/menu/{menu_id}/{lang?}', 'APIsController@menu');
Route::get('/banners/{group_id}/{lang?}', 'APIsController@banners');
// section & topics
Route::get('/section/{section_id}/{lang?}', 'APIsController@section');
Route::get('/categories/{section_id}/{lang?}', 'APIsController@categories');
Route::get('/topics/{section_id}/page/{page_number?}/count/{topics_count?}/{lang?}', 'APIsController@topics');
Route::get('/category/{cat_id}/page/{page_number?}/count/{topics_count?}/{lang?}', 'APIsController@category');
// topic sub details
Route::get('/topic/fields/{topic_id}/{lang?}', 'APIsController@topic_fields');
Route::get('/topic/photos/{topic_id}/{lang?}', 'APIsController@topic_photos');
Route::get('/topic/photo/{photo_id}/{lang?}', 'APIsController@topic_photo');
Route::get('/topic/maps/{topic_id}/{lang?}', 'APIsController@topic_maps');
Route::get('/topic/map/{map_id}/{lang?}', 'APIsController@topic_map');
Route::get('/topic/files/{topic_id}/{lang?}', 'APIsController@topic_files');
Route::get('/topic/file/{file_id}/{lang?}', 'APIsController@topic_file');
Route::get('/topic/comments/{topic_id}/{lang?}', 'APIsController@topic_comments');
Route::get('/topic/comment/{comment_id}/{lang?}', 'APIsController@topic_comment');
Route::get('/topic/related/{topic_id}/{lang?}', 'APIsController@topic_related');
// topic page
Route::get('/topic/{topic_id}/{lang?}', 'APIsController@topic');
// user topics
Route::get('/user/{user_id}/topics/page/{page_number?}/count/{topics_count?}/{lang?}', 'APIsController@user_topics');
// Forms Submit
Route::post('/subscribe', 'APIsController@subscribeSubmit');
Route::post('/comment', 'APIsController@commentSubmit');
Route::post('/order', 'APIsController@orderSubmit');
Route::post('/contact', 'APIsController@ContactPageSubmit');
//General Settings
Route::get('/general/settings', 'GeneralController@website_setting');
//Cabana Settings
Route::get('/cabana/featured/product', 'CabanaController@index');
Route::post('/cabana/featured/product', 'CabanaController@store');
//Cabana Addon
Route::get('/cabana/addon', 'CabanaAddonController@index');
Route::get('/cabana/addon/{lang}', 'CabanaAddonController@show');
//Birthday
Route::get('/birthday/packages', 'BirthdayController@index');
Route::get('/birthday/addon/{slug}', 'BirthdayAddonController@show');
//Tickets
Route::post('/ticket-hold', 'TicketController@ticketHold');
Route::post('/cabana-occupancy', 'TicketController@GetCabanaOccupancy');
Route::get('/calendar', 'TicketController@GetCalendar');
//General Ticket
Route::get('/general-package', 'GeneralTicketController@index');
Route::get('/general-ticket-package/{slug}', 'GeneralTicketController@generalTicketAddon');

//Route::get('/general-ticket-cabana/{slug}', 'GeneralTicketController@generalTicketCabana');
//Season Pass
Route::get('/season-pass', 'SeasonPassController@index');
Route::get('/season-pass-product/{slug}', 'SeasonPassAddonController@getBySlug');
//Coupon Code
Route::get('/coupon', 'CouponController@index');
Route::post('/coupon-discount', 'CouponController@store');
//Stripe
Route::post('/order-payment', 'StripeController@createPaymentIntent');
Route::post('/order-create', 'OrderController@OrderCreate');
Route::get('/order/{slug}', 'OrderController@getBySlug');
Route::middleware('apiAuth')->group(function () {
    Route::get('/secure-data', function () {
        return response()->json(['data' => 'Authorized Access!']);
    });
});