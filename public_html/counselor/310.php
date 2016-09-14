<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

// 檢查這個個案是否已有預約或諮商記錄
$sql = $mysql->query("Select * From `appointment` Where `user_id` = '$_GET[user_id]' And `counselor_id` = '$data_counselor[id]'");
if ($sql->num_rows == 0) {
    exit('您無法為此個案預約，因為此個案與您尚未有任何預約會諮商記錄。');
}

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
請點選藍色空格進行預約
<br>
<br>
<?php

$start = get_date(array('weekfirst' => 1));
$end = get_date(array('input' => $start, 'range' => 6));

// 顯示六週
for ($w = 1; $w <= 6; ++$w) {
    // 讀資料
    $sql = $mysql->query("Select * From `appointment` Where `date` Between '$start' And '$end' And `counselor_id` = '$data_counselor[id]'");
    while ($row = $sql->fetch_assoc()) {
        $appointment_data[$row['date']][$row['time']] = $row;
    }

    // 印表格
    $date = $start;
    echo '<table id="timetable">';
    echo '<tr>';
    echo '<td colspan="2"></td>';
    for ($j = 0; $j <= 23; ++$j) {
        echo '<th>'.$j.'</th>';
    }
    echo '</tr>';
    for ($i = 0; $i <= 6; ++$i) {
        echo '<tr>';
        if ($date == get_date()) {
            echo '<td bgcolor="#ffaaaa">'.$date.'</td><td bgcolor="#ffaaaa">'.$list_week[get_week($date)].'</td>';
        } else {
            echo '<th>'.$date.'</th><th>'.$list_week[get_week($date)].'</th>';
        }
        for ($j = 0; $j <= 23; ++$j) {
            if ($appointment_data[$date][$j]['id']) {
                if ($appointment_data[$date][$j]['user_id']) {
                    echo '<td bgcolor="#ffaaaa"><font color="black">預</font></td>';
                } else {
                    echo "<td bgcolor=\"#aaaaff\"><a href=\"311.php?user_id=$_GET[user_id]&appointment_id=".$appointment_data[$date][$j]['id'].'">　</a></td>';
                }
            } else {
                echo "<td><a href=\"315.php?user_id=$_GET[user_id]&date=".$date.'&time='.$j.'">　</a></td>';
            }
        }
        echo '</tr>';
        $date = get_date(array('input' => $date, 'range' => 1));
    }
    echo '</table>';

    echo '<br><br>';

    // 跳到下一週時間
    $start = get_date(array('input' => $start, 'range' => 7));
    $end = get_date(array('input' => $end, 'range' => 7));
}

?>
