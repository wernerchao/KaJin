<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<?php

foreach ($list_counselor as $k => $v) {
    echo '<a href="350.php?id='.$k.'">'.$v['name_ch'].'</a> ';
}
echo '<br><br>';

if (!$_GET['id']) {
    exit();
}

echo '現在顯示諮商師：'.$list_counselor[$_GET['id']]['name_ch'].'<br><br>';

$start = get_date(array('weekfirst' => 1));
$end = get_date(array('input' => $start, 'range' => 6));

// 顯示六週
for ($w = 1; $w <= 6; ++$w) {
    // 讀資料
    $sql = $mysql->query("Select * From `appointment` Where `date` Between '$start' And '$end' And `counselor_id` = '$_GET[id]'");
    while ($row = $sql->fetch_assoc()) {
        $appointment_data[$row['date']][$row['time']] = $row;
    }

    // 印表格
    $date = $start;
    echo '<table id="timetable">';
    echo '<tr>';
    echo "<td colspan=\"2\" align=\"center\"><a href=\"351.php?id=$_GET[id]&weekstart=$start\">編輯本週時間</a></td>";
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
                    echo '<td bgcolor="#ffaaaa"><a href="301.php?id='.$appointment_data[$date][$j]['id'].'"><font color="black">預</font></a></td>';
                } else {
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
