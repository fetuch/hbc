<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Mini-cart Section Settings
 *
 * @version 2.0.5
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Mini_Cart' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Mini_Cart extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'mini_cart';
		$this->desc = __( 'Mini-cart', 'amount-left-free-shipping-woocommerce' );
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
				'title'    => __( 'Mini Cart Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_mini_cart_options',
				'desc'     => __( 'Outputted in the mini cart widget.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_mini_cart',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_mini_cart',
				'default'  => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_mini_cart',
				'default'  => 'woocommerce_after_mini_cart',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'woocommerce_before_mini_cart'                    => __( 'Before mini cart', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_before_mini_cart_contents'           => __( 'Before mini cart contents', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_mini_cart_contents'                  => __( 'After mini cart contents', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_widget_shopping_cart_total'          => __( 'In mini cart total', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_widget_shopping_cart_before_buttons' => __( 'Before mini cart buttons', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_widget_shopping_cart_buttons'        => __( 'In mini cart buttons', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_widget_shopping_cart_after_buttons'  => __( 'After mini cart buttons', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_mini_cart'                     => __( 'After mini cart', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Position order (priority)', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Here you can move the info inside the Position selected above.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_priority_mini_cart',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_mini_cart_options',
			),
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Mini_Cart();
