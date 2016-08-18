<?php
    include 'include_function.php';
    include 'include_setting.php';
    $data = $mysql->query('select * from counselor c where c.active=1');
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <meta name="description" content="Kajin Health 開心心理線上諮詢的心理諮詢師團隊"/>
    <meta name="keywords" content="心理諮詢師團隊"/>
    <meta content="" property="og:site_name">
    <meta content="" property="og:title">
    <meta content="" property="og:description">
    <meta content="" property="og:url">
    <meta content="website" property="og:type">
    <meta content="" property="og:image">
    <meta content="image/png" property="og:image:type">
    <link rel="shortcut icon" href="images/favicon.ico?crc=426821467">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/our_team.css">
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
        <div class="green-title">
            <div class="container">
                <h1>KaJin 心理諮詢師</h1>
                <h3>KaJin Health的心理諮詢師擁有豐富的臨床經驗，也均獲得政府認可的心理師執照</h3></div>
        </div>
        <div class="teacher-big-block" style="width:100%; background:white; padding:20px;">
            <div class="container">
                <?php
                    while ($info = mysqli_fetch_assoc($data)) {
                        ?>
                <a class="profile-link" href= <?php echo 'https://kajinonline.com/profile.php?counselor='.$info['id']?> >
                <div class="col-sm-4">
                    <div class="teacher-block">
                    <div class="teacher-photo" style="background: transparent url(<?php echo 'https://kajinonline.com/images/counselors/'.$info['photo']?>) no-repeat center center/cover; "></div>
                        <div class="teacher-info">
                        <div class="teacher-name"><?php echo $info['name_ch']?></div>
                            <div class="teacher-position"><?php echo $info['title']?></div>
                            <div class="teacher-tag-block">
                                <?php
                                if (!empty($info['tag'])) {
                                    $tags = explode(',', $info['tag']);
                                    for ($i = 0;$i < count($tags);++$i) {
                                        ?>
                                    <div class="teacher-tag"><?php echo $tags[$i]?></div>
                                <?php

                                    }
                                }
                        ?>
                            </div>
                        </div>
                    </div>
                </div>
                </a>
                <?php

                    }
                ?>
                <a class="profile-link" href="https://kajinonline.com/about_us.html">
                <div class="col-sm-4 join-us">
                    <div class="teacher-block">
                        <h5 class="join" style="padding-top:130px">加入我們</h5>
                        <h5 class="join">Join Us!</h5>
                    </div>
                </div>
                </a>
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
    <!-- Google Tag Manager -->
    <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WFLZTQ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WFLZTQ');</script>
    <!-- End Google Tag Manager -->
</body>
<script src="./js/jquery-2.1.4.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

</html>
