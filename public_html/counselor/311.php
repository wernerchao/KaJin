<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

// 檢查這個個案是否已有預約或諮商記錄
$sql = $mysql->query("Select * From `appointment` Where `user_id` = '$_GET[user_id]' And `counselor_id` = '$data_counselor[id]'");
if ($sql->num_rows == 0) {
    exit('您無法為此個案預約，因為此個案與您尚未有任何預約會諮商記錄。');
}

// 檢查預約的時段是否不到24小時
$sql = $mysql->query("Select * From `appointment` Where `id` = '$_GET[appointment_id]' And `counselor_id` = '$data_counselor[id]'");
if ($sql->num_rows != 1) {
    exit();
}
$row = $sql->fetch_assoc();
if (is_datetime_passed($row['date'], $row['time'], array('pre' => 24)) >= 0) {
    exit('距離該時段已不足24小時，無法預約。');
}

// 檢查該時段是否已被預約
if ($row['user_id'] != 0) {
    exit('該時段已被其他個案預約。');
}

// 都沒問題則預約
if ($_GET['submit']) {
    $mysql->query("Update `appointment` Set `user_id` = '$_GET[user_id]', `state_payment` = '1' Where `id` = '$_GET[appointment_id]' And `counselor_id` = '$data_counselor[id]' And `user_id` = '0'");
    // 寄信通知個案
    $sql2 = $mysql->query("Select * From `user` Where `id` = '$_GET[user_id]'");
    $row2 = $sql2->fetch_assoc();
    notification('reservation_by_non_user', $row2['email'], array('date' => $row['date'], 'time' => $row['time'], 'appointment_id' => $_GET['appointment_id']));
    exit('預約完成。');
}

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
請確認以下預約資料
<br>
<br>
<form action="311.php" method="get">
	<table>
		<tr>
			<td>日期</td>
			<td><?=$row['date']?></td>
		</tr>
		<tr>
			<td>時間</td>
			<td><?=$row['time']?>:00</td>
		</tr>
		<tr>
			<td>個案ID</td>
			<td><?=$_GET['user_id']?></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="確認預約">
	<input type="button" value="返回上一頁" onclick="location.href='310.php?user_id=<?=$_GET['user_id']?>'">
	<input type="hidden" name="user_id" value="<?=$_GET['user_id']?>">
	<input type="hidden" name="appointment_id" value="<?=$_GET['appointment_id']?>">
</form>
