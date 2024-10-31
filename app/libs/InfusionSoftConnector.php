<?php
/**
 * Created by PhpStorm.
 * User: thanhphuc
 * Date: 1/18/19
 * Time: 10:49 AM
 */

class InfusionsoftConnector
{
    protected $accessKey;
    function __construct($accessKey=''){
        if($accessKey=='')
            return;
        $this->accessKey = $accessKey;
    }

    public function sendRequest($url,$method,$body){
        try{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.infusionsoft.com/crm/rest/v1/".$url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ".$this->accessKey,
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return array('success' => false, 'error' => $err);
            } else {
                if($this->check_DeveloperInactive($response)){
                    return array('success' => 1, 'response' => $response);
                }
                return array(
                    'success' => 2,
                    'error' => "Lost access key!"
                );
            }
        }
        catch(Exception $e){
            return false;
        }
    }
    function check_DeveloperInactive($tags){
        if(empty($tags)||$tags=''||$tags='<h1>Not Authorized</h1>')
            return true;
        return strpos($tags, 'Inactive');
    }

}