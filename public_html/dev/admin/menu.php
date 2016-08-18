<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

?>
<h3>管理系統</h3>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<li><a href="100.php" target="main">個案列表</a></li>
<li><a href="200.php" target="main">諮商師列表</a></li>
<li><a href="300.php" target="main">時段列表</a></li>
<li><a href="350.php" target="main">時段安排</a></li>
<li><a href="400.php" target="main">匯款記錄</a></li>
<li><a href="500.php" target="main">訊息通知</a></li>
<li><a href="600.php" target="main">諮商師反應</a></li>
<li><a href="logout.php" target="_top">登出</a></li>