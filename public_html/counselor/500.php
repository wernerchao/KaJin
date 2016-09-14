<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

$sql = $mysql->query("Select * From `payout` Where `counselor_id` = '$data_counselor[id]' Order By `id` Desc");

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<table>
	<tr>
		<th>查看</th>
		<th>ID</th>
		<th>日期</th>
		<th>金額</th>
		<!--<th>備註</th>-->
	</tr>
	<?php
        while ($row = $sql->fetch_assoc()) {
            echo '<tr>';
            echo '<td><a href="501.php?id='.$row['id'].'">查看</a></td>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['date'].'</td>';
            echo '<td>'.$row['amount'].'</td>';
            //echo "<td>".$row['note']."</td>";
            echo '</tr>';
        }
    ?>
</table>
