<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

$sql = $mysql->query("Select * From `counselor` Where `id` = '$_GET[id]'");
if($sql->num_rows != 1)
{
	header("location:200.php");
	exit();
}
$row = $sql->fetch_assoc();

$sql2 = $mysql->query("Select * From `appointment` Where `counselor_id` = '$_GET[id]' And `user_id` != '0' Order By `date` Desc, `time` Desc");

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="200.php">返回上一頁</a>
<br>
<br>
<table>
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>姓名(中文)</th>
		<th>姓名(英文)</th>
		<th>照片</th>
		<th>付款方式</th>
		<th>諮詢專長</th>
		<th>學歷與經歷</th>
		<th>專業執照</th>
		<th>相關著作</th>
	</tr>
	<?
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['email']."</td>";
		echo "<td>".$row['name_ch']."</td>";
		echo "<td>".$row['name_en']."</td>";
		echo "<td>".$row['photo']."</td>";
		echo "<td>".$row['payout_method']."</td>";
		echo "<td><pre>".$row['introduction']."</pre></td>";
		echo "<td><pre>".$row['experience']."</pre></td>";
		echo "<td><pre>".$row['certificate']."</pre></td>";
		echo "<td><pre>".$row['writing']."</pre></td>";
		echo "</tr>";
	?>
</table>

<h3>諮商紀錄</h3>

<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>費用</th>
		<th>個案ID</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
		<th>交款ID</th>
		<th>zoom ID</th>
		<th>目標</th>
		<th>功課</th>
		<th>筆記</th>
	</tr>
	<?
		while($row2 = $sql2->fetch_assoc())
		{
			echo "<tr>";
			echo "<td>".$row2['id']."</td>";
			echo "<td>".$row2['date']."</td>";
			echo "<td>".$row2['time'].":00</td>";
			echo "<td>".$row2['fee']."</td>";
			echo "<td>".$row2['user_id']."</td>";
			echo "<td>".$variable['state_counsel'][$row2['state_counsel']]."</td>";
			echo "<td>".$variable['state_payment'][$row2['state_payment']]."</td>";
			echo "<td>".$row['payout_id']."</td>";
			echo "<td>".$row2['zoom_id']."</td>";
			echo "<td>".$row2['goal']."</td>";
			echo "<td>".$row2['homework']."</td>";
			echo "<td>".$row2['note']."</td>";
			echo "</tr>";
		}
	?>
</table>