<?

include("include_function.php");
include("include_setting.php");

$date_this = get_date();
$time_this = date("H", $system['time_now']);
$minute = date("i", $system['time_now']);

$list_counselor = list_counselor();

if($minute <= 15 || 45 <= $minute)
{
	if($time_this == 0)
	{
		$mail_date = get_date(array("input" => $date_this, "range" => -1));
		$mail_hour = 23;
	}
	else
	{
		$mail_date = $date_this;
		$mail_hour = $time_this - 1;
	}

	$sql = $mysql->query("Select * From `appointment` Where `user_id` <> '0' And `state_payment` = '3' And `date` = '$mail_date' And `time` = '$mail_hour'");
	while($row = $sql->fetch_assoc())
	{
		if(!strstr($row['message'], "m00"))
		{
			$sql2 = $mysql->query("Select * From `user` Where `id` = '$row[user_id]'");
			$row2 = $sql2->fetch_assoc();
			notification("m00", $row2['email'], array("date" => $mail_date, "time" => sprintf("%02d", $mail_hour), "name" => $row2['name'], "counselor" => $list_counselor[$row['counselor_id']]['name_ch']));
			$message_new = $row['message']." m00 ";
			$mysql->query("Update `appointment` Set `message` = '$message_new' Where `id` = '$row[id]'");
		}
	}
}

?>