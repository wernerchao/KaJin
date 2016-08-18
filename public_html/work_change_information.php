<?

include("include_function.php");
include("include_setting.php");

// 個案登入
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

$str = "Update `user` Set `name` = '$_POST[name]', `phone_country` = '$_POST[phone_country]', `phone_number` = '$_POST[phone_number]', `age` = '$_POST[age]', `gender` = '$_POST[gender]', `occupation` = '$_POST[occupation]' Where `email` = '$_SESSION[email]'";
$mysql->query($str);

header("location:panel.php");

?>