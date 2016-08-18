<?

include("include_function.php");
include("include_setting.php");

if($_POST['submit'] && $_POST['step'] == 2)
{
	$_POST['email'] = trim($_POST['email']);
	$sql = $mysql->query("Select * From `user` Where `email` = '$_POST[email]'");
	if($sql->num_rows != 1) $result = FALSE;
	else
	{
		$reset_code = rand(10000000, 99999999);
		$mysql->query("Update `user` Set `reset_code` = '$reset_code' Where `email` = '$_POST[email]'");
		notification("forget", $_POST['email'], array("reset_code" => $reset_code));
		header("location:forget.php?step=3");
		exit();
	}
}
if($_GET['step'] == 4 || $_POST['step'] == 5)
{
	if($_GET['reset_code']) $reset_code = $_GET['reset_code'];
	else $reset_code = $_POST['reset_code'];
	if($_GET['email']) $email = $_GET['email'];
	else $email = $_POST['email'];
	$sql = $mysql->query("Select * From `user` Where `email` = '$email'");
	$row = $sql->fetch_assoc();
	if($sql->num_rows != 1 || $row['reset_code'] != $reset_code || $reset_code == "")
	{
		header("location:forget.php?step=9");
		exit();
	}
	if($_POST['step'] == 5)
	{
		if($_POST['password1'] != $_POST['password2'])
		{
			$result = FALSE;
			$error = 2;
			$_GET['step'] = 4;
		}
		else if($_POST['password1'] == "")
		{
			$result = FALSE;
			$error = 1;
			$_GET['step'] = 4;
		}
		else
		{
			$md5 = md5($_POST['password1']);
			$mysql->query("Update `user` Set `password` = '$md5', `reset_code` = '' Where `email` = '$email'");
			header("location:forget.php?step=6");
			exit();
		}
	}
}

?>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
		<meta name="description" content="遠端線上心理諮詢平台 | 隱私專業保密的線上心理諮詢服務"/>
		<title>KAJIN HEALTH 遠端線上諮詢平台</title>
		<link href="panelstyle.css" rel="stylesheet" type="text/css" media="all">
		<script type="text/javascript" src="jquery-1.9.1.js"></script>
	</head>
	<body>
		<div id="greenback"></div>
		<div id="popup_login" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">忘記密碼</div>
				<? if($_GET['step'] == 3) { ?>
				<div class="box_message_center">
					我們已寄送一封郵件到您的 E-mail，請至您的信箱收信依指示更改密碼。
				</div>
				<? } else if($_GET['step'] == 4) { ?>
				<!--<div class="box_message_center">
					請設定您的新密碼
				</div>-->
				<form action="forget.php" method="post">
					<table id="login_table" class="div_center">
						<tr>
							<td class="title">設定新密碼</td>
							<td class="data"><input type="password" name="password1"></td>
						</tr>
						<tr>
							<td class="title">確認密碼</td>
							<td class="data"><input type="password" name="password2"></td>
						</tr>
					</table>
					<input type="submit" name="submit" value="重 設" class="button_image">
					<input type="hidden" name="email" value="<?=$email?>">
					<input type="hidden" name="reset_code" value="<?=$reset_code?>">
					<input type="hidden" name="step" value="5">
				</form>
				<?=(($result==FALSE && $error==1)?"<div class=\"box_warning_center\">新密碼不可空白，請重新輸入。</div>":"")?>
				<?=(($result==FALSE && $error==2)?"<div class=\"box_warning_center\">新密碼兩次輸入不同，請重新輸入。</div>":"")?>
				<? } else if($_GET['step'] == 6) { ?>
				<div class="box_message_center">
					您的新密碼已經設定完成，請使用您的新密碼登入系統。
					<form action="login.php" method="post">
						<input type="submit" name="tologin" value="前往登入頁面" class="button_image">
					</form>
				</div>
				<? } else if($_GET['step'] == 9) { ?>
				<div class="box_message_center">
					您的連結已經失效，請您重新申請設定密碼。
					<form action="forget.php" method="post">
						<input type="submit" name="toforget" value="重新操作" class="button_image">
					</form>
				</div>
				<? } else { ?>
				<div class="box_message_center">
					請輸入您當初註冊時使用的 E-mail
				</div>
				<form action="forget.php" method="post">
					<table id="login_table" class="div_center">
						<tr>
							<td class="title">帳號 (E-mail)</td>
							<td class="data"><input type="text" name="email" value="<?=htmlspecialchars($_POST['email'])?>"></td>
						</tr>
					</table>
					<input type="submit" name="submit" value="重 設" class="button_image">
					<input type="hidden" name="redirect" value="<?=$_GET['redirect']?>">
					<input type="hidden" name="step" value="2">
				</form>
				<?=(($result==FALSE && $_POST['submit'])?"<div class=\"box_warning_center\">此 E-mail 尚未註冊，請確認是否輸入錯誤。</div>":"")?>
				<div id="login_create" style="top: 280px;"><a href="login.php">回上一頁</a></div>
				<? } ?>
			</div>
		</div>
	</body>
</html>