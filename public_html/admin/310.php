<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

if ($_POST['submit']) {
    if ($_POST['counselor_id'] && $_POST['year'] && $_POST['month'] && $_POST['day']) {
        if ($_POST['hour']) {
            $date = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
            foreach ($_POST['hour'] as $v) {
                $str = "Insert Into `appointment` (`counselor_id`, `date`, `time`) Values ('$_POST[counselor_id]', '$date', '$v')";
                $mysql->query($str);
            }
            echo "<script>alert('時段已新增！');</script>";
        } else {
            echo "<script>alert('未選擇時段！');</script>";
        }
    } else {
        echo "<script>alert('諮商師及日期不可空白！');</script>";
    }
}

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="300.php">返回上一頁</a>
<br>
<br>
<form action="310.php" method="post">
	<table>
		<tr>
			<td>諮商師</td>
			<td>
				<select name="counselor_id">
				<option value="0"></option>
				<?php
                    foreach ($list_counselor as $k => $v) {
                        echo '<option value="'.$k.'">'.$v['name_ch'].'</option>';
                    }
                ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>日期</td>
			<td>
				<input type="text" name="year" size="4">年
				<input type="text" name="month" size="2">月
				<input type="text" name="day" size="2">日
			</td>
		</tr>
		<tr>
			<td>時間</td>
			<td>
				<?php
                    for ($i = 0; $i < 24; ++$i) {
                        echo '<input type="checkbox" name="hour[]" value="'.$i.'">'.$i.':00 ';
                        if ($i % 6 == 5) {
                            echo '<br>';
                        }
                    }
                ?>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" name="submit" value="新增">
</form>
