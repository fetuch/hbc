<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Checkout Section Settings
 *
 * @version 2.1.5
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Checkout' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Checkout extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'checkout';
		$this->desc = __( 'Checkout', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.1.5
	 * @since   1.6.0
	 */
	function get_settings() {
		$checkout_options = array(
			array(
				'title'    => __( 'Checkout Options', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_options',
				'desc'     => __( 'Outputted on the checkout page.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_checkout',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'           => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'            => $this->get_placeholders_desc(),
				'desc_tip'        => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'              => 'alg_wc_left_to_free_shipping_info_content_checkout',
				'default'         => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'            => 'textarea',
				'css'             => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_checkout',
				'default'  => 'woocommerce_checkout_after_order_review',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'woocommerce_before_checkout_form'              => __( 'Before checkout form', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_before_customer_details'  => __( 'Before customer details', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_billing'                  => __( 'Billing', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_shipping'                 => __( 'Shipping', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_after_customer_details'   => __( 'After customer details', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_before_order_review'      => __( 'Before order review', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_order_review'             => __( 'Order review', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_before_shipping'      => __( 'Order review: Before shipping (in table)', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_after_shipping'       => __( 'Order review: After shipping (in table)', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_before_submit'        => __( 'Order review: Payment: Before submit button', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_review_order_after_submit'         => __( 'Order review: Payment: After submit button', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_checkout_after_order_review'       => __( 'After order review', 'amount-left-free-shipping-woocommerce' ),
					'woocommerce_after_checkout_form'               => __( 'After checkout form', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Position order (priority)', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Here you can move the info inside the Position selected above.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_priority_checkout',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_options',
			),
		);

		$wrapper_options = array(
			array(
				'title' => __( 'Wrapper options', 'amount-left-free-shipping-woocommerce' ),
				'type'  => 'title',
				'id'    => 'alg_wc_left_to_free_shipping_checkout_wrapper_options',
				'desc'  => __( 'Sometimes, depending on the position used the content should be wrapped.', 'amount-left-free-shipping-woocommerce' ) . ' '.__( 'E.g., If it\'s being displayed inside a table it will need HTML row tags.', 'amount-left-free-shipping-woocommerce' ) . '<br />' .
				           __( 'Here you can setup how the wrapping will take place.', 'amount-left-free-shipping-woocommerce' ),
			),
			array(
				'title'    => __( 'Wrap method', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'select',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_wrap_method',
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
				'id'              => 'alg_wc_left_to_free_shipping_checkout_wrap_template',
				'default'         => '<tr><th></th><td>{content}</td></tr>',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_wrapper_options',
			),
		);

		$style = array(
			array(
				'title'    => __( 'Custom style', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_style',
			),
			array(
				'title'    => __( 'Custom style', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Customize the style', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_custom_style_enabled',
				'default'  => 'no',
			),
			array(
				'title'    => __( 'Text color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_checkout_custom_color',
				'default'  => '#000000',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Font size', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Font size in pixels.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_checkout_font_size',
				'default'  => '16',
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_checkout_style',
			),
		);

		return array_merge( $checkout_options, $wrapper_options, $style );
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Checkout();
