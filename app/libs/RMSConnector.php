<?php

class RMSConnector
{
    protected $salepage_url;
    protected $url;
    protected $username;
    protected $password;
    protected $access_token;
    protected $action = array(
        'connect' => '/login',
        'register' => '/v1/affiliates/sign_up',
        'login' => '/v1/affiliates/sign_in',
        'share' => '/v1/shares/stats',
        'order' => '/v1/orders',
        'synchronize' => '/v1/products/import',
        'check_commissions' => '/v1/commissions/check_commissions',
    );

    function __construct($username = '',$password = '')
    {

        $this->url = RMS_API;

        if( !empty($username) && !empty($password)){
            $this->password = $password;
            $this->username = $username;
        }else{
            $this->access_token = get_option('rms_token');
        }
    }

    function post($action, $data = false)
    {

        $http_header = array(
            'Content-type:application/json',
            'charset:UTF-8',
            'X-Security-Token:' . $this->access_token
        );
        $url = $this->url . $action;

        $result = $this->callback($url,$http_header,$data);

        if( $result['code'] == 440){
            $check=  $this->reconnect();
            if($check['success']===true){
                $http_header = array(
                    'Content-type:application/json',
                    'charset:UTF-8',
                    'X-Security-Token:' . $this->access_token
                );
                $url = $this->url . $action;
                $result = $this->callback($url,$http_header,$data);

            }else{

                $result =  array(
                    'success'=> false,
                    'message' =>  $check['message']
                );
                return $result;
            }
        }

        if( $result['code'] == 200){
            $result =  array(
                'success'=> true,
                'data' =>  $result['body']->data
            );

        }else{

            $this->errors_log($url,$data,$result['body']->message,$result['body']->code); 
            $rms_error = load_ini(RMS_ERROR);
            $pars = preg_split( "/[\s,]*'([^']+)'[\s,]*" ."+/", $result['body']->message, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $message_error = sprintf($rms_error[$result['body']->code],$pars[1],$pars[3]);

            $result =  array(
                'success'=> false,
                'error' => $result,
                'message' =>  $message_error
            );
        }

        return $result;
    }

    function callback($url, $http_header,$data){

        try {
            $curl_handle = curl_init();
            $headers = array();

            $header_func = function($curl, $header) use (&$headers)
            {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2)
                    return $len;

                $name = strtolower(trim($header[0]));
                if (!array_key_exists($name, $headers))
                    $headers[$name] = [trim($header[1])];
                else
                    $headers[$name][] = trim($header[1]);

                return $len;
            };

            $options = array(
                CURLOPT_URL            => $url,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER     => $http_header,
                CURLOPT_ENCODING       => "",
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_CONNECTTIMEOUT => 120,
                CURLOPT_TIMEOUT        => 120,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_VERBOSE        => true,
                CURLOPT_HEADERFUNCTION => $header_func,
                CURLOPT_POSTFIELDS     => json_encode($data),
                CURLOPT_DNS_USE_GLOBAL_CACHE => false,
                CURLOPT_DNS_CACHE_TIMEOUT => 2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
            );

            curl_setopt_array( $curl_handle, $options );
            $response = curl_exec($curl_handle);

            if ($response === false) {
                throw new Exception(curl_error($curl_handle), curl_errno($curl_handle));
            }

            $header_size = curl_getinfo($curl_handle, CURLINFO_HEADER_SIZE);
            $body = json_decode($this->_unicode_decode(substr($response, $header_size)));

            $code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
            curl_close($curl_handle);

            if($code == 200){
                return array(
                    'header' => $headers,
                    'code' => $code,
                    'body' => $body
                );
            }else{
                if(isset($body) && isset($body->message) && $body->message != null){
                    return array(
                        'header' => $headers,
                        'code' => $code,
                        'body' => $body
                    );
                }else{
                    $rms_error = load_ini(RMS_ERROR);
                    return array(
                        'header' => $headers,
                        'code' => $code,
                        'body' => (object)array(
                            'message' => $rms_error[$code]
                        )
                    );
                }

            }




        } catch (Exception $e) {
            return array(
                'header' => null,
                'code' => $e->getCode(),
                'body' => (object)array(
                    'message' => $e->getMessage()
                ),
            );
        }
    }

    function _unicode_decode($str) {
        $str=str_ireplace("\\","",$str);
        $str=str_ireplace("u0001","?",$str);
        $str=str_ireplace("u0002","?",$str);
        $str=str_ireplace("u0003","?",$str);
        $str=str_ireplace("u0004","?",$str);
        $str=str_ireplace("u0005","?",$str);
        $str=str_ireplace("u0006","?",$str);
        $str=str_ireplace("u0007","•",$str);
        $str=str_ireplace("u0008","?",$str);
        $str=str_ireplace("u0009","?",$str);
        $str=str_ireplace("u000A","?",$str);
        $str=str_ireplace("u000B","?",$str);
        $str=str_ireplace("u000C","?",$str);
        $str=str_ireplace("u000D","?",$str);
        $str=str_ireplace("u000E","?",$str);
        $str=str_ireplace("u000F","¤",$str);
        $str=str_ireplace("u0010","?",$str);
        $str=str_ireplace("u0011","?",$str);
        $str=str_ireplace("u0012","?",$str);
        $str=str_ireplace("u0013","?",$str);
        $str=str_ireplace("u0014","¶",$str);
        $str=str_ireplace("u0015","§",$str);
        $str=str_ireplace("u0016","?",$str);
        $str=str_ireplace("u0017","?",$str);
        $str=str_ireplace("u0018","?",$str);
        $str=str_ireplace("u0019","?",$str);
        $str=str_ireplace("u001A","?",$str);
        $str=str_ireplace("u001B","?",$str);
        $str=str_ireplace("u001C","?",$str);
        $str=str_ireplace("u001D","?",$str);
        $str=str_ireplace("u001E","?",$str);
        $str=str_ireplace("u001F","?",$str);
        $str=str_ireplace("u0020"," ",$str);
        $str=str_ireplace("u0021","!",$str);
        $str=str_ireplace("u0022","\"",$str);
        $str=str_ireplace("u0023","#",$str);
        $str=str_ireplace("u0024","$",$str);
        $str=str_ireplace("u0025","%",$str);
        $str=str_ireplace("u0026","&",$str);
        $str=str_ireplace("u0027","'",$str);
        $str=str_ireplace("u0028","(",$str);
        $str=str_ireplace("u0029",")",$str);
        $str=str_ireplace("u002A","*",$str);
        $str=str_ireplace("u002B","+",$str);
        $str=str_ireplace("u002C",",",$str);
        $str=str_ireplace("u002D","-",$str);
        $str=str_ireplace("u002E",".",$str);
        $str=str_ireplace("u2026","…",$str);
        $str=str_ireplace("u002F","/",$str);
        $str=str_ireplace("u0030","0",$str);
        $str=str_ireplace("u0031","1",$str);
        $str=str_ireplace("u0032","2",$str);
        $str=str_ireplace("u0033","3",$str);
        $str=str_ireplace("u0034","4",$str);
        $str=str_ireplace("u0035","5",$str);
        $str=str_ireplace("u0036","6",$str);
        $str=str_ireplace("u0037","7",$str);
        $str=str_ireplace("u0038","8",$str);
        $str=str_ireplace("u0039","9",$str);
        $str=str_ireplace("u003A",":",$str);
        $str=str_ireplace("u003B",";",$str);
        $str=str_ireplace("u003C","<",$str);
        $str=str_ireplace("u003D","=",$str);
        $str=str_ireplace("u003E",">",$str);
        $str=str_ireplace("u2264","=",$str);
        $str=str_ireplace("u2265","=",$str);
        $str=str_ireplace("u003F","?",$str);
        $str=str_ireplace("u0040","@",$str);
        $str=str_ireplace("u0041","A",$str);
        $str=str_ireplace("u0042","B",$str);
        $str=str_ireplace("u0043","C",$str);
        $str=str_ireplace("u0044","D",$str);
        $str=str_ireplace("u0045","E",$str);
        $str=str_ireplace("u0046","F",$str);
        $str=str_ireplace("u0047","G",$str);
        $str=str_ireplace("u0048","H",$str);
        $str=str_ireplace("u0049","I",$str);
        $str=str_ireplace("u004A","J",$str);
        $str=str_ireplace("u004B","K",$str);
        $str=str_ireplace("u004C","L",$str);
        $str=str_ireplace("u004D","M",$str);
        $str=str_ireplace("u004E","N",$str);
        $str=str_ireplace("u004F","O",$str);
        $str=str_ireplace("u0050","P",$str);
        $str=str_ireplace("u0051","Q",$str);
        $str=str_ireplace("u0052","R",$str);
        $str=str_ireplace("u0053","S",$str);
        $str=str_ireplace("u0054","T",$str);
        $str=str_ireplace("u0055","U",$str);
        $str=str_ireplace("u0056","V",$str);
        $str=str_ireplace("u0057","W",$str);
        $str=str_ireplace("u0058","X",$str);
        $str=str_ireplace("u0059","Y",$str);
        $str=str_ireplace("u005A","Z",$str);
        $str=str_ireplace("u005B","[",$str);
        $str=str_ireplace("u005C","\\",$str);
        $str=str_ireplace("u005D","]",$str);
        $str=str_ireplace("u005E","^",$str);
        $str=str_ireplace("u005F","_",$str);
        $str=str_ireplace("u0060","`",$str);
        $str=str_ireplace("u0061","a",$str);
        $str=str_ireplace("u0062","b",$str);
        $str=str_ireplace("u0063","c",$str);
        $str=str_ireplace("u0064","d",$str);
        $str=str_ireplace("u0065","e",$str);
        $str=str_ireplace("u0066","f",$str);
        $str=str_ireplace("u0067","g",$str);
        $str=str_ireplace("u0068","h",$str);
        $str=str_ireplace("u0069","i",$str);
        $str=str_ireplace("u006A","j",$str);
        $str=str_ireplace("u006B","k",$str);
        $str=str_ireplace("u006C","l",$str);
        $str=str_ireplace("u006D","m",$str);
        $str=str_ireplace("u006E","n",$str);
        $str=str_ireplace("u006F","o",$str);
        $str=str_ireplace("u0070","p",$str);
        $str=str_ireplace("u0071","q",$str);
        $str=str_ireplace("u0072","r",$str);
        $str=str_ireplace("u0073","s",$str);
        $str=str_ireplace("u0074","t",$str);
        $str=str_ireplace("u0075","u",$str);
        $str=str_ireplace("u0076","v",$str);
        $str=str_ireplace("u0077","w",$str);
        $str=str_ireplace("u0078","x",$str);
        $str=str_ireplace("u0079","y",$str);
        $str=str_ireplace("u007A","z",$str);
        $str=str_ireplace("u007B","{",$str);
        $str=str_ireplace("u007C","|",$str);
        $str=str_ireplace("u007D","}",$str);
        $str=str_ireplace("u02DC","˜",$str);
        $str=str_ireplace("u007E","~",$str);
        $str=str_ireplace("u007F","",$str);
        $str=str_ireplace("u00A2","¢",$str);
        $str=str_ireplace("u00A3","£",$str);
        $str=str_ireplace("u00A4","¤",$str);
        $str=str_ireplace("u20AC","€",$str);
        $str=str_ireplace("u00A5","¥",$str);
        $str=str_ireplace("u0026quot;","\"",$str);
        $str=str_ireplace("u0026gt;",">",$str);
        $str=str_ireplace("u0026lt;",">",$str);
        return $str;
    }


    function register($user)
    {
        $result = $this->post($this->action['register'], $user);
        return $result;
    }


    function login($user)
    {
        $result = $this->post($this->action['login'], $user);
        return $result;
    }


    function share($info)
    {
        $result = $this->post($this->action['share'], $info);
        return $result;
    }

    function check_commissions($data)
    {
        $result = $this->post($this->action['check_commissions'], $data);
        return $result;
    }

    function synchronize($data)
    {
        $result = $this->post($this->action['synchronize'], $data);
        return $result;
    }
    
    function order($order)
    {
        $result = $this->post($this->action['order'], $order);
        return $result;
    }


    function connect()
    {
        $http_header = array(
            'username:' . $this->username,
            'password:' . $this->password
        );
        $url = $this->url . $this->action['connect'];

        $result = $this->callback($url,$http_header,array());

        if( $result['code'] == '200'){
            $result =  array(
                'success'=> true,
                'data' =>  $result['header']
            );

        }else{
            $rms_error = load_ini(RMS_ERROR);

            $rms_error  = $rms_error[$result['code']]?$rms_error[$result['code']]:"Lỗi không tương thích Plugin. Xin liên hệ Admin RMS để được xử lý rms@dntg.com.vn";

            $result =  array(
                'success'=> false,
                'message' =>   $rms_error,
                'result'=> $result
            );
        }

        return $result;
    }

    function reconnect(){

            $this->password = get_option('rms_password');
            $this->username = get_option('rms_username');
            $result = $this->connect();
            $this->access_token = $result['data']['x-security-token'][0];
            update_option('rms_token', $result['data']['x-security-token'][0]);
            return $result;
    }

    function errors_log($url,$data,$error,$http_status='unknow'){
        $path_name =RMS_ERROR_LOG;
        $handle = fopen($path_name, 'a');
        $time= date("Y-m-d H:i:s");
        $error_data= '['.$time.']'.$http_status.'  |  '.$url.'-'.json_encode($data).'  |  '.$error."\n" ;

        fwrite($handle, $error_data);
        fclose($handle);
    }
}
