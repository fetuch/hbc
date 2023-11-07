<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Section Settings
 *
 * @version 2.0.5
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Section' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.5
	 * @since   1.0.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_alg_wc_left_to_free_shipping',                   array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_wc_left_to_free_shipping' . '_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_placeholders_desc.
	 *
	 * @version 1.6.0
	 * @since   1.4.8
	 * @todo    [maybe] add to placeholders *info*: "raw" amounts: `%amount_left_for_free_shipping_raw%`, `%free_shipping_min_amount_raw%` and `%current_cart_total_raw%`.
	 */
	function get_placeholders_desc() {
		return sprintf( __( 'Available placeholders: %s.', 'amount-left-free-shipping-woocommerce' ), '<code>' . implode( '</code>, <code>', array(
				'%amount_left_for_free_shipping%',
				'%free_shipping_min_amount%',
				'%current_cart_total%',
			) ) . '</code>' );
	}

}

endif;
