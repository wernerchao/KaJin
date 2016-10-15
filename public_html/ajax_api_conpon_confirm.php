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

// 檢查 code 是否存在
$sql = $mysql->query("SELECT * FROM `coupon` WHERE `code` = '$code'");
if ($sql->num_rows == 1) {
    $code_info = $sql->fetch_assoc();
    // 檢查使用者是否已用過目前 code
    $used_sql = $mysql->query("SELECT * FROM `coupon_record` WHERE `user_id`='".$data_user['id']."' AND `coupon_id`='".$code_info['id']."'");
    if ($used_sql->num_rows > 0) { // 已使用過
        $output = array('status' => 100);
    } else { // 未使用過，回傳折扣
        $output = array('status' => 0, 'discount' => $code_info['discount']);
    }
} else { // code 無效
    $output = array('status' => 999);
}

echo json_encode($output);
