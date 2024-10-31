<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 7/11/17
 * Time: 9:33 AM
 */

namespace RMS\Bizs;


class InfusionSoftTagBiz
{
    function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'rms_infusionsoft';
        $this->db =& $wpdb;
    }

    function get($id){
        $sql_link = 'SELECT * FROM '.$this->table_name. ' WHERE infusion_id = '.$id;
        $result = $this->db->get_row($sql_link);
        return $result;
    }

    function deleteAll(){
        $sql = 'DELETE FROM '.$this->table_name;
        $this->db->query($sql);
        return true;
    }

    function getAll(){
        $sql_link = 'SELECT * FROM ' . $this->table_name;
        $result = $this->db->get_results($sql_link);
        return $result;
    }

    function insertAll($list){
        $values = array();
        foreach ( $list as $key => $value ) {
            $values[] = $this->db->prepare( "(%s,%s)", $key, $value );
        }
        $values = implode( ",\n", $values );
        $query = "INSERT INTO $this->table_name (infusion_id,infusion_name) VALUES {$values} ";

        return $this->db->query($query);
    }

    function get_infusion(){
        $sql_infusion = 'SELECT * FROM ' . $this->table_name;
        $result = $this->db->get_results($sql_infusion);
        return $result;
    }
}