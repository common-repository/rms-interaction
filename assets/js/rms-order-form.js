/**
 * Created by phucn on 4/12/2017.
 */
"use strict";

var RmsOrderForm={};
(
    function (e,$){

        e.init = function () {
            e.setting_order_rms = $('#setting_order_rms');
            e.submit = $('#setting_submit_btn');
            e.shortcode_id = $('#shortcode_id');
            e.price = $('#price');
            e.saleprice = $('#saleprice');
            e.commission = $('#commission');
            e.infusion_tags = $('#infusion_tags');
            e.redirect = $('#redirect');
            e.product = $('#product');
            e.product_code = $('#product_code');
            e.product_description = $('#product_description');
            e.success = $('#success');
            e.style = $('#style');
            e.order_edits = $('.order_edit');

        };

        e.bind_events = function () {
            e.setting_order_rms.submit(function (event) {
                event.preventDefault();
                RmsOrderForm.saveForm();
            });

            $('input.input_field').change(function (e) {
                RmsOrderForm.select_fields();
            });

            e.order_edits.click(function(){
                if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này?')){
                    RmsOrderForm.delete(($(this).data('id')));
                }
            });

            e.style.change(function () {
                var classCss = 'rms-order' + e.style.val();
                var $frm = e.$(".rms-order");
                $frm.attr('class', '');
                $frm.addClass('rms-order').addClass(classCss)
            });

        };

        e.ajax = function (data,call) {
            $.ajax({
                url: ajax_url,
                dataType: 'json',
                method: 'POST',
                data: data,
                success: function (result) {
                    call(result);
                }
            });
        };

        e.saveForm = function () {

            var postData = new Object();

            var fields = e.validate(this);

            if(fields===false)
                return;


            postData = e.setting_order_rms.serializeObject();
            postData['success'] = postData["success"].replace(/\\/gi,"");
            postData.success = tinymce.get('success').getContent();
            if((postData.is_create=='updated')||(postData.is_create=='create')||(postData.is_create=='update'))
            e.ajax({
                action: 'order_builder_save_rms',
                data: postData
            },function (result) {
                if (result) {
                    if (result.success) {
                        if(postData.is_create=='create'){
                            $("#is_create").val('created');
                            $("#setting_order_rms").submit();
                        }
                        else
                        {
                            if(postData.is_create=='updated'){
                               $("#is_create").val('update');
                               $("#setting_order_rms").submit();
                            }
                            else
                            {
                                alert('Cập nhật thành công');
                                location.href = '/wp-admin/admin.php?page=rms-manage-form-order';
                            }
                        }
                    }
                    else {
                        alert(result.message);
                    }
                }
                else
                    alert('Error system!');
            });
        };

        /**
         * Delete Shortcode
         * @param id
         */

        e.delete = function (id) {
            e.ajax({
                action: 'order_builder_delete_rms',
                id: id
            },function (result) {
                if(result){
                    if(result.success == false){
                        alert("Can't delete this item!");
                    }else
                        location.reload();
                }else
                    alert('System Error!');
            });
        };

        e.validate = function (frm) {
            var valid = true, fields = [];

            $('.input_field').each(function (i, o) {
                var item = $(o ).data();
                item.checked = $(o).is(':checked');
                if (!item.checked) {
                    var $field = $('#value_' + item.name);
                    if ($field!= null && $field.val()!=null && $field.val().length > 0)
                        item.value = $('#value_' + item.name).val();

                    if (item.mandatory && !item.hasOwnProperty('value')) {
                        valid = "Please check your field '" + item.label + "', It is required!";
                        return;
                    }
                }
                if (item.checked || item.hasOwnProperty('value'))
                    fields.push(item);

            });

            if (valid !== true) {
                alert(valid);
                return false;
            }
            else
                return fields;
        };

        $(function(){
            
            $('#show_email').change(function () {
                if(($('#show_email').is(':checked'))===false){
                   $('#show_phonenumber').prop('checked', true); 
                   $('#required_phonenumber').prop('checked', true);
                } 
            });
            $('#show_phonenumber').change(function () {
                if(($('#show_phonenumber').is(':checked'))===false){
                   $('#show_email').prop('checked', true); 
                   $('#required_email').prop('checked', true);
                } 
            });
            $('#required_email').change(function () {
                if(($('#required_email').is(':checked'))===false){
                   $('#show_phonenumber').prop('checked', true); 
                   $('#required_phonenumber').prop('checked', true);
                } 
            });
            $('#required_phonenumber').change(function () {
                if(($('#required_phonenumber').is(':checked'))===false){
                   $('#show_email').prop('checked', true); 
                   $('#required_email').prop('checked', true);
                } 
            });

            $('#show_address').change(function () {
                if(($('#show_address').is(':checked'))===false){
                   $('#required_address').prop('checked', false);
                } 
            });

            $('#required_address').change(function () {
                if(($('#required_address').is(':checked'))===true){
                   $('#show_address').prop('checked', true);
                } 
            });

            $('#show_number').change(function () {
                if(($('#show_number').is(':checked'))===false){
                   $('#required_number').prop('checked', false);
                } 
            });

            $('#required_number').change(function () {
                if(($('#required_number').is(':checked'))===true){
                   $('#show_number').prop('checked', true);
                } 
            });

            $('#show_discount').change(function () {
                if(($('#show_discount').is(':checked'))===false){
                   $('#required_discount').prop('checked', false);
                } 
            });

            $('#required_discount').change(function () {
                if(($('#required_discount').is(':checked'))===true){
                   $('#show_discount').prop('checked', true);
                } 
            });

            var max_inputs = 100;
            var inputs_meta = $("#inputs_meta");
            var x = inputs_meta.length;
            var index = $("#index").val();
            var index_row = 0;
            var button_add = $("#meta_add");
            if (index==0) index_row=7; else index_row=parseInt(index)+7;
            $(button_add).click(function()
            {
               
                    if (x <= max_inputs)
                    {    
                        $(inputs_meta).append('<tr class="inputs-meta" data-row="row-'+x+'">'+
                                                    '<td rowspan="2" style="text-align: center;" >'+index_row+'</td>'+
                                                    '<td class="typeform" style="vertical-align: top;border-right-color: #FFFFFF;border-bottom-color: #FFFFFF;">'+
                                                        '<input type="text" name="meta_name" placeholder="Thông tin cần được cung cấp" >'+ 
                                                    '</td>'+
                                                    '<td style="vertical-align: top;border-bottom-color: #FFFFFF;">'+
                                                        '<select class="meta_option" name="meta_type" style="width: 200px;">'+
                                                            '<option value="meta_textbox">'+
                                                                'Văn bản'+
                                                            '</option>'+
                                                            '<option value="meta_number">'+
                                                                'Số'+
                                                            '</option>'+
                                                            '<option value="meta_textarea">'+
                                                                'Văn bản dài'+
                                                            '</option>'+
                                                            '<option value="meta_select">'+
                                                                'Danh Sách'+
                                                            '</option>'+
                                                            '<option value="meta_checkbox">'+
                                                                'Nhiều lựa chọn'+
                                                            '</option>'+
                                                            '<option value="meta_radio">'+
                                                                'Lựa chọn duy nhất'+
                                                            '</option>'+
                                                        '</select>'+
                                                    '</td>'+
                                                    '<td rowspan="2" >'+ 
                                                        'Hiển thị '+
                                                        '<input type="checkbox" id="meta_show_'+index+'" name="meta_show_'+index+'" checked style="max-height:  20px; min-width: 10px;">'+
                                                        ' Bắt buộc nhập '+
                                                        '<input type="checkbox" id="meta_required_'+index+'" name="meta_required_'+index+'" style="max-height:  20px; min-width: 10px;">'+
                                                    '</td>'+
                                                    '<td style="vertical-align: center;" rowspan="2">'+
                                                        '<button class="removeclass" type="button"> <img src="../wp-content/plugins/rms-interaction/assets/images/remove.png"> </button>'+
                                                    '</td>'+
                                                    '</tr>'+
                                                    '<tr class="inputs-meta_1 row-'+x+'"><td class="inputs-meta-tag" colspan="2">'+
                                                        '<input type="text" style="width: 455px;" placeholder="Dữ liệu mặc định" value="" name="meta_value">'+
                                                    '</td>'+
                                                '</tr>'
                        ); 
                        
                        $('.meta_option').on('change', function() {
                            var elm = $($(this).parents('.inputs-meta').get(0)).attr('data-row');
                            elm = '.'+elm;
                            if((($(this).val()=="meta_radio")||($(this).val()=="meta_checkbox"))||($(this).val()=="meta_select"))
                            {
                                $(elm).find('.inputs-meta-tag').html('<input class="tags"  type="text" name="meta_value">');
                            }
                            else
                            {
                                $(elm).find('.inputs-meta-tag').html('<input type="text" style="width: 455px;" placeholder="Dữ liệu mặc định" value="" name="meta_value">');
                            }

                            $('.tags').tagsInputOption();
                        });
                        x++;
                        var meta_show='#meta_show_'+index;
                        var meta_required='#meta_required_'+index;
                        $(meta_show).change(function () {
                            if(($(meta_show).is(':checked'))===false){
                               $(meta_required).prop('checked', false);
                            } 
                        });

                        $(meta_required).change(function () {
                            if(($(meta_required).is(':checked'))===true){
                               $(meta_show).prop('checked', true);
                            } 
                        });
                        index++;
                        index_row++;
                    }          
                    return false;

            });
            $('body').on('click','.removeclass',function(){   //to remove name field 
                var elm = $($(this).parents('.inputs-meta').get(0)).attr('data-row');
                elm = '.'+elm;  
                $(elm).remove();
                $($(this).parents('tr.inputs-meta').get(0)).remove();
                x--; 
            });
            var i=0;
            var max_row=index;
            for(i=0;i<max_row;i++){
                var meta_show='#meta_show_'+i;
                var meta_required='#meta_required_'+i;
                $(meta_show).change(function () {
                    if(($(meta_show).is(':checked'))===false){
                       $(meta_required).prop('checked', false);
                    } 
                });

                $(meta_required).change(function () {
                    if(($(meta_required).is(':checked'))===true){
                       $(meta_show).prop('checked', true);
                    } 
                });
            }

            $('#infusion_tags').tagsInput();
            RmsOrderForm.init();
            RmsOrderForm.bind_events();
        });
    }
)(RmsOrderForm,jQuery);
