<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Cart Section Settings.
 *
 * @version 2.1.5
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Cart' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Cart extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'cart';
		$this->desc = __( 'Cart', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.1.5
	 * @since   1.6.0
	 * @todo    [maybe] "notice" as "position" (same in "Checkout" section)
	 * @todo    [maybe] multiple positions (same in "Mini-cart" and "Checkout" sections)
	 */
	function get_settings() {
		$cart_options = array(
			array(
				'title'    => __( 'Cart Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_cart_options',
				'desc'     => __( 'Outputted on the cart page.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_cart',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'           => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'            => $this->get_placeholders_desc(),
				'desc_tip'        => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'              => 'alg_wc_left_to_free_shipping_info_content_cart',
				'default'         => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'            => 'textarea',
				'css'             => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_cart',
				'default'  => 'woocommerce_after_cart_totals',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'woocommerce_before_cart'                    => __( 'Before cart', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_before_cart_table'              => __( 'Before cart table', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_before_cart_contents'           => __( 'Before cart contents', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_contents'                  => __( 'Cart contents', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_coupon'                    => __( 'Cart coupon', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_actions'                   => __( 'Cart actions', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_cart_contents'            => __( 'After cart contents', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_cart_table'               => __( 'After cart table', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_collaterals'               => __( 'Cart collaterals', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_cart'                     => __( 'After cart', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_before_cart_totals'             => __( 'Before cart totals', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_totals_before_shipping'    => __( 'Cart totals: Before shipping (in table)', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_totals_after_shipping'     => __( 'Cart totals: After shipping (in table)', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_totals_before_order_total' => __( 'Cart totals: Before order total (in table)', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_totals_after_order_total'  => __( 'Cart totals: After order total (in table)', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_proceed_to_checkout'            => __( 'Proceed to checkout', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_cart_totals'              => __( 'After cart totals', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_before_shipping_calculator'     => __( 'Before shipping calculator', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_shipping_calculator'      => __( 'After shipping calculator', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_cart_is_empty'                  => __( 'If cart is empty', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Position order (priority)', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Here you can move the info inside the Position selected above.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_priority_cart',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_cart_options',
			),
		);

		$wrapper_options = array(
			array(
				'title' => __( 'Wrapper options', 'amount-left-free-shipping-woocommerce' ),
				'type'  => 'title',
				'id'    => 'alg_wc_left_to_free_shipping_cart_wrapper_options',
				'desc'  => __( 'Sometimes, depending on the position used the content should be wrapped.', 'amount-left-free-shipping-woocommerce' ) . ' ' . __( 'E.g., If it\'s being displayed inside a table it will need HTML row tags.', 'amount-left-free-shipping-woocommerce' ) . '<br />' .
				           __( 'Here you can setup how the wrapping will take place.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Wrap method', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'select',
				'id'       => 'alg_wc_left_to_free_shipping_cart_wrap_method',
				'desc_tip' => sprintf( __( '%s will wrap the content automatically, depending on the position.', 'amount-left-free-shipping-woocommerce' ), __( 'Smart', 'amount-left-free-shipping-woocommerce' ) ) . '<br />' .
				              sprintf( __( '%s will not wrap the content.', 'amount-left-free-shipping-woocommerce' ), __( 'Ignore', 'amount-left-free-shipping-woocommerce' ) ) . '<br />' .
				              sprintf( __( '%s will wrap the content in any situation.', 'amount-left-free-shipping-woocommerce' ), __( 'Force', 'amount-left-free-shipping-woocommerce' ) ),
				'class'    => 'chosen_select',
				'default'  => 'ignore',
				'options'  => array(
					'smart'  => __( 'Smart', 'amount-left-free-shipping-woocommerce' ),
					'ignore' => __( 'Ignore', 'amount-left-free-shipping-woocommerce' ),
					'force'  => __( 'Force', 'amount-left-free-shipping-woocommerce' ),
				)
			),
			array(
				'title'           => __( 'Wrap template', 'amount-left-free-shipping-woocommerce' ),
				'type'            => 'text',
				'desc'            => sprintf( __( 'Probably %s should suit well for positions using tables.', 'amount-left-free-shipping-woocommerce' ), '<code>' . htmlentities( '<tr><th></th><td>' ) . '{content}' . htmlentities( '</td></tr>' ) . '</code>' ),
				'id'              => 'alg_wc_left_to_free_shipping_cart_wrap_template',
				'default'         => '<tr><th></th><td>{content}</td></tr>',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'type' => 'sectionend',
				'id'   => 'alg_wc_left_to_free_shipping_cart_wrapper_options',
			),
		);

		$style = array(
			array(
				'title'    => __( 'Custom style', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_cart_style',
			),
			array(
				'title'    => __( 'Custom style', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Customize the style', 'amount-left-free-shipping-woocommerce' ),
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_left_to_free_shipping_cart_custom_style_enabled',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Text color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_cart_custom_color',
				'default'  => '#000000',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Font size', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Font size in pixels.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_cart_font_size',
				'default'  => '16',
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_cart_style',
			),
		);

		return array_merge( $cart_options, $wrapper_options, $style );


	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Cart();
