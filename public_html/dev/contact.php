<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

// 讀取個案資料
$data_user = load_user();
if($data_user == "ERROR") logout_user(array("rd" => "login.php"));

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
				$("#block").click(function(){
					$("#block").fadeOut(300);
					contact_close();
				});
			});
			function contact()
			{
				$("#block").fadeIn(300);
				$("#popup_contact").fadeIn(300);
				$.ajax({
					url: "ajax_contact.php"
				});
			}
			function contact_close()
			{
				$("#block").fadeOut(300);
				$("#popup_contact").fadeOut(300);
			}
		</script>
		<? include("include_hotjar.php"); ?>
		<? include("include_support.php"); ?>
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
					<div class="menuitem nonactive left"><a href="panel.php">HOME</a></div>
					<div class="menuitem active"><a href="contact.php">專人安排</a><img src="img/pointer.png" class="pointer"></div>
					<div class="menuitem nonactive"><a href="reserve.php">預約諮詢</a></div>
					<div class="menuitem nonactive right"><a href="board.php">訊息通知</a></div>
				</div>
			</div>
		</div>
		<div id="main">
			<div id="contact">
				<div class="box_title">
					<img src="img/icon-person.png">
					專人安排
				</div>
				<div id="contact_text">
					　　據統計，有超過 200 萬人受情緒、行為、人際關係困擾，但要找到合適、能幫助的心理諮商師，一個人平均要花上 5 年，以及許多冤枉錢。透過 KaJin Health 諮詢前的專人心理評估技術，我們將先分析您的困擾，為您安排正確適合的諮詢師來協助您。<br>
					<br>
					　　若您不確定您要找誰預約，或是不太清楚自己的困擾，KaJin 會有受過訓練的專員與您聯絡，了解您的問題，並且安排適當的專業老師與您諮詢，謝謝。
					<input type="submit" name="submit" value="請與我聯絡" class="button_image" onclick="contact()">
				</div>
			</div>
		</div>
		<? include("include_bottom.php"); ?>
		<!-- JQuery 及 popup 區 -->
		<div id="block">
		</div>
		<div id="popup_contact" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">專人安排</div>
				<table id="reserve_table">
					<tr>
						<td class="data" colspan="2">我們已收到您的訊息，我們將會儘快以下列聯絡方式聯絡您，謝謝！</td>
					</tr>
					<tr>
						<td class="title">E-mail</td>
						<td class="data"><?=$data_user['email']?></td>
					</tr>
					<tr>
						<td class="title">電話</td>
						<td class="data"><?=$data_user['phone_number']?></td>
					</tr>
				<table>
				<input type="submit" name="submit" value="確認" class="button_image" onclick="contact_close()">
			</div>
		</div>
	</body>
</html>