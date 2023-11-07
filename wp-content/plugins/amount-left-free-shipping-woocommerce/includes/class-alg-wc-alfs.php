<?php
/**
 * Amount Left for Free Shipping for WooCommerce.
 *
 * @version 2.3.0
 * @since   2.3.0
 * @author  WPFactory
 */

if ( ! class_exists( 'Alg_WC_Left_To_Free_Shipping' ) ) :

	/**
	 * Main Alg_WC_Left_To_Free_Shipping Class
	 *
	 * @class   Alg_WC_Left_To_Free_Shipping
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	final class Alg_WC_Left_To_Free_Shipping {

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '2.3.0';

		/**
		 * @var   Alg_WC_Left_To_Free_Shipping The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * $file_system_path.
		 *
		 * @since 2.3.0
		 */
		protected $file_system_path;

		/**
		 * $free_version_file_system_path.
		 *
		 * @since 2.3.0
		 */
		protected $free_version_file_system_path;

		/**
		 * Main Alg_WC_Left_To_Free_Shipping Instance
		 *
		 * Ensures only one instance of Alg_WC_Left_To_Free_Shipping is loaded or can be loaded.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 * @return  Alg_WC_Left_To_Free_Shipping - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Initializer.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 * @access  public
		 */
		function init() {

			// Localization
			add_action( 'init', array( $this, 'localize' ) );

			// Pro
			if ( 'left-to-free-shipping-for-woocommerce-pro.php' === basename( $this->get_filesystem_path() ) ) {
				require_once( 'pro/class-alg-wc-alfs-pro.php' );
			}

			// Adds compatibility with HPOS.
			add_action( 'before_woocommerce_init', function () {
				$this->declare_compatibility_with_hpos( $this->get_filesystem_path() );
				if ( ! empty( $this->get_free_version_filesystem_path() ) ) {
					$this->declare_compatibility_with_hpos( $this->get_free_version_filesystem_path() );
				}
			} );

			// Include required files
			$this->includes();

			// Admin
			if ( is_admin() ) {
				$this->admin();
			}
		}

		/**
		 * localize.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 *
		 */
		function localize() {
			// Set up localisation
			load_plugin_textdomain( 'amount-left-free-shipping-woocommerce', false, dirname( plugin_basename( $this->get_filesystem_path() ) ) . '/langs/' );
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 */
		function includes() {
			// Functions
			require_once( 'alg-wc-alfs-functions.php' );
			// Widget
			require_once( 'class-alg-wc-widget-alfs.php' );
			// Core
			$this->core = require_once( 'class-alg-wc-alfs-core.php' );
		}

		/**
		 * admin.
		 *
		 * @version 2.3.0
		 * @since   1.3.0
		 */
		function admin() {
			// Action links
			add_filter( 'plugin_action_links_' . plugin_basename( $this->get_filesystem_path() ), array( $this, 'action_links' ) );
			// Settings
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			// Version update
			if ( get_option( 'alg_wc_left_to_free_shipping_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @version 1.4.7
		 * @since   1.0.0
		 * @param   mixed $links
		 * @return  array
		 */
		function action_links( $links ) {
			$custom_links = array();
			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_left_to_free_shipping' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
			if ( 'left-to-free-shipping-for-woocommerce.php' === basename( $this->get_filesystem_path() ) ) {
				$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/amount-left-free-shipping-woocommerce/">' .
				                  __( 'Go Pro', 'amount-left-free-shipping-woocommerce' ) . '</a>';
			}
			return array_merge( $custom_links, $links );
		}

		/**
		 * Add Amount Left for Free Shipping settings tab to WooCommerce settings.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 */
		function add_woocommerce_settings_tab( $settings ) {
			$settings[] = require_once( 'settings/class-alg-wc-settings-alfs.php' );
			return $settings;
		}

		/**
		 * version_updated.
		 *
		 * @version 1.3.0
		 * @since   1.2.0
		 */
		function version_updated() {
			// Update version
			update_option( 'alg_wc_left_to_free_shipping_version', $this->version );
		}

		/**
		 * Get the plugin url.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 * @return  string
		 */
		function plugin_url() {
			return untrailingslashit( plugin_dir_url( $this->get_filesystem_path() ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 2.3.0
		 * @since   1.0.0
		 * @return  string
		 */
		function plugin_path() {
			return untrailingslashit( plugin_dir_path( $this->get_filesystem_path() ) );
		}

		/**
		 * get_filesystem_path.
		 *
		 * @version 3.0.3
		 * @since   2.4.3
		 *
		 * @return string
		 */
		function get_filesystem_path() {
			return $this->file_system_path;
		}

		/**
		 * set_filesystem_path.
		 *
		 * @version 3.0.3
		 * @since   3.0.3
		 *
		 * @param   mixed  $file_system_path
		 */
		public function set_filesystem_path( $file_system_path ) {
			$this->file_system_path = $file_system_path;
		}

		/**
		 * get_free_version_filesystem_path.
		 *
		 * @version 3.0.3
		 * @since   3.0.3
		 *
		 * @return mixed
		 */
		public function get_free_version_filesystem_path() {
			return $this->free_version_file_system_path;
		}

		/**
		 * set_free_version_filesystem_path.
		 *
		 * @version 3.0.3
		 * @since   3.0.3
		 *
		 * @param   mixed  $free_version_file_system_path
		 */
		public function set_free_version_filesystem_path( $free_version_file_system_path ) {
			$this->free_version_file_system_path = $free_version_file_system_path;
		}

		/**
		 * Declare compatibility with custom order tables for WooCommerce.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 *
		 * @param $filesystem_path
		 *
		 * @return void
		 * @link    https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
		 *
		 */
		function declare_compatibility_with_hpos( $filesystem_path ) {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $filesystem_path, true );
			}
		}

	}

endif;