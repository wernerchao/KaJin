<?

include("../include_function.php");
include("../include_setting.php");

login_admin("", $_SESSION['admin_password'], array("f_rd" => "login.php"));

if($_POST['submit'])
{
	if($_POST['step'] == 2)
	{
		$sql = $mysql->query("Select * From `appointment` Where `counselor_id` = '$_POST[counselor_id]' And `payout_id` = '0' And `user_id` != '0' Order By `date` Desc, `time` Desc");
	}
	else if($_POST['step'] == 3)
	{
		if($_POST['year'] && $_POST['month'] && $_POST['day'] && $_POST['amount'])
		{
			if($_POST['appointment_id'])
			{
				$date = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
				$str = "Insert Into `payout` (`counselor_id`, `date`, `amount`, `note`) Values ('$_POST[counselor_id]', '$date', '$_POST[amount]', '$_POST[note]')";
				$mysql->query($str);
				$id = $mysql->insert_id;
				foreach($_POST['appointment_id'] as $v)
				{
					$str = "Update `appointment` Set `payout_id` = '$id' Where `id` = '$v'";
					$mysql->query($str);
				}
				echo "<script>alert('記錄已新增！');</script>";
			}
			else
			{
				echo "<script>alert('未選擇記錄！');</script>";
			}
		}
		else
		{
			echo "<script>alert('日期及金額不可空白！');</script>";
		}
		$_POST['step'] = 2;
		$sql = $mysql->query("Select * From `appointment` Where `counselor_id` = '$_POST[counselor_id]' And `payout_id` = '0' And `user_id` != '0' Order By `date` Desc, `time` Desc");
	}
}

$list_counselor = list_counselor();

?>
<link href="adminstyle.css" rel="stylesheet" type="text/css" media="all">
<a href="400.php">返回上一頁</a>
<br>
<br>
<form action="410.php" method="post">
1. 選擇諮商師<br>
	<select name="counselor_id">
		<?
			foreach($list_counselor as $k => $v)
			{
				echo "<option value=\"".$k."\">".$v['name_ch']."</option>";
			}
		?>
	</select>
	<input type="submit" name="submit" value="選擇">
	<input type="hidden" name="step" value="2">
</form>
<?
	if($_POST['step'] == 2)
	{
?>
<form action="410.php" method="post">
	2. 選擇諮商記錄<br>
	<table>
		<tr>
			<th>選擇</th>
			<th>ID</th>
			<th>日期</th>
			<th>時間</th>
			<th>諮商師</th>
			<th>費用</th>
			<th>個案ID</th>
			<th>諮商狀態</th>
			<th>付款狀態</th>
			<th>zoom ID</th>
			<th>回報</th>
			<th>評價</th>
		</tr>
		<?
			while($row = $sql->fetch_assoc())
			{
				echo "<tr>";
				echo "<td><input type=\"checkbox\" name=\"appointment_id[]\" value=\"".$row['id']."\"></td>";
				echo "<td>".$row['id']."</td>";
				echo "<td>".$row['date']."</td>";
				echo "<td>".$row['time'].":00</td>";
				echo "<td>".$list_counselor[$row['counselor_id']]['name_ch']."</td>";
				echo "<td>".$row['fee']."</td>";
				echo "<td>".$row['user_id']."</td>";
				echo "<td>".$variable['state_counsel'][$row['state_counsel']]."</td>";
				echo "<td>".$variable['state_payment'][$row['state_payment']]."</td>";
				echo "<td>".$row['zoom_id']."</td>";
				echo "<td>".$row['report']."</td>";
				echo "<td>".$row['evaluate']."</td>";
				echo "</tr>";
			}
		?>
	</table>
	<br>
	3. 填寫匯款資料<br>
	<table>
		<tr>
			<td>日期</td>
			<td>
				<input type="text" name="year" size="4">年
				<input type="text" name="month" size="2">月
				<input type="text" name="day" size="2">日
			</td>
		</tr>
		<tr>
			<td>金額</td>
			<td><input type="text" name="amount" size="10"></td>
		</tr>
		<tr>
			<td>備註</td>
			<td><textarea name="note" cols="25" rows="5"></textarea></td>
		</tr>
	</table>
	<br>
	<input type="submit" name="submit" value="登記匯款">
	<input type="hidden" name="step" value="3">
	<input type="hidden" name="counselor_id" value="<?=$_POST['counselor_id']?>">
</form>
<?
	}
?>