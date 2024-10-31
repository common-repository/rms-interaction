<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 7/11/17
 * Time: 9:33 AM
 */

namespace RMS\Bizs;


class ShortcodeLogBiz
{
    function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'rms_order_form_log';
        $this->db =& $wpdb;
    }

    function delete($id){
        if(is_int($id)){
            $sql = 'DELETE FROM '.$this->table_name. ' WHERE id = '.$id;
            $this->db->query($sql);
            return true;
        }
        return false;
    }

    function get_shortcode($id){
        $sql_link = 'SELECT * FROM '.$this->table_name. ' WHERE id = '.$id;
        $result = $this->db->get_row($sql_link);

        return $result;

    }

    function get_shortcodes(){
        $sql_link = 'SELECT * FROM ' . $this->table_name;
        $result = $this->db->get_results($sql_link);

        return $result;
    }
}