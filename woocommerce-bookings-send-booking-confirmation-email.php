<?php
/*
 * Plugin Name:       WooCommerce Bookings Send Booking Confirmation Email
 * Description:       Ensures the Bookign Confirmation email is sent out when admin approval isn't required
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      8.1
 * Author:            Websavers Inc.    
 * Author URI:        https://websavers.ca
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wc-bookings-availability-timetable
 */


// WC_Product_Booking_Data_Store_CPT::get_bookable_product_ids

add_action( 'admin_init', function(){

    if ( ! class_exists('WC_Bookings') ) {
        add_action( 'admin_notices', function(){
            ?>
            <div class="error notice">
                <p><?php _e( 'The WooCommerce Bookings plugin must be installed for this plugin to work.', 'wc-bookings-send-confirmation-email' ); ?></p>
            </div>
            <?php
        } );
    }

});

add_action( 'woocommerce_order_status_completed', function( $order_id ){
   // Send Booking Confirmation email to customer
   WC()->mailer()->emails['WC_Email_Customer_Invoice']->trigger($order_id);
}, 10, 1);
