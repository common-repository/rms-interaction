<?php

namespace RMS\Ctrs;

use RMSConnector;


class UserCtr
{
    function __construct()
    {
        add_action( 'wp_ajax_rms_login', array(&$this, 'login')  );
        add_action( 'wp_ajax_nopriv_rms_login', array(&$this, 'login') );

        add_action( 'wp_ajax_rms_logout', array(&$this, 'logout')  );
        add_action( 'wp_ajax_nopriv_rms_logout', array(&$this, 'logout') );

        add_action( 'wp_ajax_rms_register', array(&$this, 'register')  );
        add_action( 'wp_ajax_nopriv_rms_register', array(&$this, 'register') );

    }


    function login(){

        $data = $_POST['data'];
        $con = new RMSConnector();

        if(!isset($data['email']) || $data['email'] ==''){
            echo json_encode(array(
                'success'=>false,
                'message'=> 'Vui lòng nhập email!'
            ));
            die();
        }

        if(!isset($data['password']) || $data['password'] ==''){
            echo json_encode(array(
                'success'=>false,
                'message'=> 'Vui lòng nhập mật khẩu!'
            ));
            die();
        }

        $result = $con->login(array(
            'email' => $data['email'],
            'password' => $data['password']
        ));

        if($result){

            if( $result['success'] == true){
                $_SESSION['aff_id'] = $result['data']->id;
                $_SESSION['rms_referral'] = $nickname  =  $result['data']->nickname;
                $_SESSION['rms_fullname'] = $result['data']->last_name.' '.$result['data']->first_name;
                $_SESSION['phone']        = $result['data']->phone;
                if($data['save_cookie']==1){
                    setcookie('rms_nickname',$nickname,time() + 86400 * 365,'/',$_SERVER['SERVER_NAME']);
                }
                echo json_encode(array(
                    'success'=>true,
                    'message'=> 'Đăng nhập thành công'
                ));
            }else
                echo json_encode(array(
                    'success'=>false,
                    'message'=> $result['message']
                ));

        }else
            echo json_encode(array(
                'success'=>false,
                'message'=> 'Kết nối máy chủ RMS thất bại'
            ));

        die();
    }

    function logout(){
        unset($_SESSION['rms_referral']);
        setcookie('rms_nickname','',time() - 86400 * 365,'/',$_SERVER['SERVER_NAME']);
        echo 1;
        die();

    }

    function register(){
        try{
            $data = $_POST['data'];

            if(!isset($data['terms']) || $data['terms'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng xác minh bạn đã đồng ý với điều lệ của chúng tôi!'
                ));
                die();
            }

            if(!isset($data['first_name']) || $data['first_name'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập tên!'
                ));
                die();
            }

            if(!isset($data['nickname']) || $data['nickname'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập mã giới thiệu của bạn!'
                ));
                die();
            }

            if(!isset($data['email']) || $data['email'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập email!'
                ));
                die();
            }

            if(!isset($data['confirmed_email']) || $data['confirmed_email'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập email xác nhận!'
                ));
                die();
            }

            if(!isset($data['phone']) || $data['phone'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập số điện thoại!'
                ));
                die();
            }

            if(!isset($data['password']) || $data['password'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập mật khẩu!'
                ));
                die();
            }

            if(!isset($data['confirmed_password']) || $data['confirmed_password'] ==''){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Vui lòng nhập lại mật khẩu!'
                ));
                die();
            }

            if($data['email'] != $data['confirmed_email']){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Email xác nhận không khớp!'
                ));
                die();
            }

            if($data['password'] != $data['confirmed_password']){
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Mật khẩu xác nhận không khớp!'
                ));
                die();
            }

            if(empty($data['referrer'])) {
                unset($data['referrer']);
            }

            $channel = json_decode($channel);

            $data['fe_url'] = RMS_FE;
            $data['subscriber_domain_name'] = get_option('rms_subscriber');


            $con = new RMSConnector();

            $result = $con->register($data);

            if($result){
                echo json_encode($result);
            }else
                echo json_encode(array(
                    'success'=>false,
                    'message'=> 'Đăng ký không thành công!'
                ));
        }
        catch(\Exception $e){
            echo json_encode(array(
                'success'=>false,
                'message'=> $e->getMessage()
            ));
        }
        die();
    }

}