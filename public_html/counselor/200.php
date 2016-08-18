<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

$sql = $mysql->query("Select * From `appointment` Where `counselor_id` = '$data_counselor[id]' And `user_id` <> '0' Order By `date` Desc, `time` Desc");

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<table>
	<tr>
		<th>查看</th>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>個案ID</th>
		<th>費用</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
		<th>交款ID</th>
		<th>zoom ID</th>
		<th>回報</th>
		<th>評價</th>
	</tr>
	<?php
        while ($row = $sql->fetch_assoc()) {
            echo '<tr>';
            echo '<td><a href="201.php?id='.$row['id'].'">查看</a></td>'; //
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['date'].'</td>';
            echo '<td>'.$row['time'].':00</td>';
            echo '<td><a href="301.php?id='.$row['user_id'].'">'.$row['user_id'].'</a></td>';
            echo '<td>'.$row['fee'].'</td>';
            echo '<td>'.$variable['state_counsel'][$row['state_counsel']].'</td>';
            echo '<td>'.$variable['state_payment'][$row['state_payment']].'</td>';
            echo '<td>'.$row['payout_id'].'</td>';
            echo '<td>'.$row['zoom_id'].'</td>';
            echo '<td>'.$row['report'].'</td>';
            echo '<td>'.$row['evaluate'].'</td>';
            echo '</tr>';
        }
    ?>
</table>
