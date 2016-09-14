<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

?>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>管理系統</title>
<frameset cols="200, *">
	<frame name="menu" src="menu.php">
    <frame name="main" src="main.php">
</frameset>