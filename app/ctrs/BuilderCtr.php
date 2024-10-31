<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 4/12/2017
 * Time: 2:22 PM
 */

namespace RMS\Ctrs;

use \RMS\Bizs\ShortcodeLogBiz;
use \RMS\Bizs\BuilderBiz;

class BuilderCtr
{
    function __construct()
    {
        add_action( 'wp_ajax_order_builder_save_rms', array(&$this, 'rms_order_builder_save') );
        add_action( 'wp_ajax_order_builder_delete_rms', array(&$this, 'rms_order_builder_delete') );
        add_action( 'wp_ajax_link_shortcode_delete', array(&$this, 'rms_link_shortcode_delete') );

    }

    function rms_order_builder_save(){
        if(isset($_POST['data'])){
            $data = $_POST['data'];
            $biz = new \RMS\Bizs\BuilderBiz();
            $biz->save($data);

            echo json_encode(array(
                'success'=>true
            ));
            die();

        }

    }

    function rms_order_builder_delete(){
        if(isset($_POST['id'])){
            $biz = new BuilderBiz;
            echo json_encode(array(
                'success'=>$biz->delete(intval($_POST['id']))
            ));
        }else
            echo json_encode(array(
                'success'=>false,
                'message'=> 'ID is empty!'
            ));

        die;
    }

    function rms_lead_builder_save(){
        if(isset($_POST['data'])){
            $data = $_POST['data'];
            $biz = new \RMS\Ctrs\BuiderBizFormLead();
            $result = $biz->save($data);
            if($result){
                echo json_encode(array(
                    'success'=>true,
                    'data' => $result['data']
                ));
            } else{
                echo json_encode(array(
                    'success'=>false,
                    'message' => 'Thêm shortcode thất bại.'
                ));
            }


            die();

        }
    }

    function rms_lead_builder_delete(){
        if(isset($_POST['id'])){
            $biz = new \RMS\Ctrs\BuiderBizFormLead();
            echo json_encode(array(
                'success'=>$biz->delete(intval($_POST['id']))
            ));
        }else
            echo json_encode(array(
                'success'=>false,
                'message'=> 'ID is empty!'
            ));

        die;
    }

    function rms_link_shortcode_delete(){
        if(isset($_POST['id'])){
            $biz = new ShortcodeLogBiz();
            echo json_encode(array(
                'success'=>$biz->delete(intval($_POST['id']))
            ));
        }else
            echo json_encode(array(
                'success'=>false,
                'message'=> 'ID is empty!'
            ));

        die;
    }
    
}
