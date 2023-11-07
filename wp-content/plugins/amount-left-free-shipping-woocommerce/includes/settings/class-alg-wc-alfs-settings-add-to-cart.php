<?php
/**
 * Amount Left for Free Shipping for WooCommerce - "Add to Cart" Section Settings
 *
 * @version 2.0.5
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Add_To_Cart' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Add_To_Cart extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'add_to_cart';
		$this->desc = __( 'Add to cart', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.5
	 * @since   1.6.0
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( '"Add to Cart" Notice Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_add_to_cart_options',
				'desc'     => __( 'Notice displayed when "Add to cart" button is clicked.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_add_to_cart',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_add_to_cart',
				'default'  => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_add_to_cart',
				'default'  => 'after',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'before' => __( 'Before the standard text', 'amount-left-free-shipping-woocommerce' ),
					'after'  => __( 'After the standard text', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Glue', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( '"Glue" is inserted between the standard "add to cart" text and "left for free shipping" text.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_glue_add_to_cart',
				'default'  => '<br>',
				'type'     => 'text',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_add_to_cart_options',
			),
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Add_To_Cart();
