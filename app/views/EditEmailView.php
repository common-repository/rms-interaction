<?php

namespace RMS\Views;

class EditEmailView{

    static function content() {
		$biz = new \RMS\Bizs\EmailBiz();
        // $setting = null;
        $setting = $biz->get_email_setting(1);

        function get_data($data,$field){
            return isset($data)?$data->{$field}:'';
        }

        ?>
        <script>
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo RMS_URL . '/assets/css/rms-tags.css' ?>" />
        <script src="<?php echo RMS_URL . '/assets/js/rms-tags.js' ?>"></script> -->

        <div class="guide" style="padding-top: 10px">
            <tr valign="top">
                <h3>Cách dùng:</h3>
                <td>
                    <ul style="line-height: 30px">
                        <li>
                            <strong>Bước 1:</strong> Tích vô ô "cho phép gửi email"<br>
                        </li>
                        <li>
                            <strong>Bước 2:</strong> Đặt tiêu đề và nội dung email phù hợp với công ty của bạn
                        </li>
                        <li>
                            <strong>Bước 3:</strong> Lưu thay đổi
                        </li>

                        <li>
                            <strong>* Lưu ý:</strong> 
                            <br>- Email này sẽ được gửi tới khách hàng khi mua hàng thành công từ form mua hàng.
                            <br>- Việc gửi email sẽ thông qua cơ chế gửi email mặc định được cài đặt trên website của bạn. Nếu email gửi không thành công, vui lòng kiểm tra cài đặt gửi email (SMTP).
                        </li>
                    </ul>
                </td>
            </tr>
        </div>

        <div class="guide" style="padding-top: 10px">
            <tr valign="top">
                <h3>Danh sách từ khóa:</h3>
                <td>
                    <ul style="line-height: 30px">
                        <li>
                            <strong>[rms-customer-name]</strong> Tên khách hàng đăng ký trong form mua hàng.
                        </li>
                        <li>
                            <strong>[rms-customer-phone]</strong> Số điện thoại khách hàng đăng ký.
                        </li>
                        <li>
                            <strong>[rms-customer-email]</strong> Email khách hàng đăng ký.
                        </li>
                        <li>
                            <strong>[rms-product-name]</strong> Tên khách hàng đăng ký trong form mua hàng.
                        </li>
                        <li>
                            <strong>[rms-product-price]</strong> Tên khách hàng đăng ký trong form mua hàng.
                        </li>
                    </ul>
                </td>
            </tr>
        </div>

        <div class="wrap">
            <h2>Cài đặt email thông báo mua hàng thành công</h2>
            <br> <br>
            <fieldset class="fieldset-manage" style="padding: 10px;">
                <form id="rms-email-setting" class="rms-email-setting" method="post">
                    <input type="hidden" value="<?php echo get_data($setting,'id'); ?>" name="id"  id="id">
                    <table>
                    	 <tr>
                            <td class="typeform">Cho phép gửi email:</td>
                            <td>
                                <input id="checkbox_allow" class="checked" type="checkbox" name="allow" value="1" <?php echo get_data($setting,'allow')==1?"checked":""; ?> style="min-height: 15px; min-width: 15px;" >  
                                <label for="checkbox_allow"> Gửi cho khách hàng đặt mua sản phẩm từ form.</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform">Tiêu đề email:</td>
                            <td>
                                <input type="text" required maxlength="350" placeholder="Khóa học sử dụng RMS" value="<?php echo get_data($setting,'subject'); ?>" id="subject" name="subject" style="width: 100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform">Nội dung email:</td>
                            <td style="width: 90%">
                                <?php wp_editor(get_data($setting,'content'),'content',$setting = array());?>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>

            </fieldset>
        </div>
    <?php }
}