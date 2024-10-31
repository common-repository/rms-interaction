<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 19/07/2016
 * Time: 9:47 SA
 */

namespace RMS\Views;

class ConfigView{

    static function content() {
        $timeLimited = get_option('rms_timeout');
        $timeLimited = $timeLimited==0?30:$timeLimited;

        ?>
        <div class="wrap">
            <h2>Kết nối RMS</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" id="check_connection_rms" action="options.php">
                <?php settings_fields( 'rms-setting-connect' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Khóa API:</th>
                        <td>
                            <input type="text" name="rms_username" style="width: 90%" value="<?php echo get_option('rms_username'); ?>" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Khóa bí mật:</th>
                        <td>
                            <input type="password" name="rms_password" style="width: 90%"  value="<?php echo get_option('rms_password'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian tồn tại mã giới thiệu</th>
                        <td>
                            <input type="number" name="rms_timeout" value="<?php echo $timeLimited; ?>" /><span> ngày</span><br>
                            <small>Sau khi người dùng click vào một liên kết có chứa mã giới thiệu, mã đó sẽ tồn tại trên máy người dùng để theo dỏi các hoạt động mua hàng hoặc trở thành cộng tác viên cho công ty.</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Đồng bộ sản phẩm</th>
                        <td>
                            <input id="rms_sync_all" class="button button-primary" type="button" value="Đồng bộ tất cả"/><br>
                            <small>Đồng bộ sản phẩm trên website và hệ thống RMS sẽ giúp việc thống kê được chính xác hơn. Thông thường các sản phẩm được tạo ra sau thời điểm cài đặt kết nối thành công sẽ được tự động đồng bộ.</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Loại Chia sẻ</th>
                        <td>
                            <div class="radio-type" style="padding: 10px">
                                <label><input type="radio" checked name="rms_option_type_share" value="1" <?php echo (get_option('rms_option_type_share') == 1 ? 'checked' : '')?> >Mặc định</label>
                            </div>
                            <div class="radio-type" style="padding: 10px">
                                <label><input type="radio" name="rms_option_type_share" value="2" <?php echo (get_option('rms_option_type_share') == 2 ? 'checked' : '')?>>Chuỗi chia sẻ</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Khóa form đăng ký CTV</th>
                        <td>
                            <div class="radio-type" style="padding: 10px">
                                <label><input type="radio" checked name="rms_option_disabled_register" value="0" <?php echo (get_option('rms_option_disabled_register') == 0 ? 'checked' : '')?> >Hiển thị</label>
                            </div>
                            <div class="radio-type" style="padding: 10px">
                                <label><input type="radio" name="rms_option_disabled_register" value="1" <?php echo (get_option('rms_option_disabled_register') == 1 ? 'checked' : '')?>>Khóa</label>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Cách dùng:</th>
                        <td>
                            <ul style="line-height: 30px">
                                <li>
                                    <strong>1.Quản lý cộng tác viên:</strong><br>
                                    Đặt đoạn mã <input style="text-align: center; width: 155px" value="[rms]" onfocus="this.select()"> Vào nội dung trang web bạn muốn người dùng đăng ký vào hệ thống RMS.
                                </li>
                                <li>
                                    <strong>3.Chức năng đặt hàng:</strong><br>
                                    Để tạo mã nhúng cho chức năng mua hàng, bạn vui lòng click vào menu <a href="<?php echo $_SERVER['PHP_SELF'].'?page=rms-manage-form-order'?>" style="text-decoration: none;color: #000;"><b>"Manage Order"</b></a> của plugin RMS.
                                </li>
                                <li>
                                    <strong>4.Chức năng chọn màu sắc nút chia sẻ và nút đăng ký mua hàng cho phù hợp với web:</strong><br>
                                    Để sử dụng chức năng màu sắc, bạn vui lòng click vào menu <a href="<?php echo $_SERVER['PHP_SELF'].'?page=color-setting-RM'?>" style="text-decoration: none;color: #000;"><b>"Manage Color"</b></a> của plugin RMS.
                                </li>
                                <li>
                                    <strong>5.Chức năng xem link của mã nhúng :</strong><br>
                                    Để sử dụng chức năng xem link, bạn vui lòng click vào menu <a href="<?php echo $_SERVER['PHP_SELF'].'?page=rms-manage-link-shortcode'?>" style="text-decoration: none;color: #000;"><b>"Manage Link Shortcode"</b></a> của plugin RMS.
                                </li>
                                <li>
                                    <strong>6.Chức năng chỉnh sửa css của form :</strong><br>
                                    Để sử dụng chức chỉnh sửa css, bạn vui lòng click vào menu <a href="<?php echo $_SERVER['PHP_SELF'].'?page=rms-custom-css'?>" style="text-decoration: none;color: #000;"><b>"Custom CSS"</b></a> của plugin RMS.
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php }
}