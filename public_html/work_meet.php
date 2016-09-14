<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

// 讀取個案資料
$data_user = load_user();
if($data_user == "ERROR")
{
	$error = 1;
}
else
{
	$sql = $mysql->query("Select * From `appointment` Where `id` = '$_GET[appointment_id]'");
	$row = $sql->fetch_assoc();

	if($row['user_id'] != $data_user['id'] || $row['state_payment'] != 3)
	{
		$error = 2;
	}
	else
	{
		$allow_from = mktime($row['time'], 0, 0, substr($row['date'], 5, 2), substr($row['date'], 8, 2), substr($row['date'], 0, 4)) - 1200;
		$allow_until = mktime($row['time'], 0, 0, substr($row['date'], 5, 2), substr($row['date'], 8, 2), substr($row['date'], 0, 4)) + 4800;
		//echo $allow_from."~".$allow_until."<br>now=".$system['time_now'];
		if($allow_from <= $system['time_now'] && $system['time_now'] <= $allow_until)
		{
			$ok = 1;
		}
		else
		{
			$error = 3;
		}
	}
}

if($error == 1)
{
	echo "<script>alert('預約資料有誤，請聯絡工作人員。');window.close();</script>";
}
else if($error == 2)
{
	echo "<script>alert('預約資料有誤，請聯絡工作人員。');window.close();</script>";
}
else if($error == 3)
{
	echo "<script>alert('諮詢時間未到或是諮詢時間已經結束。');window.close();</script>";
}
else
{
	header("location:https://zoom.us/j/".$row['zoom_id']);
	//echo "https://zoom.us/j/".$row['zoom_id'];
	exit();
}

?>