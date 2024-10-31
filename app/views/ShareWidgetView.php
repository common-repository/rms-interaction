<?php
/**
 * Created by MrDevNET.
 * User: mrdevnet
 * Date: 1/1/2019 AD
 * Time: 10:08 PM
 */

namespace RMS\Views;


class ShareWidgetView
{
    static function content() {

        if(isset($_SESSION['rms_referral'])) {
            $referral_nickname  = $_SESSION['rms_referral'];

            $api_bitly = get_option('rms_bitly');
            $key_aff  = get_option('rms_config_sharelink')?get_option('rms_config_sharelink'):"rms";
            $color = get_option('rms_color') ? get_option('rms_color') : "ff9c00";
            $notify = get_option('rms_note');
            $user_browser = self::getBrowser();

            $bitly = $api_bitly?new \Bitly_RMS($api_bitly):false;

            $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $share_link = $current_url;

            if (!isset($_GET['rms']) && !isset($_GET['login']) && !isset($_GET[$key_aff])) {
                $default_url = $current_url . ((strrpos($current_url, '?') === false) ? "?" : "&") . $key_aff . "=". $referral_nickname;
                $mix_url = $current_url . ((strrpos($current_url, '?') === false) ? "?" : "&") . 'sharing=' . time() . '&'. $key_aff .'=' . $referral_nickname . '&browser=' . $user_browser;
                $share_link  = (get_option('rms_option_type_share') == 2)?$mix_url:$default_url;
            }
            $short_url = ($bitly !== false)?$bitly->shorten($share_link):$share_link;

            ?>

            <div class="block-share">
                <a href="<?php echo RMS_FE ?>" target="_black" class="change-icon"
                   style="background-color:#<?php echo $color; ?>"><span class="rms-home"></span></a>
                <div id="rms_share_referral" class="alert-rms">
                    <div class="cirle animated infinite tada" style="border:2px solid #<?php echo $color ?>"></div>
                    <div class="right-ff-rms animated infinite tada" style="background-color: #<?php echo $color ?>">
                        <a id="show_share_referral" href="javascript:void(0);">
                        <span>
                            <i class="rms rms-share-squared animated infinite tada icon-share"
                               style="background-color: #<?php echo $color ?>"></i>
                        </span>
                        </a>
                    </div>
                    <div class="th-rms input-group-rms" id="rms_share_mode" style="display:none;">
                        <div>
                            <i class="glyphicon glyphicon-remove icon-close" id="close_share" style="display:none;"></i>
                            <div class="container-fluid">
                                <div class="circle-row-share">
                                    <div class="sicon">
                                        <div class="sicon-div">
                                            <div class="icon-width">
                                                <div class="icon-circle animated bounceInDown">
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_link ?>"
                                                       class="ifacebook" title="Facebook" target="_BLANK">
                                                        <i class="rms rms-facebook"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="icon-width">
                                                <div class="icon-circle animated bounceInDown">
                                                    <a href="http://plus.google.com/share?url=<?php echo $share_link ?>"
                                                       class="igoogle" title="Google+" target="_BLANK">
                                                        <i class="rms rms-google"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="icon-width">
                                                <div class="icon-circle  animated bounceInDown">
                                                    <a href="http://twitter.com/share?url=<?php echo $share_link ?>"
                                                       class="itwittter" title="Twitter"><i class="rms rms-twitter"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="share-inp">
                                        <?php
                                        if ($bitly) { ?>
                                            <input class="media-input input-popup-share animated rubberBand"
                                                   style="font-size: 15px;" id="foo" onclick="this.select()"
                                                   value="<?php echo $short_url ?>">
                                        <?php } ?>
                                        <input class="media-input input-popup-share animated rubberBand"
                                               style="font-size: 15px;" id="foo1" onclick="this.select()"
                                               value="<?php echo $share_link ?><?php ?>">
                                    </div>
                                    <?php
                                    if ($notify) { ?>
                                        <div id="alert-popup">
                                            <div class="edit-alert">
                                                <article class="export-alert animated rubberBand"><?php echo $notify; ?></article>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    private static function getBrowser(){
        $arr_browsers = ["Firefox", "Chrome", "Safari", "Opera",
            "MSIE", "Trident", "Edge"];

        $agent = $_SERVER['HTTP_USER_AGENT'];

        $user_browser = '';
        foreach ($arr_browsers as $browser) {
            if (strpos($agent, $browser) !== false) {
                $user_browser = $browser;
                break;
            }
        }

        switch ($user_browser) {
            case 'MSIE':
                $user_browser = 'Internet Explorer';
                break;

            case 'Trident':
                $user_browser = 'Internet Explorer';
                break;

            case 'Edge':
                $user_browser = 'Internet Explorer';
                break;
        }

        return $user_browser;
    }
}