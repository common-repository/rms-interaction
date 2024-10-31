<?php
/**
 * Created by MrDevNET.
 * User: mrdevnet
 * Date: 8/30/2018 AD
 * Time: 10:36 PM
 */

namespace RMS\Boot;
use RMS\Views\ProductMetaBoxView;
use RMS\Views\InfusionTagView;

class RMSMetaBox
{
    function __construct()
    {
        add_action( 'add_meta_boxes', array($this,'meta_boxs_register'));
    }

    function meta_boxs_register(){
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            add_meta_box('rms_product_config','RMS - Cấu hình',array(&$this,'rms_product_config'),'product');
        }
    }

    function rms_product_config($post){
        return ProductMetaBoxView::content($post);
    }

}