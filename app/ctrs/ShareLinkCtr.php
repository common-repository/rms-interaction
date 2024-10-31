<?php

namespace RMS\Ctrs;


class ShareLinkCtr
{
	function __construct()
    {
        add_action( 'wp_ajax_rms_config_sharelink', array(&$this, 'update_setting_sharelink')  );
        add_action( 'wp_ajax_nopriv_rms_config_sharelink', array(&$this, 'update_setting_sharelink') );
    }

    function update_setting_sharelink(){
    	$data = $_POST['data'];
    	update_option('rms_config_sharelink',$data['rms_config_sharelink']);
    }
}