<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

// 讀取個案資料
$data_user = load_user();
if($data_user == "ERROR") logout_user(array("rd" => "login.php"));

if($_POST)
{
	if(trim($_POST['message']) != "")
	{
		// 寫入資料庫
		$mysql->query("Insert Into `message` (`user_id`, `date`, `time`, `timestamp`, `sender`, `type`, `content`) Values ('$data_user[id]', '".get_date()."', '".date("H:i", $system['time_now'])."', '$system[time_now]', '1', '0', '$_POST[message]')");
		// 寄信通知管理員
		notification("message_from_user", $system['admin_email'], array("email" => $data_user['email'], "message" => $_POST['message']));
		// 重讀網頁
		header("location:board.php");
		exit();
	}
}

// 讀取所有留言
$sql = $mysql->query("Select * From `message` Where `user_id` = '$data_user[id]' Order By `timestamp` Desc");

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
				});
			});
			function get_message(id)
			{
				$.ajax({
					url: "ajax_get_message.php",
					data: {id: id},
					type: "POST",
					dataType: "json",
					success: function(result){
						if(result.error == 1) alert("訊息讀取失敗。");
						else
						{
							//alert(result.content);
							if(result.sender == 1)
							{
								$(".message_box").hide();
								$("#message_box_you").show();
								$("#message_box_you").find(".inbox_item_title").text(result.title);
								$("#message_box_you").find(".inbox_item_datetime").text(result.datetime);
								$("#message_box_you").find(".message_content").html(result.content);
							}
							if(result.sender == 0)
							{
								$(".message_box").hide();
								$("#message_box_kajin").show();
								$("#message_box_kajin").find(".inbox_item_title").text(result.title);
								$("#message_box_kajin").find(".inbox_item_datetime").text(result.datetime);
								$("#message_box_kajin").find(".message_content").html(result.content);
							}
						}
					}
				});
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
					<div class="menuitem nonactive"><a href="contact.php">專人安排</a></div>
					<div class="menuitem nonactive"><a href="reserve.php">預約諮詢</a></div>
					<div class="menuitem active right"><a href="board.php">訊息通知</a><img src="img/pointer.png" class="pointer"></div>
				</div>
			</div>
		</div>
		<div id="main">
			<div id="compose">
				<form action="board.php" method="post">
					<textarea name="message" id="compose_message" placeholder="您可以利用對話窗與 KaJin 平台對話，不管您有任何的問題，都請您留下您的訊息，我們會在最短的時間回覆您。KaJin 平台也會使用 Email 和對話窗與您對話，請您放心，這個對話窗只有您本人與 KaJin 平台會看到。"></textarea>
					<input type="submit" name="submit" value="留言發問" class="button_image" style="margin-top: 0px; margin-left: 780px;">
				</form>
			</div>
			<div id="inbox">
				<?
					while($row = $sql->fetch_assoc())
					{
						if($row['sender'] == 0)
						{
							echo "<div class=\"inbox_item\">";
							echo "<div class=\"inbox_item_photo circle_kajin\">";
							echo "<div class=\"inbox_item_photo_name name_kajin\">Kajin</div>";
							echo "</div>";
							echo "<div class=\"inbox_item_sender name_kajin\">KAJIN 心理諮詢小幫手</div>";
							echo "<div class=\"inbox_item_title name_kajin\" onclick=\"get_message($row[id]);\">";
							if($row['type'] == 0) echo mb_strimwidth(trim(htmlspecialchars($row['content'])), 0, 30, '...', 'UTF-8');
							else echo $row['title'];
							echo "</div>";
							echo "<div class=\"inbox_item_datetime\">".date("Y.m.d g:i a", $row['timestamp'])."</div>";
							echo "</div>";
						}
						else if($row['sender'] == 1)
						{
							echo "<div class=\"inbox_item\">";
							echo "<div class=\"inbox_item_photo circle_you\">";
							echo "<div class=\"inbox_item_photo_name name_you\">You</div>";
							echo "</div>";
							echo "<div class=\"inbox_item_sender name_you\">$data_user[name]</div>";
							echo "<div class=\"inbox_item_title name_you\" onclick=\"get_message($row[id]);\">".mb_strimwidth(htmlspecialchars($row['content']), 0, 30, '...', 'UTF-8')."</div>";
							echo "<div class=\"inbox_item_datetime\">".date("Y.m.d g:i a", $row['timestamp'])."</div>";
							echo "</div>";
						}
					}
				?>
			</div>
			<div class="message_box">
			</div>
			<div class="message_box" style="display:none;" id="message_box_you">
				<div class="message_top">
					<div class="inbox_item_photo circle_you">
						<div class="inbox_item_photo_name name_you">You</div>
					</div>
					<div class="inbox_item_sender name_you"><?=$data_user['name']?></div>
					<div class="inbox_item_title name_you" class="massage_box_title">1</div>
					<div class="inbox_item_datetime" class="massage_box_datetime"></div>
				</div>
				<div class="message_content">123</div>
			</div>
			<div class="message_box" style="display:none;" id="message_box_kajin">
				<div class="message_top">
					<div class="inbox_item_photo circle_kajin">
						<div class="inbox_item_photo_name name_kajin">Kajin</div>
					</div>
					<div class="inbox_item_sender name_kajin">KAJIN 心理諮詢小幫手</div>
					<div class="inbox_item_title name_kajin" class="massage_box_title"></div>
					<div class="inbox_item_datetime" class="massage_box_datetime"></div>
				</div>
				<div class="message_content"></div>
			</div>
		</div>
		<? include("include_bottom.php"); ?>
		<!-- JQuery 及 popup 區 -->
		<div id="block">
		</div>
	</body>
</html>