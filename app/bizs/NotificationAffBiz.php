<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 1/8/19
 * Time: 10:29 AM
 */

namespace RMS\Bizs;


class NotificationAffBiz
{
    function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'rms_notification_aff_setting';
        $this->db =& $wpdb;
    }

    function save_setting($item)
    {
        $this->db->replace($this->table_name, $item);

        if ($this->db->insert_id) {
            $item['id'] = $this->db->insert_id;
            $this->db->replace($this->table_name, $item);
        }
    }

    function get_notification_aff()
    {
        $sql = 'SELECT * FROM ' . $this->table_name . ' ORDER BY id DESC LIMIT 0,1  ';
        $result = $this->db->get_row($sql);

        return $result?$result: (Object) array(
            "allow" => false,
            "content_success" =>""
        );
    }
}