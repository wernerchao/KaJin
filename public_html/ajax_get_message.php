<?

include("include_function.php");
include("include_setting.php");

// 檢查個案登入狀態
$data_user = load_user();
if($data_user == "ERROR") exit("{\"error\":1}");

// 讀取訊息
$sql = $mysql->query("Select * From `message` Where `user_id` = '$data_user[id]' And `id` = '$_POST[id]'");
if($sql->num_rows != 1) exit("{\"error\":1}");

// 輸出 jason
$row = $sql->fetch_assoc();
if($row['type'] == 0) $title = mb_strimwidth(trim($row['content']), 0, 40, '...', 'UTF-8');
$output = array(
	"sender" => $row['sender'],
	"type" => $row['type'],
	"content" => str_replace("\r\n", "<br>", ($row['sender']==1)?htmlspecialchars($row['content']):$row['content']),
	"title" => $title,
	"datetime" => date("Y.m.d g:i a", $row['timestamp'])
);
echo json_encode($output);

?>