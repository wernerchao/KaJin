<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

$sql = $mysql->query("Select * From `payout` Order By `id` Desc");

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="410.php">新增記錄</a>
<br>
<br>
<table>
	<tr>
		<th>查看</th>
		<th>ID</th>
		<th>日期</th>
		<th>諮商師</th>
		<th>金額</th>
		<th>備註</th>
	</tr>
	<?
		while($row = $sql->fetch_assoc())
		{
			echo "<tr>";
			echo "<td><a href=\"401.php?id=".$row['id']."\">查看</a></td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['date']."</td>";
			echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
			echo "<td>".$row['amount']."</td>";
			echo "<td>".$row['note']."</td>";
			echo "</tr>";
		}
	?>
</table>