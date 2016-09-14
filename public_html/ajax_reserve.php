<?

include("include_function.php");
include("include_setting.php");

// 檢查個案登入狀態
$data_user = load_user();
if($data_user == "ERROR") exit("{\"error\":1}");

// 讀取資商師清單
$list_counselor = list_counselor();

// 檢查是否已被預約
$sql = $mysql->query("Select * From `appointment` Where `id` = '$_POST[id]' And `user_id` = '0'");
if($sql->num_rows != 1) exit("{\"error\":1}");

// 檢查是否已經超過時間 (現在的小時也不准預約)
$row = $sql->fetch_assoc();
if(is_datetime_passed($row['date'], $row['time'], array("pre" => $system['pre_hour'])) != -1) exit("{\"error\":1}");

// 計算費用
$fee = count_fee($data_user['id'], "text");

// 輸出 jason
$output = array("date" => $row['date'], "time" => sprintf("%02d:00", $row['time']), "counselor" => $list_counselor[$row['counselor_id']]['name_ch'], "fee" => $fee, "counselor_id" => $row['counselor_id']);
echo json_encode($output);

?>