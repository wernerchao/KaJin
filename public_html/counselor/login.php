<?

include("../include_function.php");
include("../include_setting.php");

if($_POST)
{
	$result = login_counselor($_POST['email'], $_POST['password'], array("s_save" => 1, "s_rd" => "index.php?redirect=".$_POST['redirect']));
	$_GET['redirect'] = $_POST['redirect'];
}

?>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>諮商師後台系統</title>
<form action="login.php" method="post">
登入<br>
<br>
<table>
	<tr>
		<td>E-mail</td><td><input type="text" name="email"></td>
	</tr>
	<tr>
		<td>密碼</td><td><input type="password" name="password"></td>
	</tr>
</table>
<input type="submit" name="submit" value="登入">
<input type="hidden" name="redirect" value="<?=$_GET['redirect']?>">
</form>
<? if($_POST && $result == FALSE) echo "<font color=\"red\">帳號或密碼錯誤</font><br><br>"; ?>
<a href="forget.php">忘記密碼</a>