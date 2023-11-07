<?php

namespace InspireLabs\WoocommerceInpost\shipping;

use InspireLabs\WoocommerceInpost\EasyPack_Helper;
use WC_Shipping_Method;
use WC_Shipping_Rate;

class Easypack_Shipping_Rates
{

    public function init()
    {


        add_action('woocommerce_after_shipping_rate', function ($method, $index) {
            /**
             * @var WC_Shipping_Rate $method
             */

			
			$fs_method_name = null;
			
			if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                
                if ( strpos( $method->get_method_id(), 'flexible_shipping_single') !== false ) {
                    $fs_method_name = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $method->get_instance_id() );
                }
            }
			
			if ( 0 === strpos( $method->get_method_id(), 'easypack_' )
				|| ( isset( $fs_method_name ) && 0 === strpos( $fs_method_name, 'easypack_') ) ) {

				$meta = $method->get_meta_data();
				if ( is_array( $meta ) && isset( $meta['logo'] ) ) {
					$custom_logo = $meta['logo'];
				}

				if ( empty( $custom_logo ) ) {

					$method_name = EasyPack_Helper()->validate_method_name( $method->get_method_id() );

					if( $method_name === 'easypack_parcel_machines_weekend' || ( isset( $fs_method_name ) && $fs_method_name === 'easypack_parcel_machines_weekend' ) ) {
						$img = ' <span class="easypack-weekend-shipping-method-logo"><img style="" src="'
							. EasyPack()->getPluginImages()
							. 'logo/inpost-paczka-w-weekend.png" /><span>';

					} else if( $method_name === 'easypack_parcel_machines' || $method_name === 'easypack_parcel_machines_cod'
                        || ( isset( $fs_method_name ) && $fs_method_name === 'easypack_parcel_machines' )
                        || ( isset( $fs_method_name ) && $fs_method_name === 'easypack_parcel_machines_cod' )
                    ) {

                        $img = ' <span class="easypack-shipping-method-logo"><img style="" src="'
                            . EasyPack()->getPluginImages()
                            . 'logo/inpost-paczkomat-logo.png" /><span>';

                    } else {

                        $img = ' <span class="easypack-shipping-method-logo"><img style="" src="'
                            . EasyPack()->getPluginImages()
                            . 'logo/inpost-kurier-logo.png" /><span>';

                    }


				} else {
					$img = ' <span class="easypack-custom-shipping-method-logo"><img style="" src="'
						. $custom_logo . '" /><span>';
				}
				echo $img;
			}

        }, 10, 2);
    }
}
