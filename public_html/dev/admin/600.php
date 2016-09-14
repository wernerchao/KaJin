<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

$list_counselor = list_counselor();

if($_GET['showall']) $sql = $mysql->query("Select * From `counselor_board` Order By `date` Asc, `time` Asc, `id` Asc");
else $sql = $mysql->query("Select * From `counselor_board` Where `replied` = '0' Order By `date` Asc, `time` Asc, `id` Asc");

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="600.php?showall=1">顯示所有反應</a>
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
		<th>諮商師</th>
		<th>內容</th>
	</tr>
	<?
		while($row = $sql->fetch_assoc())
		{
			echo "<tr>";
			echo "<td><a href=\"601.php?id=".$row['id']."\">回應</a></td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".(($row['replied']==1)?"OK":"")."</td>";
			echo "<td>".$row['date']."</td>";
			echo "<td>".$row['time'].":00</td>";
			echo "<td>".(($row['sender']==1)?"諮商師":"Kajin")."</td>";
			echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
			echo "<td>".$row['message']."</td>";
			echo "</tr>";
		}
	?>
</table>