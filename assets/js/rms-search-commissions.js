jQuery(function($){
    $(document).on('click','#rms-search-commission', function(event) {
        event.preventDefault();
        if($('#rms-search-key').val() == ''){
            alert('Vui lòng nhập tên sản phẩm.');
            return;
        }
        $(this).val('Đang tìm...');
        var rms_search_key = $('#rms-search-key').val();
        jQuery.ajax({
            url: ajax_url,
            dataType: 'json',
            method: "POST",
            data:
                {
                    action: 'rms_search_commissions_eanring',
                    search_key: rms_search_key
                },
            success: function(result){
                if(result){
                    if(result.success){
                        show_search_result(result.message);
                    }
                    else{
                        $('#result_search').html('Không có dữ liệu phù hợp.');
                    }
                }
                else
                    alert('Kết nối máy chủ thất bại, Vui lòng thử lại!');
                $('#rms-search-commission').val('Tìm kiếm');
            }
        });
    });

    function show_search_result(data){
        if(data.length){
            var html = '';
            var formatter = new Intl.NumberFormat('vi-VI', {
              style: 'currency',
              currency: 'VND',
              minimumFractionDigits: 0,
            });
            data.forEach(function(item){
                var total_commission = item.commission.cbs+item.commission.coov+item.commission.copg;
                html += '<div class="rm-form-group" style="border-bottom-width: 1px; border-bottom-color: black; border-bottom-style: dashed;">'
                +'<a href="'+item.link+'" target="_blank">'+item.title+'</a><br>'
                +'Giá bán: '+formatter.format(item.price)+'<br>'
                +'Có thể nhận được: '+formatter.format(total_commission)+'<br>'
                +'</div>';
            });
            $('#result_search').html(html);
        }else{
            $('#result_search').html('Không có dữ liệu phù hợp.');
        }
    }
});