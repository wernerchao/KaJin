<?

include("include_function.php");
include("include_setting.php");

// �Ӯ׵n�J
login_user($_SESSION['user_email'], $_SESSION['user_password'], array("f_rd" => "login.php"));

// Ū���Ӯ׸��
$data_user = load_user();
if($data_user == "ERROR") logout_user(array("rd" => "login.php"));

// �ˬd�O�_�w�Q�w��
$sql = $mysql->query("Select * From `appointment` Where `id` = '$_POST[appointment_id]' And `user_id` = '0'");
if($sql->num_rows != 1) header("location:reserve.php");

// �ˬd�O�_�w�g�W�L�ɶ� (�{�b���p�ɤ]����w��)
$row = $sql->fetch_assoc();
if(is_datetime_passed($row['date'], $row['time']) != -1) header("location:reserve.php");

/* �H�U�� code */

// check finished, start to reserve

// count price
/*$sql2 = $mysql->query("Select * From `appointment` Where `user_id` = '$user_data[id]'");
if($sql2->num_rows == 0) $fee = "800";
else $fee = "2000";

// update data
$str = "Update `appointment` Set `user_id` = '$user_data[id]', `fee` = '$fee', `state_counsel` = '1', `state_payment` = '2' Where `id` = '$_POST[appointment_id]'";
$mysql->query($str);*/

// send mail
/*$list_counselor = list_counselor();
$sql = $mysql->query("Select * From `appointment` Where `id` = '$_POST[appointment_id]'");
$row = $sql->fetch_assoc();
notification("reserve", $system['admin_email'], array("email" => $user_data['email'], "date" => $row['date'], "time" => $row['time'], "counselor" => $list_counselor[$row['counselor_id']]['name_ch']));*/

//header("location:reserve_finish.php?id=$_POST[appointment_id]");

// ���I�ڭ���
header("location:payment.php?appointment_id=$_POST[appointment_id]");
exit();

?>