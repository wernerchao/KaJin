<?php

include 'include_function.php';
// include 'include_setting_test.php';
include 'include_setting.php';
include 'include_stripe.php';
include 'include_zoom.php';

$fee = $_POST['fee'];
$fee_twd = $fee / 3;
// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array('f_rd' => 'login.php'));

// 讀取個案資料
$data_user = load_user();
if ($data_user == 'ERROR') {
    logout_user(array('rd' => 'login.php'));
}

// 讀取資商師清單
$list_counselor = list_counselor();

// 讀取預約資料
$sql = $mysql->query("Select * From `appointment` Where `id` = '$_POST[appointment_id]'");
$data_appointment = $sql->fetch_assoc();

// 檢查是否是自己的預約或是是空的預約，若已被別人預約則跳到錯誤頁
if ($data_appointment['user_id'] != $data_user['id'] && $data_appointment['user_id'] != 0) {
    // header('location:error.php?type=B1');
    $output = array('status' => 'B1');
    echo json_encode($output);
    exit();
}

// 檢查是否已經超過時間 (現在的小時也不准預約)
if (is_datetime_passed($data_appointment['date'], $data_appointment['time'], array('pre' => $system['pre_hour'])) != -1) {
    // header('location:error.php?type=tle');
    $output = array('status' => 'tle');
    echo json_encode($output);
    exit();
}

// 檢查是否已付款
if ($data_appointment['state_payment'] != 0 && $data_appointment['state_payment'] != 1 && $data_appointment['state_payment'] != 2) {
    // header('location:error.php?type=B2');
    $output = array('status' => 'B2');
    echo json_encode($output);
    exit();
}

// 算費用
// $fee_twd = count_fee($data_user['id'], 'twd', array('default' => $data_appointment['fee']));
// $fee_usd = count_fee($data_user['id'], 'usd', array('default' => $data_appointment['fee']));
// if ($_POST['fee'] != $fee_twd) {
//     header('location:error.php?type=B3');
//     exit();
// }

// 鎖定時段
/*if($data_appointment['user_id'] == 0)
{
    $mysql->query("Update `appointment` Set `user_id` = '$data_user[id]' Where `id` = '$_POST[appointment_id]' And `user_id` = '0'");
    if($mysql->affected_rows != 1)
    {
        header("location:error.php?type=B4");
        exit();
    }
}*/

// 執行扣款
try {
    $charge = \Stripe\Charge::create(array(
        'amount' => $fee, // amount in cents, again
        'currency' => 'usd',
        'source' => $_POST['token'],
        'description' => $data_user['name'].'(ID='.$data_user['id'].') 預約 '.$data_appointment['date'].sprintf(' %02d:00', $data_appointment['time']).'(時段代號='.$_POST['appointment_id'].') 諮商師='.$list_counselor[$data_appointment['counselor_id']]['name_ch'],
    ));
} catch (\Stripe\Error\Card $e) {
    // header('location:error.php?type=B5');
    $output = array('status' => 'B5');
    echo json_encode($output);
    exit();
} catch (\Stripe\Error\InvalidRequest $e) {
    // header('location:error.php?type=B6');
    $output = array('status' => 'B6');
    echo json_encode($output);
    exit();
}

// 鎖定時段
if ($data_appointment['user_id'] == 0) {
    $mysql->query("Update `appointment` Set `user_id` = '$data_user[id]' Where `id` = '$_POST[appointment_id]' And `user_id` = '0'");
    if ($mysql->affected_rows != 1) {
        // header('location:error.php?type=B4');
        $output = array('status' => 'B4');
        echo json_encode($output);
        exit();
    }
}

/* 付款成功 */

// 修改為已付款
$mysql->query("Update `appointment` Set `state_counsel` = '1', `state_payment` = '3', `fee` = '$fee_twd' Where `id` = '$_POST[appointment_id]'");

// 新增 coupon record
$mysql->query("INSERT INTO `coupon_record`(`user_id`,`coupon_id`) VALUES('$data_user[id]',(SELECT `id` FROM `coupon` WHERE `code`='$_POST[coupon]'))");

// 新增 zoom id
$zoom_id = create_meeting();
$mysql->query("Update `appointment` Set `zoom_id` = '$zoom_id' Where `id` = '$_POST[appointment_id]'");
// 寄信通知老師
notification('reservation_to_counselor', $list_counselor[$data_appointment['counselor_id']]['email'], array('name' => $list_counselor[$data_appointment['counselor_id']]['name_ch'], 'date' => $data_appointment['date'], 'time' => sprintf(' %02d:00', $data_appointment['time'])));
// 寄信通知管理員
notification('reservation_to_admin', $system['admin_email'], array('counselor' => $list_counselor[$data_appointment['counselor_id']]['name_ch'], 'date' => $data_appointment['date'], 'time' => sprintf(' %02d:00', $data_appointment['time']), 'fee' => $fee_twd, 'user_id' => $data_user['id'], 'email' => $data_user['email']));

// header("location:finish.php?appointment_id=$_POST[appointment_id]");
$output = array('status' => 0);
echo json_encode($output);
