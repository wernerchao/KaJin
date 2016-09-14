<?php

function sqlChars($data)
{
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $result[$k] = sqlChars($v);
        }
    }
    //else $result = str_replace("'", "'", trim($data));
    else {
        $result = mysql_real_escape_string(trim($data));
    }

    return $result;
}

function eregPhone($str)
{
    $before = array('０', '１', '２', '３', '４', '５', '６', '７', '８', '９', '－', '-', '＃', '＊', '　', ' ');
    $after = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '', '', '#', '*', '', '');

    $str = trim($str);
    for ($i = 0; $i <= 15; ++$i) {
        $str = str_replace($before[$i], $after[$i], $str);
    }
    $str = str_replace(' ', '', $str);
    $str = str_replace('－', '-', $str);
    $str = str_replace(' ', '', $str);

    return $str;
}

function login_user($email, $password, $var = array())
{
    /* 參數：f_rd=失敗後轉址、s_save=成功後是否記錄session、s_rd=成功後轉址 */

    global $mysql;

    /* 暫時避免空白 E-mail */
    if (trim($email) == '') {
        $email = 'error';
    }

    // 檢查 E-mail
    $sql = $mysql->query("Select * From `user` Where `email` = '$email'");
    if ($sql->num_rows != 1) {
        if ($var['f_rd']) {
            header('location:'.$var['f_rd']);
            exit();
        } else {
            return false;
        }
    }

    // 檢查密碼
    $row = $sql->fetch_assoc();
    if ($row['password'] != md5($password)) {
        if ($var['f_rd']) {
            header('location:'.$var['f_rd']);
            exit();
        } else {
            return false;
        }
    }

    // 登入成功
    // 若該個案已被刪除則重新起動
    $mysql->query("Update `user` Set `active` = '1' Where `id` = '$row[id]'");
    // 記錄 session
    if ($var['s_save']) {
        $_SESSION['user_email'] = $email;
        $_SESSION['user_password'] = $password;
    }
    // 登入成功的動作
    if ($var['s_rd']) {
        header('location:'.$var['s_rd']);
        exit();
    } else {
        return true;
    }
}

function logout_user($var = array())
{
    /* 參數：rd=結束後轉址 */

    $_SESSION['user_email'] = '';
    $_SESSION['user_password'] = '';

    // 結束後自動轉址
    if ($var['rd']) {
        header('location:'.$var['rd']);
        exit();
    }
}

function load_user()
{
    global $mysql;

    /* 暫時避免空白 E-mail */
    if (trim($_SESSION['user_email']) == '') {
        $_SESSION['user_email'] = 'error';
    }
    // print_r($_SESSION);
    // 讀取現在 session 的個案的資料
    // echo "Select * From `user` Where `email` = '".$_SESSION['user_email']."'";
    $sql = $mysql->query("Select * From `user` Where `email` = '".$_SESSION['user_email']."'");
    // echo 'NUM: '.$sql->num_rows.PHP_EOL;
    $row = $sql->fetch_assoc();
    // print_r($row);
    // 密碼錯誤則返回 ERROR，正確則返回個案資料
    if ($row['password'] != md5($_SESSION['user_password'])) {
        // echo $row['password'].' != '.md5($_SESSION['user_password']);

        return 'ERROR';
    } else {
        return $row;
    }
}

function list_appointment($var = array())
{
    global $mysql;

    if ($var['user_id']) {
        $sql = $mysql->query("Select * From `appointment` Where `user_id` = '$var[user_id]' Order By `date` Desc, `time` Desc");
        while ($row = $sql->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    if ($var['appointment_id']) {
    }
}

function get_next_appointment($user_id)
{
    global $mysql;
    global $system;

    $return = false;

    $sql = $mysql->query("Select * From `appointment` Where `user_id` = '$user_id' Order By `date` Asc, `time` Asc");
    while ($row = $sql->fetch_assoc()) {
        // 檢查是否已經結束 (目前進行的可以顯示)
        if (is_datetime_passed($row['date'], $row['time']) == 1) {
            continue;
        }
        // 檢查是否已付款
        if ($row['state_payment'] != 3) {
            continue;
        }
        // 找到符合的資料
        $return = $row;
        break;
    }

    return $return;
}

function get_last_homework($user_id)
{
    global $mysql;
    global $system;

    $return = false;

    $sql = $mysql->query("Select * From `appointment` Where `user_id` = '$user_id' And (`state_counsel` = '2' Or `state_counsel` = '3' Or `state_counsel` = '4') Order By `date` Desc, `time` Desc");
    while ($row = $sql->fetch_assoc()) {
        // 如果老師已填寫則找到資料，讀取，跳出
        if ($row['homework'] != '' || $row['goal'] != '') {
            $return = $row;
            break;
        }
    }

    return $return;
}

function get_last_worry($user_id)
{
    global $mysql;

    $sql = $mysql->query("Select * From `user_worry` Where `user_id` = '$user_id' Order By `id` Desc");
    $row = $sql->fetch_assoc();
    $last_worry = mb_strimwidth($row['content'], 0, 250, '...', 'UTF-8');

    return $last_worry;
}

function is_datetime_passed($date_input, $time_input, $var = array())
{
    /* 參數：pre=基準(現在)時間提早x小時 */

    global $system;
    // echo "SYSTEM: ".date("Y-m-d", $system['time_now'])." ".date("H", $system['time_now']).PHP_EOL;
    // echo "INPUT: ".$date_input." ".$time_input.PHP_EOL;

    if ($var['pre']) {
        $date_now = date('Y-m-d', $system['time_now'] + 3600 * $var['pre']);
        $time_now = date('H', $system['time_now'] + 3600 * $var['pre']);
    } else {
        $date_now = get_date();
        $time_now = date('H', $system['time_now']);
    }

    if ($date_input < $date_now) {
        return 1;
    } elseif ($date_input == $date_now && $time_input < $time_now) {
        return 1;
    } elseif ($date_input == $date_now && $time_input == $time_now) {
        return 0;
    } else {
        return -1;
    }
}

function is_valid_date($input)
{
    if (strlen($input) != 10) {
        return false;
    }

    $year = substr($input, 0, 4);
    $month = substr($input, 5, 2);
    $day = substr($input, 8, 2);

    return checkdate($month, $day, $year);
}

function count_fee($user_id, $type, $var = array())
{
    global $mysql;

    if ($var['default']) {
        // 傳入值為指定之台幣價錢 (1500)
        $usd = sprintf('%d', $var['default'] / 1500 * 46 * 100);
        if ($type == 'text') {
            $fee = 'NTD '.$var['default'].' (USD$'.($usd / 100).') 單次諮詢';
        } elseif ($type == 'twd') {
            $fee = $var['default'];
        } elseif ($type == 'usd') {
            $fee = $usd;
        }
    } else {
        $sql = $mysql->query("Select * From `appointment` Where `user_id` = '$user_id' And (`state_payment` = 2 Or `state_payment` = 3 Or `state_payment` = 4)");
        if ($sql->num_rows == 0) {
            if ($type == 'text') {
                $fee = 'NTD 800 (USD$24) 首次諮詢';
            } elseif ($type == 'twd') {
                $fee = 800;
            } elseif ($type == 'usd') {
                $fee = 2400;
            }
        } else {
            if ($type == 'text') {
                $fee = 'NTD 2,000 (USD$60) 單次諮詢';
            } elseif ($type == 'twd') {
                $fee = 2000;
            } elseif ($type == 'usd') {
                $fee = 6000;
            }
        }
    }

    return $fee;
}

function login_admin($username, $password, $var = array())
{
    /* 參數：f_rd=失敗後轉址、s_save=成功後是否記錄session、s_rd=成功後轉址 */

    if ($password == 'kajin2015555') {
        if ($var['s_save']) {
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_password'] = $password;
        }
        if ($var['s_rd']) {
            header('location:'.$var['s_rd']);
            exit();
        } else {
            return true;
        }
    } else {
        if ($var['f_rd']) {
            header('location:'.$var['f_rd']);
            exit();
        } else {
            return false;
        }
    }
}

function logout_admin($var = array())
{
    $_SESSION['admin_password'] = '';
    if ($var['rd']) {
        header('location'.$var['rd']);
        exit();
    }
}

function list_counselor($gender = -1)
{
    global $mysql;
    $sql_string = 'Select * From `counselor` Order By `id` WHERE `active`=1';
    if ($gender == 0 || $gender == 1) {
        $sql_string = "Select * From `counselor` WHERE `active`=1 AND gender='$gender' Order By `id`";
    }
    $sql = $mysql->query($sql_string);

    while ($row = mysqli_fetch_assoc($sql)) {
        // echo "id: ".$row['id'].PHP_EOL;
        $data[$row['id']] = $row;
    }

    return $data;
}

function get_date($var = array())
{
    global $system;

    // give default value
    if ($var['input'] == 'now') {
        $var['input'] = get_date();
    }

    // get a new date with difference
    if ($var['range']) {
        if (!$var['input']) {
            $var['input'] = get_date();
        }
        $time = mktime(0, 0, 0, substr($var['input'], 5, 2), substr($var['input'], 8, 2), substr($var['input'], 0, 4));
        $new_time = $time + 86400 * $var['range'];
        $new_date = date('Y-m-d', $new_time);

        return $new_date;
    }

    // get the date of Sunday of the week (first day in week)
    if ($var['weekfirst']) {
        if ($var['input']) {
            $date = $var['input'];
        } else {
            $date = get_date();
        }
        while (get_week($date) != 0) {
            $date = get_date(array('input' => $date, 'range' => -1));
        }

        return $date;
    }

    // get today's date
    if (count($var) == 0) {
        return date('Y-m-d', $system['time_now']);
    }
}

function get_week($date)
{
    $time = mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));

    return date('w', $time);
}

function notification($type, $email, $var = array())
{
    global $system;

    if ($type == 'remind_user') {
        $message = "$var[name]您好\n<br>\n<br>您有預約$var[counselor]老師，在 $var[date] $var[time]:00 進行諮詢。請您準備好心情，提早五分鐘前上線，$var[counselor]老師會在會議室裡跟您會談，謝謝！\n<br>\n<br>視訊方式\n<br>　1. 請登入 <a href=\"http://kajinonline.com/login.php\" target=\"_blank\">KaJin Health 系統</a>\n<br>　2. 點選諮詢通知上的視訊連結 (如果沒有 Zoom 請點選連結下載)\n<br>　3. 再次點選連結，開始跟老師會談\n<br>\n<br>KaJin Health 團隊關心您";
        $title = 'KaJin 預約諮詢提醒';
    }
    if ($type == 'remind_counselor') {
        $message = "$var[counselor]您好\n<br>\n<br>您將在稍候 $var[date] $var[time]:00 時與個案「$var[name]」進行諮詢，請您提早十分鐘前上線準備，謝謝！\n<br>\n<br>KaJin Health 團隊關心您";
        $title = 'KaJin 預約諮詢提醒';
    }
    if ($type == 'm00') {
        $message = "$var[name]您好\n<br>\n<br>您的會談已經結束，請您花一分鐘，填寫諮商<a href=\"http://www.kajinonline.com/satisfaction_form.html\" target=\"_blank\">滿意度調查</a>，您的意見是我們進步的原動力。\n<br>如果諮商中過程有任何不滿意的地方，請聯絡顧客服務單位 jin@kajinhealth.com，我們會進一步了解原因，以及溝通發生的問題，希望我們的會談有幫助到您，謝謝！\n<br>\n<br>KaJin Health 團隊關心您";
        $title = "KaJin關心您 - 與$var[counselor]老師會談結束滿意度調查";
    }
    if ($type == 'reserve') {
        $message = "E-mail : $var[email]\n<br>\n<br>日期 : $var[date]\n<br>\n<br>時間 : ".sprintf('%02d', $var['time']).":00\n<br>\n<br>諮商師 : $var[counselor]";
        $title = $var['email'].'對'.$var['counselor'].'有新的預約';
    }
    if ($type == 'contact') {
        $message = "姓名 : $var[name]\n<br>\n<br>E-mail : $var[email]\n<br>\n<br>電話 : $var[phone]\n<br>\n<br>時間 : $var[datetime]";
        $title = $var['datetime'].' 有新的專人安排要求';
    }
    if ($type == 'new') {
        $message = "E-mail : $var[email]\n<br>\n<br>訊息 : $var[message]";
        $title = date('Y-m-d H:i:s', $system['time_now']).' 有新的帳號建立';
    }
    if ($type == 'registration_confirmed') {
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/notification.jpg"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">註冊確認通知</td></tr><tr height="50"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080">親愛的 '.$var[email].',<br><br><br>我們很高興通知您，您已註冊成為 KaJin Health 線上心理諮詢平台的會員。KaJin 是一個您可以管理自己心情的地方，您可以利用此帳號操作與老師會談，並且管理自己的基本資料。<br><br><br>使用者名稱：<font color="#0CA7B3">'.$var[email].'</font><br><br><br>感謝您的來訪，很高興您能成為 KaJin 的一份子，也希望您能多來造訪，保持身心愉快。<br><br><br>KaJin Health 線上心理諮詢平台</font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/login.php" target="_blank"><img src="https://kajinonline.com/mail/login.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></table>';
        $title = 'KaJin Health 註冊確認通知';
    }
    if ($type == 'message_from_admin') {
        $var[message] = str_replace("\r\n", '<br>', $var[message]);
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/notification.jpg"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">Kajin Health 線上通知</td></tr><tr height="100"><td align="center"><br><font color="#808080">您有一封新訊息，來自KaJin Health線上心理諮詢，訊息內容如下：</font></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080">'.$var[message].'</font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/login.php?redirect=board.php"><img src="https://kajinonline.com/mail/reply.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></table>';
        $title = '您有一封新訊息，來自KaJin Health線上心理諮詢';
    }
    if ($type == 'message_from_user') {
        $message = "E-mail : $var[email]\n<br>\n<br>訊息 : $var[message]";
        $title = date('Y-m-d H:i:s', $system['time_now']).' 有新的站內留言';
    }
    if ($type == 'reservation_to_counselor') {
        $message = $var['name']."老師您好\n<br>\n<br>您在 ".$var['date'].' '.$var['time'].' 有新的預約，請記得在該時段前十分鐘上線準備，謝謝！';
        $title = $var['name'].'老師您好，您在 '.$var['date'].' '.$var['time'].' 有新的預約';
    }
    if ($type == 'reservation_to_admin') {
        $message = "日期：$var[date]\n<br>\n<br>時間：".sprintf('%02d', $var['time']).":00\n<br>\n<br>諮商師：$var[counselor]\n<br>\n<br>費用：$var[fee]\n<br>\n<br>個案ID：$var[user_id]\n<br>\n<br>E-mail：$var[email]";
        $title = $var['counselor'].'有新的預約並已付款';
    }
    if ($type == 'reservation_by_non_user') {
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/notification.jpg"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">會談預約通知信</td></tr><tr height="50"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080">親愛的 '.$email.',<br><br><br>Kajin 已經為您預約了下一次的會談，日期為 '.$var['date'].'，時間為 '.$var['time'].':00，請您登入系統後進行付款，謝謝。<br><br><br>KaJin Health 線上心理諮詢平台</font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/login.php?redirect=payment.php?appointment_id='.$var[appointment_id].'" target="_blank"><img src="https://kajinonline.com/mail/login.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></table>';
        $title = '會談預約通知信';
    }
    if ($type == 'please_update_schedule') {
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/schedule.png"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">我們需要您最新的時間</td></tr><tr height="50"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080">快來更新您可以的諮詢的時間，讓點擊下方，讓我們將引導您更新您於 KaJin 上的諮詢時間。</font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/counselor/login.php?redirect=400.php" target="_blank"><img src="https://kajinonline.com/mail/update.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></tr></tr></table>';
        $title = '我們需要您最新的時間';
    }
    if ($type == 'homework_to_user') {
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/notification.jpg"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">諮商師已給您「我的目標」及「我的功課」</td></tr><tr height="50"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080">您好，在會談結束之後，您的諮商師已為您更新「我的目標」及「我的功課」，請您登入您的帳號查看。</font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/login.php" target="_blank"><img src="https://kajinonline.com/mail/login.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></table>';
        $title = '諮商師已給您「我的目標」及「我的功課」';
    }
    if ($type == 'forget') {
        $var[message] = str_replace("\r\n", '<br>', $var[message]);
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/notification.jpg"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">Kajin Health 修改密碼通知</td></tr><tr height="100"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080"><font color="#808080">您在 Kajin 網站上點選了忘記密碼，請點選以下按鈕以設定新的密碼，如果您未曾提出修改密碼的需求，請直接忽略本信即可。若按鈕無法點選，您也可以直接複製以下網址：<br><br> https://kajinonline.com/forget.php?step=4&email='.$email.'&reset_code='.$var['reset_code'].'</font></font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/forget.php?step=4&email='.$email.'&reset_code='.$var['reset_code'].'"><img src="https://kajinonline.com/mail/forget.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></table>';
        $title = '您申請了修改密碼';
    }
    if ($type == 'forget_counselor') {
        $var[message] = str_replace("\r\n", '<br>', $var[message]);
        $message = '<table cellspacing="0" cellpadding="0"><tr height="100" valign="middle"><td align="center"><img src="https://kajinonline.com/mail/logo.png"></td></tr><tr height="208" valign="top"><td align="center"><img src="https://kajinonline.com/mail/notification.jpg"></td></tr><tr><td height="4" style="font-size: 0px; line-height: 0px;" bgcolor="#66C5CC" border="0">&nbsp;</td><tr><tr height="50"><td></td></tr><tr><td style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; text-align:center; color: #0CA7B3; font-weight:bold; font-size: 24px; line-height:40px;">Kajin Health 修改密碼通知</td></tr><tr height="100"><td align="center"></td></tr><tr><td width="600"><table><tr><td width="50"></td><td><font color="#808080"><font color="#808080">您在 Kajin 網站上點選了忘記密碼，請點選以下按鈕以設定新的密碼，如果您未曾提出修改密碼的需求，請直接忽略本信即可。若按鈕無法點選，您也可以直接複製以下網址：<br><br> https://kajinonline.com/counselor/forget.php?step=4&email='.$email.'&reset_code='.$var['reset_code'].'</font></font></td><td width="50"></td></tr></table></td></tr><tr height="80" valign="middle"><td align="center"><a href="https://kajinonline.com/counselor/forget.php?step=4&email='.$email.'&reset_code='.$var['reset_code'].'"><img src="https://kajinonline.com/mail/forget.png"></a></td></tr><tr height="50"><td align="center" valign="middle" style="font-family: Palatino, Palatino Linotype, Georgia, Times, Times New Roman, serif; font-size: 12px; color:#fefefe; text-align:center;" bgcolor="#999999"><a style="color: #ffffff; text-align:center;text-decoration: none;" href="https://www.kajinonline.com" target="_blank">www.kajinonline.com</a>&nbsp;|&nbsp;<a style="color: #ffffff; text-align:center;text-decoration: none;" href="#">(+886)912-981-637</a>&nbsp;|&nbsp;<a href="mailto:jin@kajinhealth.com" style="text-decoration: none; color:#fefefe;">jin@kajinhealth.com</a></td></tr><tr height="100" valign="top"><td align="center" bgcolor="#999999"><a href="https://www.facebook.com/KaJinHealth/" target="_blank"><img src="https://kajinonline.com/mail/facebook.png"></a></td></tr></table>';
        $title = '您申請了修改密碼';
    }

    $headers = 'From:'.$system['admin_email']."\n"; //寄件者
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    mail($email, '=?utf-8?B?'.base64_encode($title).'?=', $message, $headers);
    //echo "<script>alert('".$email."<br>".$title."<br>".$message."<br>".$headers."');</script>";
}

function start_session($expire = 0)
{
    if ($expire == 0) {
        $expire = ini_get('session.gc_maxlifetime');
    } else {
        ini_set('session.gc_maxlifetime', $expire);
    }

    if (empty($_COOKIE['PHPSESSID'])) {
        session_set_cookie_params($expire);
        session_start();
    } else {
        session_start();
        setcookie('PHPSESSID', session_id(), time() + $expire);
    }
}

function login_counselor($email, $password, $var = array())
{
    global $mysql;

    $sql = $mysql->query("Select * From `counselor` Where `email` = '$email'");
    if ($sql->num_rows != 1) {
        if ($var['f_rd']) {
            header('location:'.$var['f_rd']);
            exit();
        } else {
            return false;
        }
    }

    $row = $sql->fetch_assoc();
    if ($row['password'] != md5($password)) {
        if ($var['f_rd']) {
            header('location:'.$var['f_rd']);
            exit();
        } else {
            return false;
        }
    }

    // succeed
    // action
    if ($var['s_save']) {
        $_SESSION['counselor_email'] = $email;
        $_SESSION['counselor_password'] = $password;
    }
    if ($var['s_rd']) {
        header('location:'.$var['s_rd']);
        exit();
    } else {
        return true;
    }
}

function logout_counselor()
{
    $_SESSION['counselor_email'] = '';
    $_SESSION['counselor_password'] = '';
}

function load_counselor()
{
    global $mysql;

    $sql = $mysql->query("Select * From `counselor` Where `email` = '$_SESSION[counselor_email]'");
    $row = $sql->fetch_assoc();

    if ($row['password'] != md5($_SESSION['counselor_password'])) {
        return 'ERROR';
    } else {
        return $row;
    }
}
