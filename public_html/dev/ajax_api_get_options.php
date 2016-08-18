<?php

    include 'include_function.php';
    include 'include_setting.php';
    
    $sql_string = 'SELECT `q_id`,`opt_id`,`opt_text` FROM  `answer_options`';

    if (isset($_GET['q_id'])) {
    	$sql_string .= ' WHERE `q_id`='.$_GET['q_id'];
    	if (isset($_GET['opt_id'])) {
    		$sql_string .= ' AND `opt_id`='.$_GET['opt_id'];
    	}
    }

    $sql = $mysql->query($sql_string);
    while ($row = mysqli_fetch_assoc($sql)) {
        $result[$row['q_id']][] = $row;
    }

    echo json_encode($result);
