<?php

include '../include_function.php';
include '../include_setting.php';

if ($_POST) {
    login_admin($_POST['username'], $_POST['password'], array('s_save' => 1, 's_rd' => 'index.php'));
}

?>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>管理系統</title>
<form action="login.php" method="post">
登入<br>
<br>
帳號 <input type="text" name="username"><br>
密碼 <input type="password" name="password"><br>
<br>
<input type="submit" name="submit" value="登入">
</form>
