<?php
/**
 * Created by PhpStorm.
 * User: mrrms
 * Date: 10/12/16
 * Time: 1:49 PM
 */

namespace RMS\Boot;
use RMS\Views\BitlyShortLinkView;
use RMS\Views\ConfigView;
use RMS\Views\CustomCssView;
use RMS\Views\GetResponceSettingView;
use RMS\Views\LinkShortcodeView;
use RMS\Views\NotificationAffSuccessView;
use RMS\Views\ShareLinkConfigView;
use RMS\Views\ManageColorView;
use RMS\Views\ManageFormOrderView;
use RMS\Views\NoteView;
use RMS\Bizs\ShortcodeLogBiz;
use RMS\Views\EditEmailView;


class BackEnd
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action( 'admin_init', array($this, 'admin_init_hooking'));
        add_action( 'admin_enqueue_scripts', array($this, 'admin_script') );

    }

    function add_menu() {
        add_menu_page('RMS Setting', 'RMS', 'administrator', 'rms-config',array($this, 'rms_settings'), '',80);

        add_submenu_page( 'rms-config', 'Cài đặt kết nối', 'Kết nối', 'administrator', 'rms-config',array(&$this, 'rms_settings'));
        add_submenu_page( 'rms-config', 'Cài đặt kết nối GetResponce', 'Kết nối GetResponce', 'administrator', 'rms-config-getresponce',array(&$this, 'rms_config_getresponce'));
        add_submenu_page( 'rms-config', 'Cài đặt thông tin share link CTV', 'Cấu hình share link', 'administrator', 'rms-config-sharelink',array(&$this, 'rms_config_sharelink'));
        add_submenu_page( 'rms-config', 'Ghí chú', 'Ghi chú', 'administrator', 'rms-info',array(&$this, 'settings_page_info'));
        add_submenu_page( 'rms-config', 'Bitly shortlink', 'Bitly Shortlink', 'administrator', 'rms-bitly_shortlink',array(&$this, 'settings_page_bitly_shortlink'));
        add_submenu_page( 'rms-config', 'Form mua hàng', 'Form mua hàng', 'administrator', 'rms-manage-form-order',array(&$this, 'settings_page_manage_form_order'));
        add_submenu_page( 'rms-config', 'Email thông báo', 'Email thông báo', 'administrator', 'rms-edit-email',array(&$this, 'settings_page_edit_email'));
        add_submenu_page( 'rms-config', 'Thông báo CTV', 'Thông báo CTV', 'administrator', 'rms-notification-aff',array(&$this, 'settings_page_notification_aff'));
        add_submenu_page( 'rms-config', 'Phong cách', 'Màu sắc', 'administrator', 'color-setting-RM',array(&$this, 'settings_page_color'));
        add_submenu_page( 'rms-config', 'Mã nhúng', 'Mã nhúng', 'administrator', 'rms-manage-link-shortcode',array(&$this, 'rms_order_form_log'));
        add_submenu_page( 'rms-config', 'Tùy chỉnh css', 'Tùy chỉnh CSS', 'administrator', 'rms-custom-css',array(&$this, 'setting_custom_css'));

    }

    function rms_settings(){
        ConfigView::content();
    }

    function admin_script(){
        wp_enqueue_style( 'form-manage-style', RMS_URL . '/assets/css/admin-form-manage.css', true );
        wp_enqueue_style( 'chosen.min', RMS_URL . '/assets/css/chosen.min.css', true );

        wp_enqueue_script( 'rms-synchronize-products', RMS_URL . '/assets/js/rms-synchronize-products.js', true );
        wp_enqueue_script( 'rms-order-form', RMS_URL . '/assets/js/rms-order-form.js' );
        wp_enqueue_script('rms-setting-color', RMS_URL . '/assets/js/rms-setting-color.js' );
        wp_enqueue_script('rms-shortcode', RMS_URL . '/assets/js/rms-shortcode.js' );
        wp_enqueue_script( 'rms-lib', RMS_URL . '/assets/js/rms.js', false );
        wp_enqueue_script( 'rms-config', RMS_URL . '/assets/js/rms-config.js', true );
        wp_enqueue_script( 'rms-tags', RMS_URL . '/assets/js/rms-tags.js', true );
        wp_enqueue_script( 'rms-getresponce', RMS_URL . '/assets/js/rms-setting-getresponce.js', true );
        wp_enqueue_script( 'rms-config-sharelink', RMS_URL . '/assets/js/rms-config-sharelink.js', true );
        wp_enqueue_script( 'rms-email', RMS_URL . '/assets/js/rms-email.js', true );
        wp_enqueue_script( 'rms-notification-aff', RMS_URL . '/assets/js/rms-notification-aff.js', true );
        wp_enqueue_script( 'rms-chosen', RMS_URL . '/assets/js/chosen.jquery.min.js', true );
    }

    function settings_page_info(){
        return NoteView::content();

    }

    function rms_config_getresponce(){
        return GetResponceSettingView::content();
    }

    function rms_config_sharelink(){
        return ShareLinkConfigView::content();
    }

    function settings_page_manage_form_order(){
        return ManageFormOrderView::content();
    }
    function settings_page_bitly_shortlink(){
        return BitlyShortLinkView::content();
    }

    function settings_page_color(){
        return ManageColorView::content();
    }

    function rms_order_form_log(){
        $biz = new ShortcodeLogBiz();
        $items = $biz->get_shortcodes();
        $log = $_GET['shortcode_id']?$biz->get_shortcode($_GET['shortcode_id']):null;

        return LinkShortcodeView::content($items,$log);
    }

    function setting_custom_css(){
        return CustomCssView::content();
    }

    function settings_page_edit_email(){
        return EditEmailView::content();
    }

    function settings_page_notification_aff(){
        return NotificationAffSuccessView::content();
    }


    function admin_init_hooking() {
        register_setting( 'rms-setting-connect', 'rms_username' );
        register_setting( 'rms-setting-connect', 'rms_password' );
        register_setting( 'rms-setting-connect', 'rms_timeout' );
        register_setting( 'rms-setting-token', 'rms_token' );
        register_setting( 'rms-setting-token', 'rms_channel');
        register_setting( 'rms-setting-color', 'rms_color' );
        register_setting( 'rms-setting-css', 'rms_css' );
        register_setting('rms-setting-bitly', 'rms_bitly');
        register_setting( 'rms-setting-note', 'rms_note');
        register_setting( 'rms-setting-connect', 'rms_option_type_share');
        register_setting( 'rms-setting-connect', 'rms_option_disabled_register');
    }


}