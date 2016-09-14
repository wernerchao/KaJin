<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

// 讀取個案資料
$data_user = load_user();
if($data_user == "ERROR") logout_user(array("rd" => "login.php"));

// 如果沒諮商記錄就進入導引
/*if(count_fee($data_user['id'], "twd") == 800)
{
	header("./start/");
	exit();
}*/

// 讀取資商師清單
$list_counselor = list_counselor();

// 設定月曆顯示日期起迄
$date_start = get_date(array("input" => "now", "weekfirst" => TRUE));
$date_end = get_date(array("input" => $date_start, "range" => 20));

// 暫訂：若沒有filter就指定為1號
if(!$_GET['counselor_id']) $_GET['counselor_id'] = 1;

// 檢查個案是否已被鎖定諮商師
if($data_user['counselor_id'])
{
	// 被鎖定
	$sql = $mysql->query("Select * From `appointment` Where `date` Between '$date_start' And '$date_end' And `user_id` = '0' And `counselor_id` = '$data_user[counselor_id]' Order By `date` Asc, `time` Asc");
}
else if($_GET['counselor_id'])
{
	// 沒被鎖定但 filter 有指定
	$sql = $mysql->query("Select * From `appointment` Where `date` Between '$date_start' And '$date_end' And `user_id` = '0' And `counselor_id` = '$_GET[counselor_id]' Order By `date` Asc, `time` Asc");
}
else
{
	// 沒鎖定而且 filter 沒指定
	$sql = $mysql->query("Select * From `appointment` Where `date` Between '$date_start' And '$date_end' And `user_id` = '0' Order By `date` Asc, `time` Asc");
}

// 讀到陣列裡
while($row = $sql->fetch_assoc())
{
	$available[substr($row['date'], 0, 4)][substr($row['date'], 5, 2)][substr($row['date'], 8, 2)][$row['id']] = $row;
}

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
					reserve_close();
				});
			});
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
							alert("本時段已無法預約，因諮商必須於<?=$system['pre_hour']?>小時前預約並付款。");
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
							$("#reserve_counselor").html(result.counselor+"　<a href='work_counselor_intro.php?id="+result.counselor_id+"' target='_blank'>詳細資歷</a>");
							$("#reserve_fee").html(result.fee);
							$("#appointment_id").val(id);
						}
					}
				});
			}
			function reserve_close()
			{
				$("#block").fadeOut(300);
				$("#popup_reserve").fadeOut(300);
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
					<div class="menuitem active"><a href="reserve.php">預約諮詢</a><img src="img/pointer.png" class="pointer"></div>
					<div class="menuitem nonactive right"><a href="board.php">訊息通知</a></div>
				</div>
			</div>
		</div>
		<div id="main">
			<div id="available">
				<div class="box_title">
					<img src="img/icon-talk.png">
					可預約時段
				</div>
				<?
					// 沒鎖定諮商師才顯示 filter
					if($data_user['counselor_id'] == 0)
					{
				?>
				<div id="available_counselor_list">
					<!--<span class="available_counselor_item<?=((!$_GET['counselor_id'])?" available_counselor_item_selected":"")?>"><a href="reserve.php?counselor_id=0">所有諮商師</a></span>-->
					<?
						foreach($list_counselor as $k => $v)
						{
							if($k == 7) continue;
							if($k == 11) continue;
							echo "<span class=\"available_counselor_item".(($k==$_GET['counselor_id'])?" available_counselor_item_selected":"")."\"><a href=\"reserve.php?counselor_id=$k\">".$v['name_ch']."</a></span>";
						}
						if(count_fee($data_user['id'], "twd") == 800)
						{
							echo "<div style=\"clear:both;\"></div>";
							echo "<span class=\"available_counselor_item\"><a href=\"./start/\">我不知道哪位老師適合我，請幫我配對</a></span>";
						}
					?>
					<div style="clear:both;"></div>
				</div>
				<?
					}
				?>
				<table id="available_table" class="box_radius">
					<tr>
						<th>星期日</th>
						<th>星期一</th>
						<th>星期二</th>
						<th>星期三</th>
						<th>星期四</th>
						<th>星期五</th>
						<th>星期六</th>
					</tr>
					<?
						$date_this = $date_start;
						for($i=0; $i<21; $i++)
						{
							if($i % 7 == 0) echo "<tr>";
							// day in the past
							if($date_this < get_date())
							{
								echo "<td class=\"past\">$date_this";
							}
							// today
							if($date_this == get_date())
							{
								echo "<td class=\"today\">$date_this<br>";
							}
							// day in the future
							if($date_this > get_date())
							{
								echo "<td class=\"future\">$date_this<br>";
							}
							// list all available slots
							if($date_this >= get_date())
							{
								if(count($available[substr($date_this, 0, 4)][substr($date_this, 5, 2)][substr($date_this, 8, 2)]))
								{
									foreach($available[substr($date_this, 0, 4)][substr($date_this, 5, 2)][substr($date_this, 8, 2)] as $k => $v)
									{
										echo "<div class=\"slot\"><a onclick=\"reserve($k)\">";
										if(is_datetime_passed($date_this, $v['time'], array("pre" => $system['pre_hour'])) != -1) echo "<font color=\"#aaaaaa\">";
										echo sprintf("%02d", $v['time']).":00 - ".$list_counselor[$v['counselor_id']]['name_ch'];
										echo "</a></div>";
									}
								}
							}
							echo "</td>";
							if($i % 7 == 6) echo "</tr>";
							$date_this = get_date(array("input" => $date_this, "range" => 1));
						}
					?>
				</table>
			</div>
		</div>
		<? include("include_bottom.php"); ?>
		<!-- JQuery 及 popup 區 -->
		<div id="block">
		</div>
		<div id="popup_reserve" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">預約諮詢</div>
				<table id="reserve_table">
					<tr>
						<td class="title">日期</td>
						<td class="data" id="reserve_date"></td>
					</tr>
					<tr>
						<td class="title">時間</td>
						<td class="data" id="reserve_time"></td>
					</tr>
					<tr>
						<td class="title">諮商師</td>
						<td class="data" id="reserve_counselor"></td>
					</tr>
					<tr>
						<td class="title">費用</td>
						<td class="data" id="reserve_fee"></td>
					</tr>
					<tr>
						<td  class="title" colspan="2"><input type="checkbox" id="reserve_agree" onclick="reserve_agree_click()"> 我已詳閱<a href="agreement.pdf" target="_blank">網路心理諮詢同意書</a></td>
					</tr>
				</table>
				<form action="work_reserve.php" method="post">
					<input type="submit" name="submit" value="預約" class="button_image" onclick="if(!reserve_do())return false;">
					<input type="hidden" name="appointment_id" value="" id="appointment_id">
				</form>
				<div class="box_warning_center" id="reserve_message_1" style="display:none;">請先閱讀並同意網路諮詢同意書。</div>
			</div>
		</div>
	</body>
</html>