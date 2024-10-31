<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 7/7/17
 * Time: 3:10 PM
 */

namespace RMS\Views;


class LinkShortcodeView
{
    static function content($items,$log){

        function get_data($data,$field){
            return isset($data)?$data->{$field}:'';
        }
        ?>
        <script>
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <div class="wrap">
            <div class="list-manage">
                <form method="post" id="form_shortcode">
                    <input type="hidden" value="<?php echo get_data($log,'id'); ?>" name="id"  id="shortcode_id">
                    <h1 style="margin-bottom: 10px"><b>Danh sách mã nhúng</b></h1>
                    <table class="wp-list-table widefat fixed striped pages" style="width: 100%">
                        <thead>
                        <tr class="tr-short" style="background-color: #292929">
                            <th style="width: 40px">ID</th>
                            <th>Đường dẫn</th>
                            <th>Tên mã nhúng</th>
                            <th>Cập nhật cuối</th>
                            <th style="width: 80px;">Hoạt động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($items as $item){
                            ?>
                            <tr>
                                <td><?php echo $item->id; ?></td>
                                <td><?php echo $item->link; ?></td>
                                <td class="short-code"><input style="width: 100%" readonly onfocus="this.select()" value="<?php echo htmlentities($item->shortcode); ?>" type="text"></td>
                                <td><?php echo $item->lastdate; ?></td>
                                <td class="action" style="text-align: center;"><a style="color: red; " class="link_edit" data-id="<?php echo $item->id ?>" href="javascript:void(0)">Xóa</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
    }
}