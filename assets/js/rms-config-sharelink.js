jQuery(function($){
    $("#rms-config-sharelink-form").submit(function(event){
       location.reload(false);
       var regex = /^[a-z.\-_$@*!]{1,20}$/;
       var key=$('#rms_config_sharelink').val();
       var check = regex.test(key) ? 'true' : 'false' ;
       if(check == 'false') {
        event.preventDefault();
        alert ('Từ khóa nhận biết thông tin CTV là chữ thường không dấu, không khoảng trắng và ký tự đặt biệt và phải có ít nhất 1 ký tự');
       }
       else  {
        var dataconnection=$('#rms-config-sharelink-form').serializeObject();
        jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                dataType: 'json',
                method: "POST",
                data:
                    {
                        action: 'rms_config_sharelink',
                        data: dataconnection
                    },
                success: function(result){
                    event.preventDefault();
                    alert ('Cài đặt từ khóa nhận biết thông tin CTV thành công !');
                }
        });
       }
       
      event.preventDefault();
      var dataconnection = $("#rms-config-sharelink-form").serializeObject();
      jQuery.ajax({
          url: '/wp-admin/options.php',
          method: "POST",
          data: dataconnection

      });
    });

});