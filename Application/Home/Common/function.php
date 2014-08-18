<?php

require_once 'OAuth/Tencent.php';

//填写自己的appid
$client_id = '801323796';
//填写自己的appkey
$client_secret = '156f7633ab8626e9a6fa23e9ab201e13';

OAuth::init($client_id, $client_secret);
Tencent::$debug = false;
?>
