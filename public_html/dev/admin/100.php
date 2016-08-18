<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

$sql = $mysql->query("Select * From `user` Where `active` = '1' Order By `id` Desc");

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<table>
	<tr>
		<th>查</th>
		<th>ID</th>
		<th>Email</th>
		<th>姓名</th>
		<th>諮商師</th>
		<th>國碼</th>
		<th>電話</th>
		<th>年齡</th>
		<th>性別</th>
		<th>職業</th>
		<th>專</th>
		<th>導</th>
		<th>備註</th>
	</tr>
	<?
		while($row = $sql->fetch_assoc())
		{
			echo "<tr>";
			echo "<td><a href=\"101.php?id=".$row['id']."\">查</a></td>";
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['email']."</td>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
			echo "<td>".$row['phone_country']."</td>";
			echo "<td>".$row['phone_number']."</td>";
			echo "<td>".$variable['age'][$row['age']]."</td>";
			echo "<td>".$variable['gender'][$row['gender']]."</td>";
			echo "<td>".$row['oupation']."</td>";
			// 專人
			echo "<td>".((strstr($row['system_record'],"contact"))?"V":"")."</td>";
			// 引導
			echo "<td>";
			if(strstr($row['system_record'],"start3")) echo "C";
			else if(strstr($row['system_record'],"start2")) echo "B";
			else if(strstr($row['system_record'],"start1")) echo "A";
			echo "</td>";
			echo "<td>".mb_strimwidth($row['note'], 0, 15, '...', 'UTF-8')."</td>";
			echo "</tr>";
		}
	?>
</table>