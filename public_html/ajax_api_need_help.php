<?php

    include 'include_function.php';
    include 'include_setting.php';
    
    if (!isset($_POST['time'])) {

        exit("{\"error\" : 1}");
    
    } else {

        // 判斷是否登入
        $data_user = load_user();   
        if($data_user == "ERROR") {
            exit("{\"error\" : 1}");
        }

        $sql_string = "Update `user` Set `system_record` = '".$data_user['system_record']." contact".$_POST['time']."' Where `id` = '$data_user[id]'";

        if ($mysql->query($sql_string)) {
    
            notification("contact", $system['admin_email'], array("name" => $data_user['name'], "email" => $data_user['email'], "phone" => "(".$data_user['phone_country'].")".$data_user['phone_number'], "datetime" => date("Y-m-d H:i:s", $system['time_now'])));
            echo "{\"error\" : 0}";
    
        } else {

            exit("{\"error\" : 1}");

        }
    }        

