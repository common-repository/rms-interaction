/**
 * Created by phucn on 4/12/2017.
 */
"use strict";

var RmsEmailForm={};
(
    function (e,$){

        e.init = function () {
            e.setting_email_rms = $('#rms-email-setting');
            e.submit = $('#setting_submit_btn');
            e.success = $('#success');
            e.style = $('#style');
        };

        e.bind_events = function () {
            e.setting_email_rms.submit(function (event) {
                event.preventDefault();
                RmsEmailForm.saveForm();
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

            postData = e.setting_email_rms.serializeObject();
            postData['content'] = postData["content"].replace(/\\/gi,"");
            postData.content = tinymce.get('content').getContent();
            e.ajax({
                action: 'email_save_rms',
                data: postData
            },function (result) {
                if (result) {
                    if (result.success) {
                        alert('Cập nhật thành công');
                        location.href = '/wp-admin/admin.php?page=rms-edit-email';
                    }
                    else {
                        alert(result.message);
                    }
                }
                else
                    alert('Error system!');
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
            RmsEmailForm.init();
            RmsEmailForm.bind_events();
        })
    }
)(RmsEmailForm,jQuery);
