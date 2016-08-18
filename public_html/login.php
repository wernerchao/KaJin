<?php

include 'include_function.php';
include 'include_setting.php';

if ($_POST['submit'] && trim($_POST['email']) != '') {
    if ($_POST['create'] == 1) {
        $result = login_user($_POST['email'], $_SESSION['default_password'], array('s_save' => '1'));
        if ($result == true) {
            $_SESSION['default_password'] = '';
            // 更新使用者自行設定的密碼
            $update_pswd_query = "UPDATE `user` SET `password`='".md5($_POST['password'])."' WHERE `email` ='".$_POST['email']."'";
            $sql = $mysql->query($update_pswd_query);
            // if ($sql) {
                // echo '已更新密碼！';
            // }
            $_SESSION['user_password'] = $_POST['password'];
            //header("location:panel.php?tutor=1");
            //改成導入引導步驟
            header('location:./start/');
            exit();
        }
    } else {
        $result = login_user($_POST['email'], $_POST['password'], array('s_save' => '1'));
        if ($result == 1) {
            if ($_POST['redirect']) {
                header('location:'.$_POST['redirect']);
            } else {
                header('location:panel.php');
            }
            exit();
        }

        // 如果登入失敗要留存 redirect 資料
        $_GET['redirect'] = $_POST['redirect'];
    }
}

// 如果有 redirect 資料，先檢查是否已經登入，若是已登入就轉址過去，若是未登入或登入失敗就把 session 清掉並顯示登入畫面
if ($_GET['redirect']) {
    login_user($_SESSION['user_email'], $_SESSION['user_password'], array('s_rd' => $_GET['redirect']));
    // if failed, delete session email, in order to show the login form with redirect value
    logout_user();
}

// 如果 session 有資料而且不是試圖登入的話就檢查是否能登入，若是登入失敗就把 session 清掉並顯示登入畫面
if ($_SESSION['user_email'] && !$_POST['submit']) {
    login_user($_SESSION['user_email'], $_SESSION['user_password'], array('s_rd' => 'panel.php'));
    logout_user();
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
	  <!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WFLZTQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WFLZTQ');</script>
  <!-- End Google Tag Manager -->

		<div id="greenback"></div>
		<div id="popup_login" class="div_innerline_out">
			<div class="div_innerline_in">
				<div class="box_title_center">登入系統</div>
				<form action="login.php" method="post">
					<table id="login_table" class="div_center">
						<tr>
							<td class="title">帳號 (E-mail)</td>
							<td class="data"><input type="text" name="email" value="<?=htmlspecialchars($_POST['email'])?>"></td>
						</tr>
						<tr>
							<td class="title">密碼</td>
							<td class="data"><input type="password" name="password"></td>
						</tr>
					</table>
					<input type="submit" name="submit" value="登 入" class="button_image">
					<input type="hidden" name="redirect" value="<?=$_GET['redirect']?>">
				</form>
				<?=(($result == false && $_POST['submit']) ? '<div class="box_warning_center">帳號或密碼錯誤，請重新輸入。</div>' : '')?>
				<div id="login_create"><a href="index.html#leave_a_message">建立新帳號</a><br><a href="forget.php">忘記密碼</a></div>
			</div>
		</div>
	</body>
</html>
