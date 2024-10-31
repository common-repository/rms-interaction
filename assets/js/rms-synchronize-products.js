    jQuery(function($){
        $("#setting_order_rms").submit(function(event){
            event.preventDefault();
            var dataconnection = $("#setting_order_rms").serializeObject();
            if(dataconnection.is_create=='created')
            jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                dataType: 'json',
                method: "POST",
                data:
                    {
                        action: 'rms_synchronize',
                        data: dataconnection
                    },
                success: function(result){

                    if(result.success==true) {
                        $("#product_id").val(result.product_id);
                        $("#is_create").val("updated");
                        $("#shortcode_id").val(result.current_id);
                        $("#setting_order_rms").submit();
                    }else {
                        alert(result.message)
                    } 
                }
            });
        });

        $("#setting_order_rms").submit(function(event){
            event.preventDefault();
            var dataconnection = $("#setting_order_rms").serializeObject();
            if(dataconnection.is_create=='created')
            jQuery.ajax({
                url: '/wp-admin/options.php',
                method: "POST",
                data: dataconnection

            });
        });

    });