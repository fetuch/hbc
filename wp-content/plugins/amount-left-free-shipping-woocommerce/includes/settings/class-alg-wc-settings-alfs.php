<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Settings
 *
 * @version 2.1.5
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_Left_To_Free_Shipping' ) ) :

class Alg_WC_Settings_Left_To_Free_Shipping extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.1.5
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_left_to_free_shipping';
		$this->label = __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' );
		parent::__construct();
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'maybe_unsanitize_option' ), PHP_INT_MAX, 3 );
		// Sections
		require_once( 'class-alg-wc-alfs-settings-section.php' );
		require_once( 'class-alg-wc-alfs-settings-general.php' );
		require_once( 'class-alg-wc-alfs-settings-cart.php' );
		require_once( 'class-alg-wc-alfs-settings-mini-cart.php' );
		require_once( 'class-alg-wc-alfs-settings-checkout.php' );
		require_once( 'class-alg-wc-alfs-settings-store-notice.php' );
		require_once( 'class-alg-wc-alfs-settings-add-to-cart.php' );
		require_once( 'class-alg-wc-alfs-settings-manual-min-amount.php' );
		require_once( 'class-alg-wc-alfs-settings-advanced.php' );
		// Create notice about pro
		add_action( 'admin_init', array( $this, 'add_promoting_notice' ) );
	}

	/**
	 * add_promoting_notice.
	 *
	 * @version 2.0.6
	 * @since   2.0.5
	 */
	function add_promoting_notice() {
		$promoting_notice = wpfactory_promoting_notice();
		$promoting_notice->set_args( array(
			'url_requirements'              => array(
				'page_filename' => 'admin.php',
				'params'        => array( 'page' => 'wc-settings', 'tab' => $this->id ),
			),
			'enable'                        => true === apply_filters( 'alg_wc_left_to_free_shipping_settings', true ),
			'optimize_plugin_icon_contrast' => true,
			'template_variables'            => array(
				'%pro_version_url%'    => 'https://wpfactory.com/item/amount-left-free-shipping-woocommerce/',
				'%plugin_icon_url%'    => 'https://ps.w.org/amount-left-free-shipping-woocommerce/assets/icon-128x128.jpg',
				'%pro_version_title%'  => __( 'Amount Left for Free Shipping for WooCommerce Pro', 'amount-left-free-shipping-woocommerce' ),
				'%main_text%'          => __( 'Disabled options can be unlocked using <a href="%pro_version_url%" target="_blank"><strong>%pro_version_title%</strong></a>', 'amount-left-free-shipping-woocommerce' ),
				'%btn_call_to_action%' => __( 'Upgrade to Pro version', 'amount-left-free-shipping-woocommerce' ),
			),
		) );
		$promoting_notice->init();
	}

	/**
	 * maybe_unsanitize_option.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 * @todo    [next] find better solution
	 */
	function maybe_unsanitize_option( $value, $option, $raw_value ) {
		return ( ! empty( $option['alg_wc_alfs_raw'] ) ? $raw_value : $value );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.7
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'amount-left-free-shipping-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'amount-left-free-shipping-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'amount-left-free-shipping-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'amount-left-free-shipping-woocommerce' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.4.7
	 * @since   1.0.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			if ( method_exists( 'WC_Admin_Settings', 'add_message' ) ) {
				WC_Admin_Settings::add_message( __( 'Your settings have been reset.', 'amount-left-free-shipping-woocommerce' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
			}
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'amount-left-free-shipping-woocommerce' ) . '</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Settings_Left_To_Free_Shipping();
