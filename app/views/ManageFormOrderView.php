<?php
/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 4/7/2017
 * Time: 9:53 AM
 */

namespace RMS\Views;


use RMS\Bizs\BuilderBiz;
use RMS\Bizs\InfusionSoftTagBiz;

class ManageFormOrderView
{
    /**
     *
     */
    static function content(){

        $biz = new BuilderBiz();
        $items = $biz->get_shortcodes();
        $infusion_Biz = new InfusionSoftTagBiz();
        $setting = null;
        function get_data($data,$field){
            return isset($data)?$data->{$field}:'';
        }
        if(isset($_GET['shortcode_id'])){
            $setting = $biz->get_shortcode($_GET['shortcode_id']);
            $show_check=explode(';',get_data($setting,'show_'));
            $required_check=explode(';',get_data($setting,'require_'));
        }else{
            $show_check = $required_check = array(1,1,1,1);
        }

        ?>
        <script>
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <link rel="stylesheet" type="text/css" href="<?php echo RMS_URL . '/assets/css/rms-tags.css' ?>" />
        <script src="<?php echo RMS_URL . '/assets/js/rms-tags.js' ?>"></script>
        <script type="text/javascript">

            function onAddTag(tag) {
                alert("Added a tag: " + tag);
            }
            function onRemoveTag(tag) {
                alert("Removed a tag: " + tag);
            }

            function onChangeTag(input,tag) {
                alert("Changed a tag: " + tag);
            }

        </script>
        <div class="wrap">
            <h2>Tạo form đặt hàng</h2>
            <br> <br>
            <fieldset class="fieldset-manage" style="padding: 10px;">
                <h3>Tùy chỉnh mã nhúng</h3>
                <form id="setting_order_rms" class="setting_order_rms" method="post">
                    <input type="hidden" value="<?php echo get_data($setting,'id'); ?>" name="id"  id="shortcode_id">
                    <input type="hidden" value="<?php echo get_data($setting,'product_id'); ?>" name="product_id"  id="product_id">
                    <input type="hidden" value="<?php if(get_data($setting,'product')=="") echo "create"; else echo "update"; ?>" name="is_create"  id="is_create">
                    <table>
                        <tr>
                            <td class="typeform">Tiêu đề sản phẩm:</td>
                            <td>
                                <input type="text" required maxlength="250" placeholder="Khóa học sử dụng RMS" value="<?php echo get_data($setting,'product'); ?>" id="product" name="product">
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform">Mô tả:</td>
                            <td>
                                <input type="text" maxlength="250" placeholder="Sản phẩm tốt chất lượng cao" value="<?php echo get_data($setting,'product_description'); ?>" id="product_description" name="product_description">
                            </td>
                        </tr>
                        <tr>
                            <td>Giá sản phẩm:</td>
                            <td>
                                <input type="text" placeholder="100000" required value="<?php echo get_data($setting,'price'); ?>" id="price" name="price">
                            </td>
                        </tr>
                        <tr>
                            <td>Giá bán sản phẩm:</td>
                            <td>
                                <input type="text" placeholder="90000" required value="<?php echo get_data($setting,'saleprice'); ?>" id="saleprice" name="saleprice">
                            </td>
                        </tr>
                        <tr>
                            <td>Hoa hồng %:</td>
                            <td>
                                <input type="number" min="0" placeholder="45" value="<?php echo get_data($setting,'commission'); ?>" id="commission" name="commission">
                            </td>
                        </tr>
                        <tr>
                            <td>URL chuyển hướng:</td>
                            <td>
                                <input type="url" placeholder="http://rms.com.vn" value="<?php echo get_data($setting,'redirect'); ?>" id="redirect" name="redirect">
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform">Nút gửi:</td>
                            <td>
                                <input class="successinp" required type="text" value="<?php echo get_data($setting,'submit_btn'); ?>"  id="setting_submit_btn" name="submit_btn"
                                       placeholder="Mua ngay">
                            </td>
                        </tr>
                        <tr>
                            <td>Infusion Tags</td>
                            <td>
                                <select multiple="true" class="limitedNumbChosen" name="infusion_tags" id="infusion_name" style="width: 250px">
                                    <option value="0">
                                        Chọn thẻ Tag
                                    </option>
                                    <?php
                                    $tags = $infusion_Biz->get_infusion();
                                    $tags_selected = explode(',',get_data($setting,'infusion_tags'));
                                    foreach ($tags as $tag){
                                        if($tag->infusion_name != '' && ($tag->infusion_name != '0')){
                                            ?>
                                            <option <?php echo in_array($tag->infusion_name,$tags_selected)?'selected':'' ?> value="<?php echo $tag->infusion_name; ?>" >
                                                <?php echo $tag->infusion_name; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform">Hiển thị:</td>
                            <td>
                                <select id="shortcode_style" name="style" style="width: 190px;" >
                                    <option value="default">
                                        Một cột (mặc định)
                                    </option>
                                    <?php if (get_data($setting,'style') == '2_column'){ ?>
                                    <option  selected value="<?php echo get_data($setting,'style'); ?>" >
                                        Hai cột
                                    </option>
                                    <?php } else { ?>
                                    <option value="2_column">
                                        Hai cột
                                    </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Thêm thông tin:</td>
                            <td>
                                <button id='meta_add' name='meta_add'> Thêm </button>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <table id="inputs_meta" style="border-collapse: collapse;" border="1px" >
                                    <tr>
                                        <td colspan="5" style="text-align: center; background-color: #111111; color: white; ">
                                          Các trường thông tin hiển thị trong form mua hàng 
                                        </td>
                                    </tr>
                                    <tr style="text-align: center; background-color: #33FFFF;">
                                        <td> STT </td>
                                        <td colspan="2"> Trường thông tin </td>
                                        <td> Ẩn / bắt buộc </td>
                                        <td> Xóa </td>
                                    </tr>
                                    <tr > 
                                        <td style="text-align: center;"> 1</td>
                                        <td style="border-right-color: #FFFFFF;"> 
                                            <input type="text" name="name" value="Họ và tên" disabled="disabled">
                                        </td>
                                        <td> 
                                            <input type="text" name="value" value="Văn Bản" disabled="disabled" style="min-width: 200px;">
                                        </td>
                                        <td > 
                                            Hiển thị
                                            <input type="checkbox"  name="show" checked disabled="disabled" style="max-height:  20px; min-width: 10px;">
                                            Bắt buộc nhập
                                            <input type="checkbox"  name="required" checked disabled="disabled" style="max-height:  20px; min-width: 10px;">
                                        </td>
                                        <td><button class="removeclass" type="button" disabled ><img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"></button></td>

                                    </tr>

                                    <tr > 
                                        <td style="text-align: center;">2</td>
                                        <td style="border-right-color: #FFFFFF;"> 
                                            <input type="text" name="name" value="Email" disabled="disabled">
                                        </td>
                                        <td> 
                                            <input type="text" name="value" value="Văn Bản" disabled="disabled" style="min-width: 200px;">
                                        </td>
                                        <td > 
                                            Hiển thị
                                            <input type="checkbox"  name="show_0" id="show_email" <?php echo (isset($show_check[0]) && $show_check[0]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                            Bắt buộc nhập
                                            <input type="checkbox"  name="required_0" id="required_email" <?php echo (isset($required_check[0]) && $required_check[0]==1)?'checked':''; ?>  style="max-height:  20px; min-width: 10px;">
                                        </td>
                                        <td><button class="removeclass" type="button" disabled ><img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"></button></td>
                                    </tr>
                                     <tr > 
                                        <td style="text-align: center;">3</td>
                                        <td style="border-right-color: #FFFFFF;"> 
                                            <input type="text" name="name" value="Số Điện Thoại" disabled="disabled">
                                        </td>
                                        <td> 
                                            <input type="text" name="value" value="Số" disabled="disabled" style="min-width: 200px;">
                                        </td>
                                        <td > 
                                            Hiển thị
                                            <input type="checkbox"  name="show_1" id="show_phonenumber" <?php echo (isset($show_check[1]) && $show_check[1]==1)?'checked':''; ?>  style="max-height:  20px; min-width: 10px;">
                                            Bắt buộc nhập
                                            <input type="checkbox"  name="required_1" id="required_phonenumber" <?php echo (isset($required_check[1]) && $required_check[1]==1)?'checked':''; ?>  style="max-height:  20px; min-width: 10px;">
                                        </td>
                                        <td><button class="removeclass" type="button" disabled ><img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"></button></td>
                                    </tr>
                                    <tr > 
                                        <td style="text-align: center;">4</td>
                                        <td style="border-right-color: #FFFFFF;"> 
                                            <input type="text" name="name" value="Địa Chỉ" disabled="disabled">
                                        </td>
                                        <td> 
                                            <input type="text" name="value" value="Văn Bản" disabled="disabled" style="min-width: 200px;">
                                        </td>
                                        <td > 
                                            Hiển thị
                                            <input type="checkbox"  name="show_2" id="show_address" <?php echo (isset($show_check[2]) && $show_check[2]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                            Bắt buộc nhập
                                            <input type="checkbox"  name="required_2" id="required_address" <?php echo (isset($required_check[2]) && $required_check[2]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                        </td>
                                        <td><button class="removeclass" type="button" disabled ><img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"></button></td>
                                    </tr>
                                    <tr > 
                                        <td style="text-align: center;">5</td>
                                        <td style="border-right-color: #FFFFFF;"> 
                                            <input type="text" name="name" value="Số lượng" disabled="disabled">
                                        </td>
                                        <td> 
                                            <input type="text" name="value" value="Số" disabled="disabled" style="min-width: 200px;">
                                        </td>
                                        <td > 
                                            Hiển thị
                                            <input type="checkbox"  name="show_3" id="show_number" <?php echo (isset($show_check[3]) && $show_check[3]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                            Bắt buộc nhập
                                            <input type="checkbox"  name="required_3" id="required_number" <?php echo (isset($required_check[3]) && $required_check[3]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                        </td>
                                        <td><button class="removeclass" type="button" disabled ><img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"></button></td>
                                    </tr>

                                    <tr > 
                                        <td style="text-align: center;">6</td>
                                        <td style="border-right-color: #FFFFFF;"> 
                                            <input type="text" name="name" value="Mã giảm giá" disabled="disabled">
                                        </td>
                                        <td> 
                                            <input type="text" name="value" value="Văn Bản" disabled="disabled" style="min-width: 200px;">
                                        </td>
                                        <td > 
                                            Hiển thị
                                            <input type="checkbox"  name="show_4" id="show_discount"  <?php echo (isset($show_check[4]) && $show_check[4]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                            Bắt buộc nhập
                                            <input type="checkbox"  name="required_4" id="required_discount" <?php echo (isset($required_check[4]) && $required_check[4]==1)?'checked':''; ?> style="max-height:  20px; min-width: 10px;">
                                        </td>
                                        <td><button class="removeclass" type="button" disabled ><img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"></button></td>
                                    </tr>
    
                                    <?php  
                                        $meta_data=json_decode(get_data($setting,'meta_data'));
                                        $index=0;
                                        $cols_index=7;
                                        if(is_array($meta_data)){
                                            foreach ($meta_data as $key => $value) { ?>
                                                <tr class="inputs-meta" data-row="<?php echo "row-".$index ; ?>" >
                                                    <td rowspan="2" style="text-align: center;" > <?php echo $cols_index++; ?> </td>
                                                    <td class="typeform" style="vertical-align: top;border-right-color: #FFFFFF;border-bottom-color: #FFFFFF;">
                                                        <input style="margin-top:auto;" type="text" name="meta_name" value="<?php echo $value->name; ?>" >
                                                    </td>
                                                    <td style="vertical-align: top;border-bottom-color: #FFFFFF;" >
                                                        <select class="meta_option" name="meta_type" value="meta_checkbox" style="width: 200px;">
                                                            <option value="meta_textbox" <?php if($value->type=="meta_textbox") echo("selected"); ?> >
                                                                Văn bản
                                                            </option>
                                                            <option value="meta_number" <?php if($value->type=="meta_number") echo("selected"); ?>>
                                                                Số
                                                            </option>
                                                            <option value="meta_textarea" <?php if($value->type=="meta_textarea") echo("selected"); ?> >
                                                                Văn bản dài
                                                            </option>
                                                            <option value="meta_select" <?php if($value->type=="meta_select") echo("selected"); ?> >
                                                                Danh Sách
                                                            </option>
                                                            <option value="meta_checkbox" <?php if($value->type=="meta_checkbox") echo("selected"); ?> >
                                                                Nhiều lựa chọn 
                                                            </option>
                                                            <option value="meta_radio" <?php if($value->type=="meta_radio") echo("selected"); ?> >
                                                                Lựa chọn duy nhất 
                                                            </option>
                                                        </select>
                                                    </td>
                                                     <td rowspan="2"> 
                                                        Hiển thị
                                                        <input type="checkbox" id="meta_show_<?php echo $index; ?>" name="meta_show_<?php echo $index; ?>" <?php if($value->show=="1") echo("checked");?> style="max-height:  20px; min-width: 10px;">
                                                        Bắt buộc nhập
                                                        <input type="checkbox" id="meta_required_<?php echo $index; ?>" name="meta_required_<?php echo $index; ?>" <?php if($value->required=="1") echo("checked");?> style="max-height:  20px; min-width: 10px;">
                                                    </td>
                                                    <td style="vertical-align: center;" rowspan="2">
                                                        <button class="removeclass" type="button" style="max-height: 100%;"> <img src="<?php echo RMS_URL . '/assets/images/remove.png'; ?>"> </button>
                                                    </td>
                                                </tr>
                                                <tr  class="<?php echo "inputs-meta_1 row-".$index; ?>" >
                                            <?php   if(($value->type=="meta_select")||($value->type=="meta_checkbox")||($value->type=="meta_radio")){ ?>
                                                        <td class="inputs-meta-tag" colspan="2" style="width: 400px;">
                                                            <input class="tags"  type="text" name="meta_value" value="<?php echo $value->value;?>" >
          
                                                        </td>
                                                        <script type="text/javascript">jQuery(function($) { $('.tags').tagsInputOption();}); </script>
                                            <?php   } else { ?>
                                                        <td class="inputs-meta1" colspan="2" >
                                                            <input type="text" value="<?php echo $value->value; ?>" name="meta_value" placeholder="Dữ liệu mặc định" style="width: 455px;">
                        
                                                        </td>
                                            <?php   }  ?>
                                                </tr>
                                    <?php   $index++ ; }
                                        }   ?>        
                                    <input type="hidden" value="<?php echo $index; ?>" name="index" id="index" >
                                </table> 
                            </td>
                        </tr>
                        <tr>
                            <td class="typeform">Thông báo khi mua hàng thành công:</td>
                            <td style="width: 90%">
                                <?php wp_editor(get_data($setting,'success'),'success',$setting = array());?>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                    <?php if(isset($_GET['shortcode_id'])){
                        ?>
                        <button onclick="history.go(-1);" class="back">Quay lại </button>
                        <?php
                    } ?>
                </form>

            </fieldset>
            <div class="list-manage">
                <form method="post" id="form_shortcode">
                    <h1 style="margin-bottom: 10px"><b>Danh sách mã nhúng</b></h1>
                    <table class="wp-list-table widefat fixed striped pages" style="width: 100%">
                        <thead>
                        <tr class="tr-short" style="background-color: #292929">
                            <th style="width: 40px">ID</th>
                            <th>Tiêu đề sản phẩm</th>
                            <th>Mã nhúng</th>
                            <th>Giá sản phẩm</th>
                            <th>Giá bán sản phẩm</th>
                            <th>Hoa hồng %</th>
                            <th>URL chuyển hướng</th>
                            <th>Nút gửi</th>
                            <th>Infusion tags</th>
                            <th>Hiển thị</th>
                            <th style="width: 80px">Hoạt động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($items as $item){
                            ?>
                            <tr>
                                <td><?php echo $item->id; ?></td>
                                <td><?php echo $item->product; ?></td>
                                <td class="short-code"><input style="width: 100%" readonly onfocus="this.select()" value="<?php echo htmlentities($item->shortcode); ?>" type="text"></td>
                                <td><?php echo $item->price; ?></td>
                                <td><?php echo $item->saleprice; ?></td>
                                <td><?php echo $item->commission; ?></td>
                                <td><?php echo $item->redirect; ?></td>
                                <td><?php echo $item->submit_btn; ?></td>
                                <td><?php echo $item->infusion_tags; ?></td>
                                <td><?php echo $item->style == 'default'?'Một cột':'Hai cột'; ?></td>
                                <td class="action"><a href="?page=rms-manage-form-order&shortcode_id=<?php echo $item->id ?>">Sửa</a> |
                                    <a style="color: red;" class="order_edit" data-id="<?php echo $item->id ?>" href="javascript:void(0)">Xóa</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="guide" style="padding-top: 10px">
                        <tr valign="top">
                            <h3>Cách dùng:</h3>
                            <td>
                                <ul style="line-height: 30px">
                                    <li>
                                        <strong>Bước 1:</strong> Nhập thông tin ở form "Tùy chỉnh mã nhúng" phía trên để tạo ra mã nhúng.<br>
                                    </li>
                                    <li>
                                        <strong>Bước 2:</strong> Sau khi đã hoàn tất bước 1 ,lấy mã nhúng ở form "Danh sách mã nhúng" đặt vào nội dung trang web bạn muốn người dùng mua hàng vào hệ thống RMS.
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}