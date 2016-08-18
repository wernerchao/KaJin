<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

$sql = $mysql->query('Select * From `counselor` Order By `id` Desc');

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<table>
	<tr>
		<th>查看</th>
		<th>ID</th>
		<th>Email</th>
		<th>姓名(中文)</th>
		<th>姓名(英文)</th>
		<th>照片</th>
		<th>付款方式</th>
		<th>諮詢專長</th>
		<th>學歷與經歷</th>
		<th>專業執照</th>
		<th>相關著作</th>
	</tr>
	<?php
        while ($row = $sql->fetch_assoc()) {
            echo '<tr>';
            echo '<td><a href="201.php?id='.$row['id'].'">查看</a></td>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['email'].'</td>';
            echo '<td>'.$row['name_ch'].'</td>';
            echo '<td>'.$row['name_en'].'</td>';
            echo '<td>'.$row['photo'].'</td>';
            echo '<td>'.$row['payout_method'].'</td>';
            echo '<td><pre>'.$row['introduction'].'</pre></td>';
            echo '<td><pre>'.$row['experience'].'</pre></td>';
            echo '<td><pre>'.$row['certificate'].'</pre></td>';
            echo '<td><pre>'.$row['writing'].'</pre></td>';
            echo '</tr>';
        }
    ?>
</table>
