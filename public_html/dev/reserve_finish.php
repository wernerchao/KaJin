<?

include("include_function.php");
include("include_setting.php");

user_login($_SESSION['email'], $_SESSION['password'], array("f_rd" => "login.php"));

$user_data = user_load();

if($user_data == "ERROR")
{
	header("location:logout.php");
	exit();
}

$sql = $mysql->query("Select * From `appointment` Where `id` = '$_GET[id]'");
$row = $sql->fetch_assoc();

if($row['user_id'] != $user_data['id'] || ($row['state_payment'] != 1 && $row['state_payment'] != 2) || get_date() > $row['date'] || (get_date() == $row['date'] && date("H", $system['time_now']) > $row['time']))
{
	header("location:panel.php");
	exit();
}

?>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
		<meta name="description" content="遠端線上心理諮詢平台 | 隱私專業保密的線上心理諮詢服務"/>
		<title>KAJIN HEALTH 遠端線上諮詢平台</title>
		<link href="panelstyle.css" rel="stylesheet" type="text/css" media="all">
		<script type="text/javascript" src="jquery-1.9.1.js"></script>
		<script>
			$(function(){
				$("#change_password_button").click(function(){
					$("#block").fadeIn(300);
					$("#popup_change_password").fadeIn(300);
				});
				$("#block").click(function(){
					$("#block").fadeOut(300);
					$("#popup_change_reserve").fadeOut(300);
					change_password_close();
					reserve_close();
				});
			});
			function change_password()
			{
				$("#popup_change_password").find(".box_warning_center").hide();
				$.ajax({
					url: "ajax_change_password.php",
					data: {old: $("#change_password_old").val(), new1: $("#change_password_new1").val(), new2: $("#change_password_new2").val()},
					type: "POST",
					dataType: "text",
					success: function(result){
						if(result == 1) $("#change_password_message_1").show();
						if(result == 2) $("#change_password_message_2").show();
						if(result == 3) $("#change_password_message_3").show();
						if(result == "succeed")
						{
							alert("修改成功，下次登入請使用新密碼。");
							change_password_close();
						}
					}
				});
			}
			function change_password_close()
			{
				$("#block").fadeOut(300);
				$("#popup_change_password").fadeOut(300);
				$("#popup_change_password").find(".box_warning_center").fadeOut(300);
				$("#change_password_old").val("");
				$("#change_password_new1").val("");
				$("#change_password_new2").val("");
			}
			function reserve_close()
			{
				$("#block").fadeOut(300);
				$("#popup_reserve").fadeOut(300);
			}
			function reserve(id)
			{
				$.ajax({
					url: "ajax_reserve.php",
					data: {id: id},
					type: "POST",
					dataType: "json",
					success: function(result){
						if(result.error == 1)
						{
							alert("本時段已經無法預約。");
							reserve_close();
							location.reload();
						}
						else
						{
							$("#block").fadeIn(300);
							$("#popup_reserve").fadeIn(300);
							$("#popup_reserve").find(".box_warning_center").hide();
							$('#reserve_agree').attr('checked', false);
							$("#reserve_date").html(result.date);
							$("#reserve_time").html(result.time);
							$("#reserve_counselor").html(result.counselor);
							$("#reserve_fee").html(result.fee);
							$("#appointment_id").val(id);
						}
					}
				});
			}
			function reserve_do()
			{
				if(!$("#reserve_agree").is(":checked"))
				{
					$("#reserve_message_1").show();
					return false;
				}
				return true;
			}
			function reserve_agree_click()
			{
				$("#reserve_message_1").hide();
			}
		</script>
		<? include("include_support.php"); ?>
	</head>
	<body>
		<div id="top">
			<div id="top_logo"><a href="./" target="_blank"><img src="img/logo.png"></a></div>
			<div id="top_kajin"><a href="./" target="_blank">KAJIN HEALTH</a></div>
			<div id="top_button">
				<span><img src="img/icon-person.png" style="position: relative; top: 6px;"></span>
				<span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php">登出</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="change_password_button">修改密碼</a></span>
			</div>
		</div>
		<div id="head">
			<div id="head_inner">
				<div id="menubar">
					<div class="menuitem nonactive left"><a href="panel.php">HOME</a></div>
					<div class="menuitem nonactive"><a href="contact.php">專人安排</a></div>
					<div class="menuitem active left"><a href="reserve.php">預約諮詢</a><img src="img/pointer.png" class="pointer"></div>
				</div>
			</div>
		</div>
		<div id="main">
			<div id="available">
				<div class="box_title">
					<img src="img/icon-talk.png">
					預約諮詢
				</div>
				<div id="reserve_finish_message">
					您已成功預約本時間，請點選下列連結進行付款，付款後12小時內工作人員將會確認您的付款並預約時段。<br>
					在付款時 Email 欄位請填寫您在本網站註冊的帳號 <?=$user_data['email']?>，以方便確認。<br>
					<br>
					<? if($row['fee'] == 1500) { ?>
					<div data-celery="5613283fbb178903007b0042" data-celery-type="embed" data-celery-version="v2">請稍候......</div>
					<script async type="text/javascript" src="https://www.trycelery.com/js/celery.js"></script>
					<? } else if($row['fee'] == 600) { ?>
					<div data-celery="562ed10cf52a8d03003af995" data-celery-type="embed" data-celery-version="v2">請稍候......</div>
					<script async type="text/javascript" src="https://www.trycelery.com/js/celery.js"></script>
					<? } ?>
				</div>
			</div>
		</div>
		<div id="bottom">
			<span><img src="img/logo_black.png" style="position: relative; top: 15px;"></span>
			<span>&nbsp;&nbsp;</span>
			<span id="bottom_kajin">KAJIN HEALTH</span>
			<span>&nbsp;&nbsp;</span>
			<span id="bottom_copyright">Copyright © 2015 KAJIN, Inc. All rights reserved.</span>
			<!--
			<span ><img src="img/logo_black.png"></span>
			<span >&nbsp;&nbsp;</span>
			<span id="bottom_kajin" style="height:43px; line-height:43px; border:1px solid;">KAJIN HEALTH</span>
			<span >&nbsp;&nbsp;</span>
			<span id="bottom_copyright">Copyright © 2015 KAJIN, Inc. All rights reserved.</span>
			-->
		</div>
		<div id="block">
		</div>
		<div id="popup_change_password" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">修改密碼</div>
				<form method="post">
					<table id="login_table">
						<tr>
							<td class="title">舊密碼</td>
							<td class="data"><input type="password" name="old" id="change_password_old" placeholder="請輸入舊密碼"></td>
						</tr>
						<tr>
							<td class="title">新密碼</td>
							<td class="data"><input type="password" name="new1" id="change_password_new1" placeholder="請輸入新密碼"></td>
						</tr>
						<tr>
							<td class="title">確認密碼</td>
							<td class="data"><input type="password" name="new2" id="change_password_new2" placeholder="請再次輸入新密碼"></td>
						</tr>
					</table>
					<input type="button" name="submit" value="修 改" class="button_image" onclick="change_password()">
				</form>
				<div class="box_warning_center" id="change_password_message_1" style="display:none;">舊密碼錯誤，請重新輸入。</div>
				<div class="box_warning_center" id="change_password_message_2" style="display:none;">新密碼兩次輸入不同，請重新輸入。</div>
				<div class="box_warning_center" id="change_password_message_3" style="display:none;">新密碼不可空白，請重新輸入。</div>
			</div>
		</div>
	</body>
</html>