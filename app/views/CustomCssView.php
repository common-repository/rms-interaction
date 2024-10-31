<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 8/16/17
 * Time: 9:11 AM
 */

namespace RMS\Views;


class CustomCssView
{
    static function content(){
        ?>
        <div class="wrap">
            <h2>Chỉnh sửa css</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="content" class="updated-css">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'rms-setting-css' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row" style="width: 100px">Chỉnh css:</th>
                        <td>
                            <textarea name="rms_css" style="width: 100%; height: 300px"><?php echo get_option('rms_css'); ?></textarea>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <div class="guide" style="padding-top: 10px">
                <tr valign="top">
                    <h3>Cách dùng:</h3>
                    <td>
                        <ul style="line-height: 30px">
                            <li>
                                Bạn có thể nhập css của bạn vào, để chỉnh sửa giao diện.<br>
                            </li>
                        </ul>
                    </td>
                </tr>
            </div>
        </div>
        <?php
    }
}