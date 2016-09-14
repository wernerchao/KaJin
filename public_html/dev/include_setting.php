<?php

include 'include_db.php';

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
if (!$mysql) {
    echo '無法連結資料庫！請通知系統管理員。'.PHP_EOL;
    echo 'Error: Unable to connect to MySQL.'.PHP_EOL;
    echo 'Debugging errno: '.mysqli_connect_errno().PHP_EOL;
    echo 'Debugging error: '.mysqli_connect_error().PHP_EOL;
    exit;
}
$mysql->query('Set Names Utf8');

// 轉 https ********** 上傳前要打開！！！！！ *****
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == '') {
    $redirect = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: $redirect");
    exit();
}

// stripe
// $system['stripe']['public'] = 'pk_live_DsZ9kTZ8onNEmJXR690i6rwR';
// $system['stripe']['secret'] = 'sk_live_JcY3hQQ20ZpeGIC46ZIGcjlt';

// stripe test
$system['stripe']['public'] = 'pk_test_6pRNASCoBOKtIshFeQd4XMUh';
$system['stripe']['secret'] = 'sk_test_BQokikJOvBiI2HlWgH4olfQ2';

// sqlChars
$system['sqlchars'] = 0;

//session_start();
start_session(2592000);
header('Access-Control-Allow-Credentials: true');

$system['admin_email'] = 'support@kajinhealth.com, jin@kajinhealth.com, wilson@kajinhealth.com, otwo.kajin@gmail.com, din1030@gmail.com';
$system['time_difference'] = 28800;
$system['time_now'] = time() + $system['time_difference'];

$system['pre_hour'] = 12;

$variable['age'] = array('1' => '25 歲以下', '2' => '25 - 35 歲', '3' => '35 - 50 歲', '4' => '50 歲以上');
$variable['gender'] = array('1' => '男', '2' => '女');
$variable['state_counsel'] = array('0' => '', '1' => '待進行', '2' => '完成', '3' => '完成(諮商師遲到)', '4' => '完成(個案遲到)', '5' => '取消', '6' => '諮商師未出席', '7' => '個案未出席', '9' => '爭議');
$variable['state_payment'] = array('0' => '', '1' => '未付款', '2' => '待確認', '3' => '已付款', '4' => '待退款', '5' => '已退款');

//$list_random_name = array("西瓜", "紅葡萄", "白葡萄", "香蕉", "水蜜桃", "甘蔗", "鳳梨", "菠蘿蜜", "橘", "柳橙", "柑", "香吉士", "白柚", "葡萄柚", "檸檬", "萊姆", "李子", "蜜李", "草莓", "水梨", "西洋梨", "高接梨", "哈密瓜", "香瓜", "蜜世界", "狀元瓜", "木瓜", "櫻桃", "蟠桃", "白鳳桃", "蘋果", "青蘋果", "青龍", "紅毛丹", "蛇皮果", "榴槤", "椏答枳", "山竹", "荔枝", "龍眼", "釋迦", "番荔枝", "芭樂", "蕃石榴", "石榴", "火龍果", "奇異果", "百香果", "紅柿", "水柿", "蕃茄", "西紅柿", "小蕃茄", "芒果", "土芒果", "愛文芒果", "香水芒果", "楊梅", "楊桃", "桑椹", "覆盆子", "藍梅", "酪梨", "枇杷", "小紅莓", "蓮霧", "油柑", "金桔", "桔", "栗子", "椰子", "紅毛榴槤", "紅棗", "蜜棗", "蔓越莓", "蜜梨", "恐龍蛋");

$list_random_name = array('圓形西瓜', '酒紅葡萄', '自由葡萄', '藝術香蕉', '香水蜜桃', '旅遊甘蔗', '虛線鳳梨', '菠菠蘿蜜', '紅色小橘', '哈哈柳橙', '來柑一杯', '香香吉士', '黑白柚柚', '葡萄柚柚', '檸檬起司', '萊姆起水', '李子蛋糕', '蜜李漢堡', '草莓小生', '水梨果子', '西洋梨貓', '高高荔枝', '哈哈密瓜', '格子香瓜', '狀元瓜瓜', '原木木瓜', '櫻桃杯杯', '微笑蟠桃', '白鳳桃子', '蘋果香香', '滾動蘋果', '飛翔山竹', '荔枝遊戲', '繽紛荔枝', '薄荷芭樂', '蕃轉石榴', '火龍果子', '奇異果汁', '百香果', '紐約柿子', '天使蕃茄', '小優蕃茄', '芒果冰茶', '八度芒果', '愛文芒果', '香水芒果', '楊桃汁', '桑椹樹', '覆桂盆子', '藍梅果乾', '酪梨茶', '枇杷枇杷', '小紅莓', '珍珠蓮霧', '柑八茶葉', '日本金桔', '桔祥如意', '黑糖栗子', '一顆椰子', '紅棗好讚', '蜜棗棒棒', '蔓越莓摺', '點心樂園', '小恐龍蛋');

$list_week = array(0 => '星期日', 1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六');

if ($system['sqlchars']) {
    $_POST = @sqlChars($_POST);
}
