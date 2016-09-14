<?

include("../include_function.php");
include("../include_setting.php");

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array("f_rd" => "login.php"));

$data_counselor = load_counselor();

$sql = $mysql->query("Select * From `payout` Where `id` = '$_GET[id]' And `counselor_id` = '$data_counselor[id]'");
if($sql->num_rows != 1)
{
	header("location:500.php");
	exit();
}
$row = $sql->fetch_assoc();

$sql2 = $mysql->query("Select * From `appointment` Where `payout_id` = '$_GET[id]'");

$list_counselor = list_counselor();

?>
<link href="counselorstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="500.php">返回上一頁</a>
<br>
<br>
<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>金額</th>
		<!--<th>備註</th>-->
	</tr>
	<tr>
		<form>
			<td><?=$row['id']?></td>
			<td><?=$row['date']?></td>
			<td><?=$row['amount']?></td>
			<!--<td><? /* echo $row['note']; */?></td>-->
			<input type="hidden" name="payout_id" value="<?=$_GET['id']?>">
			<input type="hidden" name="action" value="edit">
		</form>
	</tr>
</table>

<h3>所屬諮商紀錄</h3>

<table>
	<tr>
		<th>ID</th>
		<th>日期</th>
		<th>時間</th>
		<th>費用</th>
		<th>個案ID</th>
		<th>諮商狀態</th>
		<th>付款狀態</th>
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
			echo "</tr>";
		}
	?>
</table>