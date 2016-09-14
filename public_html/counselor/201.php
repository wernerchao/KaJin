<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

if ($_POST['submit']) {
    $sql = $mysql->query("Select * From `appointment` Where `id` = '$_POST[id]' And `counselor_id` = '$data_counselor[id]'");
    if ($sql->num_rows != 1) {
        header('location:200.php');
        exit();
    }
    $row = $sql->fetch_assoc();

    // 以時間檢查是否為已結束的諮商
    if ($row['date'] < get_date() || ($row['date'] == get_date() && $row['time'] <= date('H', $system['time_now']))) {
        if ($row['state_counsel'] == 1) {
            $mysql->query("Update `appointment` Set `state_counsel` = '2' Where `id` = '$_POST[id]' And `counselor_id` = '$data_counselor[id]'");
            /***** 還有寄回饋通知信還沒寫 *****/
            echo "<script>alert('諮商狀態已標記為「完成」。');</script>";
            // 寄通知信
            $sql2 = $mysql->query("Select * From `user` Where `id` = '$row[user_id]'");
            $row2 = $sql2->fetch_assoc();
            notification('homework_to_user', $row2['email']);
        }

        $mysql->query("Update `appointment` Set `goal` = '$_POST[goal]', `homework` = '$_POST[homework]', `note` = '$_POST[note]' Where `id` = '$_POST[id]' And `counselor_id` = '$data_counselor[id]'");
        echo "<script>alert('「我的目標」、「我的功課」及「諮商筆記」已更新。');</script>";
    }
    $_GET['id'] = $_POST['id'];
}

$sql = $mysql->query("Select * From `appointment` Where `id` = '$_GET[id]' And `counselor_id` = '$data_counselor[id]'");
if ($sql->num_rows != 1) {
    header('location:200.php');
    exit();
}
$row = $sql->fetch_assoc();

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="200.php">返回上一頁</a>
<br>
<br>
<a href="310.php?user_id=<?=$row['user_id']?>">為此個案預約下一個時段</a>
<br>
<br>
<table>
	<tr>
		<td>ID</td>
		<td><?=$row['id']?></td>
	</tr>
	<tr>
		<td>日期</td>
		<td><?=$row['date']?></td>
	</tr>
	<tr>
		<td>時間</td>
		<td><?=$row['time']?>:00</td>
	</tr>
	<tr>
		<td>費用</td>
		<td><?=$row['fee']?></td>
	</tr>
	<tr>
		<td>個案ID</td>
		<td><?=$row['user_id']?> <a href="301.php?id=<?=$row['user_id']?>">查看資料</a></td>
	</tr>
	<tr>
		<td>諮商狀態</td>
		<td><?=$variable['state_counsel'][$row['state_counsel']];?></td>
	</tr>
	<tr>
		<td>付款狀態</td>
		<td><?=$variable['state_payment'][$row['state_payment']];?></td>
	</tr>
	<tr>
		<td>zoom ID</td>
		<td><?=$row['zoom_id']?> <a href="https://zoom.us/j/<?=$row['zoom_id']?>" target="_blank">啟動 zoom</a></td>
	</tr>
	<!--
	<tr>
		<td>回報</td>
		<td><?=$row['report']?></td>
	</tr>
	<tr>
		<td>評價</td>
		<td><?=$row['evaluate']?></td>
	</tr>
	-->
</table>

<?php
    if ($row['date'] < get_date() || ($row['date'] == get_date() && $row['time'] <= date('H', $system['time_now']))) {
        ?>

<h3>諮商完成登記</h3>

登記以下資料後將自動將諮商狀態標記為「完成」<br><br>

<form action="201.php" method="post">
	<table>
		<tr>
			<td>我的目標(個案看得到)</td>
			<td><textarea name="goal" cols="40" rows="5"><?=htmlspecialchars($row['goal'])?></textarea></td>
		</tr>
		<tr>
			<td>我的功課(個案看得到)</td>
			<td><textarea name="homework" cols="40" rows="5"><?=htmlspecialchars($row['homework'])?></textarea></td>
		</tr>
		<tr>
			<td>諮商筆記(個案無法觀看)</td>
			<td><textarea name="note" cols="40" rows="5"><?=htmlspecialchars($row['note'])?></textarea></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="登記/修改">
	<input type="hidden" name="id" value="<?=$_GET['id']?>">
</form>

<?php

    }
?>
