<?php

include 'include_function.php';
include 'include_setting.php';

$email = $mysql->real_escape_string($_POST['email']);
$password = $mysql->real_escape_string(md5($_POST['password']));
$name_ch = $mysql->real_escape_string($_POST['name_ch']);
$name_en = $mysql->real_escape_string($_POST['name_en']);
$tel = $mysql->real_escape_string($_POST['tel']);
$location = $mysql->real_escape_string($_POST['location']);
$gender = $mysql->real_escape_string($_POST['gender']);
$languages = $mysql->real_escape_string(implode(',', $_POST['languages']));
// $price = $mysql->real_escape_string($_POST['price']);
// $payout = $mysql->real_escape_string($_POST['payout']);
// $website = $mysql->real_escape_string($_POST['website']);
// $video_url = $mysql->real_escape_string($_POST['video_url']);
// $certificate = $mysql->real_escape_string($_POST['certificate']);
// $career = $mysql->real_escape_string($_POST['career']);
// $speciality = $mysql->real_escape_string($_POST['speciality']);
$topics = $mysql->real_escape_string(implode(',', $_POST['topics']));
$schools = $mysql->real_escape_string(implode(',', $_POST['schools']));
// $words = $mysql->real_escape_string($_POST['words']);

if (is_null($email) || is_null($password)) {
    // $result = array('error' => 1, 'type' => '帳號資訊不完整！');
    // exit(json_encode($result));
    $result = 'account_info_error';
} elseif (is_null($name_en) || is_null($gender) || is_null($languages)
|| is_null($tel) || is_null($location)
// || is_null($career) || is_null($speciality) || is_null($words) || is_null($payout)
 || is_null($topics) || is_null($schools)) {
    // $result = array('error' => 1, 'type' => '個人資訊不完整！');
    // exit(json_encode($result));
    $result = 'personal_info_error';
} else {
    $sql_string = "SELECT * FROM `counselor` WHERE `email` = '$email'";
    $sql = $mysql->query($sql_string);
    if ($sql->num_rows > 0) {
        $result = 'account_exist';
    } else {
        $photo = ($gender) ? 'nonamem.jpg' : 'nonamef.jpg';
        $sql_string = 'INSERT INTO `counselor`(`photo`,`email`,`password`,`name_ch`,`name_en`,`whole_name`,`tel`,`location`,`gender`,`languages`,`topics`,`schools`) '.
            "VALUES ('$photo','$email','$password','$name_ch','$name_en','$name_ch 心理諮詢師','$tel','$location','$gender','$languages','$topics','$schools')";
        // echo $sql_string;
        if (!$mysql->query($sql_string)) {
            echo $sql_string;
            $result = 'db_error';
        } else {
            $name = (!empty($name_ch)) ? $name_ch : $name_en;
            $gender = ($gender == 1) ? '男' : '女';
            notification('new_counselor_to_admin', $system['admin_email'], array('name' => $name, 'gender' => $gender, 'email' => $email, 'tel' => $tel));
            $result = 'success';
        }

        $upload_img_error = false;
        if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] > 0) {
            $upload_img_error = true;

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
            $new_fileneme = $mysql->insert_id.'.'.$file_ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], 'images/counselors/'.$new_fileneme);
            $update_string = "UPDATE `counselor` SET `photo` = '$new_fileneme' WHERE `id` = $mysql->insert_id";
            $mysql->query($update_string);
            if ($mysql->affected_rows != 1) {
                $upload_img_error = true;
            }
        }
    }
}

?>

    <!DOCTYPE html>
    <html>

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
                提醒您至後台編輯詳細的個人資料讓個案更了解您，
                <br> 並定期登入後台管理可供預約之時段，
                <br> 若有個案預約您將會收到通知，預約時段三十分鐘前會再發信提醒，您亦可在後台觀看預約個案之資訊。
                <br> 再次歡迎您加入 KaJin Health 開心心理！
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
