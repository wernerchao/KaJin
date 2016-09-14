<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

if ($_POST['delete']) {
    $sql = $mysql->query("Select * From `appointment` Where `user_id` = '$_POST[id]'");
    if ($sql->num_rows == 0) {
        $mysql->query("Update `user` Set `active` = '0' Where `id` = '$_POST[id]'");
        header('location:100.php');
        exit();
    } else {
        echo "<script>alert('此個案已經相關資料，無法刪除。');</script>";
    }

    $_GET['id'] = $_POST['id'];
}

if ($_POST['change_information']) {
    if ($_POST['password']) {
        $md5 = md5($_POST['password']);
        $mysql->query("Update `user` Set `password` = '$md5' Where `id` = '$_POST[id]'");
        echo "<script>alert('密碼已修改。');</script>";
    }

    $mysql->query("Update `user` Set `note` = '$_POST[note]' Where `id` = '$_POST[id]'");
    echo "<script>alert('備註已修改。');</script>";

    $_GET['id'] = $_POST['id'];
}

if ($_POST['change_counselor']) {
    $mysql->query("Update `user` Set `counselor_id` = '$_POST[counselor_id]' Where `id` = '$_POST[id]'");
    echo "<script>alert('鎖定諮商師已修改。');</script>";

    $_GET['id'] = $_POST['id'];
}

// 發起新留言
if ($_POST['new_message']) {
    if ($_POST['message']) {
        $mysql->query("Insert Into `message` (`user_id`, `date`, `time`, `timestamp`, `sender`, `type`, `content`, `replied`) Values ('$_POST[id]', '".get_date()."', '".date('H:i', $system['time_now'])."', '$system[time_now]', '0', '0', '$_POST[message]', '1')");
        $sql_tmp = $mysql->query("Select * From `user` Where `id` = '$_POST[id]'");
        $row_tmp = $sql_tmp->fetch_assoc();
        notification('message_from_admin', $row_tmp['email'], array('message' => $_POST['message']));
        echo '<script>alert("留言已送出！");</script>';
    }

    $_GET['id'] = $_POST['id'];
}

$sql = $mysql->query("Select * From `user` Where `id` = '$_GET[id]'");
if ($sql->num_rows != 1) {
    header('location:100.php');
    exit();
}
$row = $sql->fetch_assoc();

$sql2 = $mysql->query("Select * From `user_worry` Where `user_id` = '$_GET[id]' Order By `id` Desc");

$sql3 = $mysql->query("Select * From `appointment` Where `user_id` = '$_GET[id]' Order By `date` Desc, `time` Desc");

$sql4 = $mysql->query("Select * From `message` Where `user_id` = '$_GET[id]' Order By `timestamp` Desc");

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="100.php">返回上一頁</a>
<br>
<br>
<table>
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>姓名</th>
		<th>國碼</th>
		<th>電話</th>
		<th>年齡</th>
		<th>性別</th>
		<th>職業</th>
		<th>刪除</th>
	</tr>
	<?php
        echo '<tr>';
        echo '<td>'.$row['id'].'</td>';
        echo '<td>'.$row['email'].'</td>';
        echo '<td>'.$row['name'].'</td>';
        echo '<td>'.$row['phone_country'].'</td>';
        echo '<td>'.$row['phone_number'].'</td>';
        echo '<td>'.$variable['age'][$row['age']].'</td>';
        echo '<td>'.$variable['gender'][$row['gender']].'</td>';
        echo '<td>'.$row['oupation'].'</td>';
        echo "<td><form action=\"101.php\" method=\"post\"><input type=\"submit\" name=\"delete\" value=\"刪除\" onclick=\"if(confirm('確定要刪除嗎？'))return true;else return false;\"><input type=\"hidden\" name=\"id\" value=\"".$row['id'].'"></form></td>';
        echo '</tr>';
    ?>
</table>

註冊日期：<?=$row['register_time']?><br>
註冊位置：<?=$row['register_ip']?><br>

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

<h3>修改資料</h3>

<form action="101.php" method="post">
	<table>
		<tr>
			<th>備註</th>
			<th>密碼 (不修改則空白)</th>
			<th>修改</th>
		<tr>
		<tr>
			<td><textarea name="note" cols="25" rows="4"><?=htmlspecialchars($row['note'])?></textarea></td>
			<td><input type="text" name="password"></td>
			<td><input type="submit" name="change_information" value="修改"></td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?=$row['id']?>">
</form>

<h3>鎖定諮商師</h3>

<form action="101.php" method="post">
	<select name="counselor_id">
		<option value="0"<?=(($row['counselor_id'] == 0) ? ' selected' : '')?>>無</option>
		<?php
            foreach ($list_counselor as $k => $v) {
                echo '<option value="'.$k.'"'.(($row['counselor_id'] == $k) ? ' selected' : '').'>'.$v['name_ch'].'</option>';
            }
        ?>
	</select>
	<input type="submit" name="change_counselor" value="修改">
	<input type="hidden" name="id" value="<?=$row['id']?>">
</form>

<hr>

<h3>留言記錄</h3>

<form action="101.php" method="post">
	<table>
		<tr>
			<th>發起新的留言</th>
			<th>送出</th>
		</tr>
		<tr>
			<td><textarea name="message" cols="60" rows="4"></textarea></td>
			<td><input type="submit" name="new_message" value="送出"></td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?=$row['id']?>">
</form>

<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>內容</th>
	</tr>
	<?php
        while ($row4 = $sql4->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row4['id'].'</td>';
            echo '<td>'.$row4['date'].'</td>';
            echo '<td>'.$row4['time'].'</td>';
            echo '<td>'.(($row4['sender'] == 1) ? '個案' : 'Kajin').'</td>';
            echo '<td>'.$row4['content'].'</td>';
            echo '</tr>';
        }
    ?>
</table>


<h3>煩惱記錄</h3>

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
		<th>個案ID</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
		<th>zoom ID</th>
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
            echo '<td><a href="101.php?id='.$row3['user_id'].'">'.$row3['user_id'].'</a></td>';
            echo '<td>'.$variable['state_counsel'][$row3['state_counsel']].'</td>';
            echo '<td>'.$variable['state_payment'][$row3['state_payment']].'</td>';
            echo '<td>'.$row3['zoom_id'].'</td>';
            echo '<td>'.$row3['goal'].'</td>';
            echo '<td>'.$row3['homework'].'</td>';
            echo '<td>'.$row3['note'].'</td>';
            echo '</tr>';
        }
    ?>
</table>
