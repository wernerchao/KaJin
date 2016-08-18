<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

if (!$_GET['id']) {
    $_GET['id'] = $_POST['payout_id'];
}
if (!$_GET['action']) {
    $_GET['action'] = $_POST['action'];
}

if ($_GET['action'] == 'edit') {
    $str = "Update `payout` Set `date` = '$_POST[date]', `amount` = '$_POST[amount]', `note` = '$_POST[note]' Where `id` = '$_GET[id]'";
    $mysql->query($str);
    echo "<script>alert('記錄已修改！');</script>";
}
if ($_GET['action'] == 'remove') {
    $str = "Update `appointment` Set `payout_id` = '0' Where `id` = '$_GET[appointment_id]'";
    $mysql->query($str);
    echo "<script>alert('諮商記錄已從本匯款記錄移除！');</script>";
}
if ($_GET['action'] == 'add') {
    $mysql->query("Update `appointment` Set `payout_id` = '$_GET[id]' Where `id` = '$_POST[appointment_id]' And `payout_id` = '0'");
    if ($mysql->affected_rows == 1) {
        echo "<script>alert('諮商記錄已新增至本匯款記錄！');</script>";
    } else {
        echo "<script>alert('新增失敗！可能是ID錯誤或是該諮商記錄已有所屬匯款記錄。');</script>";
    }
}

$sql = $mysql->query("Select * From `payout` Where `id` = '$_GET[id]'");
if ($sql->num_rows != 1) {
    header('location:400.php');
    exit();
}
$row = $sql->fetch_assoc();

$sql2 = $mysql->query("Select * From `appointment` Where `payout_id` = '$_GET[id]'");

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="400.php">返回上一頁</a>
<br>
<br>
<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>諮商師</th>
		<th>金額</th>
		<th>備註</th>
		<th>修改</th>
	</tr>
	<tr>
		<form action="401.php" method="post">
			<td><?=$row['id']?></td>
			<td><input type="text" name="date" value="<?=$row['date']?>" size="6"></td>
			<td><?=$list_counselor[$row['counselor_id']]['name_ch']?></td>
			<td><input type="text" name="amount" value="<?=$row['amount']?>" size="4"></td>
			<td><textarea name="note" cols="25" rows="2"><?=$row['note']?></textarea></td>
			<td><input type="submit" name="submit" value="修改"></td>
			<input type="hidden" name="payout_id" value="<?=$_GET['id']?>">
			<input type="hidden" name="action" value="edit">
		</form>
	</tr>
	<?php
        /*
        while($row = $sql->fetch_assoc())
        {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['date']."</td>";
            echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
            echo "<td>".$row['amount']."</td>";
            echo "<td>".$row['note']."</td>";
            echo "</tr>";
        }
        */
    ?>
</table>

<h3>所屬諮商紀錄</h3>

<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>諮商師</th>
		<th>費用</th>
		<th>個案ID</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
		<th>移除</th>
	</tr>
	<?php
        while ($row2 = $sql2->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row2['id'].'</td>';
            echo '<td>'.$row2['date'].'</td>';
            echo '<td>'.$row2['time'].':00</td>';
            echo '<td>'.$list_counselor[$row2['counselor_id']]['name_ch'].'</td>';
            echo '<td>'.$row2['fee'].'</td>';
            echo '<td>'.$row2['user_id'].'</td>';
            echo '<td>'.$variable['state_counsel'][$row2['state_counsel']].'</td>';
            echo '<td>'.$variable['state_payment'][$row2['state_payment']].'</td>';
            echo "<td><a href=\"401.php?id=$_GET[id]&action=remove&appointment_id=$row2[id]\" onclick=\"if(!confirm('確定要移除？'))return false;\">移除</a></td>";
            echo '</tr>';
        }
    ?>
</table>

<br>
<br>
<form action="401.php" method="post">
	新增諮商記錄到本匯款記錄<br>
	請輸入時段ID <input type="text" name="appointment_id" size="2"><br>
	<input type="submit" name="submit" value="新增">
	<input type="hidden" name="action" value="add">
	<input type="hidden" name="payout_id" value="<?=$_GET['id']?>">
</form>
