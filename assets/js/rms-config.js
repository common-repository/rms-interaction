/**
 * Created by thanhphuc on 8/18/17.
 */
jQuery(function($){
    $("#check_connection_rms").submit(function(event){
        event.preventDefault();
        var dataconnection = $("#check_connection_rms").serializeObject();
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            dataType: 'json',
            method: "POST",
            data:
                {
                    action: 'rms_connect',
                    data: dataconnection
                },
            success: function(result){
                if(result){
                    alert(result.message)
                }
                else
                    alert('Kết nối máy chủ thất bại, Vui lòng thử lại!');
            }
        });

        jQuery.ajax({
            url: '/wp-admin/options.php',
            method: "POST",
            data: dataconnection
        });
    });

    $("#rms_sync_all").click(function(event){
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            dataType: 'json',
            method: "POST",
            data:
                {
                    action: 'rms_synchronize_all',
                    data: {}
                },
            success: function(result){
                if(result){
                    alert(result.message);
                }
                else
                    alert('Kết nối máy chủ thất bại, Vui lòng thử lại!');
            }
        });
    });
});