<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 5/10/2017
 * Time: 10:21 AM
 */

namespace RMS\Views;


class ManageColorView
{
    static function content(){
        ?>
        <div class="wrap">
            <h2>Cài đặt màu sắc</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated-color">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'rms-setting-color' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row" style="width: 160px">Nhập mã màu:</th>
                        <td>
                            <input name="rms_color" class="jscolor jscolor-active"  value="<?php echo get_option('rms_color'); ?>" autocomplete="off" style="background-image: none; background-color: rgb(153, 204, 0); color: rgb(0, 0, 0);">
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
                                Nhập mã màu mà bạn muốn thay đổi vào ô nhập mã màu.<br>
                            </li>
                        </ul>
                    </td>
                </tr>
            </div>
        </div>
        <?php
    }
}