<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

if ($_POST['change_information']) {
    $mysql->query("Update `counselor` Set `payout_method` = '$_POST[payout_method]' Where `id` = '$data_counselor[id]'");
    echo "<script>alert('個人資料已修改。');</script>";
}

if ($_POST['change_password']) {
    if (md5($_POST['old']) != $data_counselor['password']) {
        echo "<script>alert('舊密碼錯誤。');</script>";
    } elseif ($_POST['new1'] != $_POST['new2']) {
        echo "<script>alert('新密碼兩次輸入不同。');</script>";
    } elseif ($_POST['new1'] == '') {
        echo "<script>alert('新密碼不可空白。');</script>";
    } else {
        $password = md5($_POST['new1']);
        $mysql->query("Update `counselor` Set `password` = '$password' Where `id` = '$data_counselor[id]'");
        $_SESSION['counselor_password'] = $_POST['new1'];
        echo "<script>alert('密碼已修改。');</script>";
    }
}

$data_counselor = load_counselor();
$data_counselor['languages'] = str_replace(array('CH', 'EN'), array('中文', '英文'), $data_counselor['languages']);
?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<p>
    <a href="101.php">修改個人資料</a>
</p>
<table>
	<tr>
		<th>ID</th>
		<th>E-mail</th>
		<th>姓名(中文)</th>
		<th>姓名(英文)</th>
        <th>聯絡電話</th>
		<th>地區</th>
		<th>諮詢語言</th>
	</tr>
	<tr>
		<td><?=$data_counselor['id']?></td>
		<td><?=$data_counselor['email']?></td>
		<td><?=$data_counselor['name_ch']?></td>
		<td><?=$data_counselor['name_en']?></td>
		<td><?=$data_counselor['tel']?></td>
		<td><?=$data_counselor['location']?></td>
		<td><?=$data_counselor['languages']?></td>
	</tr>
</table>
<br>

<table>
	<tr>
		<th>單次諮詢費用</th>
    </tr>
	<tr>
		<td><?=$data_counselor['price']?></td>
	</tr>
</table>
<p>
    （目前 Kajin Health 的諮詢費用統一為首次 USD$24、往後每次 USD$60。
    <br>
    我們正在規劃開放讓諮詢師自行訂定諮詢費用，您可以在本欄位預先填寫自訂收費，功能上線後我們會通知您。）
</p>
<h3>您擅長的諮詢主題</h3>
<ol>
    <?php
    $ans_sql = "SELECT ao.* FROM `counselor` c,`answer_options` ao WHERE c.`id` = '$data_counselor[id]' AND ao.`q_id`= 1 and FIND_IN_SET(ao.`opt_id` , c.`topics`) > 0";
    $topics = $mysql->query($ans_sql);
    while ($topics_array = $topics->fetch_assoc()) {
        echo '<li>'.$topics_array['opt_text'].'</li>';
    }
    ?>
</ol>
<h3>您擅長的學派</h3>
<ol>
	<?php
    $ans_sql = "SELECT ao.* FROM `counselor` c,`answer_options` ao WHERE c.`id` = '$data_counselor[id]' AND ao.`q_id`= 2 and FIND_IN_SET(ao.`opt_id` , c.`schools`) > 0";
    $schools = $mysql->query($ans_sql);
    while ($schools_array = $schools->fetch_assoc()) {
        echo '<li>'.$schools_array['counselor_opt'].'</li>';
    }
    ?>
</ol>
<br>
<table>
	<tr>
		<th>給個案的話</th>
    </tr>
	<tr>
		<td><?=$data_counselor['words']?></td>
	</tr>
</table>
<br>
<table>
	<tr>
		<th>個人網站或部落格</th>
        <th>個人介紹或相關影片</th>
    </tr>
	<tr>
		<td><?=$data_counselor['website']?></td>
        <td><?=$data_counselor['video_url']?></td>
	</tr>
</table>

<!-- <h3>修改個人資料</h3> -->
<!-- <form action="100.php" method="post">
	<table>
		<tr>
			<th>付款方式 (銀行+帳號)</th>
			<th>修改</th>
		<tr>
		<tr>
			<td><textarea name="payout_method" cols="25" rows="4"><?=htmlspecialchars($data_counselor['payout_method'])?></textarea></td>
			<td><input type="submit" name="change_information" value="修改"></td>
		</tr>
	</table>
</form> -->

<h3>修改密碼</h3>
<form action="100.php" method="post">
	<table>
		<tr>
			<th>舊密碼</th>
			<th>新密碼</th>
			<th>確認新密碼</th>
			<th>修改</th>
		<tr>
		<tr>
			<td><input type="password" name="old"></td>
			<td><input type="password" name="new1"></td>
			<td><input type="password" name="new2"></td>
			<td><input type="submit" name="change_password" value="修改"></td>
		</tr>
	</table>
</form>
