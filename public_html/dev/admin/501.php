<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

if($_POST) $_GET['id'] = $_POST['id'];

$sql = $mysql->query("Select * From `message` Where `id` = '$_GET[id]' Order By `timestamp` Asc");
if($sql->num_rows != 1)
{
	header("location:500.php");
	exit();
}

if($_POST)
{
	if(trim($_POST['message']))
	{
		// 寫入資料庫
		$row = $sql->fetch_assoc();
		$mysql->query("Insert Into `message` (`user_id`, `date`, `time`, `timestamp`, `sender`, `type`, `content`, `replied`) Values ('$row[user_id]', '".get_date()."', '".date("H:i", $system['time_now'])."', '$system[time_now]', '0', '0', '$_POST[message]', '1')");
		$sql2 = $mysql->query("Select * From `user` Where `id` = '$row[user_id]'");
		$row2 = $sql2->fetch_assoc();
		notification("message_from_admin", $row2['email'], array("message" => $_POST['message']));
		echo "<script>alert(\"訊息已回應！\");</script>";
	}
	if($_POST['replied'])
	{
		$mysql->query("Update `message` Set `replied` = '1' Where `id` = '$_GET[id]'");
		echo "<script>alert(\"已標記為已處理！\");</script>";
	}
	$sql = $mysql->query("Select * From `message` Where `id` = '$_GET[id]' Order By `time` Asc");
}

$row = $sql->fetch_assoc();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="500.php">返回上一頁</a>
<br>
<br>
<table>
	<tr>
		<th>ID</th>
		<th>狀態</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>個案ID</th>
		<th>內容</th>
	</tr>
	<?
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".(($row['replied']==1)?"OK":"")."</td>";
		echo "<td>".$row['date']."</td>";
		echo "<td>".$row['time'].":00</td>";
		echo "<td>".(($row['sender']==1)?"個案":"Kajin")."</td>";
		echo "<td>".$row['user_id']."</td>";
		echo "<td>".$row['content']."</td>";
		echo "</tr>";
	?>
</table>

<h3>回應留言</h3>

<form action="501.php" method="post">
	若只需標記則回應內容可空白<br>
	<textarea name="message" cols="80" rows="10"></textarea>
	<br>
	<input type="checkbox" name="replied" value="1"> 標示為已處理<br>
	<input type="submit" name="submit" value="回應">
	<input type="hidden" name="id" value="<?=$_GET['id']?>">
</form>

<h3>個案過去留言</h3>

<?

$sql2 = $mysql->query("Select * From `message` Where `user_id` = '$row[user_id]' Order By `date` Desc, `time` Desc");

?>

<table>
	<tr>
		<th>ID</th>
		<th>狀態</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>個案ID</th>
		<th>內容</th>
	</tr>
	<?
		while($row2 = $sql2->fetch_assoc())
		{
			if($row['id'] == $row2['id']) echo "<tr bgcolor=\"#ffaaaa\">";
			else echo "<tr>";
			echo "<td>".$row2['id']."</td>";
			echo "<td>".(($row2['replied']==1)?"OK":"")."</td>";
			echo "<td>".$row2['date']."</td>";
			echo "<td>".$row2['time'].":00</td>";
			echo "<td>".(($row2['sender']==1)?"個案":"Kajin")."</td>";
			echo "<td>".$row2['user_id']."</td>";
			echo "<td>".$row2['content']."</td>";
			echo "</tr>";
		}
	?>
</table>
