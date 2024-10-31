<?php
/**
 * Created by MrDevNET.
 * User: mrdevnet
 * Date: 8/26/2018 AD
 * Time: 10:55 PM
 */

namespace RMS\Ctrs;
use RMS\Bizs\InfusionSoftTagBiz;
use RMSConnector;
use rmsApp;
use InfusionsoftConnector;

class MainCtr
{
    function __construct()
    {
        add_action( 'wp_ajax_rms_connect', array(&$this, 'connect')  );
        add_action( 'wp_ajax_nopriv_rms_connect', array(&$this, 'connect') );
    }


    function connect(){
        $update_version = new rmsApp();
        $update_version->plugin_activate();
        $tagBiz = new InfusionSoftTagBiz();
        
        $data = $_POST['data'];
        $con = new RMSConnector($data['rms_username'],$data['rms_password']);
        $result = $con->connect();
        if($result['success'] == true){

            update_option('rms_username',$data['rms_username']);
            update_option('rms_password',$data['rms_password']);
            update_option('rms_timeout',$data['rms_timeout']);
            update_option('rms_token', $result['data']['x-security-token'][0]);
            update_option('rms_channel',$result['data']['x-user-profile'][0]);
            $user_profile_rms = json_decode(utf8_encode($result['data']['x-user-profile'][0]),true);
            update_option('rms_subscriber',$user_profile_rms['subscriber_domain_name']);
            update_option('channel_id',$user_profile_rms['id']);

            if($user_profile_rms['infusion_config']['access_token']){
                $infu_tags = new InfusionsoftConnector($user_profile_rms['infusion_config']['access_token']);
                $tags = $infu_tags->sendRequest('tags','GET','');

                if($tags['success']===1){
                    $infusion = json_decode($tags['response']);

                    $list = [];
                    foreach ($infusion->tags as $item) {
                        $list[$item->id] = $item->name;
                    }
                    $tagBiz->deleteAll();
                    $tags = $tagBiz->insertAll($list);
                }
            }
            

            echo json_encode(array(
                'success'=>true,
                'message'=> 'Kết nối thành công tới '.$user_profile_rms['subscriber_domain_name'],
                'update_tags' => $tags
            ));
        }else
            echo json_encode(array(
                'success'=>false,
                'message'=> $result['message'],
            ));
        die();
    }
}