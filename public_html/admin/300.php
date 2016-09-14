<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

if ($_GET['showall']) {
    $sql = $mysql->query('Select * From `appointment` Order By `date` Desc, `time` Desc');
} else {
    $sql = $mysql->query("Select * From `appointment` Where `user_id` != '0' Order By `date` Desc, `time` Desc");
}

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="310.php">新增時段</a>
<br>
<br>
<a href="300.php?showall=1">不隱藏未被預約的時段</a>
<br>
<br>
<table>
	<tr>
		<th>查看</th>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>個案ID</th>
		<th>諮商師</th>
		<th>費用</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
		<th>交款ID</th>
		<th>zoom ID</th>
		<!--<th>目標</th>
		<th>功課</th>
		<th>筆記</th>-->
	</tr>
	<?php
        while ($row = $sql->fetch_assoc()) {
            echo '<tr>';
            echo '<td><a href="301.php?id='.$row['id'].'">查看</a></td>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['date'].'</td>';
            echo '<td>'.$row['time'].':00</td>';
            echo "<td><a href='101.php?id=".$row['user_id']."'>".$row['user_id'].'</td>';
            echo '<td>'.$list_counselor[$row['counselor_id']]['name_ch'].'</td>';
            echo '<td>'.$row['fee'].'</td>';
            echo '<td>'.$variable['state_counsel'][$row['state_counsel']].'</td>';
            echo '<td>'.$variable['state_payment'][$row['state_payment']].'</td>';
            echo '<td>'.$row['payout_id'].'</td>';
            echo '<td>'.$row['zoom_id'].'</td>';
            //echo "<td>".$row['goal']."</td>";
            //echo "<td>".$row['homework']."</td>";
            //echo "<td>".$row['note']."</td>";
            echo '</tr>';
        }
    ?>
</table>
