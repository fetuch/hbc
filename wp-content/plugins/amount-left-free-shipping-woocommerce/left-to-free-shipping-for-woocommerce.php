<?php
/*
Plugin Name: Free Shipping Over Amount Bar for WooCommerce
Plugin URI: https://wpfactory.com/item/amount-left-free-shipping-woocommerce/
Description: Show your customers the amount left for free shipping in WooCommerce.
Version: 2.3.1
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: amount-left-free-shipping-woocommerce
Domain Path: /langs
Copyright: © 2023 WPFactory
WC tested up to: 8.1
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'alg_wc_left_to_free_shipping_is_plugin_active' ) ) {
	/**
	 * alg_wc_left_to_free_shipping_is_plugin_active.
	 *
	 * @version 2.0.7
	 * @since   2.0.7
	 */
	function alg_wc_left_to_free_shipping_is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', ( array ) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, ( array ) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}
}

// Check for active plugins
if (
	! alg_wc_left_to_free_shipping_is_plugin_active( 'woocommerce/woocommerce.php' ) ||
	( 'left-to-free-shipping-for-woocommerce.php' === basename( __FILE__ ) && alg_wc_left_to_free_shipping_is_plugin_active( 'left-to-free-shipping-for-woocommerce-pro/left-to-free-shipping-for-woocommerce-pro.php' ) )
) {
	if ( function_exists( 'alg_wc_left_to_free_shipping' ) ) {
		$alfs = alg_wc_left_to_free_shipping();
		if ( method_exists( $alfs, 'set_free_version_filesystem_path' ) ) {
			$alfs->set_free_version_filesystem_path( __FILE__ );
		}
	}
	return;
}

// Composer autoload
if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping' ) ) :
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
endif;

require_once( 'includes/class-alg-wc-alfs.php' );


if ( ! function_exists( 'alg_wc_left_to_free_shipping' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Left_To_Free_Shipping to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Left_To_Free_Shipping
	 */
	function alg_wc_left_to_free_shipping() {
		return Alg_WC_Left_To_Free_Shipping::instance();
	}
}

add_action( 'plugins_loaded', function () {
	$alfs = alg_wc_left_to_free_shipping();
	$alfs->set_filesystem_path( __FILE__ );
	$alfs->init();
} );
