<?php

include 'include_function.php';
include 'include_setting.php';
// include 'include_setting_test.php';

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array('f_rd' => 'login.php'));

// 讀取個案資料
$data_user = load_user();
if ($data_user == 'ERROR') {
    logout_user(array('rd' => 'login.php'));
}

// 讀取資商師清單
$list_counselor = list_counselor();

// 讀取預約資料
$sql = $mysql->query("Select * From `appointment` Where `id` = '$_GET[appointment_id]'");
$data_appointment = $sql->fetch_assoc();

// 檢查是否是自己的預約或是是空的預約，若已被別人預約則跳到錯誤頁
if ($data_appointment['user_id'] != $data_user['id'] && $data_appointment['user_id'] != 0) {
    header('location:error.php?type=A1');
    exit();
}

// 檢查是否已經超過時間 (現在的小時也不准預約)
if (is_datetime_passed($data_appointment['date'], $data_appointment['time'], array('pre' => $system['pre_hour'])) != -1) {
    header('location:error.php?type=tle');
    exit();
}

// 檢查是否已付款
if ($data_appointment['state_payment'] != 0 && $data_appointment['state_payment'] != 1 && $data_appointment['state_payment'] != 2) {
    header('location:error.php?type=A2');
    exit();
}

// 算費用
$fee_text = count_fee($data_user['id'], 'text', array('default' => $data_appointment['fee']));
$fee_twd = count_fee($data_user['id'], 'twd', array('default' => $data_appointment['fee']));
$fee_usd = count_fee($data_user['id'], 'usd', array('default' => $data_appointment['fee']));

?>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
		<meta name="description" content="遠端線上心理諮詢平台 | 隱私專業保密的線上心理諮詢服務"/>
		<title>KAJIN HEALTH 遠端線上諮詢平台</title>
		<link href="panelstyle.css" rel="stylesheet" type="text/css" media="all">
		<script type="text/javascript" src="jquery-1.9.1.js"></script>
		<link rel="shortcut icon" href="img/favicon.ico">
        <script src="https://checkout.stripe.com/checkout.js"></script>
		<script>
			$(function(){
                var coupon_code;
                var final_fee = <?= $fee_usd; ?>;
                var fee = <?= $fee_usd; ?>;
				$("#block").click(function(){
					$("#block").fadeOut(300);
				});
                $('#coupon_chk').click(function(event) {
                    if (fee == 2400) { // 第一次預約
        				$.ajax({
        					url: '../ajax_api_conpon_confirm.php',
        					dataType: 'json',
        					data: {
        						code: $('#coupon_input').val()
        					}
        				}).done(function(result) {
        					if (result.status == 0) {
        						// console.log(final_fee_twd, final_fee_usd, fee);
        						// final_fee_twd = fee.twd * result.discount;
        						final_fee = fee * result.discount;
        						coupon_code = $('#coupon_input').val();
        						$('#coupon_hint').html('<p>使用代碼後，您本次諮詢費用為美金' + final_fee / 100 + '元。（原價' + fee / 100 + '元）</p>');
        						// console.log(final_fee_twd, final_fee_usd, fee);
        					} else {
        						// final_fee_twd = fee.twd;
        						// final_fee_usd = fee.usd;
        						if (result.status == 999) {
        							$('#coupon_hint').html('<p>優惠代碼無效，請重新輸入。</p>');
        						} else if (result.status == 100) {
        							$('#coupon_hint').html('<p>您已使用過此用代碼。</p>');
        						}
        					}
        				}).fail(function() {
        					alert('系統出現異常，請重新操作！');
        				});
        			} else { // 非第一次預約
        				$('#coupon_hint').html('<p>優惠代碼僅限首次諮詢使用。</p>');
        			}
                });
                $('#pay_btn').on('click', function() {
        			var self = this;
        			var handler = StripeCheckout.configure({
        				// key: 'pk_test_6pRNASCoBOKtIshFeQd4XMUh',
        				key: 'pk_live_jL7Bu4XKJxHi7uQh7eqzPde9',
        				image: './img/logo.png',
        				locale: 'auto',
        				alipay: true,
        				closed: function() {
        					$(self).parent().children().prop('disabled', false);
        					setTimeout(function() {
        						$('iframe.stripe_checkout_app').remove();
        					}, 100);
        				},
        				token: function(res) {
        					$.ajax({
        						url: 'work_payment.php', //real
        						type: 'POST',
        						data: {
        							appointment_id: <?=$_GET['appointment_id']?>,
        							fee: final_fee,
                                    coupon: coupon_code,
                                    token: res.id
        						},
        						dataType: 'json',
        						success: function(data) {
        							if (data.status == 0) {
        								window.location = 'finish.php?appointment_id=<?=$_GET[appointment_id]?>';
        							} else
                                        window.location = 'error.php?type='+ data.status;
        								// alert('付款失敗，請重新操作。');
        						}
        					});
        				}
        			});
        			handler.open({
        				name: 'Kajin Health',
        				description: '預約付款：美金' + (final_fee / 100) + '元',
        				amount: final_fee
        			});
        		});
			});
		</script>
		<?php include 'include_hotjar.php'; ?>
		<?php include 'include_support.php'; ?>
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
					預約諮詢
				</div>
				<div id="reserve_finish_message">
					您欲預約的資訊如下<br>
					<br>
					諮商師：<?=$list_counselor[$data_appointment['counselor_id']]['name_ch']?><br>
					日期：<?=$data_appointment['date']?><br>
					時間：<?=sprintf('%02d:00', $data_appointment['time'])?><br>
					<br>
					若以上資訊無誤，您可點選下方的按鈕進行付款。
					<?php
                        if ($fee_twd == 800) {
                            echo '這是您第一次預約，諮商費用為 800 元 (約美金 24 元)。';
                        } elseif ($fee_twd == 2000) {
                            echo '費用為 2000 元 (約美金 60 元)。';
                        } else {
                            echo "費用為 $fee_twd 元 (約美金 ".($fee_usd / 100).' 元)。';
                        }
                    ?>
                    <br>
                    <p>
                        若您有優惠代碼，請輸入後按下確認鍵。
                        <br>
                        優惠代碼：<input type="text" id="coupon_input" style="height:inherit;">
                        &nbsp;&nbsp;<button id="coupon_chk" class="rate_btn" type="button" name="button">確認</button>
                    </p>
                    <p id="coupon_hint" style="color:green"></p>
					<br>
                    <button type="button" class="rate_btn" id="pay_btn">前往付款</button>
				</div>
			</div>
		</div>
		<?php include 'include_bottom.php'; ?>
		<!-- JQuery 及 popup 區 -->
		<div id="block">
		</div>
	</body>
</html>
