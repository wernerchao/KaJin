<?php

include("include_function.php");
include("include_setting.php");

/********** data transformation **********/

// email
$_POST['Email'] = trim($_POST['Email']);
// phone_country
$_POST[custom_U8475] = $_POST[custom_U14847];
// phone_number
$_POST[custom_U807] = $_POST[custom_U9683];
// age
$_POST[custom_U5503] = $_POST[custom_U9687];
// gender
$_POST[custom_U2291] = "0";
// worry
$_POST[custom_U794] = $_POST[custom_U9660];

// 得知管道
// $_POST[custom_U794] = $_POST[custom_U9660];

/********** transformation ended **********/

// prevent page refreshing
if($_SESSION['default_password'])
{
	//header("location:login.php");
	//exit();
}

// prevent direct link
if($_POST['Email'] == "")
{
	//echo "empty";
	header("location:index.php");
	exit();
}

// check if this user exists
$now = date("Y-m-d H:i:s", $system['time_now']);
$sql = $mysql->query("Select * From `user` Where `email` = '$_POST[Email]'");

// user 不存在，需要設定密碼
if($sql->num_rows == 0)
{
	$exist = FALSE;
	shuffle($list_random_name);

	// 將手機存成密碼
	$password = eregPhone($_POST['custom_U807']);
	$password_md5 = md5($password);
	$str = "Insert Into `user` (`active`, `email`, `password`, `name`, `phone_country`, `phone_number`, `age`, `gender`, `register_ip`, `register_time`) Values ('1', '$_POST[Email]', '$password_md5', '$list_random_name[0]', '$_POST[custom_U8475]', '$_POST[custom_U807]', '$_POST[custom_U5503]', '$_POST[custom_U2291]', '$_SERVER[REMOTE_ADDR]', '$now')";
	$mysql->query($str);
	$user_id = $mysql->insert_id;
	$_SESSION['default_password'] = $password;
	notification("new", $system['admin_email'], array("email" => $_POST['Email'], "message" => $_POST[custom_U794]));
	notification("registration_confirmed", $_POST['Email'], array("email" => $_POST['Email'], "password" => $password));
}
else
{
	$exist = TRUE;
	$sql = $mysql->query("Select * From `user` Where `email` = '$_POST[Email]'");
	$row = $sql->fetch_assoc();
	$user_id = $row['id'];
	$mysql->query("Update `user` Set `active` = '1' Where `id` = '$user_id'");
}

// save worry of this user
$str = "Insert Into `user_worry` (`user_id`, `datetime`, `content`, `ip`) Values ('$user_id', '$now', '$_POST[custom_U794]', '$_SERVER[REMOTE_ADDR]')";
$mysql->query($str);

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
		<?php
			if($exist == FALSE)
			{
		?>
		<div id="greenback"></div>
		<div id="popup_login" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">登入系統</div>
				<div class="box_message_center">
					留言送出後，KaJin 將為您設立帳號，<br>
					協助您獲得完整的諮詢服務。
				</div>
				<form id="set_pswd_form" action="login.php" method="post">
					<table id="login_table" class="div_center">
						<tr>
							<td class="title">帳號 (E-mail)</td>
							<td class="data"><?php echo $_POST['Email']?></td>
						</tr>
						<tr>
							<td class="title">設定密碼</td>
							<td class="data"><input type="password" name="password"></td>
						</tr>
						<tr>
							<td class="title">再次輸入密碼</td>
							<td class="data"><input type="password" name="password_check"></td>
						</tr>
					</table>
					<!-- <div class="box_warning_center">帳號為您的電子郵件，密碼預設為您的手機號碼。</div> -->
					<input type="submit" name="submit" value="進入系統" class="button_image">
					<input type="hidden" name="email" value="<?php echo $_POST['Email']?>">
					<input type="hidden" name="create" value="1">
				</form>
				<div id="pswd_hint" class="box_warning_center" style="display: none;">兩次密碼輸入不同，請再次確認。</div>
			</div>
		</div>
		<?php
			}
			elseif($exist == TRUE)
			{
		?>
		<div id="greenback"></div>
		<div id="popup_login" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">登入系統</div>
				<div class="box_message_center">
					您曾經在本平台留言，請您輸入您的密碼，以登入平台。<br>
					如您未曾修改過密碼，則預設密碼為您當時輸入的電話號碼(不含國碼)。
				</div>
				<form action="login.php" method="post">
					<table id="login_table" class="div_center">
						<tr>
							<td class="title">帳號 (E-mail)</td>
							<td class="data"><?php echo $_POST['Email']?></td>
						</tr>
						<tr>
							<td class="title">密碼</td>
							<td class="data"><input type="password" name="password"></td>
						</tr>
					</table>
					<input type="submit" name="submit" value="登 入" class="button_image">
					<input type="hidden" name="email" value="<?php echo $_POST['Email']?>">
				</form>
			</div>
		</div>
		<?php
			}
		?>
	</body>
	<script type="text/javascript">
		$(document).ready(function() {
			$(document).on('submit','#set_pswd_form', function(e){
			// $('#set_pswd_form').on('submit', function(e){
		        
		        var pswd = $('input[name="password"]').val();
		        var pswd_chk = $('input[name="password_check"]').val();
		        if (pswd == pswd_chk) {
		            // alert("submit");
		            // $('#set_pswd_form').submit();
		           // return true;
		        } else {
		        	e.preventDefault();
		        	$('#pswd_hint').show();
		           return false;
		        }
	    	});
		});
	</script>
</html>