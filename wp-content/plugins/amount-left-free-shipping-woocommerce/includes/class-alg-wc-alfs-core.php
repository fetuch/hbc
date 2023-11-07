<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Core Class.
 *
 * @version 2.2.7
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping_Core' ) ) :

class Alg_WC_Left_To_Free_Shipping_Core {

	/**
	 * Constructor.
	 *
	 * @version 2.2.3
	 * @since   1.0.0
	 * @todo    [maybe] move shortcodes inside `alg_wc_left_to_free_shipping_enabled` (or maybe remove `alg_wc_left_to_free_shipping_enabled` completely?)
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_enabled', 'yes' ) ) {
			// Cart
			if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_info_enabled_cart', 'no' ) ) {
				add_action(
					get_option( 'alg_wc_left_to_free_shipping_info_position_cart', 'woocommerce_after_cart_totals' ),
					array( $this, 'show_left_to_free_shipping_info_cart' ),
					get_option( 'alg_wc_left_to_free_shipping_info_priority_cart', 10 )
				);
			}
			// Shortcodes
			add_shortcode( 'alg_get_left_to_free_shipping',          array( $this, 'get_left_to_free_shipping_shortcode' ) ); // deprecated
			add_shortcode( 'alg_wc_left_to_free_shipping',           array( $this, 'get_left_to_free_shipping_shortcode' ) );
			add_shortcode( 'alg_wc_left_to_free_shipping_translate', array( $this, 'translate_shortcode' ) );
			// Default notice
			add_action( 'wp',                                             array( $this, 'create_default_notice' ) );
			add_action( 'woocommerce_add_to_cart_validation',             array( $this, 'clear_notices' ), 1 );
			// Core loaded
			do_action( 'alg_wc_left_to_free_shipping_core_loaded', $this );
			// Min amount extra options
			add_filter( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array( $this, 'get_user_role_available_on_min_amount' ), 9, 2 );
			add_filter( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array( $this, 'get_currency_available_on_min_amount' ), 10, 2 );
			add_filter( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array( $this, 'get_shipping_zone_available_on_min_amount' ), 11, 2 );
			// Manual amount - Minus 1
			add_filter( 'alg_wc_get_left_to_free_shipping_validation', array( $this, 'handle_manual_min_amount_special_value_minus_one' ), 10, 2 );
			// Hide shipping methods
			add_filter( 'woocommerce_package_rates', array( $this, 'hide_shipping_methods' ), 10, 1 );
			// Hide by disabled shipping methods.
			add_filter( 'alg_wc_get_left_to_free_shipping_validation', array( $this, 'hide_notification_by_disabled_shipping_method' ) );
		}
	}

	/**
	 * hide_notification_by_disabled_shipping_method.
	 *
	 * @version 2.2.3
	 * @since   2.2.3
	 *
	 * @param $validation
	 *
	 * @return bool
	 */
	function hide_notification_by_disabled_shipping_method( $validation ) {
		if (
			! empty( $shipping_methods = get_option( 'alg_wc_left_to_free_shipping_hide_by_disabled_shipping_method', array( 'free_shipping' ) ) ) &&
			! empty( $shipping_packages = WC()->cart->get_shipping_packages() ) &&
			! empty( $shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) ) ) &&
			! empty( $zone_id = $shipping_zone->get_id() ) &&
			! empty( $delivery_zones = WC_Shipping_Zones::get_zones() ) &&
			isset( $delivery_zones[ $zone_id ] ) &&
			isset( $delivery_zones[ $zone_id ]['shipping_methods'] ) &&
			! empty( $methods_from_zone = $delivery_zones[ $zone_id ]['shipping_methods'] ) &&
			! empty( $disabled_methods_from_zone = wp_list_pluck( wp_list_filter( $methods_from_zone, array( 'enabled' => 'no' ) ), 'id' ) )
		) {
			$operator   = get_option( 'alg_wc_left_to_free_shipping_hide_by_disabled_shipping_method_operator', 'or' );
			$intersect  = array_intersect( $shipping_methods, $disabled_methods_from_zone );
			$validation = ! ( count( $intersect ) > 0 );
			if ( 'and' === $operator ) {
				$validation = ! ( count( $intersect ) === count( $shipping_methods ) );
			}
		}
		return $validation;
	}

	/**
	 * Hide shipping methods when free shipping is available.
	 *
	 * @version 2.2.6
	 * @since   2.1.7
	 *
	 * @param $packages
	 *
	 * @return mixed
	 */
	function hide_shipping_methods( $packages ) {
		if (
			'yes' === get_option( 'alg_wc_left_to_free_shipping_hide_shipping_methods', 'no' ) &&
			'free-shipping-reached' === $this->get_left_to_free_shipping( array(
				'min_cart_amount'    => 0,
				'is_ajax_response'   => false,
				'free_delivery_text' => 'free-shipping-reached',
			) ) &&
			! empty( $free_shipping_package = array_filter( $packages, function ( WC_Shipping_Rate $shipping_rate ) {
				return get_option( 'alg_wc_left_to_free_shipping_hide_shipping_methods_free_shipping_method', 'free_shipping' ) === $shipping_rate->get_method_id();
			} ) )
		) {
			$packages = $free_shipping_package;
		}
		return $packages;
	}

	/**
	 * handle_manual_min_amount_special_value_minus_one.
	 *
	 * @version 2.1.1
	 * @since   2.1.1
	 *
	 * @param $validation
	 * @param $args
	 *
	 * @return bool
	 */
	function handle_manual_min_amount_special_value_minus_one( $validation, $args ) {
		if ( 'hide_amount_left' === get_option( 'alg_wc_left_to_free_shipping_sv_minusone', 'alg_wc_left_to_free_shipping_sv_minusone' ) ) {
			$min_free_shipping_amount = (float) $args['min_free_shipping_amount'];
			if ( - 1 == $min_free_shipping_amount ) {
				$validation = false;
			}
		}
		return $validation;
	}

	/**
	 * clear_notices.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 *
	 * @param $passed
	 *
	 * @return boolean
	 */
	function clear_notices( $passed ) {
		if ( $passed && 'yes' === get_option( 'alg_wc_left_to_free_shipping_clear_notices', 'no' ) ) {
			wc_clear_notices();
		}
		return $passed;
	}

	/**
	 * can_display_default_notice_by_function.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 *
	 * @param $functions
	 *
	 * @return bool
	 */
	function can_display_default_notice_by_function( $functions ) {
		foreach ( $functions as $function ) {
			if ( function_exists( $function ) && call_user_func( $function ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * create_default_notice.
	 *
	 * @version 2.2.6
	 * @since   1.9.6
	 */
	function create_default_notice() {
		if (
			'no' == get_option( 'alg_wc_left_to_free_shipping_default_notice_enabled', 'no' )
			|| is_admin()
			|| wp_doing_ajax()
			||
			(
				! empty( $functions = get_option( 'alg_wc_left_to_free_shipping_default_notice_display_by_function', '' ) )
				&& ! $this->can_display_default_notice_by_function( $functions )
			)
		) {
			return;
		}
		$message = $this->get_left_to_free_shipping( array(
			'content'          => get_option( 'alg_wc_left_to_free_shipping_default_notice_content', $this->get_default_content() ),
			'location'         => 'wc_notice',
			'is_ajax_response' => true
		) );
		wc_add_notice( $message, get_option( 'alg_wc_left_to_free_shipping_default_notice_type', 'notice' ) );
	}

	/**
	 * add_to_log.
	 *
	 * @version 1.7.0
	 * @since   1.7.0
	 */
	function add_to_log( $message ) {
		if ( function_exists( 'wc_get_logger' ) && ( $log = wc_get_logger() ) ) {
			$log->log( 'info', $message, array( 'source' => 'alg-wc-alfs' ) );
		}
	}

	/*
	 * get_left_to_free_shipping_shortcode.
	 *
	 * @version 2.2.1
	 * @since   1.0.0
	 */
	function get_left_to_free_shipping_shortcode( $atts, $content ) {
		if ( ! alg_wc_left_to_free_shipping_is_admin() ) {
			$atts = shortcode_atts( array(
				'content'                  => '',
				'template'                 => '{content}',
				'multiply_by'              => 1,
				'min_free_shipping_amount' => 0,
				'free_delivery_text'       => false,
				'min_cart_amount'          => get_option( 'alg_wc_left_to_free_shipping_min_cart_amount', 0 )
			), $atts, 'alg_wc_left_to_free_shipping' );
			return $this->get_left_to_free_shipping( $atts );
		} else {
			return '';
		}
	}

	/**
	 * translate_shortcode.
	 *
	 * @version 1.8.0
	 * @since   1.3.0
	 */
	function translate_shortcode( $atts, $content = '' ) {
		// E.g.: `[alg_wc_left_to_free_shipping_translate lang="DE" lang_text="%amount_left_for_free_shipping% für kostenlosen Versand" not_lang_text="%amount_left_for_free_shipping% left for free shipping"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_left_to_free_shipping_translate lang="DE"]%amount_left_for_free_shipping% für kostenlosen Versand[/alg_wc_left_to_free_shipping_translate][alg_wc_left_to_free_shipping_translate not_lang="DE"]%amount_left_for_free_shipping% left for free shipping[/alg_wc_left_to_free_shipping_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * show_left_to_free_shipping_info_cart.
	 *
	 * @version 2.1.5
	 * @since   1.0.0
	 */
	function show_left_to_free_shipping_info_cart() {
		$args             = array( 'content' => get_option( 'alg_wc_left_to_free_shipping_info_content_cart', $this->get_default_content() ) );
		$args['location'] = 'cart';
		$wrap_template    = get_option( 'alg_wc_left_to_free_shipping_cart_wrap_template', '<tr><th></th><td>{content}</td></tr>' );
		if (
			'smart' === ( $wrap_method = get_option( 'alg_wc_left_to_free_shipping_cart_wrap_method', 'ignore' ) )
			&& in_array( current_filter(), array(
				'woocommerce_cart_totals_before_shipping',
				'woocommerce_cart_totals_after_shipping',
				'woocommerce_cart_totals_before_order_total',
				'woocommerce_cart_totals_after_order_total'
			) ) ) {
			$args['template'] = $wrap_template;
		} elseif ( 'force' === $wrap_method ) {
			$args['template'] = $wrap_template;
		}
		echo $this->get_left_to_free_shipping( $args );
	}

	/*
	 * get_cart_total.
	 *
	 * @version 2.1.0
	 * @since   1.4.0
	 * @see     `WC_Shipping_Free_Shipping::is_available()` (/woocommerce/includes/shipping/free-shipping/class-wc-shipping-free-shipping.php)
	 */
	function get_cart_total() {
		if ( ! function_exists( 'WC' ) || ! isset( WC()->cart ) ) {
			return 0;
		}
		$cart_total_params   = array(
			'get_total' => array( 'raw' )
		);
		$cart_total_function = get_option( 'alg_wc_left_to_free_shipping_cart_total_method', 'get_displayed_subtotal' );
		$cart_total_params   = isset( $cart_total_params[ $cart_total_function ] ) ? $cart_total_params[ $cart_total_function ] : array();
		$total               = call_user_func_array( array( WC()->cart, $cart_total_function ), $cart_total_params );
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_include_discounts', 'yes' ) ) {
			if ( WC()->cart->display_prices_including_tax() ) {
				$total = round( $total - ( WC()->cart->get_discount_total() + WC()->cart->get_discount_tax() ), wc_get_price_decimals() );
			} else {
				$total = round( $total - WC()->cart->get_discount_total(), wc_get_price_decimals() );
			}
		}
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_exclude_shipping', 'no' ) ) {
			$shipping_taxes = 'yes' === get_option( 'alg_wc_left_to_free_shipping_exclude_shipping_taxes', 'yes' ) ? WC()->cart->get_shipping_tax() : 0;
			$total = round( $total - ( WC()->cart->get_shipping_total() + $shipping_taxes ), wc_get_price_decimals() );
		}
		return apply_filters( 'alg_wc_left_to_free_shipping_cart_total', $total );
	}

	/**
	 * is_cart_virtual.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	function is_cart_virtual() {
		if ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_check_virtual', 'no' ) ) {
			if ( function_exists( 'WC' ) && isset( WC()->cart ) && ! WC()->cart->is_empty() ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					if ( $cart_item['data'] instanceof WC_Product ) {
						if ( ! $cart_item['data']->is_virtual() ) {
							return false;
						}
					}
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * is_equal.
	 *
	 * @version 1.8.0
	 * @since   1.4.2
	 * @todo    [maybe] better epsilon value
	 */
	function is_equal( $float1, $float2 ) {
		$epsilon = ( defined( 'PHP_FLOAT_EPSILON' ) ? PHP_FLOAT_EPSILON : 0.000000001 );
		return ( abs( $float1 - $float2 ) < $epsilon );
	}

	/**
	 * get_default_content.
	 *
	 * @version 1.8.0
	 * @since   1.6.0
	 */
	function get_default_content() {
		return sprintf( __( '%s left for free shipping', 'amount-left-free-shipping-woocommerce' ), '%amount_left_for_free_shipping%' );
	}

	/*
	 * get_manual_min_amount_types.
	 *
	 * @version 2.2.7
	 * @since   1.9.0
	 */
	function get_manual_min_amount_types() {
		$types = array(
			'roles'            => __( 'User roles', 'amount-left-free-shipping-woocommerce' ),
			'currencies'       => __( 'Currencies', 'amount-left-free-shipping-woocommerce' ),
			'zones'            => __( 'Shipping zones', 'amount-left-free-shipping-woocommerce' ),
			'shipping_methods' => __( 'Shipping methods', 'amount-left-free-shipping-woocommerce' ),
			'shipping_classes' => __( 'Shipping classes', 'amount-left-free-shipping-woocommerce' ),
		);

		return apply_filters( 'alg_wc_left_to_free_shipping_amount_types', $types );
	}

	/**
	 * get_user_role_available_on_min_amount.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 *
	 * @param $id_array
	 * @param $args
	 *
	 * @return array
	 */
	function get_user_role_available_on_min_amount( $id_array, $args ) {
		$types = $args['types'];
		if ( isset( $types['roles'] ) ) {
			$user          = ( function_exists( 'wp_get_current_user' ) ? wp_get_current_user() : false );
			$current_roles = ( ! empty( $user->roles ) ? $user->roles : array( 'guest' ) );
			foreach ( $current_roles as $current_role ) {
				if ( '' == $current_role ) {
					$current_role = 'guest';
				}
				if ( in_array( $current_role, $types['roles'] ) ) {
					$id_array[] = $current_role;
				}
			}
		}
		return $id_array;
	}

	/**
	 * get_currency_available_on_min_amount.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 *
	 * @param $id_array
	 * @param $args
	 *
	 * @return array
	 */
	function get_currency_available_on_min_amount( $id_array, $args ) {
		$types = $args['types'];
		if ( isset( $types['currencies'] ) ) {
			$current_currency = get_woocommerce_currency();
			if ( in_array( $current_currency, $types['currencies'] ) ) {
				$id_array[] = 'currencies:' . $current_currency;
			}
		}
		return $id_array;
	}

	/**
	 * get_shipping_zone_available_on_min_amount.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 *
	 * @param $id_array
	 * @param $args
	 *
	 * @return array
	 */
	function get_shipping_zone_available_on_min_amount( $id_array, $args ) {
		$types = $args['types'];
		if ( isset( $types['zones'] ) ) {
			if ( function_exists( 'WC' ) && ( $wc_cart = WC()->cart ) ) {
				$packages = $wc_cart->get_shipping_packages();
				foreach ( $packages as $package ) {
					$shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $package );
					$current_zone  = 'z' . $shipping_zone->get_id();
					if ( in_array( $current_zone, $types['zones'] ) ) {
						$id_array[] = 'zones:' . $current_zone;
					}
				}
			}
		}
		return $id_array;
	}

	/*
	 * get_manual_min_amount.
	 *
	 * @version 2.7.2
	 * @since   1.9.0
	 * @todo    [maybe] pre-check `function_exists( 'WC' ) && ( $wc_cart = WC()->cart )`
	 */
	function get_manual_min_amount() {
		if ( 'yes' !== get_option( 'alg_wc_left_to_free_shipping_mma_enabled', 'yes' ) ) {
			return false;
		}
		$general_manual_min_amount = get_option( 'alg_wc_left_to_free_shipping_manual_min_amount', 0 );
		// Extra options
		$amounts = get_option( 'alg_wc_left_to_free_shipping_mma_roles_val', array() );
		if ( ! empty( $amounts ) ) {
			$types = array();
			foreach ( $this->get_manual_min_amount_types() as $type => $title ) {
				$values = get_option( "alg_wc_left_to_free_shipping_mma_{$type}", array() );
				if ( ! empty( $values ) ) {
					$types[ $type ] = $values;
				}
			}
			if ( ! empty( $types ) ) {
				$id  = apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount_available_types', array(), array( 'types' => $types ) );
				$cart_shipping_classes = array();

				if(isset($id['cart_shipping_classes']) && !empty($id['cart_shipping_classes'])){
					
					$cart_shipping_classes = $id['cart_shipping_classes'];
					unset($id['cart_shipping_classes']);
					
					$shipping_priority = get_option( 'alg_wc_left_to_free_shipping_multiple_shipping_priority', 'highest' );
					$tobe_sorted = array();
					foreach($cart_shipping_classes as $cls){
						$new_id = $id;
						$new_id[] = $cls;
						$_id = implode( '|', $new_id );
						$amount = isset( $amounts[ $_id ] ) ? $amounts[ $_id ] : $general_manual_min_amount;
						$tobe_sorted[$_id] = $amount;
						
					}
					
					if($shipping_priority == 'highest'){
						arsort($tobe_sorted);
					}else{
						asort($tobe_sorted);
					}
					
					$selected_amount = reset($tobe_sorted);
					$selected_id = key($tobe_sorted);
					if ( ! empty( $selected_id ) && isset( $amounts[ $selected_id ] ) ) {
						return apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount', array( 'amount' => $selected_amount, 'is_available' => false ), array(
							'id'      => $selected_id,
							'amounts' => $amounts,
						) );
					}
					
					
				}else{

					$_id = implode( '|', $id );
					$amount = isset( $amounts[ $_id ] ) ? $amounts[ $_id ] : $general_manual_min_amount;
					if ( ! empty( $_id ) && isset( $amounts[ $_id ] ) ) {
						return apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount', array( 'amount' => $amount, 'is_available' => false ), array(
							'id'      => $_id,
							'amounts' => $amounts,
						) );
					}
				}
			}
		}
		// General manual min amount
		return apply_filters( 'alg_wc_left_to_free_shipping_manual_min_amount', array( 'amount' => $general_manual_min_amount, 'is_available' => false ), array( 'amounts' => $amounts ) );
	}

	/*
	 * get_min_free_shipping_amount.
	 *
	 * @version 2.2.0
	 * @since   1.4.0
	 * @todo    [next] `either`: should check for coupon (e.g. coupon not applied: "Or a coupon";            coupon applied: "You have free delivery!")
	 * @todo    [next] `both`:   should check for coupon (e.g. coupon not applied: "You also need a coupon"; coupon applied: "You have free delivery!")
	 * @todo    [maybe] split into smaller functions
	 * @todo    [maybe] Flat rate + zero rate (both by shipping class or no class)
	 */
	function get_min_free_shipping_amount() {
		$is_available           = false;
		$get_amount_manually    = true;
		$manual_min_amount_data = $this->get_manual_min_amount();
		if (
			false === $manual_min_amount_data ||
			(
				empty( $manual_min_amount_data['amount'] ) &&
				'ignore' === get_option( 'alg_wc_left_to_free_shipping_sv_zero', 'ignore' )
			)
		) {
			$get_amount_manually = false;
		}
		if ( $get_amount_manually ) {
			return $manual_min_amount_data;
		}
		// Sets shipping country automatically, if empty.
		if (
			( empty( $shipping_country = WC()->customer->get_shipping_country() ) || 'default' === $shipping_country ) &&
			'yes' === get_option( 'alg_wc_left_to_free_shipping_set_shipping_country_automatically', 'no' ) &&
			! empty( $delivery_zones = WC_Shipping_Zones::get_zones() )
		) {
			$first_zone      = reset( $delivery_zones );
			$first_zone_code = $first_zone['zone_locations'][0]->code;
			WC()->customer->set_shipping_country( $first_zone_code );
		}
		// Automatic min amount
		$min_free_shipping_amount = 0;
		$current_wc_version       = get_option( 'woocommerce_version', null );
		$free_shipping            = version_compare( $current_wc_version, '2.6.0', '<' ) ? new WC_Shipping_Legacy_Free_Shipping() : new WC_Shipping_Free_Shipping();
		if ( $free_shipping->is_enabled() && in_array( $free_shipping->requires, array( 'min_amount', 'either', 'both' ) ) ) {
			$min_free_shipping_amount = $free_shipping->min_amount;
		}
		$do_check_for_available_free_shipping = ( 'yes' === get_option( 'alg_wc_left_to_free_shipping_check_free_shipping', 'no' ) );
		if (
			0 == $min_free_shipping_amount &&
			function_exists( 'WC' ) && ( $wc_shipping = WC()->shipping ) && ( $wc_cart = WC()->cart ) &&
			$wc_shipping->enabled &&
			( $packages = $wc_cart->get_shipping_packages() )
		) {
			$shipping_methods = $wc_shipping->load_shipping_methods( $packages[0] );
			foreach ( $shipping_methods as $shipping_method ) {
				if (
					'yes' === $shipping_method->enabled && 0 != $shipping_method->instance_id &&
					$shipping_method instanceof WC_Shipping_Free_Shipping
				) {
					if ( in_array( $shipping_method->requires, array( 'min_amount', 'either', 'both' ) ) ) {
						if ( $shipping_method->is_available( $packages[0] ) ) {
							$is_available = true;
						}
						$min_free_shipping_amount = $shipping_method->min_amount;
						if ( ! $do_check_for_available_free_shipping ) {
							break;
						}
					} elseif ( $do_check_for_available_free_shipping ) {
						$is_available             = true;
						$min_free_shipping_amount = 0;
						break;
					}
				}
			}
		}
		return array( 'amount' => $min_free_shipping_amount, 'is_available' => $is_available );
	}

	/**
	 * are_there_available_shipping_methods.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 *
	 * @return bool
	 */
	function are_there_available_shipping_methods() {
		$rates    = array();
		$packages = WC()->shipping()->get_packages();
		foreach ( $packages as $i => $package ) {
			if ( ! empty( $package['rates'] ) ) {
				$rates[] = $package['rates'];
			}
		}
		return count( $rates ) > 0 ? true : false;
	}

	/**
	 * check_cart_free_shipping.
	 *
	 * @version 1.9.6
	 * @since   1.9.6
	 *
	 * @return bool
	 */
	function check_cart_free_shipping() {
		if (
			'yes' == get_option( 'alg_wc_left_to_free_check_cart_free_shipping', 'no' )
			&& WC()->cart
			&& WC()->cart->get_shipping_total() == 0
			&& $this->are_there_available_shipping_methods()
		) {
			return true;
		}
		return false;
	}

	/*
	 * get_left_to_free_shipping.
	 *
	 * @version 2.2.3
	 * @since   1.0.0
	 * @return  string
	 * @todo    [next] `$empty_cart_text` as function optional param (similar as `$free_delivery_text`)
	 * @todo    [maybe] add `notice_type` param (i.e. `cart`, `mini-cart`, `checkout`, `store-notice` etc.)
	 */
	function get_left_to_free_shipping( $args = null ) {
		$args = wp_parse_args( $args, array(
			'content'                       => '',
			'content_wrapper'               => '', // Example: <span class="alg_wc_left_to_free_shipping">{content}</span>
			'multiply_by'                   => 1,
			'location'                      => '',
			'min_free_shipping_amount'      => 0,
			'free_delivery_text'            => false,
			'template'                      => '{content}',
			'is_ajax_response'              => false,
			'min_cart_amount'               => get_option( 'alg_wc_left_to_free_shipping_min_cart_amount', 0 )
		) );
		$args = apply_filters( 'alg_wc_get_left_to_free_shipping_args', $args );
		if ( 0 == $args['min_free_shipping_amount'] ) {
			$min_free_shipping_amount_data = $this->get_min_free_shipping_amount();
			$args['min_free_shipping_amount']      = (float) $min_free_shipping_amount_data['amount'];
		}
		$min_free_shipping_amount = (float) $args['min_free_shipping_amount'];
		if (
			apply_filters( 'alg_wc_get_left_to_free_shipping_validation', true, $args )
			&& ! $this->is_cart_virtual()
			&& (
				empty( $min_cart_amount = $args['min_cart_amount'] )
				|| ( (float) $min_cart_amount > 0 && $this->get_cart_total() > (float) $min_cart_amount )
			)
		) {
			$total = $this->get_cart_total();
			// Placeholders
			$amount_left_for_free_shipping = ( $min_free_shipping_amount - $total ) * $args['multiply_by'];
			$free_shipping_min_amount      = ( $min_free_shipping_amount )          * $args['multiply_by'];
			$current_cart_total            = ( $total )                             * $args['multiply_by'];
			$placeholders = array(
				'%amount_left_for_free_shipping%'     => wc_price( $amount_left_for_free_shipping ),
				'%free_shipping_min_amount%'          => wc_price( $free_shipping_min_amount ),
				'%current_cart_total%'                => wc_price( $current_cart_total ),
				'%amount_left_for_free_shipping_raw%' => $amount_left_for_free_shipping,
				'%free_shipping_min_amount_raw%'      => $free_shipping_min_amount,
				'%current_cart_total_raw%'            => $current_cart_total,
			);
			// Content
			if (
				$min_free_shipping_amount_data['is_available']
				|| $total > $min_free_shipping_amount
				|| $this->is_equal( $total, $min_free_shipping_amount )
				|| $this->check_cart_free_shipping()
			) {
				if ( false === $args['free_delivery_text'] ) {
					$args['free_delivery_text'] = get_option( 'alg_wc_left_to_free_shipping_info_content_reached',
						__( 'You have free delivery!', 'amount-left-free-shipping-woocommerce' ) );
				}
				$result = $args['free_delivery_text'];
			} else {
				if (
					'yes' === get_option( 'alg_wc_left_to_free_shipping_custom_empty_cart', 'no' ) &&
					function_exists( 'WC' ) && isset( WC()->cart ) && WC()->cart->is_empty()
				) {
					$result = get_option( 'alg_wc_left_to_free_shipping_info_content_empty_cart', '' );
				} else {
					if ( '' == $args['content'] ) {
						$args['content'] = $this->get_default_content();
					}
					$result = $args['content'];
				}
			}
			if ( '' === $result ) {
				return null;
			}
			// Add content wrapper.
			if (
				! empty( $args['content_wrapper'] ) &&
				'{content}' !== $args['content_wrapper'] &&
				false !== strpos( $args['content_wrapper'], '{content}' )
			) {
				$result = str_replace( '{content}', $result, $args['content_wrapper'] );
			}
			// Result.
			$result = str_replace( array_keys( $placeholders ), $placeholders, $result );
			$result = do_shortcode( $result );
			$args['original_result'] = $result;
			$result = str_replace( '{content}', $result, $args['template'] );
			// Result
			return apply_filters( 'alg_wc_get_left_to_free_shipping', $result, $args );
		}
	}

	/*
	 * get_default_ajax_events.
	 *
	 * @version 2.2.4
	 * @since   1.9.1
	 * @todo    [next] [maybe] add `wc_fragments_loaded updated_shipping_method applied_coupon removed_coupon wc_fragments_refreshed`
	 */
	function get_default_ajax_events() {
		return array(
			'updated_cart_totals',
			'added_to_cart',
			'removed_from_cart',
			'wc_fragment_refresh',
			'updated_checkout',
			'wc_cart_emptied',
			'updated_shipping_method',
			'alg_wc_alfs_wc_cart_checkout_updated_totals'
		);
	}

}

endif;

return new Alg_WC_Left_To_Free_Shipping_Core();
