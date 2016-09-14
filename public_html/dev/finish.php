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
		<script type="text/javascript" src="jquery-1.9.1.js"></script>
		<link rel="shortcut icon" href="img/favicon.ico">
		<script>
			$(function(){
				$("#block").click(function(){
					$("#block").fadeOut(300);
				});
			});
		</script>
		<? include("include_hotjar.php"); ?>
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
					<div class="menuitem active"><a href="reserve.php">預約諮詢</a><img src="img/pointer.png" class="pointer"></div>
					<div class="menuitem nonactive right"><a href="board.php">訊息通知</a></div>
				</div>
			</div>
		</div>
		<div id="main">
			<div id="available">
				<div class="box_title">
					<img src="img/icon-talk.png">
					付款成功
				</div>
				<div id="reserve_finish_message">
					感謝您選擇 Kajin，我們已寄信通知諮商師，敬請您在預約的時段回到本網站，屆時就可以與老師對談。
				</div>
			</div>
		</div>
		<? include("include_bottom.php"); ?>
		<!-- JQuery 及 popup 區 -->
		<div id="block">
		</div>
	</body>
</html>