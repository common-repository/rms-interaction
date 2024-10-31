<?php
/**
 * Created by MrDevNET.
 * User: mrdevnet
 * Date: 8/30/2018 AD
 * Time: 10:27 PM
 */

namespace RMS\Boot;
use \RMSConnector;
use \ShareInfo;

class RMSHook
{
    function __construct()
    {
        add_action('init', array(&$this, 'init_hooking'), 30);


        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            //when create a new order
            add_action('wp_insert_post', array(&$this, 'add_insert_post'), 10, 3);

            // when update product/order
            add_action( 'save_post', array(&$this,'save_post_hooking'), 10, 3 );
            add_action( 'publish_product', array(&$this, 'synchronize_list_product'), 10, 1);

            // add metadata of product item in an order added_{$meta_type}_meta
            add_action ('added_order_item_meta',array(&$this,'added_order_item_meta'),10,4);
            add_action ('added_order_item_meta',array(&$this,'added_item_infusion_tags'),10,4);
            add_action( 'woocommerce_after_order_notes',array(&$this,'discount_field_checkout_process'),10,1);
            add_action( 'woocommerce_checkout_order_processed', array(&$this, 'woocommerce_checkout_order_processed'), 10, 1 );
        }
    }

    function init_hooking() {

        if(!session_id()) {
            session_start();
        }

        if(!isset($_SESSION['rms_referral']))
            if(isset($_COOKIE['rms_nickname'])){
                $_SESSION['rms_referral'] = $_COOKIE['rms_nickname'];
            }

        if (defined('DOING_AJAX') && DOING_AJAX)
            return;

        try{
            $key_aff  = get_option('rms_config_sharelink');
            if($key_aff == '') $key_aff='rms';
            if(isset($_REQUEST[$key_aff])){
                $ex = intval(get_option('rms_timeout'));
                $ex = $ex==0?30:$ex;
                setcookie('rms_referral', $_REQUEST[$key_aff] , time() + (86400 * $ex), "/");

                $current_page = $this->current_url();

                $pages = (isset($_SESSION['rms_shares']) && is_array(json_decode($_SESSION['rms_shares'])))?json_decode($_SESSION['rms_shares']):array();

                $pages[] = $current_page;
                $_SESSION['rms_shares'] = json_encode($pages);

                $con = new RMSConnector();
                $link_info = new ShareInfo();

                $share_info = array(
                    "nickname" => $_REQUEST[$key_aff],
                    "url" => $current_page,
                    "click_info"=> array(
                        "country" => $link_info->getCountry(),
                        "device_type" => $link_info->getDevice(),
                        "os" => $link_info->getOS()
                    )
                );

               $con->share($share_info)  ;

            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }


    }

    function current_url()
    {
        $url      = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $key_aff  = get_option('rms_config_sharelink');
        if($key_aff == '') $key_aff='rms';

        if($_REQUEST[$key_aff]){
            $url      = str_replace('&'.$key_aff.'=' . $_REQUEST[$key_aff] , '',$url);
            $url      = str_replace('?'.$key_aff.'=' . $_REQUEST[$key_aff] . '&' , '?',$url);
            $url      = str_replace('?'.$key_aff.'=' . $_REQUEST[$key_aff] , '',$url);
            $url      = str_replace($key_aff.'=' . $_REQUEST[$key_aff] , '',$url);
        }
        if($_REQUEST['sharing']){
            $url      = str_replace('&sharing=' . $_REQUEST['sharing'] , '',$url);
            $url      = str_replace('?sharing=' . $_REQUEST['sharing'] . '&' , '?',$url);
            $url      = str_replace('?sharing=' . $_REQUEST['sharing'] , '',$url);
            $url      = str_replace('sharing=' . $_REQUEST['sharing'] , '',$url);
        }
        if($_REQUEST['brower']){
            $url      = str_replace('&brower=' . $_REQUEST['brower'] , '',$url);
            $url      = str_replace('?brower=' . $_REQUEST['brower'] . '&' , '?',$url);
            $url      = str_replace('?brower=' . $_REQUEST['brower'] , '',$url);
            $url      = str_replace('brower=' . $_REQUEST['brower'] , '',$url);
        }
        $url = rtrim($url, "/");

        $validURL = str_replace("&", "&amp", $url);

        return $validURL;
    }

    function add_insert_post( $post_id, $post, $update ) {

        if ( wp_is_post_revision( $post_id ) || $update)
            return;

        $post_type = get_post_type($post_id);
        if('shop_order'==$post_type){
            if(isset($_COOKIE['rms_referral']));
            update_post_meta( $post->ID, '_rms_affiliate',$_COOKIE['rms_referral']);
        }

    }

    function _get_order_products($orderId){
        global $wpdb;
        $products =  $wpdb->get_results("SELECT o.order_item_id, o.order_item_name AS name, o.order_item_type,
                              CONCAT('{',GROUP_CONCAT(CONCAT('\"product',i.`meta_key`,'\":\"',REPLACE(i.`meta_value`,'\"','&quot;'),'\"') SEPARATOR ','),'}') AS properties 
                              FROM `{$wpdb->prefix}woocommerce_order_items` o 
                              INNER JOIN `{$wpdb->prefix}woocommerce_order_itemmeta` i ON o.order_item_id = i.order_item_id 
                              WHERE  o.`order_id`  = $orderId
                              GROUP BY o.order_item_id;");
        return $products;
    }

    function save_post_hooking( $post_id, $post, $update ) {
        if ( wp_is_post_revision( $post_id ) )
            return;

        $post_type = get_post_type($post_id);

        // Set rms_commission for Course

        if('product' == $post_type){
            $rms_commission = $_POST['rms_commission'];
            update_post_meta( $post_id, '_rms_commission', intval($rms_commission));

            $infusion = $_POST['infusion_tags'];
            update_post_meta( $post_id, '_infusion_tags', $infusion);
        }

        return;
    }

    function added_order_item_meta($mid, $object_id, $meta_key, $_meta_value)
    {
        if('_product_id'==$meta_key){
            $rms_commission = get_post_meta($_meta_value,'_rms_commission', true);
            $rms_commission = $rms_commission?$rms_commission:0;
            add_metadata( 'order_item', $object_id, '_rms_commission', $rms_commission, true);
        }
    }

    function added_item_infusion_tags($mid, $object_id, $meta_key, $_meta_value)
    {
        if('_product_id'==$meta_key){
            $infusion = get_post_meta($_meta_value,'_infusion_tags', true);
            $infusion = $infusion?$infusion:'';
            add_metadata( 'order_item', $object_id, '_infusion_tags', $infusion, true);
        }
    }

    function woocommerce_checkout_order_processed($post_id){
        
        if(get_post_meta($post_id,'_rm_sent', true) != true ){
            $products = $this->_get_order_products($post_id);
            if($products){
                $order =[];
                $order['nickname'] = '';

                if(isset($_COOKIE['rms_referral']) && !empty($_COOKIE['rms_referral'])) {
                    $order['nickname'] = $_COOKIE['rms_referral'];
                }
                if($_POST['discount_code']!=""){
                    $order['discount_code']=$_POST['discount_code'];
                }
                $order['infusion_tags'] = [];


                foreach ($products as $product){
                    $properties = json_decode($product->properties);
                    if($product->order_item_type !="line_item")
                        continue;
                    $campaign_id=get_option('rms_campaign_id');
                    $meta_data = [];
                    if($campaign_id!='') $meta_data['campaign_id']=$campaign_id;
                    $meta_data=json_encode($meta_data);
                    
                    $channel_name= strtoupper(str_replace('.','',$this->_get_channel_domain_name()));
                    $item['price'] = $properties->product_line_subtotal/$properties->product_qty;
                    $item['name']=$product->name;
                    $item['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $properties->product_product_id ), 'single-post-thumbnail' )[0];
                    $item['code'] = $channel_name.'-WOO'.$properties->product_product_id;
                    $infusion = get_post_meta($properties->product_product_id,'_infusion_tags',true);
                    $order['infusion_tags']= array_merge($order['infusion_tags'],explode(',', $infusion));
                    $order_line['product'] = $item;
                    if($properties->product_rms_commission!==''){
                        $order_line['commission'] = (($properties->product_rms_commission) / 100);
                    }
                    $order_line['price'] = $properties->product_line_subtotal/$properties->product_qty;
                    $order_line['quantity'] = $properties->product_qty;
                    $order['order_lines'][] = $order_line;
                }

                $woo_post = get_post( $post_id );
                $order['note'] = $woo_post->post_excerpt;
                $order['number'] = 'SO'.$post_id.'-'.time();
                $customer['metadata']= $meta_data;
                $customer['fullname'] = get_post_meta($post_id,'_billing_first_name',true).' '.get_post_meta($post_id,'_billing_last_name',true) ;
                $customer['email'] = get_post_meta($post_id,'_billing_email',true) ;
                $customer['phone'] = get_post_meta($post_id,'_billing_phone',true) ;
                $ctm_address = get_post_meta($post_id,'_billing_address_1',true).','.get_post_meta($post_id,'_billing_state',true). ','.get_post_meta($post_id,'_billing_city',true);
                $customer['address']=$ctm_address?$ctm_address:'Chưa cập nhật';
                $order['customer'] = $customer;

                $order['channel_domain_name'] = $this->_get_channel_domain_name();

                $con = new RMSConnector();

                $result =$con->order($order);
  
                if($result['success']==false) {
                    wp_trash_post($post_id);
                    $woo_mess = array(
                        "messages"=> '<ul class="woocommerce-error" role="alert"><li>Mã giảm giá không tồn tại !</li></ul>',
                        "refresh"=> false,
                        "reload"=> false,
                        "result"=> "failure"
                    );
                    echo json_encode($woo_mess);
                    die();
                }
                update_post_meta( $post_id, '_rm_sent', true );
            }


        }
    }

    function synchronize_list_product($ID){
        $channel_name= strtoupper(str_replace('.','',$this->_get_channel_domain_name()));
        $product_woo = wc_get_product( $ID );
        $product['price'] = $_POST['_regular_price'];
        $product['description'] =wp_trim_words( $product_woo->get_description(), 40, '...' );
        $product['code']=$channel_name.'-WOO'. $ID;
        $product['image']="";
        $product['name']=$product_woo->get_name();
        $product['channel_id']=get_option('channel_id');
        $product['products'][]=$product;
        $con = new RMSConnector();
        $result = $con->synchronize($product);
    }

    function discount_field_checkout_process($checkout){
 
        $discount_code = array('' => __('Select Discount_code', 'woocommerce' )); 

        echo '<div id="discount_field_checkout_process"><h3>'.__('Mã Giảm Giá').'</h3>';

       woocommerce_form_field( 'discount_code', array(
            'type'          => 'text',
            'class'         => array('form-row-wide'),
            'id'            => 'discount_code',
            'required'      => false,
            'label'         => __('Hãy nhập mã giảm giá để được ưu đãi'),
            'placeholder'       => __('Mã Giảm Giá ...'),
            'options'     =>  $discount_code
            ),$checkout->get_value( 'discount_code' ));

        echo '</div>';

    }

    function _get_channel_domain_name(){
        $website = $_SERVER['SERVER_NAME'];
        $patterns = array(
            '/http:\/\//',
            '/https:\/\//',
            '/www./',
            '/ /',
        );
        $str = preg_replace( $patterns, '',$website);
        $str = explode('/',$str);
        return $str[0]?$str[0]:'localhost';
    }


}