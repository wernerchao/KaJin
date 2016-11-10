<?php


include 'include_function.php';
include 'include_setting_test.php';
include 'include_stripe.php';
include 'include_zoom_test.php';

$f = fopen('pay_e.txt', 'a');
fputs($f, $_SERVER['REMOTE_ADDR'].' ');
fputs($f, '0 ');

// 輸入
$counselor_id = $_POST['counselor_id'];
$date = $_POST['date'];
$time = $_POST['time'];
/*$number = $_POST['number'];
$valid = $_POST['valid'];
$three = $_POST['three'];*/
$token = $_POST['token'];
$news = $_POST['news'];
$fee_usd = $_POST['fee_usd'];
$fee_twd = $_POST['fee_twd'];
$code = $_POST['coupon'];
fputs($f, print_r($_POST, true).'/'.$_POST['token'].'/'.$_POST['date'].'/'.$_POST['time'].'/'.$_POST['counselor_id'].'--'."\n");
// 檢查個案登入狀態
$data_user = load_user();
if ($data_user == 'ERROR') {
    exit('{"error":1, "error_type":"NOT_LOGIN"}');
}
fputs($f, '1 ');
// 讀取資商師清單
$list_counselor = list_counselor();
if ($counselor_id == 7 || $counselor_id == 11) {
    exit('{"error":1, "error_type":"NO_COUNSELOR"}');
}
fputs($f, '2 ');
// 讀取時段
if (!is_valid_date($date)) {
    exit('{"error":1, "error_type":"FORMAT_INCORRECT"}');
}
if (is_datetime_passed($date, $time, array('pre' => $system['pre_hour'])) != -1) {
    exit('{"error":, "error_type":"SLOT_INVALID"}');
}
$sql = $mysql->query("Select * From `appointment` Where `counselor_id` = '$counselor_id' And `user_id` = '0' And `date` = '$date' And `time` = '$time'");
if ($sql->num_rows != 1) {
    exit('{"error":1, "error_type":"SLOT_INVALID"}');
}
$data_appointment = $sql->fetch_assoc();
fputs($f, '3 ');
// 計算價錢
// $fee_count_twd = count_fee($data_user['id'], 'twd');
// $fee_count_usd = count_fee($data_user['id'], 'usd');
// if ($fee['twd'] != $fee_count_twd) {
//     exit('{"error":1, "error_type":"CHARGE_FAILED"}');
// }
fputs($f, '4 ');
/* 開始進行付款 */

// 鎖定時段
/*if($data_appointment['user_id'] == 0)
{
    $mysql->query("Update `appointment` Set `user_id` = '$data_user[id]' Where `id` = '$data_appointment[id]' And `user_id` = '0'");
    if($mysql->affected_rows != 1)
    {
        exit("{\"error\":1, \"error_type\":\"CHARGE_FAILED\"}");
    }
}*/
fputs($f, '5 ');
// 執行扣款
try {
    fputs($f, '5.5 ');
    $charge = \Stripe\Charge::create(array(
        'amount' => $fee_usd, // amount in cents, again
        // 'amount' => $fee_count_usd, // amount in cents, again
        'currency' => 'usd',
        'source' => $token,
        'description' => $data_user['name'].'(ID='.$data_user['id'].') 預約 '.$data_appointment['date'].sprintf(' %02d:00', $data_appointment['time']).'(時段代號='.$_POST['appointment_id'].') 諮商師='.$list_counselor[$data_appointment['counselor_id']]['name_ch'],
    ));
} catch (\Stripe\Error\Card $e) {
    fputs($f, '6 ');
    exit('{"error":1, "error_type":"CHARGE_FAILED"}');
} catch (\Stripe\Error\InvalidRequest $e) {
    fputs($f, '7 ');
    fputs($f, print_r($_POST, true).' ');

    $body = $e->getJsonBody();
    $err = $body['error'];

    fputs($f, 'Status is:'.$e->getHttpStatus()."\n");
    fputs($f, 'Type is:'.$err['type']."\n");
    fputs($f, 'Code is:'.$err['code']."\n");
    // param is '' in this case
    fputs($f, 'Param is:'.$err['param']."\n");
    fputs($f, 'Message is:'.$err['message']."\n");
    fputs($f, print_r($err, true));

    exit('{"error":1, "error_type":"CHARGE_FAILED"}');
}
fputs($f, '7.5 ');
// 鎖定時段
if ($data_appointment['user_id'] == 0) {
    $mysql->query("Update `appointment` Set `user_id` = '$data_user[id]' Where `id` = '$data_appointment[id]' And `user_id` = '0'");
    if ($mysql->affected_rows != 1) {
        exit('{"error":1, "error_type":"CHARGE_FAILED"}');
    }
}
fputs($f, '8'."\n");
/* 付款成功 */

// 修改為已付款
$mysql->query("Update `appointment` Set `state_counsel` = '1', `state_payment` = '3', `fee` = '$fee_twd' Where `id` = '$data_appointment[id]'");

// 新增 zoom id
$zoom_id = create_meeting();
$mysql->query("Update `appointment` Set `zoom_id` = '$zoom_id' Where `id` = '$data_appointment[id]'");
fputs($f, 'zoom_id='.$zoom_id."\n");

// 新增 coupon record
$mysql->query("INSERT INTO `coupon_record`(`user_id`,`coupon_id`) VALUES('$data_user[id]',(SELECT `id` FROM `coupon` WHERE `code`='$code'))");

// 寄信通知老師
notification('reservation_to_counselor', $list_counselor[$data_appointment['counselor_id']]['email'], array('name' => $list_counselor[$data_appointment['counselor_id']]['name_ch'], 'date' => $data_appointment['date'], 'time' => sprintf(' %02d:00', $data_appointment['time'])));
// 寄信通知管理員
notification('reservation_to_admin', $system['admin_email'], array('counselor' => $list_counselor[$data_appointment['counselor_id']]['name_ch'], 'date' => $data_appointment['date'], 'time' => sprintf(' %02d:00', $data_appointment['time']), 'fee' => $fee_twd, 'user_id' => $data_user['id'], 'email' => $data_user['email']));

// 記錄個案走到這一步
if (!strstr($data_user['system_record'], 'start3')) {
    $mysql->query("Update `user` Set `system_record` = '".$data_user['system_record']." start3 ' Where `id` = '$data_user[id]'");
}

// 成功
$output['succeed'] = 1;

echo json_encode($output);
