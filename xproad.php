<?php

//
define("aff12e_APIURL","http://XPROAD_TRACKING_URL/clcget.php");

define("aff12e_OFFERID",26); // int offer_id
define("aff12e_PUBID",45); // int pub_id
define("aff12e_URLID",6); // int url_id, 0 - if used default offer url
define("aff12e_SOURCE",(!empty($_GET['source'])) ? urlencode($_GET['source']) : ""); // string pub_source
define("aff12e_SUB1",(!empty($_GET['sub1'])) ? urlencode($_GET['sub1']) : ""); // string pub_sub_id1
define("aff12e_SUB2",(!empty($_GET['sub2'])) ? urlencode($_GET['sub2']) : ""); // string pub_sub_id2
define("aff12e_SUB3",(!empty($_GET['sub3'])) ? urlencode($_GET['sub3']) : ""); // string pub_sub_id3
define("aff12e_SUB4",(!empty($_GET['sub4'])) ? urlencode($_GET['sub4']) : ""); // string pub_sub_id4
define("aff12e_SUB5",(!empty($_GET['sub5'])) ? urlencode($_GET['sub5']) : ""); // string pub_sub_id5

// If session not started early, start now.
if (!isset($_SESSION)){
    session_start();
}

// function send traffic data to API, and get
function aff12e_getClickData($offer=aff12e_OFFERID, $pub_id=aff12e_PUBID, $url_id=aff12e_URLID, $source=aff12e_SOURCE, $sub1=aff12e_SUB1, $sub2=aff12e_SUB2, $sub3=aff12e_SUB3, $sub4=aff12e_SUB4, $sub5=aff12e_SUB5)
{
    $url = aff12e_APIURL;
    $data = array();
    $data['of'] = $offer;
    $data['pub_id'] = $pub_id;
    $data['url_id'] = $url_id;
    $data['pub_source'] = $source;
    $data['pub_sub_id_1'] = $sub1;
    $data['pub_sub_id_2'] = $sub2;
    $data['pub_sub_id_3'] = $sub3;
    $data['pub_sub_id_4'] = $sub4;
    $data['pub_sub_id_5'] = $sub5;


    $data['user_agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
    $data['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
    $data['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";

    $str_data = array();
    foreach($data as $key=>$val){
        $str_data[] = $key.'='.urlencode($val);
    }
    $str_data = implode('&',$str_data);

    $result = array();    
    $json_data = file_get_contents($url.'?'.$str_data);
    if ($json_data){
        $result = json_decode($json_data, TRUE);
    }
    return $result;
}
/**********************************************************************************************************************************/

/*
 After execution this code you get this additional session variables
$_SESSION = Array (
    [aff12e_publisher_id] => ...
    [aff12e_offer_id] => ...
    [aff12e_click_id] => 4c0899a6c055a1cd617819e08d3b1310e98fafc4
    [aff12e_pub_source] =>
    [aff12e_pub_sub_id_1] =>
    [aff12e_pub_sub_id_2] =>
    [aff12e_pub_sub_id_3] =>
    [aff12e_pub_sub_id_4] =>
    [aff12e_pub_sub_id_5] =>
    [aff12e_file_id] =>
    [aff12e_url_id] => 0
)

(!) aff12e_click_id - this variable what you need use in Pixels

 */

if (!isset($_SESSION['aff12e_click_id']))
{
    $aff12e_data = aff12e_getClickData(aff12e_OFFERID, aff12e_PUBID, aff12e_URLID, aff12e_SOURCE, aff12e_SUB1, aff12e_SUB2, aff12e_SUB3, aff12e_SUB4, aff12e_SUB5);
    foreach($aff12e_data as $aff12e_key=>$aff12e_val){
        $_SESSION['aff12e_'.$aff12e_key] = $aff12e_val;
    }
}

$tracking_id = $_SESSION['aff12e_click_id'];
