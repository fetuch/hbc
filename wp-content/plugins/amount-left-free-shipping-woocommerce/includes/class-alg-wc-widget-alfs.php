<?php
/**
 * Amount Left for Free Shipping for WooCommerce - Widget.
 *
 * @version 2.2.2
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Alg_WC_Widget_Left_to_Free_Shipping' ) ) :

class Alg_WC_Widget_Left_to_Free_Shipping extends WP_Widget {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'alg_wc_widget_left_to_free_shipping',
			'description' => __( 'Amount Left for Free Shipping Widget', 'amount-left-free-shipping-woocommerce' ),
		);
		parent::__construct( 'alg_wc_widget_left_to_free_shipping', __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @version 2.2.2
	 * @since   1.0.0
	 * @param   array $args
	 * @param   array $instance
	 */
	function widget( $args, $instance ) {
		if ( ! alg_wc_left_to_free_shipping_is_admin() ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo alg_wc_left_to_free_shipping()->core->get_left_to_free_shipping( array(
				'content'  => isset( $instance['content'] ) ? $instance['content'] : '',
				'location' => 'widget'
			) );
			echo $args['after_widget'];
		}
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 * @param   array $instance The widget options
	 */
	function form( $instance ) {
		$title   = ! empty( $instance['title'] )   ? $instance['title']   : __( 'Amount Left for Free Shipping', 'amount-left-free-shipping-woocommerce' );
		$content = ! empty( $instance['content'] ) ? $instance['content'] : alg_wc_left_to_free_shipping()->core->get_default_content();
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'amount-left-free-shipping-woocommerce' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content:', 'amount-left-free-shipping-woocommerce' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" type="text" value="<?php echo esc_attr( $content ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save.
	 *
	 * @version 1.9.3
	 * @since   1.0.0
	 *
	 * @param   array $new_instance The new options
	 * @param   array $old_instance The previous options
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$allowed_html             = wp_kses_allowed_html( 'post' );
		$allowed_html['progress'] = array( 'max' => array(), 'value' => array() );
		$instance                 = array();
		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['content']      = ( ! empty( $new_instance['content'] ) ) ? wp_kses( $new_instance['content'], $allowed_html ) : '';
		return $instance;
	}
}

endif;

if ( ! function_exists( 'register_alg_wc_widget_left_to_free_shipping' ) ) {
	/**
	 * register Alg_WC_Widget_Left_to_Free_Shipping widget.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function register_alg_wc_widget_left_to_free_shipping() {
		register_widget( 'Alg_WC_Widget_Left_to_Free_Shipping' );
	}
}
add_action( 'widgets_init', 'register_alg_wc_widget_left_to_free_shipping' );
