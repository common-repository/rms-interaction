<?php
namespace RMS\Ctrs;
class GetResponceCtr
{
	function __construct()
    {
        add_action( 'wp_ajax_rms_setting_getresponce', array(&$this, 'update_setting_getreponce')  );
        add_action( 'wp_ajax_nopriv_rms_setting_getresponce', array(&$this, 'update_setting_getreponce') );
    }

    function update_setting_getreponce(){
    	$data = $_POST['data'];
    	update_option('rms_campaign_id',$data['rms_campaign_id']);
    }
}