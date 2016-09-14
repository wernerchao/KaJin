<?

include("include_function.php");
include("include_setting.php");

$data_user = load_user();

if($data_user == "ERROR") echo 1;
else if(md5($_POST['old']) != $data_user['password']) echo 1;
else if($_POST['new1'] != $_POST['new2']) echo 2;
else if($_POST['new1'] == "") echo 3;
else
{
	$new = md5($_POST['new1']);
	$mysql->query("Update `user` Set `password` = '$new' Where `id` = '$data_user[id]'");
	echo "succeed";
	$_SESSION['user_password'] = $_POST['new1'];
}

?>