<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 4/7/2017
 * Time: 8:41 AM
 */

namespace RMS\Views;


class BitlyShortLinkView
{
    static function content(){
        ?>
        <div class="wrap">
            <h2>BitLy ShortLink</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated-bitly-shortlink">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" action="options.php" id="form_bitly_access_token">
                <?php settings_fields( 'rms-setting-bitly' ); ?>
                <table class="form-table" style="width: 100%">
                    <tr valign="top">
                        <th scope="row">Access Token:</th>
                        <td>
                           <input style="width: 425px;height: 35px" type="text" name="rms_bitly" value="<?php echo get_option('rms_bitly')?>" >
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Cách dùng:</th>
                        <td>
                            <ul style="line-height: 30px">
                                <li>Bạn sẽ cần một tài khoản Bitly để sử dụng chức năng này. Nếu bạn chưa có, hãy đăng ký <a href="https://bitly.com/a/sign_up" target="_blank">tại đây.</a></li>
                                <li>Xem hướng dẫn <a href="https://support.bitly.com/hc/en-us/articles/230647907-How-do-I-find-my-OAuth-access-token-" target="_blank">tại đây</a></li>
                            </ul>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <script type="text/javascript">
            function check_bitly_connect(token){
                var ajax_url = "https://api-ssl.bitly.com/v3/shorten?access_token="+token+"&longUrl="+location.protocol + "//" + location.host;
                var check = false;
                jQuery.ajax({
                    url: ajax_url,
                    method: "GET",
                    async: false,
                    success: function(result){
                        check = result.status_code==200?true:false;
                    }
                });
                return check;
            }
            jQuery(function($){
                $("#form_bitly_access_token").submit(function(event){
                    if(!check_bitly_connect($('[name="rms_bitly"]').val())){
                        event.preventDefault();
                        alert('Access Token của bạn không hợp lệ, vui lòng xem hướng dẫn và thử lại.');
                    }
                });
            });
        </script>
        <?php
    }
}