<?

include("../include_function.php");
include("../include_setting.php");

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array("f_rd" => "login.php"));

?>
<h3>諮商師後台系統</h3>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<li><a href="100.php" target="main">個人資料</a></li>
<li><a href="200.php" target="main">預約歷史</a></li>
<li><a href="300.php" target="main">您的客戶</a></li>
<li><a href="400.php" target="main">時段安排和查詢</a></li>
<li><a href="500.php" target="main">匯款記錄</a></li>
<li><a href="600.php" target="main">意見反應</a></li>
<li><a href="logout.php" target="_top">登出</a></li>