<?php
/*
Plugin Name: Frame Order Reminder Plugin Suresh Kumar
Plugin URI: https://your-plugin-url.com/
Description: Sends order reminder emails after one hour, one day, two days Suresh Kumar
Version: 1.0
Author: Suresh Kumar Meesala
Author URI: https://your-author-url.com/
License: GPL2
*/

// Add the action hook to send email reminders
// Add the action hook to send email reminders
add_action( 'woocommerce_thankyou', 'send_order_reminder_emails', 10, 1 );

function send_order_reminder_emails( $order_id ) {
    // Get the order object
    $order = wc_get_order( $order_id );

    // Check if the order contains products in the "frames" category
    $frames_category_id = get_term_by( 'name', 'frames', 'product_cat' )->term_id;
    $order_items = $order->get_items();
    $has_frames_products = false;
    foreach ( $order_items as $item ) {
        $product_id = $item->get_product_id();
        $product_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
        if ( in_array( $frames_category_id, $product_categories ) ) {
            $has_frames_products = true;
            break;
        }
    }

    // Get the customer email address
    $customer_email = $order->get_billing_email();

    // Define the email subject and message
    $subject = 'Reminder: Your Order Has Been Placed Suresh Kumar';
    $message = 'Thank you for your order. This is a reminder that your order has been placed. Suresh Kumar';

    // Schedule email reminders for orders that contain products in the "frames" category
    if ( $has_frames_products ) {
        wp_schedule_single_event( time() + 3600, 'order_reminder_email', array( $customer_email, $subject, $message ) );
        wp_schedule_single_event( time() + 86400, 'order_reminder_email', array( $customer_email, $subject, $message ) );
        wp_schedule_single_event( time() + 172800, 'order_reminder_email', array( $customer_email, $subject, $message ) );
    }
}

// Add the scheduled event hook to send emails
add_action( 'order_reminder_email', 'send_order_reminder_email', 10, 3 );

function send_order_reminder_email( $customer_email, $subject, $message ) {
    // Send the email
    wp_mail( $customer_email, $subject, $message );
}



?>
