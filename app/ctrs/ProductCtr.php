<?php
/**
 * Created by PhpStorm.
 * User: mrrms
 * Date: 10/10/16
 * Time: 6:07 PM
 */

namespace RMS\Ctrs;

use RMSConnector;


class ProductCtr
{

    function __construct()
    {
        add_action('wp_ajax_rms_synchronize', array(&$this, 'synchronize'));
        add_action('wp_ajax_nopriv_rms_synchronize', array(&$this, 'synchronize'));
        add_action('wp_ajax_rms_synchronize_all', array(&$this, 'synchronize_all'));
        add_action('wp_ajax_rms_search_commissions_eanring', array(&$this, 'search_commissions_eanring'));
    }

    function synchronize(){
        $biz = new \RMS\Bizs\BuilderBiz();
        $items = $biz->get_lastid();
        try{
            $data = $_POST['data'];
            $id =intval($items[sizeof($items)-1]->id);
            $con = new RMSConnector();
            if($data['is_create']=='created'){

                $channel_name= strtoupper(str_replace('.','',$this->domain_name()));

                $product['price'] = $data['price'];
                $product['description'] = $data['product_description'];
                $product['code']=$channel_name.'-FO'. $id;
                $product['image']="";
                $product['name']=$data['product'];
                $product['channel_id']=get_option('channel_id');
                $product['products'][]=$product;
                $result = $con->synchronize($product);
   
                if ($result['success'] == true) {
                    echo json_encode(array(
                        'success'=> true,
                        'product_id'=> $result['data'][0]->id,
                        'message'=> "Đã đồng bộ sản phẩm với hệ thống RMS !",
                        'current_id'=> $id
                    ));
                }else{
                    echo json_encode(array(
                        'success'=> false,
                        'message'=> $result['message']
                    ));
                }
            }
        
        }catch (\Exception $e){
            echo json_encode(array(
                'success'=>false,
                'message'=> $e->getMessage()
            ));
        }
        die();
    }

    function synchronize_all(){
        try{
            $con = new RMSConnector();
            $channel_name= strtoupper(str_replace('.','',$this->domain_name()));

            // synchronize form
            $biz = new \RMS\Bizs\BuilderBiz();
            $items = $biz->get_shortcodes();
            foreach ($items as $item) {
                $product = [];
                $product['price'] = $item->price;
                $product['description'] = $item->product_description;
                $product['code']=$channel_name.'-FO'. $item->id;
                $product['image']="";
                $product['name']=$item->product;
                $product['channel_id']=get_option('channel_id');
                $product['products'][]=$product;
                $con->synchronize($product);
            }

            // synchronize woo
            $items = wc_get_products();
            foreach ($items as $item) {
                $product = [];
                $product['price'] = $item->get_regular_price();
                $product['description'] =wp_trim_words( $item->get_description(), 40, '...' );
                $product['code']=$channel_name.'-WOO'. $item->id;
                $product['image']="";
                $product['name']=$item->get_name();
                $product['channel_id']=get_option('channel_id');
                $product['products'][]=$product;
                $con->synchronize($product);
            }
            
            
            echo json_encode(array(
                'success'=>true,
                'message'=> 'Danh sách sản phẩm đã được đồng bộ thành công !'
            ));
        }catch (\Exception $e){
            echo json_encode(array(
                'success'=>false,
                'message'=> $e->getMessage()
            ));
        }
        die();
    }

    function domain_name(){
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

    function search_commissions_eanring(){
        if(isset($_POST['search_key'])){
            $args = array("post_type" => "product", "s" => $_POST['search_key']);
            $query = get_posts( $args );
            $data = [];
            foreach ($query as $item) {
                $product = wc_get_product($item->ID);
                $commission = $this->_check_commissions($item->ID,$item->post_title,$product->get_regular_price(),$product->get_price(),get_post_meta($item->ID,'_rms_commission',true),'');
                $data[] = array(
                    'title'         => $item->post_title , 
                    'price'         => $product->get_sale_price()?$product->get_sale_price():$product->get_regular_price(),
                    'link'          => get_permalink( $item->ID ),
                    'commission'    => $commission,
                );
            }

            echo json_encode(array(
                'success'=> true,
                'message'=> $data,
            ));
            die();
        }
    }

    function _check_commissions($id, $name, $price, $saleprice, $commission, $product_id){
        $con= new RMSConnector();
        $channel_name = strtoupper(str_replace('.','',$this->domain_name()));
        $data['affiliate_id'] = $_SESSION['aff_id'] ;

        $product['price'] = $price;
        $product['name'] = $name;
        $product['code'] = $channel_name.'-WOO'.$id;

        if ($commission!==''){
            $order_line['commission'] = $commission / 100;
        }
        $order_line['product_id'] = $product_id;
        $order_line['product'] = $product;
        if($saleprice ==""){
            $saleprice = $price;
        }
        $order_line['price'] = $saleprice;
        $order_line['quantity'] = 1;
        $data['order_lines'][] = $order_line;

        return $con->check_commissions($data)["data"];
    }
}
