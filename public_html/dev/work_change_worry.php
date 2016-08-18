<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

$data_user = load_user();

$now = date("Y-m-d H:i:s", $system['time_now']);
$str = "Insert Into `user_worry` (`user_id`, `datetime`, `content`, `ip`) Values ('$data_user[id]', '$now', '$_POST[worry]', '$_SERVER[REMOTE_ADDR]')";
$mysql->query($str);

header("location:panel.php");

?>