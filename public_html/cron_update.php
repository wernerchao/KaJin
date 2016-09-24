<?php

$db_host = 'localhost';
$db_user = 'onlinesystem';
$db_pass = 'S1ZSPMW2015555';
$db_name = 'onlinesystem';

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
if (!$mysql) {
    exit('DB error');
}

$title = '我們需要您最新的時間';
$message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/schedule.png"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">我們需要您最新的時間</td></tr><tr height="50"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080">快來更新您可以的諮詢的時間，請點擊下方，我們將引導您更新您於 KaJin 上的諮詢時間。</font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/counselor/login.php?redirect=400.php" target="_blank"><img src="https://kajinonline.com/mail/update.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></tr></tr></table>';

$headers = 'From: KaJin Health 線上心理諮詢平台<support@kajinonline.com>'."\n"; //寄件者
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

$sql_string = 'SELECT * FROM `counselor` ORDER BY `id` WHERE active=1';
$sql = $mysql->query($sql_string);
while ($row = mysqli_fetch_assoc($sql)) {
    $counselor[] = $row;
}

foreach ($counselor as $c) {
    echo $c['email']."\r\n";
    mail($c['email'], '=?utf-8?B?'.base64_encode($title).'?=', $message, $headers);
}

mail('jin@kajinhealth.com', '=?utf-8?B?'.base64_encode($title).'?=', $message, $headers);
mail('Werner@kajinhealth.com', '=?utf-8?B?'.base64_encode($title).'?=', $message, $headers);
mail('Rachel@kajinhealth.com', '=?utf-8?B?'.base64_encode($title).'?=', $message, $headers);
