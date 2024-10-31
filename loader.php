<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$includes = array(
    'app/boot',
    'app/bizs',
    'app/libs',
    'app/ctrs',
    'app/views'
);

function load_ini($filename, $process_sections = false) {

    if( function_exists('parse_ini_file'))
        return parse_ini_file($filename,$process_sections = false);

    $ini_array = array();
    $sec_name = "";
    $lines = file($filename);
    foreach($lines as $line) {
        $line = trim($line);

        if($line == "") {
            continue;
        }

        if($line[0] == "[" && $line[strlen($line) - 1] == "]") {
            $sec_name = substr($line, 1, strlen($line) - 2);
        }
        else {
            $pos = strpos($line, "=");
            $property = substr($line, 0, $pos);
            $value = str_replace('"','',substr($line, $pos + 1)) ;

            if($process_sections) {
                $ini_array[$sec_name][$property] = $value;
            }
            else {
                $ini_array[$property] = $value;
            }
        }
    }

    return $ini_array;
}


define('RMS_URL',plugins_url('/rms-interaction'));
$rms_config =  load_ini(dirname(__FILE__). '/config.ini');
define('RMS_ERROR',dirname(__FILE__) . '/error.ini');
define('RMS_ERROR_LOG',dirname(__FILE__) . '/error_rms.log'); 

define('RMS_API',$rms_config['API']);
define('RMS_FE',$rms_config['FE']);

function rms_include($directory){
    foreach (scandir(dirname(__FILE__) .'/'. $directory) as $filename) {
        $path = dirname(__FILE__) . '/'.$directory.'/' . $filename;
        if (is_file($path)) {
            require_once $path;
        }
    }
}

foreach ($includes as $include_item){
    rms_include($include_item);
}
