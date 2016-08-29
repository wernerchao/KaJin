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
                            <div class="title" style="background:white;">相關問答</div>
                            <div class="qa">
                                <div class="block-ask">
                                    <div class="photo"></div>
                                    <form class="reply-form" method="POST" action='./ajax_api_add_reply.php'>
                                        <input type="text" placeholder="我想問老師……" name="ask">
                                        <input type="hidden" id="q_Id" name="q_Id" value="0">
                                        <input type="hidden" id="c_Id" name="c_Id" value=<?php echo $_GET['counselor']?> >
                                    </form>
                                </div>
                            </div>
                            <?php
                                $qid = 0;
                                    $rep = mysqli_fetch_assoc($reply);
                                    while ($rep) {
                                        $qid = $rep['q_Id'];
                                        ?>
                            <div class="qa">
                                <div class="small-qa">
                                    <div class="photo"></div>
                                    <div class="info">
                                    <h5 class="name"><?php echo $rep['user_name']?></h5>
                                        <p class="content"><?php echo $rep['text']?></p>
                                        <!-- <div class="time">(1小時前)</div> -->
                                        <button class="reply-btn" style="background:transparent;color:#67C4CB;border:none;font-size:14px;display:block;padding:0;">回覆</button>
                                    </div>
                                </div>
                                    <?php
                                    while ($rep = mysqli_fetch_assoc($reply)) {
                                        if ($rep['q_Id'] != $qid) {
                                            break;
                                        }
                                        ?>
                                <div class="small-qa reply">
                                    <?php
                                        if ($rep['u_Id'] != 0) {
                                            ?>
                                        <div class="photo"></div>
                                        <div class="info">
                                            <h5 class="name"><?php echo $rep['user_name'];
                                            ?></h5>
                                            <p class="content"><?php echo $rep['text']?></p>
                                        </div>
                                    <?php

                                        } else {
                                            ?>
                                        <div class="photo" style="background: transparent url(<?php echo 'https://kajinonline.com/images/'.$cou['photo']?>) no-repeat center center; background-size:contain"></div>
                                        <div class="info">
                                            <h5 class="name"><?php echo $rep['coun_name'];
                                            ?></h5>
                                            <p class="content"><?php echo $rep['text']?></p>
                                        </div>
                                    <?php

                                        }
                                        ?>
                                </div>
                                <?php

                                    }
                                        ?>
                                <div class="block-ask reply">
                                    <div class="photo"></div>
                                    <form class="reply-form" method="POST" action='./ajax_api_add_reply.php'>
                                        <input type="text" placeholder="留言……" name="ask">
                                        <input type="hidden" id="q_Id" name="q_Id" value=<?php echo $qid?> >
                                        <input type="hidden" id="c_Id" name="c_Id" value=<?php echo $_GET['counselor'] ?> >
                                    </form>
                                </div>
                            </div>
                            <?php

                                    }
                                    ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="info-block2">
                            <div class="title">諮詢師評價
                                <span>(5/5)</span> <!--請在這裡加入php來設置老師的評價分數-->
                            </div>
                            <div class='rating r1'>老師能理解我的問題</div>
                            <span></span><br> <!--設定在rating.js，星星請裝在span裡面-->
                            <div class='rating r2'>老師對我的情況有幫助</div>
                            <span></span><br>
                            <div class='rating r3'>值不值得推薦</div>
                            <span></span><br>
                        </div>
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
<script src="./js/rating.js"></script>

</html>
