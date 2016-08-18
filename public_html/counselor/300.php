<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

$sql = $mysql->query("Select * From `user` Where `counselor_id` = '$data_counselor[id]' Order By `id` Desc");

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<table>
	<tr>
		<th>查看</th>
		<th>ID</th>
		<!-- <th>Email</th> -->
		<th>姓名</th>
		<!-- <th>國碼</th> -->
		<!-- <th>電話</th> -->
		<th>年齡</th>
		<th>性別</th>
		<th>職業</th>
	</tr>
	<?php
        while ($row = @$sql->fetch_assoc()) {
            echo '<tr>';
            echo '<td><a href="301.php?id='.$row['id'].'">查看</a></td>';
            echo '<td>'.$row['id'].'</td>';
            // echo "<td>".$row['email']."</td>";
            echo '<td>'.$row['name'].'</td>';
            // echo "<td>".$row['phone_country']."</td>";
            // echo "<td>".$row['phone_number']."</td>";
            echo '<td>'.$variable['age'][$row['age']].'</td>';
            echo '<td>'.$variable['gender'][$row['gender']].'</td>';
            echo '<td>'.$row['oupation'].'</td>';
            echo '</tr>';
        }
    ?>
</table>
