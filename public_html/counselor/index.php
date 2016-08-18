<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));

?>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>諮商師後台系統</title>
<frameset cols="200, *">
	<frame name="menu" src="menu.php">
	<?php if ($_GET['redirect']) {
    ?>
	<frame name="main" src="<?=$_GET['redirect']?>">
	<?php
} else {
    ?>
    <frame name="main" src="main.php">
	<?php
} ?>
</frameset>
