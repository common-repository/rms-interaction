<?php

namespace RMS\Views;
use RMS\Bizs\Address;

class LoginView
{

    static function content($content_success,$allow){

        ob_start();
        $key_aff  = get_option('rms_config_sharelink');
        if($key_aff == '') $key_aff='rms';
        $notification_aff = ($allow == 1) ? $content_success : '';

        $disabled_register = get_option('rms_option_disabled_register');

        $disabled_register = isset($disabled_register)?$disabled_register:0;

        ?>
        <style>
            <?php  echo get_option('rms_css'); ?>
        </style>
        <div class="login-rms">
            <?php if(!isset($_SESSION['rms_referral'])){ ?>
                <script type="text/javascript">
                    window.login_url = '<?php echo get_permalink() ?>';
                </script>
                <div class="login_rm" id="login_rm" style="display: <?php echo (!isset( $_GET['rm']) || $_GET['rm']=='login')?'block':'none' ?>">
                    <h2>Đăng nhập tài khoản cộng tác</h2>
                    <form action="" id="rms_login" method="post" enctype="">
                        <div class="rm-form-group">
                            <input type="hidden" name="rms_key_aff" value="<?php echo $key_aff; ?>"  id="rms_key_aff" >
                        </div>
                        <div class="rm-form-group">
                            <input required type="email" name="email" value="" placeholder="E-Mail - Cộng tác:" id="input_email" class="rm-form-control">
                        </div>
                        <div class="rm-form-group">
                            <input required type="password" name="password" value="" placeholder="Mật Khẩu:" id="input_password" class="rm-form-control">
                        </div>
                        <div class="pas-rm">
                            <input type="checkbox" name="save_cookie" value="1" /> Tự động đăng nhập ?
                        </div>
                        <div class="loginrm">
                            <input type="submit" id="login_btn" value="Đăng nhập" class="btn btn-primary" style="background-color: #<?php echo get_option('rms_color')?>!important">
                        </div>

                        <div class="pas-rm">
                            <a href="<?php echo RMS_FE; ?>/forgot-password" id="enable_password_rm" style="color: #<?php echo get_option('rms_color')?>!important">Quên mật khẩu?</a>
                            <a href="?rm=register" id="register_password_rm" style="color: #<?php echo get_option('rms_color')?>!important; <?php echo $disabled_register==1?'display:none':''; ?> ">Đăng ký tài khoản</a>
                        </div>
                    </form>
                </div>
                <div class="register_login" id="register_login" style="display: <?php echo (isset( $_GET['rm']) && $_GET['rm']=='register')?'block':'none' ?> <?php echo $disabled_register===1?'display:none':''; ?>">
                    <h2>Đăng Ký tài khoản</h2>
                    <form action="" id="rms_register" method="post" enctype="">
                        <div style="display:none" class="notification_popup"><?php echo $notification_aff ?></div>
                        <div class="total-form">
                            <div class="r-item">
                                <div class="rm-form-group fnrm r-item-col col-form-user">
                                    <input type="text" name="last_name" value="" placeholder="Họ tên đệm" id="last_name" class="rm-form-control">
                                </div>
                                <div class="rm-form-group lnrm r-item-col col-form-user">
                                    <input required type="text" name="first_name" value="" placeholder="Tên" id="first_name" class="rm-form-control">
                                </div>
                            </div>
                            <div class="r-item">
                                <div class="rm-form-group col-form-user-12">
                                    <input required type="text" name="phone" value="" placeholder="Điện thoại" id="input_phone" class="rm-form-control">
                                </div>
                            </div>
                            <div class="r-item">
                                <div class="rm-form-group col-form-user-12">
                                    <input required type="text" name="nickname" value="" placeholder="Nickname của bạn" id="nickname" class="rm-form-control">
                                    <div class="span-nickname"><p id="span-nickname">Độ dài nickname phải từ 5-30 kí tự, và không có khoảng trống.</p></div>
                                </div>
                            </div>

                            <div class="r-item">
                                <div class="rm-form-group userrm r-item-col col-form-user">
                                    <input required type="email" name="email" value="" placeholder="Email" id="email_rm" class="rm-form-control">
                                </div>
                                <div class="rm-form-group emailrm r-item-col col-form-user">
                                    <input required type="email" name="confirmed_email" value="" placeholder="Xác nhận email" id="confirmed_email" class="rm-form-control">
                                </div>
                            </div>

                            <div class="r-item">
                                <div class="rm-form-group col-form-user-12">
                                    <input required type="password" name="password" value="" placeholder="Mật khẩu" id="password_register" class="rm-form-control">
                                </div>
                            </div>
                            <div class="r-item">
                                <div class="rm-form-group col-form-user-12">
                                    <input required type="password" name="confirmed_password" value="" placeholder="Xác nhận mật khẩu" id="confirmed_password" class="rm-form-control">
                                </div>
                            </div>
                            <div class="r-item">
                                <div class="rm-form-group col-form-user-12">
                                    <?php
                                    if(isset($_COOKIE['rms_referral'])){
                                        echo '<input type="hidden" name="referrer" value="'.$_COOKIE['rms_referral'].'">';
                                    }else
                                    {
                                        echo '<input type="text" name="referrer" value="" placeholder="Mã người giới thiệu tham gia" id="referrer" class="rm-form-control">';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="r-item">
                                <div class="checkrmrms rm-form-group col-form-user-12">
                                    <input type="checkbox" name="terms" id="rms_terms" value="1">
                                    <label for="rms_terms" ><span id="popup-ifamre">Tôi đồng ý với các chính sách của công ty!</span></label>
                                    <input type="hidden" value="<?php echo get_permalink() ?>" name="login_url">
                                </div>
                            </div>
                            <div class="regis-rm">
                                <input id="regester_btn" type="submit" value="Đăng ký" class="btn btn-pass-regis" style="background-color: #<?php echo get_option('rms_color')?>!important">
                            </div>
                            <div id="come_back_register">
                                <a href="?rm=login" id="back_bt_rm" style="color: #<?php echo get_option('rms_color')?>!important">Quay lại</a>
                            </div>
                        </div>
                    </form>
                </div>

            <?php } else{
            ?>
                <h1>Chào mừng bạn đã đến với hệ thống RMS ! </h1>
                <b>Đã kết nối tới : </b><?php echo get_option("rms_subscriber");?> </b> <br/>
                <b>Đây có phải là thông tin đăng ký của bạn  </b> <br/>
                <b>Họ và tên :</b> <?php echo $_SESSION['rms_fullname']; ?></b><br/>
                <b>Nick Name :</b> <?php echo $_SESSION['rms_referral']; ?></b><br/>
                <b>Số điện thoại :</b> <?php echo $_SESSION['phone']; ?></b>
                <h3>Bạn có thể xem thêm thông tin tại hệ thống <a target="_blank" href="<?php echo RMS_FE; ?>">RMS</a></h3>
                <h4><a id="logout-rms" href="javascript:void(0)" title="">Thoát</a></h4>
                <?php
                echo 'Kiểm tra mức hoa hồng có thể nhận được từ các sản phẩm';
                echo SearchCommissionsView::input_view();
            }?>
        </div>
        <script src="<?php echo RMS_URL . '/assets/js/rms-search-commissions.js' ?>"></script>
        <?php
        return ob_get_clean();
    }
}

