<?php


include 'include_function.php';
include 'include_setting.php';
include 'include_func_din.php';

// 檢查個案登入狀態
$data_user = load_user();
if ($data_user == 'ERROR') {
    exit('{"error":1, "error_type":"NOT_LOGIN: '.$_SESSION['user_email'].'"}');
}

// echo json_encode($_POST);

// 儲存 user 選擇的答案
if (!empty($_POST)) {
    if (!insert_user_answer($data_user['id'], $_POST)) {
        exit('{"error":1, "error_type":"OPERATION_FAILED"}');
    }
}

// 記錄個案走到這一步
if (!strstr($data_user['system_record'], 'start1')) {
    $mysql->query("Update `user` Set `system_record` = '".$data_user['system_record']." start1 ' Where `id` = '".$data_user['id']."'");
}

// 讀取資商師清單
$list_counselor = array_keys(list_counselor($_POST['answer3']));

// 有要篩掉誰？
// $list_counselor = array(1, 2, 3, 4, 5, 6, 8, 9, 10, 12, 13);

$skip_array = array(7, 11, 14, 15, 16, 17);
// 檢查六天內是否有時段
foreach ($list_counselor as $v) {
    if (!in_array($v, $skip_array)) {
        $date_this = get_date();
        $date_end = get_date(array('range' => 6));

        // 找諮商師這個日期區間沒有被約的時段
        // $result = $mysql->query("SELECT * FROM `appointment` WHERE `date` BETWEEN '$date_this' AND '$date_end' AND `counselor_id` = '$v' AND `user_id` = '0'");

        // 找有時段可以預約的老師（不管日期區間）
        global $mysql;
        $sql = "SELECT * FROM `appointment` WHERE `counselor_id` = '$v' AND `date` >= '$date_this' AND `user_id` = '0'";
        // $sql = "SELECT * FROM `appointment` WHERE `counselor_id` = '$v' AND `date` BETWEEN '$date_this' AND '$date_end' AND `user_id` = '0'";
        $result = $mysql->query($sql);

        if ($result) {
            $check = false;
            while ($row = mysqli_fetch_assoc($result)) {
                // echo "row: ".$row['id']."  ".$row['date']."  ".$row['time'].PHP_EOL;
                if (is_datetime_passed($row['date'], $row['time'], array('pre' => 12)) == -1) {
                    $check = true;
                    break;
                }
            }
            if ($check) {
                $available_list[] = $v;
            } else {
                $unavailable_list[] = $v;
            }
        } else {
            exit('{"error":1, "error_type":"NO_AVAILIBLE_COUNSELOR"}');
        }
    }
}
// 根據上面 query 篩出來的
$available_list = find_match_counselor($available_list, $_POST);
shuffle($unavailable_list);
$output = array_merge((array) $available_list, (array) $unavailable_list);
$top_three = array_slice($output, 0, 3, true);
shuffle($top_three);
echo json_encode($top_three);

// 找全部老師
// $output = find_match_counselor($list_counselor, $_POST);
// echo json_encode($output);
