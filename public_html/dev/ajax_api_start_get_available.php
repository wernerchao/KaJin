<?php

include 'include_function.php';
include 'include_setting.php';

// 輸入
$counselor_id = $_POST['counselor_id'];
$date = $_POST['date'];
$range = $_POST['range'];

// 檢查個案登入狀態
$data_user = load_user();
if ($data_user == 'ERROR') {
    exit('{"error":1, "error_type":"NOT_LOGIN"}');
}

// 記錄個案走到這一步
if (!strstr($data_user['system_record'], 'start2')) {
    $mysql->query("Update `user` Set `system_record` = '".$data_user['system_record']." start2 ' Where `id` = '$data_user[id]'");
}

// 讀取資商師清單
$list_counselor = list_counselor();
if ($counselor_id == 7 || $counselor_id == 11) {
    exit('{"error":1, "error_type":"INVALID_COUNSELOR"}');
}
else if (empty($list_counselor[$counselor_id])) {
    exit('{"error":1, "error_type":"NO_COUNSELOR"}');
}

// 讀取有空時間
if (!is_valid_date($date)) {
    exit('{"error":1, "error_type":"FORMAT_INCORRECT"}');
}
$output = array();
for ($i = 1; $i <= $range; ++$i) {
    $output['slot'][$date] = array();
    $sql = $mysql->query("Select * From `appointment` Where `counselor_id` = '$counselor_id' And `date` = '$date' And `user_id` = '0' Order By `time` Asc");
    while ($row = $sql->fetch_assoc()) {
        if (is_datetime_passed($date, $row['time'], array('pre' => $system['pre_hour'])) == -1) {
            $output['slot'][$date][] = sprintf('%02d', $row['time']);
        }
    }
    $date = get_date(array('input' => $date, 'range' => 1));
}

// 計算價錢
$data_user = load_user();
$output['fee']['twd'] = count_fee($data_user['id'], 'twd');
$output['fee']['usd'] = count_fee($data_user['id'], 'usd');

echo json_encode($output);
