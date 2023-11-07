<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Advanced Section Settings
 *
 * @version 2.1.5
 * @since   2.1.5
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_Advanced' ) ) :

	class Alg_WC_Left_To_Free_Shipping_Settings_Advanced extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.1.5
		 * @since   2.1.5
		 */
		function __construct() {
			$this->id   = 'advanced';
			$this->desc = __( 'Advanced', 'amount-left-free-shipping-woocommerce' );
			parent::__construct();
		}

		/**
		 * get_settings.
		 *
		 * @version 2.1.5
		 * @since   2.1.5
		 */
		function get_settings() {
			$advanced_settings = array(
				array(
					'title'    => __( 'Advanced options', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_advanced_options',
				),
				array(
					'title'    => __( 'Check shipping cost', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Show free shipping message if cart shipping costs are not present, i.e., when shipping total is zero', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_check_cart_free_shipping',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Check for alternative free shipping', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Check for already available alternative (i.e. not requiring minimum order amount) free shipping methods.', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'No text will be outputted in this case.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_check_free_shipping',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Virtual products', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Do not display any text if cart consists of virtual products only', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_check_virtual',
					'default'  => 'no',
					'checkboxgroup' => 'start',
					'type'     => 'checkbox',
				),
				array(
					'desc'              => __( 'Ignore virtual products in cart to reach the min amount', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'          => __( 'The free shipping method will also ignore virtual products in cart.', 'amount-left-free-shipping-woocommerce' ),
					'id'                => 'alg_wc_left_to_free_shipping_ignore_virtual_products',
					'default'           => 'no',
					'type'              => 'checkbox',
					'checkboxgroup'     => 'end',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				),
				array(
					'title'    => __( 'Clear notices', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Wipe notices when a product has been added to cart', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Enable if you have problems with duplicated messages.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_clear_notices',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_advanced_options',
				),
			);

			$ajax_settings = array(
				array(
					'title'    => __( 'AJAX Options', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Enable this section if you want the amount to be updated via AJAX, e.g. when using plugin\'s "Store notice" section, or WooCommerce "Enable AJAX add to cart buttons on archives" option, or for some positions in cart, when customer updates items quantities and the amount text is positioned outside of automatically refreshed area.', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_options',
				),
				array(
					'title'    => __( 'Enable AJAX', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Enable', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_enabled',
					'default'  => 'no',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
				),
				array(
					'title'    => __( 'Additional events', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Additional JavaScript events to update the amount text on. Leave empty if unsure.', 'amount-left-free-shipping-woocommerce' ) . '<br>' .
					              sprintf( __( 'These events are always included: %s', 'amount-left-free-shipping-woocommerce' ),
						              '<code>' . implode( ' ', alg_wc_left_to_free_shipping()->core->get_default_ajax_events() ) . '</code>' ),
					'type'     => 'text',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_events',
					'default'  => '',
					'css'      => 'width:100%;',
				),
				array(
					'title'    => __( 'Added to cart event without AJAX', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Enable', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => sprintf( __( 'Fires a custom event %s in case a product has been added to cart without AJAX.', 'amount-left-free-shipping-woocommerce' ), '<code>"alg_wc_alfs_added_to_cart"</code>' ) . '<br />' .
					              __( 'Enable it if the Store notice is not getting displayed on single product pages and if you have the "Hide" option enabled in "Store notice".', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_added_to_cart_no_ajax',
					'default'  => 'no',
				),
				array(
					'title'    => __( 'Force update', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Force update via AJAX on any page load', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Enable if you have issues with caching.', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_force_update',
					'default'  => 'no',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_ajax_options',
				),
			);

			return array_merge( $advanced_settings, $ajax_settings );
		}

	}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_Advanced();
