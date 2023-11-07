<?php
/**
 * File that define P24_Product_Grouped class.
 *
 * @package Przelewy24
 */

defined( 'ABSPATH' ) || exit;


/**
 * The class add to parent awareness of multiple currencies.
 */
class P24_Product_Grouped extends WC_Product_Grouped implements P24_MC_Product_Common_Interface {

	use P24_Product_Trait;

	/**
	 * P24_Product_Grouped constructor.
	 *
	 * @param int $product The id of product.
	 */
	public function __construct( $product = 0 ) {
		$this->populate_internal_data();
		parent::__construct( $product );
	}

}
