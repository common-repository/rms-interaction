"use strict";

var RMSNotificationAffForm = {};
(
    function (e,$) {
        e.init = function () {
            e.setting_notification_aff_rms = $('#rms-notification-aff-setting');
            e.submit = $('#setting_submit_btn');
            e.success = $('#success');
            e.style = $('#style');
        };

        e.bind_events = function () {
            e.setting_notification_aff_rms.submit(function (event) {
                event.preventDefault();
                RMSNotificationAffForm.saveForm();
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

            postData = e.setting_notification_aff_rms.serializeObject();
            postData['content_success'] = postData["content_success"].replace(/\\/gi,"");
            postData.content_success = tinymce.get('content_success').getContent();
            e.ajax({
                action: 'notification_aff_save_rms',
                data: postData
            },function (result) {
                if (result) {
                    if (result.success) {
                        alert('Cập nhật thành công');
                        location.href = '/wp-admin/admin.php?page=rms-notification-aff';
                    }
                    else {
                        alert(result.message);
                    }
                }
                else
                    alert('Hệ thống lỗi!');
            });
        };


        $(function(){
            RMSNotificationAffForm.init();
            RMSNotificationAffForm.bind_events();
        })
    }
)(RMSNotificationAffForm, jQuery);
