<?php

$db_host = 'localhost';
$db_user = 'onlinesystem';
$db_pass = 'S1ZSPMW2015555';
$db_name = 'onlinesystem';

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
if (!$mysql) {
    exit('DB error');
}
$mysql->query('SET NAMES UTF8');

$time_now = time() + 28800;
$date_this = date('Y-m-d', $time_now);
$time_this = date('H', $time_now);
$minute = date('i', $time_now);

$sql_string = 'Select * From `counselor` Order By `id`';
$sql = $mysql->query($sql_string);

while ($row = mysqli_fetch_assoc($sql)) {
    $list_counselor[$row['id']] = $row;
}

if ($time_this == 23) {
    $mail_date = date('Y-m-d', $time_now + 86400);
    $mail_hour = 0;
} else {
    $mail_date = $date_this;
    $mail_hour = $time_this + 1;
}

// 找下個小時的預約記錄
$sql = $mysql->query("Select * From `appointment` Where `user_id` <> '0' And `state_payment` = '3' And `date` = '$mail_date' And `time` = '$mail_hour'");
while ($row = $sql->fetch_assoc()) {
    if (!strstr($row['message'], 'm30')) { // 沒有被標記過通知
        // 取得 user data
        $sql2 = $mysql->query("Select * From `user` Where `id` = '$row[user_id]'");
        $row2 = $sql2->fetch_assoc();
        // 寄信通知 user
        notification('remind_user', $row2['email'], array('date' => $mail_date, 'time' => sprintf('%02d', $mail_hour), 'email' => $row2['email'], 'counselor' => $list_counselor[$row['counselor_id']]['name_ch']));
        // 寄信通知老師
        notification('remind_counselor', $list_counselor[$row['counselor_id']]['email'], array('date' => $mail_date, 'time' => sprintf('%02d', $mail_hour), 'email' => $row2['email'], 'counselor' => $list_counselor[$row['counselor_id']]['name_ch']));
        // 寄信通知 admin
        notification('remind_admin', 'support@kajinhealth.com, jin@kajinhealth.com, wilson@kajinhealth.com, otwo.kajin@gmail.com', array('date' => $mail_date, 'time' => sprintf('%02d', $mail_hour), 'email' => $row2['email'], 'counselor' => $list_counselor[$row['counselor_id']]['name_ch']));
        // 標記 user 已經記過通知信
        $message_new = $row['message'].' m30 ';
        $mysql->query("Update `appointment` Set `message` = '$message_new' Where `id` = '$row[id]'");
    }
}

function notification($type, $email, $var = array())
{
    if ($type == 'remind_user') {
        $message = "$var[email] 您好：\n<br>\n<br>您預約了 $var[counselor] 老師在 $var[date] $var[time]:00 進行諮詢。請您準備好心情，提早五分鐘上線，老師會在會議室裡跟您會談，謝謝！\n<br>\n<br>視訊方式\n<br>　1. 請登入 <a href=\"http://kajinonline.com/login.php\" target=\"_blank\">KaJin Health 系統</a>\n<br>　2. 點選諮詢通知上的視訊連結 (如果沒有 Zoom 請點選連結下載)\n<br>　3. 再次點選連結，開始跟老師會談\n<br>\n<br>KaJin Health 團隊關心您";
        $title = 'KaJin 預約諮詢提醒';
        echo "已寄信至 $email 提醒個案預約時間\n";
    }
    if ($type == 'remind_counselor') {
        $message = "$var[counselor]老師您好：\n<br>\n<br>您的個案「$var[email]」與您約在 $var[date] $var[time]:00 進行諮詢，請您提早十分鐘前上線準備，謝謝！\n<br>\n<br>KaJin Health 團隊關心您";
        $title = 'KaJin 預約諮詢提醒';
        echo "已寄信至 $email 提醒老師預約時間\n";
    }
    if ($type == 'remind_admin') {
        $message = "個案「$var[email]」與諮詢師「$var[counselor]」將於 $var[date] $var[time]:00 (UTC+8)進行諮詢。";
        $title = 'KaJin 預約諮詢提醒';
        echo "已寄信至 $email 提醒 Admin 有預約時段\n";
    }
    $headers = 'From: KaJin Health 線上心理諮詢平台<support@kajinonline.com>'."\n"; //寄件者
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    mail($email, '=?utf-8?B?'.base64_encode($title).'?=', $message, $headers);
}
