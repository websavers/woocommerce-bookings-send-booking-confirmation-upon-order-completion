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

    //$order = wc_get_order( $order_id );

    if ( is_callable( 'WC_Booking_Data_Store::get_booking_ids_from_order_id') ) {
		//$booking_data = new WC_Booking_Data_Store();
		//$booking_ids = $booking_data->get_booking_ids_from_order_id( $order->get_id() );
        $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_id( $order_id );

        foreach ( $booking_ids as $booking_id ) {
            $booking = new WC_Booking( $booking_id );
            $product_id = $booking->get_product_id();
            $product = wc_get_product($product_id);
            $requires_admin_approval = $product->get_meta( '_wc_booking_requires_confirmation', true );
            if ( $requires_admin_approval === false ) continue; //key not found, probably not a booking
            if ( empty($requires_admin_approval) || $requires_admin_approval == 0 ){
                WC()->mailer()->emails['WC_Email_Booking_Confirmed']->trigger( $booking_id );
            }
        }
	}

}, 100, 1);
