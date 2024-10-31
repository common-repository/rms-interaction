<?php
/**
 * Created by MrDevNET.
 * User: mrdevnet
 * Date: 8/31/2018 AD
 * Time: 12:29 AM
 */

namespace RMS\Boot;
use RMS\Views\LoginView;
use RMS\Views\FormOrderView;
use RMS\Bizs\BuilderBiz;
use RMSConnector;
use RMS\Views\ShareWidgetView;
use RMS\Bizs\NotificationAffBiz;

class ShortCode
{
    function __construct()
    {
        add_shortcode( 'rms' , array(&$this, 'rms_main') );
        add_shortcode( 'rms-order' , array(&$this, 'rms_order'));
        add_shortcode('rms-tracking', array(&$this,'rms_tracking'));
    }

    function rms_main($attr = array(), $content = null) {
        extract(shortcode_atts(array('id' =>''), $attr));
            $biz = new NotificationAffBiz();
            $items = $biz->get_notification_aff();

            $form = LoginView::content($items->content_success,$items->allow);
            $form = '<div>' . $form . '</div>';
            return $form;
        }

    function rms_order($attr = array(), $content = null) {
        global $wpdb;
        extract(shortcode_atts(array('id' => ''), $attr));
        if(isset($id)){

            $biz = new BuilderBiz;
            $items = $biz->get_shortcode($id);
            $name = $items->product;
            $product_id = $items->product_id;
            $price = $items->price;
            $saleprice = $items->saleprice;
            $commission = $items->commission;
            $infusion_tags = $items->infusion_tags;
            $show_=$items->show_;
            $require_=$items->require_;
            $goto = $items->redirect;
            $submit = $items->submit_btn;
            $style = $items->style;
            $popupnotification = $items->success;
            $meta_data= $items->meta_data;

            $table_name = $wpdb->prefix . "rms_order_form_log";
            $link = get_pagenum_link();
            $exists = $wpdb->get_row("SELECT * FROM $table_name WHERE link = '".$link."'");
            $last_date  = date ("Y-m-d H:i:s");
            $shortcode= '[rms-order]';
            if ( !isset($exists->link) ) {
                $wpdb->insert($table_name, array(
                    'link' => $link,
                    'shortcode' => $shortcode,
                    'lastdate' => $last_date
                ));
            }
            $commission_earning = null;
            if($_SESSION['rms_referral']!='') {
                $commission_earning = $this->_check_commissions($id, $name, $price, $saleprice, $commission, $product_id);
            }
            return FormOrderView::content($id,$name,$price,$saleprice,$commission,$goto,$submit,$popupnotification,$style,$infusion_tags,$meta_data,$show_,$require_,$commission_earning);
        }
        
        return '';
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

    function _check_commissions($id, $name, $price, $saleprice, $commission, $product_id){
        $con= new RMSConnector();
        $channel_name = strtoupper(str_replace('.','',$this->_get_channel_domain_name()));
        $data['affiliate_id'] = $_SESSION['aff_id'] ;

        $product['price'] = $price;
        $product['name'] = $name;
        $product['code'] = $channel_name.'-FO'.$id;

        if ($commission!==''){
            $order_line['commission'] = $commission / 100;
        }
        $order_line['product_id'] = $product_id;
        $order_line['product'] = $product;
        $order_line['price'] = $saleprice;
        $order_line['quantity'] = 1;

        $data['order_lines'][] = $order_line;

        return $con->check_commissions($data)["data"];
        // $cdc= $result['data']->cdc ;
        // $cbs= $result['data']->cbs ;
        // $coan_referrer1= $result['data']->coan_referrer1;
        // $coan_referrer2= $result['data']->coan_referrer2 ;
        // $coov= $result['data']->coov;
        // $copg= $result['data']->copg ;
        // $copq= $result['data']->copq ;
        // $order_earnin = $result['data']->order_earning ;
    }

    function rms_tracking($attr = array(), $content = null){
        ob_start();
        ShareWidgetView::content();
        return ob_get_clean();
    }
}