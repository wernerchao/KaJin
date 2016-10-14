<?php

include 'include_function.php';
include 'include_setting.php';

// 輸入
$code = $_GET['code'];

// 檢查個案登入狀態
$data_user = load_user();
if ($data_user == 'ERROR') {
    exit('{"error":1, "error_type":"NOT_LOGIN"}');
}

$sql = $mysql->query("SELECT * FROM `coupon` WHERE `code` = '$code'");
if ($sql->num_rows == 1) {
    $code_info = $sql->fetch_assoc();
    $output = array('status' => true, 'discount' => $code_info['discount']);
} else {
    $output = array('status' => false);
}

echo json_encode($output);
