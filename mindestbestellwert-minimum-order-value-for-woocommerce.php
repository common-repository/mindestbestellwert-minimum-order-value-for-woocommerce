<?php

/*
Plugin Name: Mindestbestellwert - Minimum Order Value for Woocommerce
Description: Simple plugin extending WooCommerce to set a minimum order value. Shows a notification in cart view and an error in checkout when the current total order value is too low. Can be configured under »WC Settings / Checkout«.
Version: 1.2
Plugin URI: http://brandbeschleuniger.net/wordpress-plugins/mindestbestellwert-woocommerce/
Author: Brandbeschleuniger
Author URI: http://brandbeschleuniger.net/
Text Domain: mindestbestellwert-minimum-order-value-for-woocommerce
*/




/**
 * SECURITY
 * see: <https://codex.wordpress.org/Writing_a_Plugin>)
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );




/**
 * i18n
 */
function minimum_order_value_load_plugin_textdomain() {
	load_plugin_textdomain( 'mindestbestellwert-minimum-order-value-for-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'minimum_order_value_load_plugin_textdomain' );




/**
 * PLUGIN LOGIC
 */
add_action( 'woocommerce_checkout_process', 'minimum_order_value_check_current_value' );
add_action( 'woocommerce_after_cart_contents', 'minimum_order_value_check_current_value' );

add_filter( 'woocommerce_get_settings_checkout', 'minimum_order_value_add_settings' );


/**
 * minimum_order_value_check_current_value
 * Called in cart view and checkout view to verify that the total order value meets the minimum.
 * NOTE: we check the subtotal _before_ discounts (coupons) are applied.
 * See <https://plugins.trac.wordpress.org/browser/woocommerce/trunk/includes/class-wc-cart.php> for property $default_totals
 */
function minimum_order_value_check_current_value( ) {
	$current_total = WC()->cart->get_subtotal();
	$minimum_order_value = get_option( 'bb_minorderval_value' );
	$msg_cart = get_option( 'bb_minorderval_msg_cart' );
	$msg_checkout = get_option( 'bb_minorderval_msg_checkout' );

	if ( ! is_numeric( $minimum_order_value ) ) {
		return;
	}

	if ( $current_total < $minimum_order_value ) {
		if ( is_cart( ) && !empty( $msg_cart ) ) {
			$error_message = sprintf( $msg_cart, wc_price( $minimum_order_value ), wc_price( $current_total ) );
			wc_print_notice( $error_message, 'error' );
		}
		elseif ( ! is_cart( ) && ! empty( $msg_checkout ) ) {
			$error_message = sprintf( $msg_checkout, wc_price( $minimum_order_value ), wc_price( $current_total ) );
			wc_add_notice( $error_message, 'error' );
		}
	}
}


/**
 * minimum_order_value_add_settings
 */
function minimum_order_value_add_settings( $settings ) {
	$updated_settings = array( );
	$done = false;

	$plugin_settings = array(
		array(
			'title'     => __( 'Minimum Order Value', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'type'      => 'title',
			'id'        => 'bb_minorderval',
			'desc' => __( 'The following options are used to configure the plugin “Mindestbestellwert - Minimum Order Value for Woocommerce” (by brandbeschleuniger.net).', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
		),
		array(
			'title'     => __( 'Minimum Order Value', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'default'   => 20.00,
			'desc'  => __( 'Please use a decimal point, not a comma.', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'id'       => 'bb_minorderval_value',
			'type'     => 'text',
			'css'      => 'width:4em;',
			'desc_tip'     => __( 'The minimum order value for the current WooCommerce shop, given in your shop’s currency.', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
		),
		array(
			'title'     => __( 'Notification Cart', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'default'  => 'Der Mindestbestellwert beträgt %s pro Bestellung. Der aktuelle Bestellwert beträgt %s.',
			'desc' => __( 'Notification for cart view', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'id'       => 'bb_minorderval_msg_cart',
			'type'     => 'textarea',
			'css' => 'width:100%;height:4em;',
			'desc_tip'     => __( 'Message shown in cart view when current total order value is too low (note: the %s are placeholders – they get replaced automatically, the first one with the defined minimum order value and the second one with the current total order value of the user’s cart).', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
		),
		array(
			'title'     => __( 'Notification Checkout', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'default'  => 'Der Mindestbestellwert beträgt %s pro Bestellung. Der aktuelle Bestellwert beträgt %s.',
			'desc' => __( 'Notification for checkout view', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
			'id'       => 'bb_minorderval_msg_checkout',
			'type'     => 'textarea',
			'css' => 'width:100%;height:4em;',
			'desc_tip'     => __( 'Message shown in checkout view when current total order value is too low (see the note above).', 'mindestbestellwert-minimum-order-value-for-woocommerce' ),
		),
		array(
			'type'  => 'sectionend',
			'id'    => 'bb_minorderval',
		),
	);

	foreach ( $settings as $setting ) {
		$updated_settings[] = $setting;
		if ( isset( $setting['id'] ) && 'payment_gateways_options' === $setting['id'] && 'sectionend' === $setting['type'] && ! $done ) {
			$updated_settings = array_merge( $updated_settings, $plugin_settings );
			$done = true;
		}
	}
	return $updated_settings;
}
