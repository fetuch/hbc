<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Functions.
 *
 * @version 2.2.1
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'alg_wc_get_left_to_free_shipping' ) ) {
	/*
	 * alg_wc_get_left_to_free_shipping.
	 *
	 * @version 1.9.4
	 * @since   1.3.0
	 */
	function alg_wc_get_left_to_free_shipping( $args = null ) {
		// Handle deprecated function params
		if ( ! is_array( $args ) ) {
			$new_params        = array();
			$deprecated_params = array(
				'content',
				'multiply_by',
				'min_free_shipping_amount',
				'free_delivery_text',
				'is_ajax_response',
			);
			for ( $i = 0; $i < func_num_args(); $i ++ ) {
				if ( ! empty( $param = func_get_arg( $i ) ) && ! is_array( $param ) ) {
					wc_deprecated_argument( '"' . $deprecated_params[ $i ] . '"', '1.9.4', sprintf( __( 'Please pass it as a key of the first parameter like %s', 'amount-left-free-shipping-woocommerce' ), "<code>alg_wc_get_left_to_free_shipping( array('{$deprecated_params[$i]}' => '') )</code>" ) );
					$new_params[ $deprecated_params[ $i ] ] = $param;
				}
			}
			$args = $new_params;
		}
		// Call real function
		if ( function_exists( 'alg_wc_left_to_free_shipping' ) ) {
			return alg_wc_left_to_free_shipping()->core->get_left_to_free_shipping( $args );
		}
	}
}

if ( ! function_exists( 'alg_wc_left_to_free_shipping_is_admin' ) ) {
	/*
	 * alg_wc_left_to_free_shipping_is_admin.
	 *
	 * @version 2.2.1
	 * @since   2.2.1
	 */
	function alg_wc_left_to_free_shipping_is_admin( $args = null ) {
		return ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) );
	}
}
