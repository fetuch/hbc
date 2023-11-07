<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Store Notice Section Settings.
 *
 * @version 2.1.8
 * @since   1.6.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Store_Notice' ) ) :

class Alg_WC_Left_To_Free_Shipping_Settings_Store_Notice extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	function __construct() {
		$this->id   = 'store_notice';
		$this->desc = __( 'Store notice', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.1.8
	 * @since   1.6.0
	 */
	function get_settings() {
		return array(
			array(
				'title'    => __( 'Default WooCommerce notice', 'amount-left-free-shipping-woocommerce' ),
				'type'     => 'title',
				'desc'     => sprintf( __( 'You may want to set the %s option along with this option.', 'amount-left-free-shipping-woocommerce' ), '<strong>"General > Hide the amount left text > By cart amount"</strong>' ),
				'id'       => 'alg_wc_left_to_free_shipping_default_notice_opt',
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_default_notice_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_default_notice_content',
				'default'  => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'           => __( 'Notice type', 'amount-left-free-shipping-woocommerce' ),
				'id'              => 'alg_wc_left_to_free_shipping_default_notice_type',
				'default'         => 'notice',
				'type'            => 'select',
				'options'         => array(
					'notice'  => __( 'Notice', 'amount-left-free-shipping-woocommerce' ),
					'error'   => __( 'Error', 'amount-left-free-shipping-woocommerce' ),
					'success' => __( 'Success', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Display by function', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Display the notice on some specific condition, like only in cart or in checkout.', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Leave it empty to disable displaying the notice everywhere.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_default_notice_display_by_function',
				'default'  => '',
				'type'     => 'multiselect',
				'options'      => array(
					'is_shop'             => __( 'Is Shop', 'popup-notices-for-woocommerce' ),
					'is_product_category' => __( 'Is Product Category', 'popup-notices-for-woocommerce' ),
					'is_product_tag'      => __( 'Is Product Tag', 'popup-notices-for-woocommerce' ),
					'is_product'          => __( 'Is Product', 'popup-notices-for-woocommerce' ),
					'is_cart'             => __( 'Is Cart', 'popup-notices-for-woocommerce' ),
					'is_checkout'         => __( 'Is Checkout', 'popup-notices-for-woocommerce' ),
					'is_account_page'     => __( 'Is Account Page', 'popup-notices-for-woocommerce' ),
					'is_wc_endpoint_url'  => __( 'Is WC Endpoint URL', 'popup-notices-for-woocommerce' ),
				),
				'class'    => 'chosen_select',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_default_notice_opt',
			),
			array(
				'title'    => __( 'Site-wide notice', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Site-wide notice.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
				              sprintf( __( 'You may also want to enable %s option for this.', 'amount-left-free-shipping-woocommerce' ), '<strong>"Advanced > AJAX"</strong>' ),
				'type'     => 'title',
				'id'       => 'alg_wc_left_to_free_shipping_store_notice_options',
			),
			array(
				'title'    => __( 'Enable/Disable', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Enable section', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_enabled_store_notice',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Content', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => $this->get_placeholders_desc(),
				'desc_tip' => __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_content_store_notice',
				'default'  => alg_wc_left_to_free_shipping()->core->get_default_content(),
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px;',
				'alg_wc_alfs_raw' => true,
			),
			array(
				'title'    => __( 'Placement hook', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'The action hook used to add the notice to the DOM.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_placement_store_notice',
				'default'  => 'wp_footer',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Animation', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Create a smooth animation effect with opacity and position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_animate_store_notice',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Auto hide time', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Set to zero to show the notice permanently.', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => __( 'Time in milliseconds it will take to auto-hide the notice.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_store_notice_hide',
				'default'  => 0,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'title'    => __( 'Position', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_position_store_notice',
				'default'  => 'bottom',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'bottom' => __( 'Bottom', 'amount-left-free-shipping-woocommerce' ),
					'top'    => __( 'Top', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Background color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_back_color_store_notice',
				'default'  => '#3d9cd2',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Text color', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_text_color_store_notice',
				'default'  => '#fff',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Font size', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( 'Font size in pixels', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_font_size_store_notice',
				'default'  => '16',
				'type'     => 'number',
			),
			array(
				'title'    => __( 'Font weight', 'amount-left-free-shipping-woocommerce' ),
				'desc_tip' => __( '400 is supposed to be normal, while 700, bold.', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_font_weight_store_notice',
				'default'  => '400',
				'type'     => 'number',
			),
			array(
				'title'    => __( 'Text alignment', 'amount-left-free-shipping-woocommerce' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_text_align_store_notice',
				'default'  => 'left',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'left'    => __( 'Left', 'amount-left-free-shipping-woocommerce' ),
					'right'   => __( 'Right', 'amount-left-free-shipping-woocommerce' ),
					'center'  => __( 'Center', 'amount-left-free-shipping-woocommerce' ),
					'justify' => __( 'Justify', 'amount-left-free-shipping-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Padding', 'amount-left-free-shipping-woocommerce' ),
				'desc'     => sprintf( __( 'Look for %s if you want help configuring the padding.', 'amount-left-free-shipping-woocommerce' ), '<a href="https://www.w3schools.com/css/css_padding.asp" target="_blank">' . __( 'padding shorthand', 'amount-left-free-shipping-woocommerce' ) . '</a>' ),
				'id'       => 'alg_wc_left_to_free_shipping_info_padding_store_notice',
				'default'  => '16px 23px',
				'type'     => 'text',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_left_to_free_shipping_store_notice_options',
			),
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Store_Notice();
