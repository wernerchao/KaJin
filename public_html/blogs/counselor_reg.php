<?php

include 'include_function.php';
include 'include_setting.php';

$email = $_POST['email'];
$password = md5($_POST['password']);
$name_ch = $_POST['name_ch'];
$name_en = $_POST['name_en'];
$gender = $_POST['gender'];
$languages = implode(',', $_POST['languages']);
$price = $_POST['price'];
$website = $_POST['website'];
$video_url = $_POST['video_url'];
$certificate = $_POST['certificate'];
$career = $_POST['career'];
$speciality = $_POST['speciality'];
$topics = implode(',', $_POST['topics']);
$schools = implode(',', $_POST['schools']);
$words = $_POST['words'];

if (is_null($email) || is_null($password)) {
    // $result = array('error' => 1, 'type' => '帳號資訊不完整！');
    // exit(json_encode($result));
    $result = 'account_info_error';
} elseif (is_null($name_en) || is_null($gender)
    || is_null($languages) || is_null($career)
    || is_null($speciality) || is_null($topics)
    || is_null($schools) || is_null($words)) {
    // $result = array('error' => 1, 'type' => '個人資訊不完整！');
    // exit(json_encode($result));
    $result = 'personal_info_error';
} else {
    $sql_string = "SELECT * FROM `counselor` WHERE `email` = '$email'";
    $sql = $mysql->query($sql_string);
    if ($sql->num_rows > 0) {
        $result = 'account_exist';
    } else {
        $sql_string = 'INSERT INTO `counselor`(`email`,`password`,`name_ch`,`name_en`, `gender`,`price`,`languages`, `speciality`,`career`,`certificate`,`topics`,`schools`,`website`,`video_url`,`words`) '.
            "VALUES ('$email','$password','$name_ch','$name_en',$gender,$price,'$languages','$speciality','$career','$certificate','$topics','$schools','$website','$video_url','$words')";
        // echo $sql_string;
        if (!$mysql->query($sql_string)) {
            echo $sql_string;
            $result = 'db_error';
        } else {
            $result = 'success';
        }

        if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] > 0) {
            // echo 'ERROR: '.$_FILES['avatar']['error'];
            // $result = array('error' => 2, 'type' => '圖片上傳出現錯誤，請登入後台重新上傳');
            // exit("圖片上傳處理異常，");
        } else {
            // echo '檔案名稱: '.$_FILES['avatar']['name'].'<br/>';
            // echo '檔案類型: '.$_FILES['avatar']['type'].'<br/>';
            // echo '檔案大小: '.($_FILES['avatar']['size'] / 1024).' Kb<br/>';
            // echo '暫存名稱: '.$_FILES['avatar']['tmp_name'];
            $file_ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            // echo $file_ext;

            move_uploaded_file($_FILES['avatar']['tmp_name'], 'counselor_img/'.$mysql->insert_id.'.'.$file_ext);
        }
    }
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="images/favicon.ico?426821467">
        <title>註冊結果</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <style>
            .panel {
                margin: 10vh auto;
                width: 500px;
                max-height: 100%;
            }
        </style>
    </head>

    <body>
        <?php if ($result == 'success'): ?>
        <!-- Success message -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">您的帳號已建立！</h3></div>
            <div class="panel-body">
                提醒您定期登入後台管理可供預約之時段，
                <br> 若有個案預約您將會收到通知，您亦可在後台觀看預約個案之資訊。
                <br> 感謝您的填寫！
            </div>
            <div class="panel-footer text-center">
                <a class="btn btn-success" href="https://www.kajinonline.com/counselor/">登入後台</a>
            </div>
        </div>

        <?php elseif ($result == 'account_exist'): ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">此帳號已存在！</h3></div>
            <div class="panel-body">
                請返回上頁重新填寫或直接登入！
            </div>
            <div class="panel-footer text-center">
                <button type="button" class="btn btn-danger" onclick="history.back()">回到上一頁</button>
                <a class="btn btn-danger" href="https://www.kajinonline.com/counselor/">登入後台</a>
            </div>
        </div>

        <?php elseif ($result == 'account_info_error'): ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">帳號資料有誤！</h3></div>
            <div class="panel-body">
                請返回上頁重新填寫！
            </div>
            <div class="panel-footer text-center">
                <button type="button" class="btn btn-danger" onclick="history.back()">回到上一頁</button>
            </div>
        </div>

        <?php elseif ($result == 'personal_info_error'): ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">個人資料有誤！</h3></div>
            <div class="panel-body">
                請返回上頁重新填寫！
            </div>
            <div class="panel-footer text-center">
                <button type="button" class="btn btn-danger" onclick="history.back()">回到上一頁</button>
            </div>
        </div>

        <?php elseif ($result == 'db_error'): ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">資料處理異常！</h3></div>
            <div class="panel-body">
                請返回上頁重新填寫！
            </div>
            <div class="panel-footer text-center">
                <button type="button" class="btn btn-danger" onclick="history.back()">回到上一頁</button>
            </div>
        </div>

        <?php endif; ?>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>

    </html>

    </html>
