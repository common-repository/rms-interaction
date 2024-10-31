/**
 * Created by phucn on 1/17/2017.
 */

jQuery(function($) {
    $('.form_price_rms').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        if(form.find('[name=fullname]').val().trim() == ''){
            alert('Vui lòng nhập họ và tên.');
            return;
        }
        if(form.find('[name=email]').val().trim() == ''){
            alert('Vui lòng nhập Email.');
            return;
        }

        var dataprice = $(form).serializeObject();
        $(form).find('input').each(function(i,e){
            $(e).attr('disabled','disabled');
        });
        $(form).find(".order_btn_rms").val('Đang mua hàng...');
        var goto = $(form).find(".goto").val();

        jQuery.ajax({
            url: ajax_url,
            dataType: "json",
            method: "POST",
            data: {
                action: 'rms_simple_order',
                data: dataprice
            },
            success: function(result){
                if(result){
                    if(result.success){
                        event.preventDefault();
                        if($(form).find(".notification_popup").html().trim()){
                            var popup = '<div class="background-popuptks" style="background: rgba(0, 0, 0, 0.14);">'+
                                '<div class="content-notification">'+
                                '<div class="close-popup"><span class="pull-right btn-close">X</span></div>'+
                                '<div class="content-iframe" style="padding: 25px"><div class="content-success">'+$(form).find(".notification_popup").html()+'</div>'+
                                '</div></div></div>';
                            $( "body" ).append(popup);
                            $(".background-popuptks").click(function(event){
                                var target = $( event.target );
                                if(!target.is('.content-notification') && !target.is('.content-notification *')){
                                    $(".background-popuptks").remove();
                                    
                                    if(goto == ''){
                                        location.reload();
                                    }else{
                                        window.location= goto;
                                    }
                                }

                            });
                            $(".btn-close").click(function (event) {
                                $(".background-popuptks").hide();
                                if(goto == ''){
                                    location.reload();
                                }else{
                                    window.location= goto;
                                }
                            })
                        }
                        else{
                            alert("Đã mua hàng thành công!")
                            if(goto == ''){
                                location.reload();
                            }else{
                                window.location= goto;
                            }
                        }
                    }
                    else{
                        alert(result.message);
                    }
                }
                else
                    alert('Kết nối máy chủ thất bại, Vui lòng thử lại!');
                $(form).find('input').each(function(i,e){
                    $(e).removeAttr('disabled');
                });
                $(form).find('.order_btn_rms').val('Mua hàng');
            }
        });
    });

    $(document).on('click','.show-commission-modal',function(){ 
        var id = $(this).attr('data-modalid');
        $('#commission-modal-'+id).css('display','block');
    });
    $(document).on('click','.commission-modal-close',function(){ 
        $('.commission-modal').css('display','none');
    });
    $(document).click(function( event ) {
        if ($(event.target).is('.commission-modal') ) {
            $('.commission-modal').css('display','none');
        }
    });
    $(document).on('click','.btn-share-now',function(){ 
        $('.commission-modal').css('display','none');
        $('#show_share_referral').click();
    });
});


