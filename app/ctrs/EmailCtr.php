<?php

namespace RMS\Ctrs;

use \RMS\Bizs\EmailBiz;

class EmailCtr
{
    function __construct()
    {
        add_action( 'wp_ajax_email_save_rms', array(&$this, 'rms_save_email_setting') );
        add_action( 'wp_ajax_email_send_rms', array(&$this, 'rms_send_email') );
    }


    function rms_save_email_setting(){
        if(isset($_POST['data'])){
            $data = $_POST['data'];
            $biz = new \RMS\Bizs\EmailBiz();
            $data['content'] = str_replace("\\","",$data['content']);
            $data['allow'] = isset($data['allow'])?$data['allow']:0;
            $biz->save_setting($data);

            echo json_encode(array(
                'success'=>true
            ));
            die();
        }
    }

    function rms_send_email(){
        echo json_encode(array(
                'success'=>true
            ));
            die();
    }

    function send_email_order_success($order_info){
        $biz = new \RMS\Bizs\EmailBiz();
        return $biz->send_email_order_success($order_info);
    }
}