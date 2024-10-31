<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 1/8/19
 * Time: 10:09 AM
 */

namespace RMS\Views;

use \RMS\Bizs\NotificationAffBiz;

class NotificationAffSuccessView
{
    static function content()
    {
        $biz = new NotificationAffBiz();
        $setting = $biz->get_notification_aff();

        function get_data($data, $field)
        {
            return isset($data) ? $data->{$field} : '';
        }

        $allow_show = get_data($setting, 'allow') == 1 ? "checked" : "";
        ?>
        <script>
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <div class="wrap">
            <h2>Cài đặt thông báo cộng tác viên đăng ký thành công:</h2>
            <br> <br>
            <fieldset class="fieldset-manage" style="padding: 10px;">
                <form id="rms-notification-aff-setting" class="rms-notification-aff-setting" method="post">
                    <input type="hidden" value="<?php echo get_data($setting, 'id'); ?>" name="id" id="id">
                    <table>
                        <tr valign="top">
                            <td class="typeform"><strong>Cách dùng:</strong></td>
                            <td>
                                <ul style="line-height: 30px">
                                    <li>
                                        <strong>Bước 1:</strong> Tích vô ô "cho phép hiên thị thông báo"<br>
                                    </li>
                                    <li>
                                        <strong>Bước 2:</strong> Soạn nội dung muốn hiển thị khi cộng tác viên đăng ký thành công
                                    </li>
                                    <li>
                                        <strong>Bước 3:</strong> Lưu thay đổi
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform"><strong>Bật thông báo:</strong></td>
                            <td>
                                <input id="checkbox_allow" class="checked" type="checkbox" name="allow"
                                       value="1" <?php echo $allow_show ?> style="min-height: 15px; min-width: 15px;">
                                <label for="checkbox_allow"> Cho phép hiển thị thông báo khi cộng tác viên đăng ký thành công.</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform"><strong>Nội dung thông báo:</strong></td>
                            <td style="width: 90%">
                                <?php wp_editor(get_data($setting, 'content_success'), 'content_success', $setting = array()); ?>
                            </td>
                        </tr>

                    </table>
                    <div class="btn-notification-admin">
                        <?php submit_button(); ?>
                    </div>
                </form>

            </fieldset>
        </div>
        <?php
    }
}