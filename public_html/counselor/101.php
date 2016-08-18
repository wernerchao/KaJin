<?php

include '../include_function.php';
include '../include_setting.php';

login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));
$data_counselor = load_counselor();

// 更改密碼
if ($_POST['change_password']) {
    if (md5($_POST['old_password']) != $data_counselor['password']) {
        echo "<script>alert('舊密碼錯誤。');</script>";
    } elseif ($_POST['new_password1'] != $_POST['new_password2']) {
        echo "<script>alert('新密碼兩次輸入不同。');</script>";
    } elseif ($_POST['new_password1'] == '') {
        echo "<script>alert('新密碼不可空白。');</script>";
    } else {
        $password = md5($_POST['new_password1']);
        $mysql->query("Update `counselor` Set `password` = '$password' Where `id` = '$data_counselor[id]'");
        $_SESSION['counselor_password'] = $_POST['new_password1'];
        login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));
        $data_counselor = load_counselor();
        echo "<script>alert('密碼已修改。');</script>";
    }
}

// 新增執照
if ($_POST['add_certificate']) {
    if (empty($_POST['certificate_title'])) {
        echo "<script>alert('資料不完整。');</script>";
    } else {
        $sql_string = 'INSERT INTO `certificate`(`c_id`,`title`,`description`) VALUES('.$data_counselor['id'].",'".$_POST['certificate_title']."','".$_POST['certificate_description']."')";
        $mysql->query($sql_string);
        if ($mysql->affected_rows < 1) {
            echo "<script>alert('執照更新失敗。');</script>";
        } else {
            $data_counselor = load_counselor();
            echo "<script>alert('執照已更新。');</script>";
        }
    }
}

// 刪除執照
if ($_POST['del_certificate']) {
    if (empty($_POST['cer_id_input'])) {
        echo "<script>alert('不明錯誤，請重新操作。');</script>";
    } else {
        $sql_string = "DELETE FROM `certificate` WHERE `id`='".$_POST['cer_id_input']."'";
        $mysql->query($sql_string);
        if ($mysql->affected_rows < 1) {
            echo "<script>alert('執照刪除失敗。');</script>";
        } else {
            $data_counselor = load_counselor();
            echo "<script>alert('執照已刪除。');</script>";
        }
    }
}

// 新增學歷
if ($_POST['add_education']) {
    if (empty($_POST['education_title'])) {
        echo "<script>alert('資料不完整。');</script>";
    } else {
        $sql_string = 'INSERT INTO `education`(`c_id`,`title`,`subtitle`) VALUES('.$data_counselor['id'].",'".$_POST['education_title']."','".$_POST['education_subtitle']."')";
        $mysql->query($sql_string);
        if ($mysql->affected_rows < 1) {
            echo "<script>alert('學歷更新失敗。');</script>";
        } else {
            $data_counselor = load_counselor();
            echo "<script>alert('學歷已更新。');</script>";
        }
    }
}

// 刪除學歷
if ($_POST['del_education']) {
    if (empty($_POST['edu_id_input'])) {
        echo "<script>alert('不明錯誤，請重新操作。');</script>";
    } else {
        $sql_string = "DELETE FROM `education` WHERE `id`='".$_POST['edu_id_input']."'";
        $mysql->query($sql_string);
        if ($mysql->affected_rows < 1) {
            echo "<script>alert('學歷刪除失敗。');</script>";
        } else {
            $data_counselor = load_counselor();
            echo "<script>alert('學歷已刪除。');</script>";
        }
    }
}

// 新增經歷
if ($_POST['add_experience']) {
    if (empty($_POST['experience_title'])) {
        echo "<script>alert('資料不完整。');</script>";
    } else {
        $sql_string = 'INSERT INTO `experience`(`c_id`,`title`,`subtitle`) VALUES('.$data_counselor['id'].",'".$_POST['experience_title']."','".$_POST['experience_subtitle']."')";
        $mysql->query($sql_string);
        if ($mysql->affected_rows < 1) {
            echo "<script>alert('經歷更新失敗。');</script>";
        } else {
            $data_counselor = load_counselor();
            echo "<script>alert('經歷已更新。');</script>";
        }
    }
}

// 刪除經歷
if ($_POST['del_experience']) {
    if (empty($_POST['exp_id_input'])) {
        echo "<script>alert('不明錯誤，請重新操作。');</script>";
    } else {
        $sql_string = "DELETE FROM `experience` WHERE `id`='".$_POST['exp_id_input']."'";
        $mysql->query($sql_string);
        if ($mysql->affected_rows < 1) {
            echo "<script>alert('經歷刪除失敗。');</script>";
        } else {
            $data_counselor = load_counselor();
            echo "<script>alert('經歷已刪除。');</script>";
        }
    }
}

if ($_POST['change_information']) {
    $name_ch = $mysql->real_escape_string($_POST['name_ch']);
    $name_en = $mysql->real_escape_string($_POST['name_en']);
    $tel = $mysql->real_escape_string($_POST['tel']);
    $location = $mysql->real_escape_string($_POST['location']);
    $gender = $mysql->real_escape_string($_POST['gender']);
    $languages = $mysql->real_escape_string(implode(',', $_POST['languages']));
    $price = $mysql->real_escape_string($_POST['price']);
    $payout = $mysql->real_escape_string($_POST['payout']);
    $website = $mysql->real_escape_string($_POST['website']);
    $video_url = $mysql->real_escape_string($_POST['video_url']);
    // $certificate = $mysql->real_escape_string($_POST['certificate']);
    // $career = $mysql->real_escape_string($_POST['career']);
    $trimmedSpeciality = array_map('trim', $_POST['profile_speciality']);
    $filterSpeciality = array_filter($trimmedSpeciality);
    $speciality = $mysql->real_escape_string(implode('::', array_values($filterSpeciality)));
    $topics = $mysql->real_escape_string(implode(',', $_POST['topics']));
    $schools = $mysql->real_escape_string(implode(',', $_POST['schools']));
    $tag = $mysql->real_escape_string($_POST['tag']);
    $words = $mysql->real_escape_string($_POST['words']);

    if (is_null($name_en) || is_null($gender) || is_null($languages)
    || is_null($tel) || is_null($location)
    || is_null($speciality) || is_null($words) || is_null($payout)
    || is_null($topics) || is_null($schools)) {
        // $result = array('error' => 1, 'type' => '個人資訊不完整！');
        // exit(json_encode($result));
        $result = 'personal_info_error';
        echo "<p class='text-error'>個人資訊不完整！</p>";
        // echo 'personal_info_error';
    } else {
        $sql_string = "UPDATE `counselor` SET `name_ch`='$name_ch', `name_en`='$name_en', `tel`='$tel', `location`='$location', `languages`='$languages', `profile_speciality`='$speciality', `price`=$price, `payout_method`='$payout', ".
        "`topics`='$topics', `schools`='$schools', `website`='$website', `video_url`='$video_url', `tag`='$tag', `words`='$words' WHERE `id`=".$data_counselor['id'];
        // echo $sql_string;
        if (!$mysql->query($sql_string)) {
            // echo $sql_string;
            // $result = 'db_error';
            echo $sql_string;
            echo "<p class='text-error'>個人資料更新失敗！</p>";
        } else {
            login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));
            $data_counselor = load_counselor();
            // echo 'success';
            // $name = (!empty($name_ch)) ? $name_ch : $name_en;
            // $gender = ($gender == 1) ? '男' : '女';
            // notification('new_counselor_to_admin', $system['admin_email'], array('name' => $name, 'gender' => $gender, 'email' => $email, 'tel' => $tel));
            // echo $result = 'success';
        }

        $upload_img_error = false;
        if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] > 0) {
            $upload_img_error = true;

            // echo 'ERROR: '.$_FILES['avatar']['error'];
            // $result = array('error' => 2, 'type' => '圖片上傳出現錯誤，請登入後台重新上傳');
            // exit("圖片上傳處理異常，");
            // echo "<p class='text-error'>頭像更新失敗！</p>";
        } else {
            // echo '檔案名稱: '.$_FILES['avatar']['name'].'<br/>';
            // echo '檔案類型: '.$_FILES['avatar']['type'].'<br/>';
            // echo '檔案大小: '.($_FILES['avatar']['size'] / 1024).' Kb<br/>';
            // echo '暫存名稱: '.$_FILES['avatar']['tmp_name'];
            $file_ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            // echo $file_ext;
            $new_fileneme = $data_counselor['id'].'.'.$file_ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], '../images/counselors/'.$new_fileneme);
            $update_string = "UPDATE `counselor` SET `photo` = '$new_fileneme' WHERE `id` = $data_counselor[id]";
            $mysql->query($update_string);
            if ($mysql->affected_rows != 1) {
                $upload_img_error = true;
            }
            login_counselor($_SESSION['counselor_email'], $_SESSION['counselor_password'], array('f_rd' => 'login.php'));
            $data_counselor = load_counselor();
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
        <link rel="shortcut icon" href="../images/favicon.ico?426821467">
        <title>諮詢師資料管理</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/fileinput.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <style>
            .kv-avatar .file-preview-frame,
            .kv-avatar .file-preview-frame:hover {
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
                text-align: center;
            }

            .kv-avatar .file-input {
                display: table-cell;
                max-width: 220px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <form id="password_update_form" role="form" data-toggle="validator" class="form-horizontal" action="./101.php" method="post">
                <legend>修改密碼</legend>
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label">舊密碼<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="old_password" name="old_password" placeholder="舊密碼" class="form-control" type="password" data-minlength="8" required />
                            </div>
                            <div class="help-block">（密碼最小長度為八位英數字）</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">新密碼<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="new_password1" name="new_password1" placeholder="新密碼" class="form-control" type="password" data-minlength="8" required />
                            </div>
                            <div class="help-block">（密碼最小長度為八位英數字）</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">再次輸入新密碼<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input name="new_password2" placeholder="再次輸入新密碼" class="form-control" type="password" data-match="#new_password1" data-match-error="密碼不符合" required />
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-md-4 text-center">
                            <input type="submit" name="change_password" class="btn btn-warning" value="修改">
                        </div>
                    </div>
                </fieldset>
            </form>
            <form id="counselor_update_form" data-toggle="validator" class="form-horizontal" action="./101.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <!-- Form Name -->
                    <legend>個人資訊</legend>

                    <div class="text-center">
                        <label class="text-center" style="margin-bottom:5px">上傳您的個人檔案照片<small class="text-success">（2MB 以內之 JPG/PNG/GIF 圖檔）</small></label>
                        <div class="kv-avatar center-block" style="width:200px; margin-bottom:20px;">
                            <input id="avatar" name="avatar" type="file" class="file-loading">
                        </div>
                    </div>

                    <!-- CH Name Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">中文姓名</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input name="name_ch" placeholder="中文姓名" class="form-control" type="text" value="<?=$data_counselor['name_ch']?>" />
                            </div>
                        </div>
                    </div>

                    <!-- EN Name Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">英文姓名<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input name="name_en" placeholder="英文姓名" class="form-control" type="text" value="<?=$data_counselor['name_en']?>" required />
                            </div>
                        </div>
                    </div>

                    <!-- Gender Radios  -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">性別<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4">
                            <div class="radio">
                                <label>

                                    <input type="radio" name="gender" value="0" <?php if ($data_counselor[ 'gender'] == 0) {
    echo ' checked="checked"';
} ?> required /> 女性
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="1" <?php if ($data_counselor[ 'gender'] == 1) {
    echo ' checked="checked"';
} ?> required /> 男性
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tel Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">聯絡電話<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                <input name="tel" placeholder="聯絡電話" class="form-control" type="tel" value="<?=$data_counselor['tel']?>" required />
                            </div>
                        </div>
                    </div>

                    <!-- Location Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">地區<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
                                <input name="location" placeholder="您所在的地區" class="form-control" type="text" value="<?=$data_counselor['location']?>" required />
                            </div>
                        </div>
                    </div>

                    <!-- Language Checkboxes -->
                    <div id="lang-checkbox-group" class="form-group checkbox-group">
                        <label class="col-md-4 control-label">諮詢語言（可複選）<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="CH" <?php if (strpos($data_counselor['languages'], 'CH') !== false) {
    echo ' checked="checked"';
} ?>> 中文
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="languages[]" value="EN" <?php if (strpos($data_counselor['languages'], 'EN') !== false) {
    echo ' checked="checked"';
} ?>> 英文
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payout Method Textarea -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">付款方式<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-6 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
                                <textarea class="form-control" name="payout" placeholder="您的銀行帳戶資訊" required><?=$data_counselor['payout_method']?></textarea>
                            </div>
                            <p class="help-block">（請填入「銀行代碼」＋「帳號」）</p>
                        </div>
                    </div>

                    <!-- Price Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">單次諮詢費用（USD）</label>
                        <div class="col-md-2 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                <input name="price" placeholder="" class="form-control" type="number" value="<?=$data_counselor['price']?>">
                            </div>
                        </div>
                    </div>
                    <p class="col-md-6 col-md-offset-3 alert alert-warning">
                        <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> 目前 Kajin Health 的諮詢費用統一為首次 USD$24、往後每次 USD$60。
                        <br>
                        <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> 我們正在規劃開放讓諮詢師自行訂定諮詢費用，您可以在本欄位預先填寫自訂收費，功能上線後我們會通知您。
                    </p>

                    <!-- Website Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">個人網站或部落格</label>
                        <div class="col-md-6 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                                <input name="website" placeholder="http://" class="form-control" type="url" value="<?=$data_counselor['website']?>">
                            </div>
                        </div>
                    </div>

                    <!-- Tags Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">個人檔案標籤</label>
                        <div class="col-md-6 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                                <input name="tag" placeholder="感情議題,青少年,性/別" class="form-control" type="text" value="<?=$data_counselor['tag']?>">
                            </div>
                            <p class="help-block">（請將不同標籤以半型逗號分隔，標籤將顯示於開心心理諮詢師團隊頁面中，讓個案能夠迅速了解您）</p>
                        </div>
                    </div>

                    <!-- Video URL Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">個人介紹或相關影片</label>
                        <div class="col-md-6 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-film"></i></span>
                                <input name="video_url" placeholder="http://" class="form-control" type="url" value="<?=$data_counselor['video_url']?>">
                            </div>
                            <p class="help-block">（請填入 YouTube/Vimeo 影片連結）</p>
                        </div>
                    </div>

                    <!-- Licence Textarea -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">專業執照</label>
                        <div class="col-md-8 inputGroupContainer">
                            <!-- <div class="input-group"> -->
                                <div class="col-md-12 text-muted form-group">
                                    （執照標題／執照說明）
                                </div>
                                    <?php
                                    $cer_result = $mysql->query("SELECT * FROM `certificate` Where `c_id` = '$data_counselor[id]'");
                                    while ($cer = mysqli_fetch_assoc($cer_result)) {
                                        ?>
                                        <div class="col-md-12 form-group">
                                            <?php echo $cer['title'];
                                        if (!empty($cer['description'])) {
                                            echo '／'.$cer['description'];
                                        }
                                        ?>
                                            <button type="button" class="btn btn-xs btn-danger" data-cerid="<?= $cer['id'];
                                        ?>" data-toggle="modal" data-target="#deleteCertificateModal">
                                                刪除執照
                                            </button>
                                        </div>
                                        <?php

                                    }
                                ?>
                            <!-- </div> -->
                        </div>
                    </div>
                    <!-- add certificate Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-md-4 text-center">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCertificateModal">
                                新增執照
                            </button>
                        </div>
                    </div>

                    <!-- education Textarea -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">學歷</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="col-md-12 text-muted form-group">
                                （學校、學位／學位說明）
                            </div>
                            <!-- <div class="input-group"> -->
                                    <?php
                                    $edu_result = $mysql->query("SELECT * FROM `education` Where `c_id` = '$data_counselor[id]'");
                                    while ($edu = mysqli_fetch_assoc($edu_result)) {
                                        ?>
                                        <div class="col-md-12 form-group">
                                            <?php echo $edu['title'];
                                        if (!empty($edu['subtitle'])) {
                                            echo '／'.$edu['subtitle'];
                                        }
                                        ?>
                                            <button type="button" class="btn btn-xs btn-danger" data-eduid="<?= $cer['id'];
                                        ?>" data-toggle="modal" data-target="#deleteEducationModal">
                                                刪除學歷
                                            </button>
                                        </div>
                                        <?php

                                    }
                                ?>
                            <!-- </div> -->
                        </div>
                    </div>
                    <!-- add education Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-md-4 text-center">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEducationModal">
                                新增學歷
                            </button>
                        </div>
                    </div>

                    <!-- experience Textarea -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">經歷</label>
                        <div class="col-md-8 inputGroupContainer">
                            <div class="col-md-12 text-muted form-group">
                                （經歷標題／經歷說明）
                            </div>
                            <!-- <div class="input-group"> -->
                                    <?php
                                    $exp_result = $mysql->query("SELECT * FROM `experience` Where `c_id` = '$data_counselor[id]'");
                                    while ($exp = mysqli_fetch_assoc($exp_result)) {
                                        ?>
                                        <div class="col-md-12 form-group">
                                            <?php echo $exp['title'];
                                        if (!empty($exp['subtitle'])) {
                                            echo '／'.$exp['subtitle'];
                                        }
                                        ?>
                                            <button type="button" class="btn btn-xs btn-danger" data-expid="<?= $exp['id'];
                                        ?>" data-toggle="modal" data-target="#deleteExperienceModal">
                                                刪除經歷
                                            </button>
                                        </div>
                                        <?php

                                    }
                                ?>
                            <!-- </div> -->
                        </div>
                    </div>
                    <!-- add experience Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-md-4 text-center">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExperienceModal">
                                新增經歷
                            </button>
                        </div>
                    </div>

                    <!-- Speciality Textarea -->
                    <div class="form-group">
                        <?php
                            $speciality = explode('::', $data_counselor['profile_speciality']);
                                for ($i = 0;$i < count($speciality);++$i) {
                                    if ($i == 0) {
                                        ?>
                            <label class="col-md-4 control-label">諮詢專長<span class="text-danger">&nbsp*</span></label>
                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <input name="profile_speciality[]" class="form-control" type="text" value="<?= $speciality[$i];
                                        ?>" required>
                                </div>
                            </div>
                            <?php

                                    } else {
                                        ?>
                                <label class="col-md-4 control-label"><span class="text-danger"></span></label>
                                <div class="col-md-8 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                        <input name="profile_speciality[]" class="form-control" type="text" value="<?= $speciality[$i];
                                        ?>">
                                    </div>
                                </div>
                                <?php

                                    }
                                }
                                for ($k = count($speciality); $k < 5; ++$k) {
                                    if ($i == 0) {
                                        ?>
                                    <label class="col-md-4 control-label">諮詢專長<span class="text-danger">&nbsp*</span></label>
                                    <div class="col-md-8 inputGroupContainer">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                            <input name="profile_speciality[]" class="form-control" type="text" value="<?= $speciality[$i];
                                        ?>" required>
                                        </div>
                                    </div>
                                    <?php

                                    } else {
                                        ?>
                                        <label class="col-md-4 control-label"><span class="text-danger"></span></label>
                                        <div class="col-md-8 inputGroupContainer">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                                <input name="profile_speciality[]" class="form-control" type="text" value="<?= $speciality[$i];
                                        ?>">
                                            </div>
                                        </div>
                                        <?php

                                    }
                                }
                                ?>
                                            <p class="help-block text-right">（請至少填入一項，並填於本欄位第一格）</p>
                    </div>

                    <!-- Topic Checkboxes -->
                    <div id="topic-checkbox-group" class="form-group checkbox-group">
                        <label class="col-md-4 control-label">擅長主題（可複選）<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="0" <?php if (strpos($data_counselor['topics'], '0') !== false) {
    echo ' checked="checked"';
} ?>> 自我困擾（內在探索，例如：憂鬱、緊張、自我實現等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="1" <?php if (strpos($data_counselor['topics'], '1') !== false) {
    echo ' checked="checked"';
} ?>> 家庭問題（非伴侶間，例如：婆媳關係等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="2" <?php if (strpos($data_counselor['topics'], '2') !== false) {
    echo ' checked="checked"';
} ?>> 感情議題（伴侶婚姻，例如：信任、背叛、溝通等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="3" <?php if (strpos($data_counselor['topics'], '3') !== false) {
    echo ' checked="checked"';
} ?>> 親子議題（教養相關，例如：兒童情緒、行為困擾等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="4" <?php if (strpos($data_counselor['topics'], '4') !== false) {
    echo ' checked="checked"';
} ?>> 工作職場（職場壓力，例如：競爭、人際困擾等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="5" <?php if (strpos($data_counselor['topics'], '5') !== false) {
    echo ' checked="checked"';
} ?>> 朋友社交（人際相處，例如：社交恐懼、同儕關係等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="6" <?php if (strpos($data_counselor['topics'], '6') !== false) {
    echo ' checked="checked"';
} ?>> 同志議題（深層探索，例如：自我調適、壓力因應等）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="topics[]" value="7" <?php if (strpos($data_counselor['topics'], '7') !== false) {
    echo ' checked="checked"';
} ?>> 兩性關係（青少年的，例如：兒童情緒、行為困擾等）
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- School Checkboxes -->
                    <div id="school-checkbox-group" class="form-group checkbox-group">
                        <label class="col-md-4 control-label">擅長學派（可複選）<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="0" <?php if (strpos($data_counselor['schools'], '0') !== false) {
    echo ' checked="checked"';
} ?> > 探索過去經驗對現在的影響、分析行為/想法背後動機（心理動力）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="1" <?php if (strpos($data_counselor['schools'], '1') !== false) {
    echo ' checked="checked"';
} ?>> 談家庭對你影響，手足對你的影響（家族）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="2" <?php if (strpos($data_counselor['schools'], '2') !== false) {
    echo ' checked="checked"';
} ?>> 談早期生活風格，手足競爭，自卑等（Alder）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="3" <?php if (strpos($data_counselor['schools'], '3') !== false) {
    echo ' checked="checked"';
} ?>> 重視過去與個人特質(應用認知行為)
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="4" <?php if (strpos($data_counselor['schools'], '4') !== false) {
    echo ' checked="checked"';
} ?>> 工談談個人存在以及生命的意義（存在主義）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="5" <?php if (strpos($data_counselor['schools'], '5') !== false) {
    echo ' checked="checked"';
} ?>> 注重情緒焦點，調節各種受創經驗和脆弱的心情，強調改變就在當下（情緒焦點）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="6" <?php if (strpos($data_counselor['schools'], '6') !== false) {
    echo ' checked="checked"';
} ?>> 冥想抒壓
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="7" <?php if (strpos($data_counselor['schools'], '7') !== false) {
    echo ' checked="checked"';
} ?>> 自我觀察，改變負向認知對情緒行為的影響（認知行為）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="8" <?php if (strpos($data_counselor['schools'], '8') !== false) {
    echo ' checked="checked"';
} ?>> 強調個人的選擇及責任，討論個案的計畫及行動（現實治療）
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="schools[]" value="9" <?php if (strpos($data_counselor['schools'], '9') !== false) {
    echo ' checked="checked"';
} ?>> 幫助個人澄清他們的個人價值觀，注重行動（Acceptance and commitment therapy, ACT）
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Words Textarea -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">給個案的話<span class="text-danger">&nbsp*</span></label>
                        <div class="col-md-6 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                <textarea class="form-control" name="words" placeholder="此段文字會顯示在您的前台檔案頁面" required><?=$data_counselor['words']?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <div class="col-md-4 text-center">
                            <input type="submit" name="change_information" class="btn btn-warning" value="修改">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <!-- /.container -->

        <div id="addCertificateModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" action="./101.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">新增執照</h4>
                    </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label">執照標題</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark"></i></span>
                                        <input class="form-control" name="certificate_title" placeholder="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">執照說明</label>
                                <div class="col-md-6 ">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark"></i></span>
                                        <input class="form-control" name="certificate_description" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <input type="submit" name="add_certificate" class="btn btn-primary" value="送出">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <div id="deleteCertificateModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" action="./101.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">刪除執照</h4>
                    </div>
                        <div class="modal-body">
                            此操作不可回復，確定刪除此執照？
                            <input type="hidden" class="cer-id-input" name="cer_id_input" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <input type="submit" name="del_certificate" class="btn btn-danger" value="刪除">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <div id="addEducationModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" action="./101.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">新增學歷</h4>
                    </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label">學校、學位</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark"></i></span>
                                        <input class="form-control" name="education_title" placeholder="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">學位說明</label>
                                <div class="col-md-6 ">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark"></i></span>
                                        <input class="form-control" name="education_subtitle" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <input type="submit" name="add_education" class="btn btn-primary" value="送出">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <div id="deleteEducationModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" action="./101.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">刪除學歷</h4>
                    </div>
                        <div class="modal-body">
                            此操作不可回復，確定刪除此學歷？
                            <input type="hidden" class="edu-id-input" name="edu_id_input" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <input type="submit" name="del_education" class="btn btn-danger" value="刪除">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->

        <div id="addExperienceModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" action="./101.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">新增經歷</h4>
                    </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label">經歷標題</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark"></i></span>
                                        <input class="form-control" name="experience_title" placeholder="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">經歷說明</label>
                                <div class="col-md-6 ">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-bookmark"></i></span>
                                        <input class="form-control" name="experience_subtitle" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <input type="submit" name="add_experience" class="btn btn-primary" value="送出">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <div id="deleteExperienceModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="form-horizontal" action="./101.php" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">刪除經歷</h4>
                    </div>
                        <div class="modal-body">
                            此操作不可回復，確定刪除此經歷？
                            <input type="hidden" class="exp-id-input" name="exp_id_input" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <input type="submit" name="del_experience" class="btn btn-danger" value="刪除">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="../scripts/fileinput.min.js"></script>
        <script src="../scripts/validator.min.js"></script>
        <script>
            $(document).on('ready', function() {
                $("#avatar_input").fileinput({
                    showCaption: true,
                    showUpload: false
                });
                $("#video_input").fileinput({
                    showCaption: false,
                    showUpload: false
                });
                $("#avatar").fileinput({
                    overwriteInitial: true,
                    maxFileSize: 2048,
                    showClose: false,
                    showCaption: false,
                    showUpload: false,
                    browseLabel: '',
                    removeLabel: '',
                    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
                    removeTitle: '刪除照片',
                    elErrorContainer: '#kv-avatar-errors',
                    msgErrorClass: 'alert alert-block alert-danger',
                    defaultPreviewContent: '<img src="../images/counselors/<?=$data_counselor['photo']?>" alt="Default Avatar" style="width:160px">',
                    // layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
                    allowedFileExtensions: ["jpg", "png", "gif"]
                });
                $('#counselor_update_form').validator().on('submit', function(e) {
                    if (!e.isDefaultPrevented()) {
                        var valid = true;
                        $(".checkbox-group").each(function(index, element) {
                            if ($(this).find("input[type=checkbox]:checked").length === 0) {
                                e.preventDefault();
                                $(this).addClass('has-error');
                                valid = false;
                            }
                        });
                        if (!valid) {
                            $('html, body').animate({
                                scrollTop: $(".has-error").first().offset().top
                            }, 500);
                            return false;
                        }
                    }
                });
                $('#deleteCertificateModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var cer_id = button.data('cerid');
                    var modal = $(this);
                    modal.find('.cer-id-input').val(cer_id);
                });
                $('#deleteEducationModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var edu_id = button.data('eduid');
                    var modal = $(this);
                    modal.find('.edu-id-input').val(edu_id);
                });
                $('#deleteExperienceModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var exp_id = button.data('expid');
                    var modal = $(this);
                    modal.find('.exp-id-input').val(exp_id);
                });
            });
        </script>
    </body>

    </html>
