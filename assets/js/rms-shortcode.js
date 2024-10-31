                    /**
 * Created by thanhphuc on 7/11/17.
 */

"use strict"

var RmsShortcode={};
(
    function (e,$){

        e.init = function () {
            e.form_shortcode = $('#form_shortcode');
            e.shortcode_id = $('#shortcode_id');
            e.link = $('.link');
            e.lastdate = $('.lastdate');
            e.link_edits = $('.link_edit');

        };

        e.bind_events = function () {
            e.link_edits.click(function(){
                if(confirm('Bạn có chắc chắn muốn xóa dữ liệu này?')){
                    RmsShortcode.delete(($(this).data('id')));
                }
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

        /**
         * Delete Shortcode
         * @param id
         */

        e.delete = function (id) {
            e.ajax({
                action: 'link_shortcode_delete',
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

        $(function(){
            RmsShortcode.init();
            RmsShortcode.bind_events();
        })
    }
)(RmsShortcode,jQuery);
