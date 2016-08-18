<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

$data_counselor = load_counselor();

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<?php

$start = get_date(array('weekfirst' => 1));
$end = get_date(array('input' => $start, 'range' => 6));

// 顯示六週
for ($w = 1; $w <= 6; ++$w) {
    // 讀資料
    $sql = $mysql->query("Select * From `appointment` Where `date` Between '$start' And '$end' And `counselor_id` = '$data_counselor[id]'");
    while ($row = $sql->fetch_assoc()) {
        $appointment_data[$row['date']][$row['time']] = $row; // 把老師開的時段塞進 array
    }

    // 印表格
    $date = $start;
    echo '<table id="timetable">';
    echo '<tr>';
    echo "<td colspan=\"2\" align=\"center\"><a href=\"401.php?weekstart=$start\">編輯本週時間</a></td>";
    for ($j = 0; $j <= 23; ++$j) {
        echo '<th>'.$j.'</th>';
    }
    echo '</tr>';
    for ($i = 0; $i <= 6; ++$i) {
        echo '<tr>';
        // 將今天顯示為紅底
        if ($date == get_date()) {
            echo '<td bgcolor="#ffaaaa">'.$date.'</td><td bgcolor="#ffaaaa">'.$list_week[get_week($date)].'</td>';
        } else {
            echo '<th>'.$date.'</th><th>'.$list_week[get_week($date)].'</th>';
        }
        for ($j = 0; $j <= 23; ++$j) {
            if ($appointment_data[$date][$j]['id']) {
                if ($appointment_data[$date][$j]['user_id']) { // 此時段有被 user 預約
                    echo '<td bgcolor="#ffaaaa"><a href="201.php?id='.$appointment_data[$date][$j]['id'].'"><font color="black">預</font></a></td>';
                } else { //老師有開時段但沒有預約
                    echo '<td bgcolor="#aaaaff"></td>';
                }
            } else {
                echo '<td></td>';
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
