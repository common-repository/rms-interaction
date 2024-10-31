<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 12/5/2016
 * Time: 4:28 PM
 */

namespace RMS\Views;


class NoteView
{
    static function content(){
        ?>
        <div class="wrap">
            <h2>Ghi chú</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated-notification">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'rms-setting-note' ); ?>
                <table class="form-table table-noti" style="width: 100%">
                    <tr valign="top">
                        <td >Thông báo này sẽ hiển thị khi Affiliate click chia sẻ:</td>
                        <td>
                            <textarea style="width: 100%;height: 200px" type="text" name="rms_note"><?php echo get_option('rms_note'); ?></textarea>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>
            </form>
        </div>

    <?php }

}