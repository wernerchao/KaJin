<?php
    header('Access-Control-Allow-Origin: *');

    include 'include_function.php';
    include 'include_setting.php';

    $check_string = 'SELECT a.`id` a_id,a.`user_id`,a.`counselor_id`,a.`date`,a.`time`,c.`name_ch` c_name,r.`id` r_id FROM `appointment` a JOIN `counselor` c ON c.`id`= a.`counselor_id` LEFT JOIN `rating` r ON a.`id` = r.`appointment` WHERE a.`id` = (SELECT MAX(a2.`id`) FROM `appointment` a2 WHERE a2.`user_id`='.$_GET['user_id']." GROUP BY a2.`user_id`) AND  (a.`state_counsel`='2'||a.`state_counsel`='3'||a.`state_counsel`='4')";

    $sql = $mysql->query($check_string);
    if ($sql->num_rows > 0) {
        $result = $sql->fetch_assoc();
        if (empty($result['r_id'])) {
            $output = array('status' => false, 'result' => $result);
            echo json_encode($output);
        } else {
            $output = array('status' => true, 'message' => '已評價過前次諮詢！');
            echo json_encode($output);
        }
    } else {
        $output = array('status' => true, 'message' => '尚未有諮詢記錄');
        echo json_encode($output);
    }
