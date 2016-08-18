<?php

include '../include_function.php';
include '../include_setting.php';

if ($_POST['submit'] && $_POST['step'] == 2) {
    $_POST['email'] = trim($_POST['email']);
    $sql = $mysql->query("Select * From `counselor` Where `email` = '$_POST[email]'");
    if ($sql->num_rows != 1) {
        $result = false;
    } else {
        $reset_code = rand(10000000, 99999999);
        $mysql->query("Update `counselor` Set `reset_code` = '$reset_code' Where `email` = '$_POST[email]'");
        notification('forget_counselor', $_POST['email'], array('reset_code' => $reset_code));
        header('location:forget.php?step=3');
        exit();
    }
}
if ($_GET['step'] == 4 || $_POST['step'] == 5) {
    if ($_GET['reset_code']) {
        $reset_code = $_GET['reset_code'];
    } else {
        $reset_code = $_POST['reset_code'];
    }
    if ($_GET['email']) {
        $email = $_GET['email'];
    } else {
        $email = $_POST['email'];
    }
    $sql = $mysql->query("Select * From `counselor` Where `email` = '$email'");
    $row = $sql->fetch_assoc();
    if ($sql->num_rows != 1 || $row['reset_code'] != $reset_code || $reset_code == '') {
        header('location:forget.php?step=9');
        exit();
    }
    if ($_POST['step'] == 5) {
        if ($_POST['password1'] != $_POST['password2']) {
            $result = false;
            $error = 2;
            $_GET['step'] = 4;
        } elseif ($_POST['password1'] == '') {
            $result = false;
            $error = 1;
            $_GET['step'] = 4;
        } else {
            $md5 = md5($_POST['password1']);
            $mysql->query("Update `counselor` Set `password` = '$md5', `reset_code` = '' Where `email` = '$email'");
            header('location:forget.php?step=6');
            exit();
        }
    }
}

?>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>諮商師後台系統</title>

<h3>忘記密碼</h3>

<?php if ($_GET['step'] == 3) {
    ?>

	我們已寄送一封郵件到您的 E-mail，請至您的信箱收信依指示更改密碼。<br><br>

	<a href="login.php">返回</font>

<?php
} elseif ($_GET['step'] == 4) {
    ?>

	<form action="forget.php" method="post">
		<table>
			<tr>
				<td>設定新密碼</td>
				<td><input type="password" name="password1"></td>
			</tr>
			<tr>
				<td>確認密碼</td>
				<td><input type="password" name="password2"></td>
			</tr>
		</table>
		<input type="submit" name="submit" value="重設密碼">
		<input type="hidden" name="email" value="<?=$email?>">
		<input type="hidden" name="reset_code" value="<?=$reset_code?>">
		<input type="hidden" name="step" value="5">
	</form>

	<?=(($result == false && $error == 1) ? '<font color="red">新密碼不可空白，請重新輸入。</font>' : '')?>
	<?=(($result == false && $error == 2) ? '<font color="red">新密碼兩次輸入不同，請重新輸入。</font>' : '')?>

<?php
} elseif ($_GET['step'] == 6) {
    ?>

	您的新密碼已經設定完成，請使用您的新密碼登入系統。<br><br>

	<a href="login.php">前往登入頁面</font>

<?php
} elseif ($_GET['step'] == 9) {
    ?>

	您的連結已經失效，請您重新申請設定密碼。<br><br>

	<a href="forget.php">重新操作</font>

<?php
} else {
    ?>

	請輸入您的 E-mail<br><br>

	<form action="forget.php" method="post">
		<table>
			<tr>
				<td>帳號 (E-mail)</td>
				<td><input type="text" name="email" value="<?=htmlspecialchars($_POST['email'])?>"></td>
			</tr>
		</table>
		<input type="submit" name="submit" value="重設密碼">
		<input type="hidden" name="step" value="2">
	</form>

	<?=(($result == false && $_POST['submit']) ? '<font color="red">此 E-mail 不存在，請確認是否輸入錯誤。</font><br><br>' : '')?>
	<a href="login.php">回上一頁</a>

<?php
} ?>
