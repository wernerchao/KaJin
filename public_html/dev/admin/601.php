<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

$list_counselor = list_counselor();

if($_POST) $_GET['id'] = $_POST['id'];

$sql = $mysql->query("Select * From `counselor_board` Where `id` = '$_GET[id]'");
if($sql->num_rows != 1)
{
	header("location:600.php");
	exit();
}

if($_POST)
{
	if(trim($_POST['message']))
	{
		$row = $sql->fetch_assoc();
		$mysql->query("Insert Into `counselor_board` (`counselor_id`, `date`, `time`, `sender`, `message`, `replied`) Values ('$row[counselor_id]', '".get_date()."', '".date("H:i", $system['time_now'])."', '0', '$_POST[message]', '1')");
		echo "<script>alert(\"反應已回應！\");</script>";
	}
	if($_POST['replied'])
	{
		$mysql->query("Update `counselor_board` Set `replied` = '1' Where `id` = '$_GET[id]'");
		echo "<script>alert(\"已標記為已處理！\");</script>";
	}
	$sql = $mysql->query("Select * From `counselor_board` Where `id` = '$_GET[id]'");
}

$row = $sql->fetch_assoc();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="600.php">返回上一頁</a>
<br>
<br>
<table>
	<tr>
		<th>ID</th>
		<th>狀態</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>諮商師</th>
		<th>內容</th>
	</tr>
	<?
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".(($row['replied']==1)?"OK":"")."</td>";
		echo "<td>".$row['date']."</td>";
		echo "<td>".$row['time'].":00</td>";
		echo "<td>".(($row['sender']==1)?"諮商師":"Kajin")."</td>";
		echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
		echo "<td>".$row['message']."</td>";
		echo "</tr>";
	?>
</table>

<h3>回應反應</h3>

<form action="601.php" method="post">
	若只需標記則回應內容可空白<br>
	<textarea name="message" cols="80" rows="10"></textarea>
	<br>
	<input type="checkbox" name="replied" value="1"> 標示為已處理<br>
	<input type="submit" name="submit" value="回應">
	<input type="hidden" name="id" value="<?=$_GET['id']?>">
</form>

<h3>諮商師過去反應</h3>

<?

$sql2 = $mysql->query("Select * From `counselor_board` Where `counselor_id` = '$row[counselor_id]' Order By `date` Desc, `time` Desc, `id` Desc");

?>

<table>
	<tr>
		<th>ID</th>
		<th>狀態</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>諮商師</th>
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
			echo "<td>".(($row['sender']==1)?"諮商師":"Kajin")."</td>";
			echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
			echo "<td>".$row2['message']."</td>";
			echo "</tr>";
		}
	?>
</table>
