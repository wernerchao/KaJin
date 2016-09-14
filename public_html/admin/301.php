<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

if ($_POST) {
    $_GET['id'] = $_POST['id'];

    $str = "Update `appointment` Set `date` = '$_POST[date]', `time` = '$_POST[time]', `counselor_id` = '$_POST[counselor_id]', `user_id` = '$_POST[user_id]', `state_counsel` = '$_POST[state_counsel]', `state_payment` = '$_POST[state_payment]', `zoom_id` = '$_POST[zoom_id]', `goal` = '$_POST[goal]', `homework` = '$_POST[homework]', `note` = '$_POST[note]', `fee` = '$_POST[fee]' Where `id` = '$_GET[id]'";
    $mysql->query($str);
    echo "<script>alert('資料已修改');</script>";
}

$sql = $mysql->query("Select * From `appointment` Where `id` = '$_GET[id]'");
if ($sql->num_rows != 1) {
    header('location:300.php');
    exit();
}
$row = $sql->fetch_assoc();

if ($_GET['action'] == 'notify' && $row['user_id'] != 0 && $row['state_payment'] < 3) {
    $sql2 = $mysql->query("Select * From `user` Where `id` = '$row[user_id]'");
    $row2 = $sql2->fetch_assoc();
    notification('reservation_by_non_user', $row2['email'], array('date' => $row['date'], 'time' => $row['time'], 'appointment_id' => $_GET['id']));
    echo "<script>alert('已寄信通知個案付費');</script>";
}

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="300.php">返回上一頁</a>
<br>
<br>
<form action="301.php" method="post">
	<table>
		<tr>
			<td>ID</td>
			<td><?=$row['id']?></td>
		</tr>
		<tr>
			<td>日期</td>
			<td><input type="text" name="date" value="<?=$row['date']?>" size="6"></td>
		</tr>
		<tr>
			<td>時間</td>
			<td><input type="text" name="time" value="<?=$row['time']?>" size="2">:00</td>
		</tr>
		<tr>
			<td>費用</td>
			<td><input type="text" name="fee" value="<?=$row['fee']?>" size="6"></td>
		</tr>
		<tr>
			<td>諮商師</td>
			<td>
				<select name="counselor_id">
				<?php
                    foreach ($list_counselor as $k => $v) {
                        echo '<option value="'.$k.'"'.(($k == $row['counselor_id']) ? ' selected' : '').'>'.$v['name_ch'].'</option>';
                    }
                ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>個案ID</td>
			<td>
				<input type="text" name="user_id" value="<?=$row['user_id']?>" size="10">
				<?php
                    if ($row['user_id'] != 0 && $row['state_payment'] < 3) {
                        echo " <a href=\"301.php?id=$_GET[id]&action=notify\">寄信通知個案付費</a>";
                    }
                ?>
			</td>
		</tr>
		<tr>
			<td>諮商狀態</td>
			<td>
				<select name="state_counsel">
				<?php
                    foreach ($variable['state_counsel'] as $k => $v) {
                        echo '<option value="'.$k.'"'.(($k == $row['state_counsel']) ? ' selected' : '').'>'.$v.'</option>';
                    }
                ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>付款狀態</td>
			<td>
				<select name="state_payment">
				<?php
                    foreach ($variable['state_payment'] as $k => $v) {
                        echo '<option value="'.$k.'"'.(($k == $row['state_payment']) ? ' selected' : '').'>'.$v.'</option>';
                    }
                ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>zoom ID</td>
			<td><input type="text" name="zoom_id" value="<?=$row['zoom_id']?>" size="10"></td>
		</tr>
		<tr>
			<td>我的目標(個案看得到)</td>
			<td><textarea name="goal" cols="30" rows="3"><?=$row['goal']?></textarea></td>
		</tr>
		<tr>
			<td>我的功課(個案看得到)</td>
			<td><textarea name="homework" cols="30" rows="3"><?=$row['homework']?></textarea></td>
		</tr>
		<tr>
			<td>諮商筆記(個案無法觀看)</td>
			<td><textarea name="note" cols="30" rows="3"><?=$row['note']?></textarea></td>
		</tr>
	</table>
	<br>
	<input type="submit" name="submit" value="修改">
	<input type="hidden" name="id" value="<?=$_GET['id']?>">
</form>
