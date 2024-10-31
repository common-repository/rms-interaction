jQuery(function($){
    $("#setting_getresponce").submit(function(event){
       location.reload(false);
       var regex = /^[a-zA-Z0-9.\-_$@*!]{5}$/;
       var camp=$('#rms_campaign_id').val();
       var check = regex.test(camp) ? 'true' : 'false' ;
       if(check == 'false') {
        event.preventDefault();
        alert ('Mã chiến dịch là chuỗi có 5 ký tự ! Không có ký tự đặc biệt');
       }
       else  {
        var datacamp=$('#setting_getresponce').serializeObject();
        jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                dataType: 'json',
                method: "POST",
                data:
                    {
                        action: 'rms_setting_getresponce',
                        data: datacamp
                    },
                success: function(result){
                    event.preventDefault();
                    location.reload(false);
                    alert ('Cài đặt mã chiến dịch cho GetResponce thành công !');
                }
        });
       }
       
      event.preventDefault();
      var dataconnection = $("#setting_getresponce").serializeObject();
      jQuery.ajax({
          url: '/wp-admin/options.php',
          method: "POST",
          data: dataconnection

      });
    });

});