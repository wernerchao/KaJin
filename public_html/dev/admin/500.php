<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

if($_GET['showall']) $sql = $mysql->query("Select * From `message` Order By `timestamp` Asc");
else $sql = $mysql->query("Select * From `message` Where `replied` = '0' Order By `timestamp` Asc");

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="500.php?showall=1">顯示所有留言</a>
<br>
<br>
<table>
	<tr>
		<th>回應</th>
		<th>ID</th>
		<th>狀態</th>
		<th>日期</th>
		<th>時間</th>
		<th>寄件者</th>
		<th>個案ID</th>
		<th>內容</th>
	</tr>
	<?
		while($row = $sql->fetch_assoc())
		{
			echo "<tr>";
			echo "<td><a href=\"501.php?id=".$row['id']."\">回應</a></td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".(($row['replied']==1)?"OK":"")."</td>";
			echo "<td>".$row['date']."</td>";
			echo "<td>".$row['time'].":00</td>";
			echo "<td>".(($row['sender']==1)?"個案":"Kajin")."</td>";
			echo "<td>".$row['user_id']."</td>";
			echo "<td>".$row['content']."</td>";
			echo "</tr>";
		}
	?>
</table>