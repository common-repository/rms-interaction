<?php

namespace RMS\Views;

class ShareLinkConfigView{

    static function content() {
        $timeLimited = get_option('rms_timeout');
        $timeLimited = $timeLimited==0?30:$timeLimited;
        $key = get_option('rms_config_sharelink');
        if($key == '') $key='rms';
        ?>
        <div class="wrap">
            <h2>Cài đặt từ khóa mã giới thiệu CTV </h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
        <form method="post" id="rms-config-sharelink-form" action="options.php">
                <?php settings_fields( 'config_getresponce' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Từ khóa mã giới thiệu:</th>
                        <td>
                            <input type="text" name="rms_config_sharelink" id="rms_config_sharelink" style="width: 90%" value="<?php echo get_option('rms_config_sharelink'); ?>" />
                        </td>
                    </tr>


                    <tr valign="top">
                        <th scope="row">Cách dùng:</th>
                        <td>
                            <ul style="line-height: 30px">
                                
                                <li>
                                    <strong>1. Điền thông tin từ khóa nhận biết mã giới thiệu CTV</strong>
                                </li>
                                <li>
                                    <strong>2. Click nút lưu (save changes) để lưu cấu hình </strong>
                                </li>
                                <li>
                                    <strong>3. Thực hiện Sharelink  </strong>
                                </li>
                                <li>
                                    <strong>4. Cấu trúc LinkShare: <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/..'.'/?sharing=2018-12-07_02-42-23pm& '.$key.' =nickname&brower=Chrome'; ?>  </strong>
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