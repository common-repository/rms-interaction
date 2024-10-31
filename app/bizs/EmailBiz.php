<?php


namespace RMS\Bizs;

class EmailBiz
{
    function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'rms_email_setting';
        $this->db =& $wpdb;
        $this->initial_data();
    }

    function save_setting($item){        
        $this->db->replace($this->table_name,$item);

        if($this->db->insert_id){
            $item['id'] = $this->db->insert_id;
            $this->db->replace($this->table_name,$item);
        }
    }

    function get_email_setting($id=1){
        $sql = 'SELECT * FROM '.$this->table_name. ' WHERE id = '.$id;
        $result = $this->db->get_row($sql);

        return $result;
    }

    function default_data(){
        $subject = 'Xác nhận đăng ký mua sản phẩm [rms-product-name]!';
        $content = file_get_contents(dirname(__FILE__).'/../libs/email_template/success_order.html');
        $this->db->insert( 
            $this->table_name, 
            array( 
                'id' => 1,
                'subject' => $subject, 
                'content' => $content, 
                'type' => 'success_order', 
            ) 
        );
    }

    function send_email_order_success($order = null){
        $sql = 'SELECT * FROM '.$this->table_name. ' WHERE id = 1';
        $email = $this->db->get_row($sql);
        $keywork = array('[rms-customer-name]', '[rms-customer-phone]', '[rms-customer-email]', '[rms-product-name]', '[rms-product-price]');
        if(isset($order)&&isset($email)&&$email->allow){
            $customer = $order['customer'];
            $product = $order['order_lines'][0];
            $to = $customer['email'];
            $subject = $email->subject;
            $message = $email->content;
            $fix = array(
                $customer['fullname'],
                $customer['phone'],
                $to,
                $product['product']['name'],
                $product['price']
            );

            $subject = str_replace($keywork, $fix, $subject);
            $message = str_replace($keywork, $fix, $message);
            $headers = array('Content-Type: text/html; charset=UTF-8');
            add_filter('wp_mail_content_type', function( $content_type ) {
                return 'text/html';
            });

            return wp_mail($to , $subject, $message, $headers);
        }
        return;
    }

    function initial_data(){
        if(!$this->get_email_setting())
            $this->default_data();
    }
}