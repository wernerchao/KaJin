<?php

include '../include_function.php';
include '../include_setting.php';

login_admin('', $_SESSION['admin_password'], array('f_rd' => 'login.php'));

$list_counselor = list_counselor();

if ($_POST['weekstart']) {
    $_GET['weekstart'] = $_POST['weekstart'];
}

if ($_GET['weekstart'] < get_date(array('weekfirst' => 1))) {
    header('location:350.php');
    exit();
}

if ($_POST['submit']) {
    // 算此週起迄日
    $start = get_date(array('input' => $_GET['weekstart'], 'weekfirst' => 1));
    $end = get_date(array('input' => $start, 'range' => 6));
    // 讀此週資料
    $sql = $mysql->query("Select * From `appointment` Where `date` Between '$start' And '$end' And `counselor_id` = '$_POST[id]'");
    while ($row = $sql->fetch_assoc()) {
        $appointment_data[$row['date']][$row['time']] = $row;
    }
    // 開始增刪時段
    $date = $start;
    for ($i = 0; $i <= 6; ++$i) {
        // 過去日期不處理
        if ($date >= get_date()) {
            for ($j = 0; $j <= 23; ++$j) {
                // 當天的已過小時不處理
                if ($date == get_date() && $j < date('H', $system['time_now'])) {
                    continue;
                }
                // 處理 $date 日的 $j 點
                // 1. 如果這是已有人預約則不可動
                if ($_POST[$date.'-'.$j] == -1 || $appointment_data[$date][$j]['user_id']) {
                    continue;
                }
                // 2. 如果要改成可預約但現在沒有則新增
                if ($_POST[$date.'-'.$j] == 1 && !$appointment_data[$date][$j]['id']) {
                    $mysql->query("Insert Into `appointment` (`counselor_id`, `date`, `time`) Values ('$_POST[id]', '$date', '$j')");
                }
                // 3. 如果要改成不可預約但現在有則刪除
                if ($_POST[$date.'-'.$j] == 0 && $appointment_data[$date][$j]['id']) {
                    $mysql->query("Delete From `appointment` Where `date` = '$date' And `time` = '$j' And `counselor_id` = '$_POST[id]' And `user_id` = '0'");
                }
            }
        }
        $date = get_date(array('input' => $date, 'range' => 1));
    }
    // 修改完成
    echo "<script>alert('修改完成。');</script>";
    // 清空變數
    $appointment_data = array();
    // admin 特有 - 把 id 傳回 $_GET
    $_GET['id'] = $_POST['id'];
}

// 算此週起迄日
$start = get_date(array('input' => $_GET['weekstart'], 'weekfirst' => 1));
$end = get_date(array('input' => $start, 'range' => 6));

// 讀此週資料
$sql = $mysql->query("Select * From `appointment` Where `date` Between '$start' And '$end' And `counselor_id` = '$_GET[id]'");
while ($row = $sql->fetch_assoc()) {
    $appointment_data[$row['date']][$row['time']] = $row;
}

// 算上週起迄日
$start_last = get_date(array('input' => $start, 'range' => -7));
$end_last = get_date(array('input' => $end, 'range' => -7));

// 讀上週資料
$sql = $mysql->query("Select * From `appointment` Where `date` Between '$start_last' And '$end_last' And `counselor_id` = '$_GET[id]'");
while ($row = $sql->fetch_assoc()) {
    $appointment_data[$row['date']][$row['time']] = $row;
}

// 製為 javascript 變數
echo '<script>';
echo 'var lastweek = new Array(7);';
echo 'var datelist = new Array(7);';
$date = $start_last;
for ($i = 0; $i <= 6; ++$i) {
    echo 'lastweek['.$i.'] = [';
    for ($j = 0; $j <= 23; ++$j) {
        if ($date < get_date(array('range' => -7))) {
            $val = -2;
        } elseif ($date == get_date(array('range' => -7)) && $j < date('H', $system['time_now'])) {
            $val = -2;
        } elseif ($appointment_data[$date][$j]['id']) {
            $val = 1;
        } else {
            $val = 0;
        }
        echo $val.(($j == 23) ? '' : ',');
    }
    echo '];';
    echo 'datelist['.$i."] = '".get_date(array('input' => $date, 'range' => 7))."';";
    $date = get_date(array('input' => $date, 'range' => 1));
}
echo '</script>';

?>
<script type="text/javascript" src="../jquery-1.9.1.js"></script>
<script>
	document.oncontextmenu=new Function("event.returnValue=false;");
	document.onselectstart=new Function("event.returnValue=false;");//禁止選取
	var clickdown = 0;
	$(function(){
		$(".slot").mouseenter(function(){
			if(clickdown == 1) toggle(this);
		});
		$(".slot").mousedown(function(){
			toggle(this);
			clickdown = 1;
		});
		$(".slot").mouseup(function(){
			clickdown = 0;
		});
		$(document).mouseup(function(){
			clickdown = 0;
		});
	});
	function toggle(item)
	{
		if($(item).hasClass("slot_reserved")) return;
		if($(item).hasClass("slot_passed")) return;

		if($(item).hasClass("slot_off"))
		{
			$(item).removeClass("slot_off");
			$(item).addClass("slot_on");
			$(item).children("input").attr("value", 1);
		}
		else if($(item).hasClass("slot_on"))
		{
			$(item).removeClass("slot_on");
			$(item).addClass("slot_off");
			$(item).children("input").attr("value", 0);
		}
	}
	function copy(day)
	{
		for(i=0; i<=23; i++)
		{
			if(lastweek[day][i] == -2) continue;
			if(lastweek[day][i] == 0)
			{
				if($('#'+datelist[day]+'-'+i).hasClass("slot_reserved"))
				{
					alert("注意："+datelist[day]+" 的 "+i+":00 時段已被預約，無法刪除，即使上週的該時段沒有開放預約。");
				}
				else
				{
					$('#'+datelist[day]+'-'+i).removeClass("slot_on");
					$('#'+datelist[day]+'-'+i).addClass("slot_off");
					$('#'+datelist[day]+'-'+i).children("input").attr("value", "0");
				}
			}
			if(lastweek[day][i] == 1)
			{
				if(!$('#'+datelist[day]+'-'+i).hasClass("slot_reserved"))
				{
					$('#'+datelist[day]+'-'+i).removeClass("slot_off");
					$('#'+datelist[day]+'-'+i).addClass("slot_on");
					$('#'+datelist[day]+'-'+i).children("input").attr("value", "1");
				}
			}
		}
	}
</script>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<?php

// 印表格
$date = $start;
echo '<form action="351.php" method="post">';
echo '<table id="timetable_edit">';
echo '<tr>';
echo '<td colspan="2"></td>';
for ($j = 0; $j <= 23; ++$j) {
    echo '<th>'.$j.'</th>';
}
echo '<th>從上週複製</th>';
echo '</tr>';
for ($i = 0; $i <= 6; ++$i) {
    echo '<tr>';
    if ($date == get_date()) {
        echo '<td bgcolor="#ffaaaa">'.$date.'</td><td bgcolor="#ffaaaa">'.$list_week[get_week($date)].'</td>';
    } else {
        echo '<th>'.$date.'</th><th>'.$list_week[get_week($date)].'</th>';
    }
    for ($j = 0; $j <= 23; ++$j) {
        if ($appointment_data[$date][$j]['user_id']) {
            echo '<td class="slot slot_reserved" id="'.$date.'-'.$j.'">預';
            echo '<input type="hidden" name="'.$date.'-'.$j.'" value="-1">';
            echo '</td>';
        } elseif ($date < get_date() || ($date == get_date() && $j < date('H', $system['time_now']))) {
            if ($appointment_data[$date][$j]['id']) {
                echo '<td class="slot slot_passed_on" id="'.$date.'-'.$j.'">';
                echo '<input type="hidden" name="'.$date.'-'.$j.'" value="-2">';
                echo '</td>';
            } else {
                echo '<td class="slot slot_passed" id="'.$date.'-'.$j.'">';
                echo '<input type="hidden" name="'.$date.'-'.$j.'" value="-2">';
                echo '</td>';
            }
        } elseif ($appointment_data[$date][$j]['id']) {
            echo '<td class="slot slot_on" id="'.$date.'-'.$j.'">';
            echo '<input type="hidden" name="'.$date.'-'.$j.'" value="1">';
            echo '</td>';
        } else {
            echo '<td class="slot slot_off" id="'.$date.'-'.$j.'">';
            echo '<input type="hidden" name="'.$date.'-'.$j.'" value="0">';
            echo '</td>';
        }
    }
    echo "<td><a href=\"#\" onclick=\"copy('".$i."')\">複製</a></td>";
    echo '</tr>';
    $date = get_date(array('input' => $date, 'range' => 1));
}
echo '</table>';
echo '<br><br>';
echo '<center>';
echo '<input type="submit" name="submit" value="修改本週時段">';
echo '<input type="hidden" name="weekstart" value="'.$_GET['weekstart'].'">';
echo "<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">"; // admin 特有 - 要回傳諮商師 id
echo '　　';
echo "<input type=\"button\" value=\"不修改，返回上一頁\" onclick=\"location.href='350.php?id=$_GET[id]'\">";
echo '</center>';
echo '</form>'

?>
