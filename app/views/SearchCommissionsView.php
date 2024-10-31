<?php

namespace RMS\Views;

class SearchCommissionsView
{

    static function content(){

        ob_start();
        echo SearchCommissionsView::main_view();
        return ob_get_clean();
    }

    static function input_view(){
        $input = '<input required id="rms-search-key" class="" type="text" name="meta_value" placeholder="Ví dụ: Hệ thống hỗ trợ khởi nghiệp" value="" style="width:70%">';
        $button = '<input id="rms-search-commission" type="button" value="Tìm kiếm"  class="btn show-commission-modal" style="background:#4e963f; color:white; margin-left: 15px;">';
        $html = '<div class="rm-form-group">'.$input.$button.'</div><div class="rm-form-group" id="result_search"></div>';
        return $html;
    }
}

