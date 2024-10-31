<?php

namespace RMS\Views;

class GetResponceSettingView{

    function __construct()
    {
        add_action( 'wp_ajax_rms_setting_getresponce', array(&$this, 'rms_setting_getresponce')  );
        add_action( 'wp_ajax_nopriv_rms_setting_getresponce', array(&$this, 'rms_setting_getresponce') );
    }

    static function content() {
        $timeLimited = get_option('rms_timeout');
        $timeLimited = $timeLimited==0?30:$timeLimited;

        ?>
        <div class="wrap">
            <h2>Cài đặt mã chiến dịch GetResponce </h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
        <form method="post" id="setting_getresponce" action="options.php">
                <?php settings_fields( 'setting_getresponce' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Khóa Token - Campaign-id:</th>
                        <td>
                            <input type="text" name="rms_campaign_id" id="rms_campaign_id" style="width: 90%" value="<?php echo get_option('rms_campaign_id'); ?>" />
                        </td>
                    </tr>


                    <tr valign="top">
                        <th scope="row">Cách dùng:</th>
                        <td>
                            <ul style="line-height: 30px">
                                
                                <li>
                                    <strong>1. Điền thông tin khóa token - campaign-id</strong>
                                </li>
                                <li>
                                    <strong>2. Click save change để lưu cấu hình </strong>
                                </li>
                                <li>
                                    <strong>3. Thực hiện các bước mua hàng để thông tin khách hàng gửi qua GetResponce  </strong>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php
    }
}