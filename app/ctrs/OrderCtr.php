<?php
/**
 * Created by PhpStorm.
 * User: mrrms
 * Date: 10/10/16
 * Time: 6:07 PM
 */

namespace RMS\Ctrs;

use RMSConnector;


class OrderCtr
{
    function __construct()
    {
        add_action('wp_ajax_rms_simple_order', array(&$this, 'order'));
        add_action('wp_ajax_nopriv_rms_simple_order', array(&$this, 'order'));
    }


    function order(){
        try{
            $data = $_POST['data'];
            $channel_name= strtoupper(str_replace('.','',$this->_get_channel_domain_name()));

            $con = new RMSConnector();
            $campaign_id=get_option('rms_campaign_id');
            $meta_label=$data['meta_label'];
            $meta_value=$data['meta_value'];
            $meta_data = [];
            foreach ($meta_label as $key => $value) {
                $meta_data[$meta_label[$key]] = $meta_value[$key] ;
            }
            if(($meta_data==null)&&($meta_label!=null))
            {
                $meta_data[$meta_label] = $meta_value ;
            }
            if($campaign_id!='') $meta_data['campaign_id']=$campaign_id;
            $meta_data=json_encode($meta_data);
            $order['note'] = $data['note'];


            $order['number'] = 'FO' . $data['form_id'] . '-' . time();

            if(isset($_COOKIE['rms_referral']) && !empty($_COOKIE['rms_referral'])){
                $order['nickname'] = $_COOKIE['rms_referral'];
            }

            if(!empty($data['discount'])){
                $order['discount_code'] = $data['discount'];
            }

            $customer['fullname'] = $data['fullname'];
            $customer['email'] = $data['email'];
            $customer['phone'] = $data['phone'];
            $customer['address'] = $data['address'];
            $customer['metadata']= $meta_data;
            
            $order['customer'] = $customer;
 

            $order['infusion_tags']= explode(',', $data['infusion_tags']);

            $product['price'] = $data['price'];
            $product['description'] = '';
            $product['image'] = '';
            $product['name'] = $data['product'];
            $product['code'] =$channel_name.'-FO'.$data['form_id'];

            if ($data['commission']!==''){
                $order_line['commission'] = $data['commission'] / 100;
            }
            $order_line['product'] = $product;
            $order_line['price'] = $data['saleprice'];
            $order_line['quantity'] = $data['quantity'];

            $order['order_lines'][] = $order_line;


            $order['channel_domain_name'] = $this->_get_channel_domain_name();

            $result =$con->order($order);

            if($result){
                $email = new \RMS\Ctrs\EmailCtr();
                $email->send_email_order_success($order);
                echo json_encode($result);
            }else
                echo json_encode(array(
                    'success'=>false,
                    'message'=>'Đặt hàng không thành công!'
                ));
        }catch (\Exception $e){
            echo json_encode(array(
                'seccess'=>false,
                'message'=> $e->getMessage()
            ));
        }

        die();
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
        return $str[0]?$str[0]:'quocle.info';
    }
}