<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

// 讀取個案資料
$data_user = load_user();
if($data_user == "ERROR") logout_user(array("rd" => "login.php"));

// 讀取資商師清單
$list_counselor = list_counselor();

/* 讀取 panel.php 要顯示的資料 */

// 讀取最後一筆我的煩惱
$last_worry = get_last_worry($data_user['id']);

// 讀取個案所有預約
$list_appointment = list_appointment(array("user_id" => $data_user['id']));

// 讀取個案下一個預約 (為了顯示在諮詢通知)
$next_appointment = get_next_appointment($data_user['id']);

// 讀取個案最後的目標和功課
$last_homework = get_last_homework($data_user['id']);

?>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
		<meta name="description" content="遠端線上心理諮詢平台 | 隱私專業保密的線上心理諮詢服務"/>
		<title>KAJIN HEALTH 遠端線上諮詢平台</title>
		<link href="panelstyle.css" rel="stylesheet" type="text/css" media="all">
		<link rel="shortcut icon" href="img/favicon.ico">
		<script type="text/javascript" src="jquery-1.9.1.js"></script>
		<script>
			$(function(){
				$("#change_password_button").click(function(){
					$("#block").fadeIn(300);
					$("#popup_change_password").fadeIn(300);
				});
				$("#change_information_button").click(function(){
					$("#block").fadeIn(300);
					$("#popup_change_information").fadeIn(300);
				});
				$("#change_worry_button").click(function(){
					$("#block").fadeIn(300);
					$("#popup_change_worry").fadeIn(300);
				});
				$("#block").click(function(){
					$("#block").fadeOut(300);
					$("#popup_change_information").fadeOut(300);
					$("#popup_change_worry").fadeOut(300);
					change_password_close();
				});
			});
			function change_password_close()
			{
				$("#block").fadeOut(300);
				$("#popup_change_password").fadeOut(300);
				$("#popup_change_password").find(".box_warning_center").fadeOut(300);
				$("#change_password_old").val("");
				$("#change_password_new1").val("");
				$("#change_password_new2").val("");
			}
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
		</script>
		<? include("include_hotjar.php"); ?>
		<? include("include_support.php"); ?>
		<? if($_GET['tutor']) include("include_tutor.php"); ?>
	</head>
	<body>
	<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WFLZTQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WFLZTQ');</script>
  <!-- End Google Tag Manager -->
  
		<div id="top">
			<div id="top_logo"><a href="./" target="_blank"><img src="img/logo.png"></a></div>
			<div id="top_kajin"><a href="./" target="_blank">KAJIN HEALTH</a></div>
			<div id="top_button">
				<span><img src="img/icon-person.png" style="position: relative; top: 6px;"></span>
				<span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php">登出</a></span>
			</div>
		</div>
		<div id="head">
			<div id="head_inner">
				<div id="menubar">
					<div class="menuitem active left"><a href="panel.php">HOME</a><img src="img/pointer.png" class="pointer"></div>
					<div class="menuitem nonactive"><a href="contact.php">專人安排</a></div>
					<div class="menuitem nonactive"><a href="reserve.php">預約諮詢</a></div>
					<div class="menuitem nonactive right"><a href="board.php">訊息通知</a></div>
				</div>
			</div>
		</div>
		<div id="main">
			<div id="information">
				<div class="box_title">
					<img src="img/icon-person.png">
					基本資料
				</div>
				<div style="position:absolute;left:270px;top:40px;font-size:16;">
					<a href="#" id="change_information_button">修改資料</a>
					<a href="#" id="change_password_button">修改密碼</a>
				</div>
				<table id="information_table">
					<tr class="ul">
						<td class="title">姓名</td>
						<td class="data"><?=$data_user['name']?></td>
					</tr>
					<tr class="ul">
						<td class="title">性別</td>
						<td class="data"><?=$variable['gender'][$data_user['gender']]?></td>
					</tr>
					<tr class="ul">
						<td class="title">年齡</td>
						<td class="data"><?=$variable['age'][$data_user['age']]?></td>
					</tr>
					<tr class="ul">
						<td class="title">職業</td>
						<td class="data"><?=$data_user['occupation']?></td>
					</tr>
					<tr class="ul">
						<td class="title">國碼</td>
						<td class="data"><?=$data_user['phone_country']?></td>
					</tr>
					<tr class="ul">
						<td class="title">電話</td>
						<td class="data"><?=$data_user['phone_number']?></td>
					</tr>
					<tr>
						<td class="title">Email</td>
						<td class="data" id="data"><?=$data_user['email']?></td>
					</tr>
				</table>
			</div>
			<div id="notice">
				<div class="box_title">
					<img src="img/icon-mail.png">
					<font color="white">諮詢通知</font>
					<? if($next_appointment == FALSE && count_fee($data_user['id'], "twd") == 800) { ?>
					<div id="notice_empty">您沒有即將進行的諮詢<br><a href="./start/">點選這裡進行新的預約</a></div>
					<? } else if($next_appointment == FALSE) { ?>
					<div id="notice_empty">您沒有即將進行的諮詢<br><a href="reserve.php">點選這裡進行新的預約</a></div>
					<? } else { ?>
					<div id="notice_next">您已經確認的下一次諮詢為<br><font color="red"><?=$next_appointment['date']?> <?=sprintf("%02d", $next_appointment['time'])?>:00</font><br><br>請您在開始之前 10 分鐘<br><a href="work_meet.php?appointment_id=<?=$next_appointment['id']?>" target="_blank">由此進入線上諮商室</a></div>
					<? } ?>
				</div>
			</div>
			<div id="worry">
				<div class="box_title">
					<img src="img/icon-message.png">
					我的煩惱
				</div>
				<div style="position:absolute;left:270px;top:40px;font-size:16;">
					<a href="#" id="change_worry_button">修改我的煩惱</a>
				</div>
				<div id="worry_word">
					<?=$last_worry?>
				</div>
			</div>
			<? if($last_homework) { ?>
			<div id="goal">
				<div class="box_title">
					<img src="img/icon-message.png">
					我的目標
				</div>
				<div id="worry_word">
					<?=str_replace("\r\n", "<br>", $last_homework['goal'])?>
				</div>
			</div>
			<div id="homework">
				<div class="box_title">
					<img src="img/icon-message.png">
					我的功課
				</div>
				<div id="worry_word">
					<?=str_replace("\r\n", "<br>", $last_homework['homework'])?>
				</div>
			</div>
			<? } ?>
			<div id="appointment">
				<div class="box_title">
					<img src="img/icon-talk.png">
					諮詢與紀錄
				</div>
				<?
					if($list_appointment)
					{
						echo "<table id=\"appointment_table\">";
						foreach($list_appointment as $k => $v)
						{
				?>
					<tr class="tl">
						<td class="date"><?=$v['date']?></td>
						<td class="time"><?=sprintf("%02d",$v['time'])?>:00</td>
						<td class="counselor"><?=$list_counselor[$v['counselor_id']]['name_ch']?></td>
						<td class="state">
							<?
								if($v['state_payment'] == 0 || $v['state_payment'] == 1) echo "未付款";
								else if($v['state_payment'] == 2) echo "付款待確認";
								else if($v['state_payment'] == 3)
								{
									if(is_datetime_passed($v['date'], $v['time']) == 1) echo "已結束";
									else if(is_datetime_passed($v['date'], $v['time']) == 0) echo "進行中";
									else if(is_datetime_passed($v['date'], $v['time']) == -1) echo "即將進行";
								}
								else if($v['state_payment'] == 4) echo "待退款";
								else if($v['state_payment'] == 5) echo "已退款";
							?>
						</td>
						<td class="evaluate">
							<? if(($v['state_counsel'] == 2 || $v['state_counsel'] == 3 || $v['state_counsel'] == 4 || $v['state_counsel'] == 9) || ($v['state_payment'] == 3 && is_datetime_passed($v['date'], $v['time']) == 1)) { ?>
							<a href="http://www.kajinonline.com/satisfaction_form.html" target="_blank">滿意度調查</a>
							<? } ?>
						</td>
						<td class="pay">
							<? if(($v['state_payment'] == 0 || $v['state_payment'] == 1 || $v['state_payment'] == 2) && is_datetime_passed($v['date'], $v['time']) == -1) { ?>
							<a href="payment.php?appointment_id=<?=$v['id']?>">我要付款</a>
							<? } ?>
						</td>
					</tr>
					<?
							}
							echo "</table>";
						}
						else
						{
							echo "<div id=\"appointment_message\">您還沒有任何諮詢預約記錄，<a href=\"./start/\">點選這裡進行預約</a>。</div>";
						}
					?>
			</div>
		</div>
		<? include("include_bottom.php"); ?>
		<!-- JQuery 及 popup 區 -->
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
		<div id="popup_change_information" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">修改資料</div>
				<form action="work_change_information.php" method="post">
					<table id="login_table">
						<tr>
							<td class="title">姓名</td>
							<td class="data"><input type="text" name="name" value="<?=htmlspecialchars($data_user['name'])?>"></td>
						</tr>
						<tr>
							<td class="title">性別</td>
							<td class="data">
								<input type="radio" name="gender" value="1"<?=(($data_user['gender']==1)?" checked":"")?>>男
								<input type="radio" name="gender" value="2"<?=(($data_user['gender']==2)?" checked":"")?>>女
							</td>
						</tr>
						<tr>
							<td class="title">年齡</td>
							<td class="data">
								<select name="age">
									<option></option>
								<?
									foreach($variable['age'] as $k => $v)
									{
										echo "<option value=\"$k\"".(($k==$data_user['age'])?" selected":"").">$v</option>";
									}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="title">職業</td>
							<td class="data"><input type="text" name="occupation" value="<?=htmlspecialchars($data_user['occupation'])?>"></td>
						</tr>
						<tr>
							<td class="title">國碼</td>
							<td class="data"><input type="text" name="phone_country" value="<?=htmlspecialchars($data_user['phone_country'])?>"></td>
						</tr>
						<tr>
							<td class="title">電話</td>
							<td class="data"><input type="text" name="phone_number" value="<?=htmlspecialchars($data_user['phone_number'])?>"></td>
						</tr>
					</table>
					<input type="submit" name="submit" value="修 改" class="button_image" onclick="change_information()">
				</form>
			</div>
		</div>
		<div id="popup_change_worry" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">修改我的煩惱</div>
				<form action="work_change_worry.php" method="post">
					<table id="login_table">
						<tr>
							<td class="data"><textarea name="worry" cols="54" rows="7"><?=htmlspecialchars($last_worry)?></textarea></td>
						</tr>
					</table>
					<input type="submit" name="submit" value="修 改" class="button_image" onclick="change_worry()">
				</form>
			</div>
		</div>
	</body>
</html>