<?

include("include_function.php");
include("include_setting.php");

// �ˬd�Ӯ׵n�J���A
$data_user = load_user();
if($data_user == "ERROR") exit("{\"error\":1}");

// �n�O
$mysql->query("Update `user` Set `system_record` = '".$data_user['system_record']." contact ' Where `id` = '$data_user[id]'");

// �H�H�q���޲z��
notification("contact", $system['admin_email'], array("name" => $data_user['name'], "email" => $data_user['email'], "phone" => "(".$data_user['phone_country'].")".$data_user['phone_number'], "datetime" => date("Y-m-d H:i:s", $system['time_now'])));

?>