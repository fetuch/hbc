<?php
/**
 * Amount Left for Free Shipping for WooCommerce - General Section Settings.
 *
 * @version 2.2.3
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Settings_General' ) ) :

	class Alg_WC_Left_To_Free_Shipping_Settings_General extends Alg_WC_Left_To_Free_Shipping_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.3.0
		 * @since   1.0.0
		 */
		function __construct() {
			$this->id   = '';
			$this->desc = __( 'General', 'amount-left-free-shipping-woocommerce' );
			parent::__construct();
		}

		/**
		 * get_user_roles_options.
		 *
		 * @version 2.0.5
		 * @since   2.0.5
		 */
		function get_user_roles_options() {
			global $wp_roles;	
			$roles = $wp_roles->roles;
			$guest = array( 'guest' => 'Guest' );
			return array_merge( wp_list_pluck( $roles, 'name' ), $guest );
		}

		/**
		 * get_settings.
		 *
		 * @version 2.2.0
		 * @since   1.0.0
		 * @todo    [next] `alg_wc_left_to_free_shipping_check_free_shipping`: default to `yes`
		 * @todo    [next] `alg_wc_left_to_free_shipping_check_virtual`: default to `yes`
		 * @todo    [next] `alg_wc_left_to_free_shipping_ajax_enabled`: better description
		 * @todo    [next] [maybe] add new sections: "AJAX", "Advanced"?
		 * @todo    [maybe] Message on free shipping reached: add checkbox (similar as it's in "Message on empty cart" option)
		 */
		function get_settings() {
			$general_opts = array(
				array(
					'title'    => __( 'General options', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_general_options',
				),
				array(
					'title'    => __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => '<strong>' . __( 'Enable plugin', 'amount-left-free-shipping-woocommerce' ) . '</strong>',
					'id'       => 'alg_wc_left_to_free_shipping_enabled',
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Shipping country', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'If empty, set shipping country automatically based on the default shipping zone', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Useful if the free message is being displayed despite the minimum amount already set on the free shipping method.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_set_shipping_country_automatically',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_general_options',
				),
			);

			$general_msg_opts = array(
				array(
					'title'    => __( 'General message options', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_general_msg_options',
				),
				array(
					'title'    => __( 'Free shipping reached', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => $this->get_placeholders_desc(),
					'desc_tip' => __( 'Outputted when min free shipping amount is reached.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					              __( 'You can use HTML and/or shortcodes here.', 'amount-left-free-shipping-woocommerce' ) . ' ' .
					              __( 'To disable - set it empty.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_info_content_reached',
					'default'  => __( 'You have free delivery!', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:100%;height:50px;',
					'alg_wc_alfs_raw' => true,
				),
				array(
					'title'    => __( 'Empty cart', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Display a custom message if the cart is empty', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'If disabled, then standard content is outputted.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_custom_empty_cart',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc_tip' => sprintf( __( 'Ignored unless "%s" checkbox is enabled.', 'amount-left-free-shipping-woocommerce' ),
						__( 'Message on empty cart', 'amount-left-free-shipping-woocommerce' ) . ' > ' . __( 'Custom message', 'amount-left-free-shipping-woocommerce' ) ),
					'desc'     => sprintf( __( 'E.g.: %s', 'amount-left-free-shipping-woocommerce' ),
							'<code>' . __( 'Free shipping on orders over %free_shipping_min_amount%.', 'amount-left-free-shipping-woocommerce' ) . '</code>' ) . '<br>' .
					              $this->get_placeholders_desc(),
					'id'       => 'alg_wc_left_to_free_shipping_info_content_empty_cart',
					'default'  => '',
					'type'     => 'textarea',
					'css'      => 'width:100%;height:50px;',
					'alg_wc_alfs_raw' => true,
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_general_msg_options',
				),
			);

			$calculation_opts = array(
				array(
					'title'    => __( 'Calculation', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_calculation',
				),
				array(
					'title'    => __( 'Cart total method', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'The method used to get the total amount from cart.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_cart_total_method',
					'default'  => 'get_displayed_subtotal',
					'type'     => 'select',
					'options'  => array(
						'get_displayed_subtotal'  => sprintf( __( 'Displayed subtotal - %s', 'amount-left-free-shipping-woocommerce' ), 'WC_Cart::get_displayed_subtotal()' ),
						'get_cart_contents_total' => sprintf( __( 'Cart contents total - %s', 'amount-left-free-shipping-woocommerce' ), 'WC_Cart::get_cart_contents_total()' ),
						'get_total' => sprintf( __( 'Cart total - %s', 'amount-left-free-shipping-woocommerce' ), 'WC_Cart::get_total( "raw" )' ),
					),
					'class'    => 'chosen_select',
				),
				array(
					'title'    => __( 'Discounts', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Exclude discounts from cart total calculation', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => sprintf( __( 'Most probably, should be enabled when %s option is set as %s', 'amount-left-free-shipping-woocommerce' ), '<strong>' . __( 'Cart total method', 'amount-left-free-shipping-woocommerce' ) . '</strong>', '<strong>' . __( 'Displayed subtotal', 'amount-left-free-shipping-woocommerce' ) . '</strong>' ),
					'id'       => 'alg_wc_left_to_free_shipping_include_discounts',
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Shipping', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Exclude shipping from cart total calculation', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => sprintf( __( 'Most probably, should be enabled when %s option it set as %s', 'amount-left-free-shipping-woocommerce' ), '<strong>' . __( 'Cart total method', 'amount-left-free-shipping-woocommerce' ) . '</strong>', '<strong>' . __( 'Cart total', 'amount-left-free-shipping-woocommerce' ) . '</strong>' ),
					'id'       => 'alg_wc_left_to_free_shipping_exclude_shipping',
					'default'  => 'no',
					'checkboxgroup' => 'start',
					'type'     => 'checkbox',
				),
				array(
					'desc'     => __( 'Also exclude shipping taxes', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Needs the above exclusion option enabled', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_exclude_shipping_taxes',
					'default'  => 'yes',
					'checkboxgroup' => 'end',
					'type'     => 'checkbox',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_calculation',
				),
			);

			$hide_other_shipping_opts = array(
				array(
					'title'    => __( 'Hide shipping methods', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Hide all the shipping methods on the frontend except the free shipping when it becomes available.', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_hide_shipping_methods_opts',
				),
				array(
					'title'    => __( 'Hide shipping methods', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Hide other shipping methods when free shipping is available', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'The free shipping will be considered available when the plugin can\'t find any amount left for free shipping.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_hide_shipping_methods',
					'type'     => 'checkbox',
					'default'  => 'no'
				),
				array(
					'title'    => __( 'Free shipping method', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_hide_shipping_methods_free_shipping_method',
					'desc'     => __( 'The only shipping method that will be visible when free shipping is reached.', 'amount-left-free-shipping-woocommerce' ),
					'default'  => 'free_shipping',
					'type'     => 'select',
					'options'  => wp_list_pluck( WC()->shipping->get_shipping_methods(), 'method_title', 'id' ),
					'class'    => 'chosen_select',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_hide_shipping_methods_opts',
				),
			);

			$hide_text_opts = array(
				array(
					'title'    => __( 'Hide the amount left text', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Hide the amount left text in some specific circumstance.', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_hide_amount_left_options',
				),
				array(
					'title'    => __( 'By cart amount', 'amount-left-free-shipping-woocommerce' ),
					'desc'     => __( 'Hides the text if cart is below a specific value.', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip' => __( 'Minimum cart amount required for displaying the amount left for free shipping.', 'amount-left-free-shipping-woocommerce' ) . '<br />'
					              . __( 'Use 0 or leave it empty to disable it.', 'amount-left-free-shipping-woocommerce' ),
					'id'       => 'alg_wc_left_to_free_shipping_min_cart_amount',
					'default'  => 0,
					'type'     => 'number',
				),
				array(
					'title'             => __( 'By shipping method', 'amount-left-free-shipping-woocommerce' ),
					'desc'              => sprintf( __( 'Hides the text if the selected shipping methods are %s.', 'amount-left-free-shipping-woocommerce' ), '<strong>' . __( 'disabled', 'amount-left-free-shipping-woocommerce' ) . '</strong>' ),
					'desc_tip'          => __( 'Leave it empty to disable.', 'amount-left-free-shipping-woocommerce' ),
					'id'                => 'alg_wc_left_to_free_shipping_hide_by_disabled_shipping_method',
					'class'             => 'chosen_select',
					'default'           => array( 'free_shipping' ),
					'options'           => wp_list_pluck( WC()->shipping->get_shipping_methods(), 'method_title', 'id' ),
					'type'              => 'multiselect',
				),
				array(
					'desc'              => sprintf( __( 'Choose if you need to have %s shipping methods disabled or %s.', 'amount-left-free-shipping-woocommerce' ), '<code>' . __( 'All', 'amount-left-free-shipping-woocommerce' ) . '</code>', '<code>' . __( 'At least one', 'amount-left-free-shipping-woocommerce' ) . '</code>' ),
					'id'                => 'alg_wc_left_to_free_shipping_hide_by_disabled_shipping_method_operator',
					'class'             => 'chosen_select',
					'default'           => 'or',
					'options'           => array(
						'or'            => __( 'At least one', 'amount-left-free-shipping-woocommerce' ),
						'and'           => __( 'All', 'amount-left-free-shipping-woocommerce' )
					),
					'type'              => 'select',
				),
				array(
					'title'             => __( 'By category', 'amount-left-free-shipping-woocommerce' ),
					'desc'              => __( 'Hides the text if a product from a specific category has been added to cart.', 'amount-left-free-shipping-woocommerce' ) . '<br />',
					'desc_tip'          => __( 'Leave it empty to disable.', 'amount-left-free-shipping-woocommerce' ),
					'id'                => 'alg_wc_left_to_free_hide_by_category',
					'class'             => 'chosen_select',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => array(),
					'options'           => wp_list_pluck( get_terms( array(
						'taxonomy'   => "product_cat",
						'hide_empty' => false,
					) ), 'name', 'term_id' ),
					'type'              => 'multiselect',
				),
				array(
					'desc'              => __( 'Check children categories', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'          => sprintf( __( 'Checks for children categories in cart so you can use just the parent categories in the %s option.', 'amount-left-free-shipping-woocommerce' ), '<strong>' . __( 'Hide by category', 'amount-left-free-shipping-woocommerce' ) . '</strong>' ),
					'id'                => 'alg_wc_left_to_free_hide_by_category_check_children',
					'default'           => 'no',
					'type'              => 'checkbox',
				),
				array(
					'title'             => __( 'By user role', 'amount-left-free-shipping-woocommerce' ),
					'desc'              => __( 'Hides the text based on user role.', 'amount-left-free-shipping-woocommerce' ),
					'desc_tip'          => __( 'Leave it empty to disable.', 'amount-left-free-shipping-woocommerce' ),
					'id'                => 'alg_wc_left_to_free_shipping_hide_by_user_role',
					'class'             => 'chosen_select',
					'custom_attributes' => apply_filters( 'alg_wc_left_to_free_shipping_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => array(),
					'options'           => $this->get_user_roles_options(),
					'type'              => 'multiselect',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_hide_amount_left_options',
				),
			);

			$info = array(
				array(
					'title'    => __( 'Info', 'amount-left-free-shipping-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_left_to_free_shipping_info',
					'desc'     => '<div style="background-color:white;color:black;padding:10px;">' .
					              '<strong>' . __( 'You can also use:', 'amount-left-free-shipping-woocommerce' ) . '</strong>' .
					              '<ol>' .
					              '<li>' .
					              '<span style="text-decoration:underline;">' . __( 'Widget', 'amount-left-free-shipping-woocommerce' ) . '</span>' . ': ' .
					              '<em>' . __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' ) . '</em>' .
					              '</li>' .
					              '<li>' .
					              '<span style="text-decoration:underline;">' . __( 'Shortcode', 'amount-left-free-shipping-woocommerce' ) . '</span>' . ': ' .
					              '<code>[alg_wc_left_to_free_shipping content="%amount_left_for_free_shipping% left for free shipping"]</code>' .
					              '</li>' .
					              '<li>' .
					              '<span style="text-decoration:underline;">' . __( 'PHP function', 'amount-left-free-shipping-woocommerce' ) . '</span>' . ': ' .
					              '<code>echo alg_wc_get_left_to_free_shipping( array( "content" => "%amount_left_for_free_shipping% left for free shipping" ) );</code>' .
					              '</li>' .
					              '</ol>' .
					              '</div>',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_left_to_free_shipping_info',
				),
			);

			return array_merge( $general_opts, $general_msg_opts, $calculation_opts, $hide_other_shipping_opts, $hide_text_opts, $info );
		}

	}

endif;

return new Alg_WC_Left_To_Free_Shipping_Settings_General();
