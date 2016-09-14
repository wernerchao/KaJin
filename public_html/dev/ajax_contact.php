<?

include("include_function.php");
include("include_setting.php");

// 檢查個案登入狀態
$data_user = load_user();
if($data_user == "ERROR") exit("{\"error\":1}");

// 登記
$mysql->query("Update `user` Set `system_record` = '".$data_user['system_record']." contact ' Where `id` = '$data_user[id]'");

// 寄信通知管理員
notification("contact", $system['admin_email'], array("name" => $data_user['name'], "email" => $data_user['email'], "phone" => "(".$data_user['phone_country'].")".$data_user['phone_number'], "datetime" => date("Y-m-d H:i:s", $system['time_now'])));

?>