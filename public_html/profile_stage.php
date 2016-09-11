<?php
    include 'include_function.php';
    include 'include_setting.php';

    $sql = $mysql->query("SELECT * FROM `counselor` WHERE `id`='$_GET[counselor]' AND active=1");
    if (mysqli_num_rows($sql) == 1) {
        $education = $mysql->query('select e.* from education e, counselor c2  where  c2.active=1 and c2.Id=e.c_id and e.c_Id='.$_GET['counselor']);
        $experience = $mysql->query('select e.* from experience e, counselor c2  where  c2.active=1 and c2.Id=e.c_id and e.c_Id='.$_GET['counselor']);
        $coun = $mysql->query('select * from counselor c where c.active=1 and c.Id='.$_GET['counselor']);
        $certificate = $mysql->query('select c.* from certificate c, counselor c2 where c2.active=1 and c2.Id=c.c_id and c.c_Id='.$_GET['counselor']);
        $publication = $mysql->query('select p.* from publication p, counselor c2 where c2.active=1 and c2.Id=p.c_id and p.c_Id='.$_GET['counselor']);
        $reply = $mysql->query('select c.* ,u.name as user_name, c1.name_ch as coun_name from comment c left join user u on c.u_id=u.Id, counselor c1 where c.c_Id='.$_GET['counselor'].' and c1.Id=c.c_Id order by c.q_id desc');
        // rating related sql
        $avg_result = $mysql->query('SELECT CAST((AVG(`r1`)+AVG(`r2`)+AVG(`r3`))/3 as DECIMAL(2,1))  total_avg, CAST(AVG(`r1`) as DECIMAL(2,1)) r1_avg ,CAST(AVG(`r2`) as DECIMAL(2,1)) r2_avg,CAST(AVG(`r3`) as DECIMAL(2,1)) r3_avg FROM `rating` WHERE `counselor`='.$_GET['counselor']);
        $rate_row = $mysql->query('SELECT r.*, u.`email` FROM `rating` r,`appointment` a,`user` u WHERE r.`appointment`=a.`id` AND a.`user_id`=u.`id` AND `counselor`='.$_GET['counselor'].' ORDER BY `id` DESC');
    } else {
        header('Location: ./our_team.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <meta name="description" content="Kajin Health 開心心理線上諮詢的心理諮詢師團隊">
    <meta name="keywords" content="">
    <meta content="" property="og:site_name">
    <meta content="" property="og:title">
    <meta content="" property="og:description">
    <meta content="" property="og:url">
    <meta content="website" property="og:type">
    <meta content="" property="og:image">
    <meta content="image/png" property="og:image:type">
    <link rel="shortcut icon" href="images/favicon.ico?crc=426821467">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/profile.css">
    <link rel="stylesheet" type="text/css" href="./css/rating.css">
    <link rel="stylesheet" type="text/css" href="./css/comment.css">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/font-awesome-4.6.3/css/font-awesome.min.css">
    <title>Kajin Health 開心心理｜心理諮詢師</title>
</head>

<body>
    <div class="navbar-wrapper">
        <div class="container navbar-container">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" class="navbar-toggle collapsed"><span class="sr-only"></span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                        <a href="https://kajinonline.com/" class="navbar-brand icon logo-img"></a><a class="navbar-brand icon-word">KAJINHEALTH 開心線上心理諮詢</a></div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="https://kajinonline.com/index.html#services">諮詢項目</a></li>
                            <li><a href="https://kajinonline.com/index.html#counselor">心理諮詢師</a></li>
                            <li><a href="https://kajinonline.com/index.html#prices">收費方式</a></li>
                            <li><a href="https://kajinonline.com/index.html#faq">常見問題</a></li>
                            <li style="background-color:#67C4CB; border: 5px; padding:0 5px; position: relative; top:5px; border-radius:5px;" class="right"><a href="https://kajinonline.com/index.html#leave_a_message" style="color:white; padding:10px;">預約諮詢</a></li>
                            <li class="right"><a href="https://kajinonline.com/panel.php" style="color:#67C4CB;">會員登入</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="page">
        <div class="model">
            <div class="login-remind-block">
                <h3 class="login-remind">登入才能留言喔</h3>
                <div>
                    <div class="login-remind-btn login-remind-back">返回</div>
                    <div class="login-remind-btn">
                        <a href="https://kajinonline.com/login.php">登入</a>
                    </div>
                </div>
            </div>
        </div>
        <div style="width:100%; background:white; padding:20px;">
            <div class="container">
                <div class="row">
                            <?php
                                while ($cou = mysqli_fetch_assoc($coun)) {
                                    ?>
                    <div class="col-sm-8">
                        <div class="info-block clearfix">
                            <div class="photo col-sm-6" style="background: transparent url(<?php echo 'https://kajinonline.com/images/counselors/'.$cou['photo']?>) no-repeat center center/cover"></div>
                            <div class="col-sm-6 photo-info">
                                <h3 class="position"><?php echo $cou['title']?></h3>
                                <h1 class="name"><?php echo $cou['name_ch']?></h1>
                                <h3 class="name-en"><?php echo $cou['name_en']?></h3>
                                <div>
                                <a href=<?php echo 'https://kajinonline.com/reserve.php?counselor_id='.$_GET['counselor']?> target="_blank" class="green-btn">預約諮詢</a>
                                <a href="https://kajinonline.com/board.php" target="_blank" class="green-btn">我要留言</a>
                                </div>
                            </div>
                        </div>
                        <div class="info-block2">
                            <div class="title">老師的話</div>
                            <div class="word"><?php echo $cou['words']?></div>
                        </div>
                        <?php
                        if (mysql_num_rows($publication) > 0) {
                            ?>
                        <div class="info-block2">
                            <div class="title">相關著作</div>
                            <ul class="book-ul">
                            <?php
                                while ($pub = mysqli_fetch_assoc($publication)) {
                                    ?>
                                <li><?php echo $pub['description']?></li>
                            <?php

                                }
                            ?>
                            </ul>
                        </div>
                        <?php

                        }
                                    ?>
                        <div class="info-block2" id="qa-big-block" style="overflow-y:scroll;max-height:1000px;background:#FAFAFA;">
                            <div class="title" style="background:white;">相關評論</div>
                            <?php
                            if ($rate_row->num_rows > 0) {
                                while ($rate = $rate_row->fetch_assoc()) {
                                    $em = explode('@', $rate['email']);
                                    $name = implode(array_slice($em, 0, count($em) - 1), '@');
                                    // $len = floor(strlen($name) / 2);

                                    $rate['email'] = substr($name, 0, 4).str_repeat('*', 4).'@'.end($em);
                                    ?>
                            <div class="rating_block">
                                <!-- <div class="userPhoto"><img src="images/fake_pic.jpg" alt="評論者H的頭像"></div> -->
                                <div class="userPhoto"><?php echo strtoupper($rate['email'][0]) ?></div>
                                <div class="commentContent">
                                    <div class="commentTitle"><?php echo $rate['email'] ?></div>
                                    <div class="commentTime">於 <?php echo date('Y/m/d H:i', strtotime($rate['created']));
                                    ?> 評論諮詢師</div>
                                    <div class="ratingSection">
                                        <div class="wrapper">
                                            <div class="r1">老師能理我的問題解</div>
                                            <span class="stars">
                                                <?php
                                                    for ($i = 0; $i < 5; ++$i) {
                                                        if ($i < round($rate['r1'])) {
                                                            echo "<i class='fa fa-star'></i>";
                                                        } else {
                                                            echo "<i class='fa fa-star-o'></i>";
                                                        }
                                                    }
                                    ?>
                                            </span><br/>
                                            <div class="r2">老師對我的情況有幫助</div>
                                            <span class="stars">
                                                <?php
                                                    for ($i = 0; $i < 5; ++$i) {
                                                        if ($i < round($rate['r2'])) {
                                                            echo "<i class='fa fa-star'></i>";
                                                        } else {
                                                            echo "<i class='fa fa-star-o'></i>";
                                                        }
                                                    }
                                    ?>
                                            </span><br/>
                                            <div class="r3">值不值得推薦</div>
                                            <span class="stars">
                                                <?php
                                                    for ($i = 0; $i < 5; ++$i) {
                                                        if ($i < round($rate['r3'])) {
                                                            echo "<i class='fa fa-star'></i>";
                                                        } else {
                                                            echo "<i class='fa fa-star-o'></i>";
                                                        }
                                                    }
                                    ?>
                                            </span><br/>
                                        </div>
                                    </div>
                                    <div class="commentFeedback">
                                        <?php echo $rate['comment'] ?>
                                    </div>
                                    <hr/>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php

                                }
                            } else {
                                echo '<div style="margin: 10px auto;">很抱歉，我們尚未收到此老師足夠的評分數量，目前無法顯示。</div>';
                            }
                                    ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <?php
                            $coun_avg = $avg_result->fetch_assoc();
                                    if (!empty($coun_avg['total_avg'])) {
                                        ?>
                        <div class="info-block2 ratingBox">
                            <div class="title">諮詢師評價

                                <span>(<?php echo $coun_avg['total_avg'] ?>/5)</span> <!--請在這裡加入php來設置老師的評價分數-->
                            </div>
                            <div class='rating r1'>老師能理解我的問題</div>
                            <span>
                            <?php
                                for ($i = 0; $i < 5; ++$i) {
                                    if ($i < round($coun_avg['r1_avg'])) {
                                        echo "<i class='fa fa-star'></i>";
                                    } else {
                                        echo "<i class='fa fa-star-o'></i>";
                                    }
                                }
                                        ?>
                            </span><br> <!--設定在rating.js，星星請裝在span裡面-->
                            <div class='rating r2'>老師對我的情況有幫助</div>
                            <span>
                            <?php
                                for ($i = 0; $i < 5; ++$i) {
                                    if ($i < round($coun_avg['r2_avg'])) {
                                        echo "<i class='fa fa-star'></i>";
                                    } else {
                                        echo "<i class='fa fa-star-o'></i>";
                                    }
                                }
                                        ?></span><br>
                            <div class='rating r3'>值不值得推薦</div>
                            <span>
                            <?php
                                for ($i = 0; $i < 5; ++$i) {
                                    if ($i < round($coun_avg['r3_avg'])) {
                                        echo "<i class='fa fa-star'></i>";
                                    } else {
                                        echo "<i class='fa fa-star-o'></i>";
                                    }
                                }
                                        ?></span><br>
                        </div>
                        <?php

                                    }
                                    ?>
                        <div class="info-block2">
                            <div class="title">專業執照</div>
                            <?php
                                while ($cert = mysqli_fetch_assoc($certificate)) {
                                    ?>
                            <div style="flex-direction:column; display:flex; align-items:center; justify-content:center; margin:60px 0;">
                            <h5 class="certificate"><?php echo $cert['title'] ?></h5>
                            <h5 class="certificate-content"><?php echo $cert['description'] ?></h5></div>
                            <?php

                                }
                                    ?>
                        </div>
                        <div class="info-block2">
                            <div class="title">諮詢專長</div>
                            <ul class="speciality-ul">
                            <?php
                                $speciality = explode('::', $cou['profile_speciality']);
                                    for ($i = 0;$i < count($speciality);++$i) {
                                        ?>
                                <li><?php echo $speciality[$i]?></li>
                            <?php

                                    }
                                    ?>
                            </ul>
                        </div>
                        <div class="info-block2">
                            <div class="title">學歷與經歷</div>
                            <?php
                                while ($exp = mysqli_fetch_assoc($experience)) {
                                    ?>
                            <div style="text-align: left; margin: 20px 40px">
                                <h5 class="green"><?php echo $exp['title']?></h5>
                                <h5 class="grey"><?php echo $exp['subtitle']?></h5>
                            </div>
                            <?php

                                }
                                    while ($edu = mysqli_fetch_assoc($education)) {
                                        ?>
                            <div style="text-align: left; margin: 20px 40px">
                                <h5 class="green"><?php echo $edu['title']?></h5>
                                <h5 class="grey"><?php echo $edu['subtitle']?></h5>
                            </div>
                            <?php

                                    }
                                    ?>
                        </div>
                    </div>
                </div>
                <?php

                                }
                ?>
                <div style="text-align:center;" class="row"><a href="https://kajinonline.com/our_team.php" style="color:#67C4CB; font-size: 22px;">回「心理諮詢師」↩</a></div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <div class="col-sm-4">
                <div class="icon kajin-consult"></div>
                <div class="greyline"></div>
                <h5>support@kajinhealth.com</h5>
                <h5>上海愚園東路28號3號樓</h5>
                <h5>157-2148-4134</h5>
                <h5><a href="https://www.facebook.com/KaJinHealth/messages/" target="_blank" class="green-word">線上客服</a></h5></div>
            <div class="col-sm-4">
                <h4 class="link"><a href="https://kajinonline.com/index.html#services">諮詢項目</a></h4>
                <h4 class="link"><a href="https://www.kajinonline.com/blogs/">部落格</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/index.html#counselor">諮詢團隊</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/index.html#faq">常見問題</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/index.html#prices">收費方式</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/about_us.html">加入我們</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/index.html#leave_a_message">預約諮詢</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/corporation.html">企業專區</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/panel.php">會員登入</a></h4>
                <h4 class="link"><a href="https://kajinonline.com/counselor/login.php">老師登入</a></h4>
                <div class="social-link"><span style="position: relative;top: -10px; margin-right: 5px;">開心社群</span>
                    <a href="https://www.facebook.com/KaJinHealth/" target="_blank" class="social-icon fb"></a>
                    <a href="https://line.me/ti/p/%40sxl4317c" target="_blank" class="social-icon line"></a>
                    <a href="https://www.youtube.com/channel/UCAdYvJ-gScOM_OuFmie6h0g" target="_blank" class="social-icon youtube"></a>
                    <a href="https://twitter.com/kajinhealth" target="_blank" class="social-icon twitter"></a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="link-block">
                    <div class="subtitle">媒體報導</div>
                    <div style="margin-top: 10px" class="icon press-report"></div>
                </div>
                <div class="link-block">
                    <div class="subtitle">合作機構</div>
                    <div style="margin-top: 10px" class="icon clinic"></div>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright">Copyright © 2015 KAJIN, Inc. All rights reserved</div>
</body>
<script src="./js/jquery-2.1.4.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/profile.js"></script>
<!-- <script src="./js/rating.js"></script> -->

</html>
