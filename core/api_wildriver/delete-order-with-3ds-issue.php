<?php  

add_action('wp_ajax_delete_order_via_ajax', 'handle_ajax_order_deletion');
add_action('wp_ajax_nopriv_delete_order_via_ajax', 'handle_ajax_order_deletion'); // Allow non-logged-in users if needed

function handle_ajax_order_deletion() {
    $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : '';

    if (empty($order_id)) {
        wp_mail(
            'design.turtles700@gmail.com', 
            'Order Deletion Failed - Missing ID', 
            "No order ID was provided in the AJAX request."
        );

        wp_send_json_error(['message' => 'Order ID is missing.']);
    }

    $response = delete_order_from_api($order_id);

    if ($response['success']) {
        wp_send_json_success(['message' => 'Order deleted successfully.']);
    } else {
        wp_send_json_error(['message' => $response['error']]);
    }
}

function delete_order_from_api($order_id) {
    $post_url = 'https://your-api-endpoint.com/delete'; // replace this
    $headers = ['Content-Type' => 'application/json'];
    $body = json_encode([$order_id]);

    $response = wp_remote_post($post_url, [
        'headers' => $headers,
        'method'  => 'POST',
        'body'    => $body
    ]);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();

        error_log('API Error: ' . $error_message);

        wp_mail(
            'design.turtles700@gmail.com', 
            'Order Deletion Failed', 
            "Order ID: $order_id\nError: $error_message"
        );

        return [
            'success' => false,
            'error' => $error_message
        ];
    }

    $api_result = wp_remote_retrieve_body($response);

    // Log & notify success
    error_log("Order deleted: $order_id | API Response: $api_result");

    wp_mail(
        'design.turtles700@gmail.com, shawnbowman6@gmail.com',
        '3ds Issue - Order Deleted Successfully',
        "The following order was deleted successfully:\n\nOrder ID: $order_id\n\nAPI Response:\n$api_result"
    );

    return [
        'success' => true,
        'result' => $api_result
    ];
}