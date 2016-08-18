<?php

    include 'include_function.php';
    include 'include_setting.php';
    
    $sql_string = 'SELECT id, name_ch, whole_name, gender, photo, speciality, career, words, short_words, profile FROM `counselor` WHERE `active`=1 ORDER BY `id`';

    if (isset($_GET['offset']) && isset($_GET['limit'])) {
    	$sql_string .= ' LIMIT '.$_GET['offset'].', '.$_GET['limit'];
    }

    $sql = $mysql->query($sql_string);
    while ($row = mysqli_fetch_assoc($sql)) {
        $counselor[$row['id']] = $row;
    }

    echo json_encode($counselor);
