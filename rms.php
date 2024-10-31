<?php
/*
Plugin Name: RMS interaction
Plugin URI: https://rms.com.vn
Description:
Version: 4.1.3.0
Author: Referral Marketing Solution (RMS)
*/

require_once "loader.php";

use \RMS\Boot\BackEnd;
use \RMS\Boot\FrontEnd;
use \RMS\Ctrs\OrderCtr;
use \RMS\Ctrs\UserCtr;
use \RMS\Ctrs\MainCtr;
use \RMS\Ctrs\BuilderCtr;
use \RMS\Ctrs\EmailCtr;
use \RMS\Ctrs\ProductCtr;
use \RMS\Ctrs\GetResponceCtr;
use \RMS\Ctrs\ShareLinkCtr;
use \RMS\Boot\RMSHook;
use \RMS\Boot\RMSMetaBox;
use RMS\Boot\ShortCode;
use \RMS\Ctrs\NotificationAffCtr;

if(!class_exists('rmsApp')) {
    class rmsApp {
        function __construct() {

            date_default_timezone_set("Asia/Ho_Chi_Minh");
            register_activation_hook (__FILE__, array ($this, 'plugin_activate'));
            register_deactivation_hook( __FILE__, array ($this,'plugin_deactivate'));

            new RMSHook();

            new BackEnd();
            new FrontEnd();
            new RMSMetaBox();
            new ShortCode();

            new MainCtr();
            new UserCtr();
            new OrderCtr();
            new ProductCtr();
            new GetResponceCtr();
            new ShareLinkCtr();
            new BuilderCtr();
            new EmailCtr();
            new NotificationAffCtr();
        }

        function plugin_activate(){
            global $wpdb;

            $order_table_name = $wpdb->prefix . 'rms_order_form';
            $table_name_link = $wpdb->prefix . 'rms_order_form_log';
            $table_email_setting = $wpdb->prefix . 'rms_email_setting';
            $table_Notification_aff = $wpdb->prefix . 'rms_notification_aff_setting';
            $table_InfisionSoft = $wpdb->prefix . 'rms_infusionsoft';

            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $order_table_name (
              id int(11) NOT NULL AUTO_INCREMENT,
              product_description  varchar(250) DEFAULT '',
              product  varchar(250) DEFAULT '' NOT NULL,
              product_id varchar(250) DEFAULT '',
              shortcode  varchar(50) DEFAULT '' NOT NULL,
              redirect varchar(250) DEFAULT '' NOT NULL,
              commission varchar(50) DEFAULT '' NOT NULL,
              infusion_tags varchar(500),
              show_ varchar(50) DEFAULT '',
              require_ varchar(50) DEFAULT '',
              meta_data text, 
              price int(11) NOT NULL,
              saleprice int(11) NOT NULL,
              success text NOT NULL,
              style varchar(50) DEFAULT 'default' NOT NULL,
              submit_btn varchar(50) DEFAULT '' NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            $sql_link = "CREATE TABLE IF NOT EXISTS $table_name_link (
              id int(11) NOT NULL AUTO_INCREMENT,
              link  varchar(250) DEFAULT '' NOT NULL,
              shortcode  varchar(50) DEFAULT '' NOT NULL,
              lastdate   varchar(50) DEFAULT '' NOT NULL ,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            $sql_email_setting = "CREATE TABLE IF NOT EXISTS $table_email_setting (
              id int(11) NOT NULL AUTO_INCREMENT,
              subject varchar(500) DEFAULT '' NOT NULL,
              content text DEFAULT '' NOT NULL,
              attachments text DEFAULT '' NOT NULL,
              headers varchar(500) DEFAULT '' NOT NULL ,
              type varchar(50) DEFAULT '' NOT NULL ,
              allow varchar(50) DEFAULT '0' NOT NULL ,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            $sql_Notification_aff = "CREATE TABLE IF NOT EXISTS $table_Notification_aff(
              id int(11) NOT NULL AUTO_INCREMENT,
              content_success text DEFAULT '' NOT NULL,
              allow varchar(50) DEFAULT '0' NOT NULL ,
              PRIMARY KEY  (id)
            )$charset_collate;";

            $sql_InfusionSoft ="CREATE TABLE IF NOT EXISTS $table_InfisionSoft(
              infusion_id varchar(50) DEFAULT '' NOT NULL,
              infusion_name text DEFAULT '' NOT NULL,
              PRIMARY KEY  (infusion_id)
            )$charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            dbDelta( $sql_link);
            dbDelta( $sql_email_setting);
            dbDelta( $sql_Notification_aff);
            dbDelta( $sql_InfusionSoft);
        }
        function plugin_deactivate() {

        }
    }
}

new rmsApp();