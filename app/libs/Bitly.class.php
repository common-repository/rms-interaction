<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 10/23/18
 * Time: 11:08 AM
 */

class Bitly_RMS
{
    public $extended;
    private $target;
    private $apiKey;
    private $ch;

    private static $buffer = array();
    function __construct($apiKey = null) {
        # Extended output mode
        $extended = false;
        # Set Bitly Shortener API target
        $this->target = 'https://api-ssl.bitly.com/v3/shorten?';
        # Set API key if available
        if ( $apiKey != null ) {
            $this->apiKey = $apiKey;
            $this->target .= 'access_token='.$apiKey.'&';
        }
        # Initialize cURL
        $this->ch = curl_init();
        # Set our default target URL
        curl_setopt($this->ch, CURLOPT_URL, $this->target);
        # We don't want the return data to be directly outputted, so set RETURNTRANSFER to true
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }
    public function shorten($url, $extended = false) {
        $full_link=$this->target."longUrl=".rawurlencode($url);
        curl_setopt_array($this->ch, array(
            CURLOPT_URL => $full_link,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(),
        ));

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        if ($err) {
            return $full_link;
        } else {
            $response = json_decode($response);
            if($response->status_code==200){
                return $response->data->url;
            }
            return $full_link;
        }
    }
    public function expand($url, $extended = false) {
        # Set cURL options
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_URL, $this->target.'shortUrl='.$url);

        if ( $extended || $this->extended ) {
            return json_decode(curl_exec($this->ch));
        } else {
            return json_decode(curl_exec($this->ch))->longUrl;
        }
    }
    function __destruct() {
        # Close the curl handle
        curl_close($this->ch);
        # Nulling the curl handle
        $this->ch = null;
    }
}