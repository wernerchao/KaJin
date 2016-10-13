<?php
    header('Access-Control-Allow-Origin: *');

    include 'include_function.php';
    include 'include_setting.php';

    $check_string = "SELECT * FROM `rating` WHERE `appointment`='$_POST[appointment]'";
    $sql = $mysql->query($check_string);
    if ($sql->num_rows > 0) {
        $output = array('status' => false, 'message' => '您已評價過此次諮詢！');
        echo json_encode($output);
        exit;
    }
    $sql_string = "INSERT INTO `rating`(`counselor`, `appointment`, `comment`, `r1`, `r2`, `r3`) VALUES ('$_POST[counselor]','$_POST[appointment]','$_POST[comment]','$_POST[0]','$_POST[1]','$_POST[2]')";

    $sql = $mysql->query($sql_string);
    if ($mysql->affected_rows > 0) {
        $output = array('status' => true, 'message' => '已送出老師評價');
        echo json_encode($output);
    } else {
        $output = array('status' => false, 'message' => '請重新操作！');
        echo json_encode($output);
    }
