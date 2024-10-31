<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 4/7/2017
 * Time: 8:41 AM
 */

namespace RMS\Views;


class GoogleShortLinkView
{
    static function content(){
        ?>
        <div class="wrap">
            <h2>Google ShortLink</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated-google-shortlink">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'rms-setting-googl' ); ?>
                <table class="form-table" style="width: 100%">
                    <tr valign="top">
                        <th scope="row">Key API:</th>
                        <td>
                           <input style="width: 425px;height: 35px" type="text" name="rms_googl" value="<?php echo get_option('rms_googl')?>" >
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Cách dùng:</th>
                        <td>
                            <ul style="line-height: 30px">
                                <li>
                                    Truy cập link: <a href="https://developers.google.com/url-shortener/v1/getting_started#APIKey" target="_blank">https://developers.google.com/url-shortener/v1/getting_started#APIKey</a> để tạo Key.
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