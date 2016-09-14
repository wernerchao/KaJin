<?

include("../include_function.php");
include("../include_setting.php");

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array("f_rd" => "login.php"));

$data_counselor = load_counselor();

if($_POST['submit'])
{
	$message = trim($_POST['message']);
	if($message != "")
	{
		$mysql->query("Insert Into `counselor_board` (`counselor_id`, `date`, `time`, `sender`, `message`) values ('$data_counselor[id]', '".get_date()."', '".date("H:i", $system['time_now'])."', '1', '$message')");
		header("location:600.php");
		exit();
	}
}

$sql = $mysql->query("Select * From `counselor_board` Where `counselor_id` = '$data_counselor[id]' Order By `id` Desc");

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">

<form action="600.php" method="post">
	<table>
		<tr>
			<td>
				<textarea name="message" cols="30" rows="3"></textarea>
			</td>
			<td>
				<input type="submit" name="submit" value="送出留言給 Kajin">
			</td>
		</tr>
	</table>
</form>

<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>內容</th>
	</tr>
	<?
		while($row = $sql->fetch_assoc())
		{
			echo "<tr>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['date']."</td>";
			echo "<td>".$row['time']."</td>";
			echo "<td>".(($row['sender'])?"您":"Kajin")."</td>";
			echo "<td>".$row['message']."</td>";
			echo "</tr>";
		}
	?>
</table>