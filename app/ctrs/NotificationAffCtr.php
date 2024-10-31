<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 1/8/19
 * Time: 10:41 AM
 */

namespace RMS\Ctrs;
use \RMS\Bizs\NotificationAffBiz;

class NotificationAffCtr
{
    function __construct()
    {
        add_action('wp_ajax_notification_aff_save_rms', array(&$this, 'rms_save_notification_aff_setting'));
    }

    function rms_save_notification_aff_setting()
    {
        if (isset($_POST['data'])) {
            $data = $_POST['data'];
            $biz = new NotificationAffBiz();
            $data['content_success'] = str_replace("\\", "", $data['content_success']);
            $data['allow'] = isset($data['allow']) ? $data['allow'] : 0;
            $biz->save_setting($data);

            echo json_encode(array(
                'success' => true,
                'message' => 'Bạn đã cập nhật thành công.'
            ));
        }else
            echo json_encode(array(
                'success'=>false,
                'message'=> 'Lỗi hệ thống.',
            ));
        die();
    }
}