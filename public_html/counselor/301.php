<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

// 檢查是不是綁定客戶
$sql = $mysql->query("Select * From `user` Where `id` = '$_GET[id]' And `counselor_id` = '$data_counselor[id]'");
if ($sql->num_rows != 1) {
    // 沒綁定的話檢查有沒有預約記錄
    $sql2 = $mysql->query("Select * From `appointment` Where `user_id` = '$_GET[id]' And `counselor_id` = '$data_counselor[id]'");
    if ($sql2->num_rows == 0) {
        header('location:300.php');
        exit();
    }
}
$row = $sql->fetch_assoc();
$sql2 = $mysql->query("Select * From `user_worry` Where `user_id` = '$_GET[id]' Order By `id` Desc");
$sql3 = $mysql->query("Select * From `appointment` Where `user_id` = '$_GET[id]' Order By `date` Desc, `time` Desc");

$data_counselor = load_counselor();
$list_counselor = list_counselor();

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="300.php">返回上一頁</a>
<br>
<br>
<a href="310.php?user_id=<?=$row['id']?>">為此個案預約下一個時段</a>
<br>
<br>
<table>
	<tr>
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
        echo '<tr>';
        echo '<td>'.$row['id'].'</td>';
        // echo '<td>'.$row['email'].'</td>';
        echo '<td>'.$row['name'].'</td>';
        // echo '<td>'.$row['phone_country'].'</td>';
        // echo '<td>'.$row['phone_number'].'</td>';
        echo '<td>'.$variable['age'][$row['age']].'</td>';
        echo '<td>'.$variable['gender'][$row['gender']].'</td>';
        echo '<td>'.$row['oupation'].'</td>';
        echo '</tr>';
    ?>
</table>

<h3>個案困擾</h3>
<ol>
    <?php
    $ans_sql = "Select ao.* From `user_answer` ua,`answer_options` ao Where ua.`user_id` = '$_GET[id]' AND ua.`id`=(
        SELECT max(id) FROM `user_answer` WHERE `user_id` = '$_GET[id]'
        ) and ao.`q_id`= 1 and FIND_IN_SET(ao.`opt_id` , ua.`answer1`) > 0";
    $user_answer = $mysql->query($ans_sql);
    while ($user_answer_array = $user_answer->fetch_assoc()) {
        echo '<li>'.$user_answer_array['opt_text'].'</li>';
    }
    ?>
</ol>
<h3>個案希望談論方式</h3>
<ol>
    <?php
    $ans_sql = "Select ao.* From `user_answer` ua,`answer_options` ao Where ua.`user_id` = '$_GET[id]' AND ua.`id`=(
        SELECT max(id) FROM `user_answer` WHERE `user_id` = '$_GET[id]'
        ) and ao.`q_id`= 2 and FIND_IN_SET(ao.`opt_id` , ua.`answer2`) > 0";
    $user_answer = $mysql->query($ans_sql);
    while ($user_answer_array = $user_answer->fetch_assoc()) {
        echo '<li>'.$user_answer_array['opt_text'].'</li>';
    }
    ?>
</ol>
<h3>留言紀錄</h3>

<table>
	<tr>
		<th>ID</th>
		<th>時間</th>
		<th>位置</th>
		<th>內容</th>
	</tr>
	<?php
        while ($row2 = $sql2->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row2['id'].'</td>';
            echo '<td>'.$row2['datetime'].'</td>';
            echo '<td>'.$row2['ip'].'</td>';
            echo '<td>'.$row2['content'].'</td>';
            echo '</tr>';
        }
    ?>
</table>

<h3>諮商紀錄</h3>

<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>諮商師</th>
		<th>費用</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
		<th>目標</th>
		<th>功課</th>
		<th>筆記</th>
	</tr>
	<?php
        while ($row3 = $sql3->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row3['id'].'</td>';
            echo '<td>'.$row3['date'].'</td>';
            echo '<td>'.$row3['time'].':00</td>';
            echo '<td>'.$list_counselor[$row3['counselor_id']]['name_ch'].'</td>';
            echo '<td>'.$row3['fee'].'</td>';
            echo '<td>'.$variable['state_counsel'][$row3['state_counsel']].'</td>';
            echo '<td>'.$variable['state_payment'][$row3['state_payment']].'</td>';
            echo '<td>'.$row3['goal'].'</td>';
            echo '<td>'.$row3['homework'].'</td>';
            echo '<td>'.$row3['note'].'</td>';
            echo '</tr>';
        }
    ?>
</table>
