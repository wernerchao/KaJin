<?php
    include("include_db.php");
    mysql_connect("localhost",$db_user,$db_pass);
    mysql_select_db("onlinesystem");
    mysql_query("set names utf8");
    $education=mysql_query("select e.* from education e, counselor c2  where  c2.active=1 and c2.Id=e.c_id and e.c_Id=" . $_GET["counselor"]);
    $coun=mysql_query("select * from counselor c where c.active=1 and c.Id=" . $_GET["counselor"]);
    $certificate=mysql_query("select c.* from certificate c, counselor c2 where c2.active=1 and c2.Id=c.c_id and c.c_Id=" . $_GET["counselor"]);
    $publication=mysql_query("select p.* from publication p, counselor c2 where c2.active=1 and c2.Id=p.c_id and p.c_Id=" . $_GET["counselor"]);

    //Star Rating Plugin
    include_once 'dbConfig.php';
    //Fetch rating deatails from database
    $query = "SELECT rating_number, FORMAT((total_points / rating_number),1) as average_rating FROM post_rating WHERE post_id = 1 AND status = 1";
    $result = $db->query($query);
    $ratingRow = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width; user-scalable:0; initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta content="" property="og:site_name">
    <meta content="" property="og:title">
    <meta content="" property="og:description">
    <meta content="" property="og:url">
    <meta content="website" property="og:type">
    <meta content="" property="og:image">
    <meta content="image/png" property="og:image:type">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/profile.css">

    <!-- Star Rating Plugin -->
    <link href="star_rating_jquery_ajax_php/rating.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="star_rating_jquery_ajax_php/rating.js"></script>
    <script language="javascript" type="text/javascript">
    $(function() {
        $("#rating_star").codexworld_rating_widget({
            starLength: '5',
            initialValue: '',
            callbackFunctionName: 'processRating',
            imageDirectory: 'images/',
            inputAttr: 'postID'
        });
    });

    function processRating(val, attrVal){
        $.ajax({
            type: 'POST',
            url: 'rating.php',
            data: 'postID='+attrVal+'&ratingPoints='+val,
            dataType: 'json',
            success : function(data) {
                if (data.status == 'ok') {
                    $('#avgrat').text(data.average_rating);
                    $('#totalrat').text(data.rating_number);
                }else{
                    alert('Some problem occured, please try again.');
                }
            }
        });
    }
    </script>
    <style type="text/css">
        .overall-rating{font-size: 14px;margin-top: 5px;color: #8e8d8d;}
    </style>

    <title>Kajin Health 開心心理線上諮詢的心理諮詢師團隊</title>
</head>

<body>
    <div class="navbar-wrapper">
        <div class="container navbar-container">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" class="navbar-toggle collapsed"><span class="sr-only"></span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                        <a href="#" class="navbar-brand icon logo-img"></a><a class="navbar-brand icon-word">KAJINHEALTH 開心線上心理諮詢</a></div>
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
        <div style="width:100%; background:white; padding:20px;">
            <div class="container">
                <div class="row">
                            <?php
                                while ($cou = mysql_fetch_array($coun, MYSQL_ASSOC)){
                            ?>
                    <div class="col-sm-8">
                        <div class="info-block clearfix">
                            <div class="photo col-sm-6" style="background: transparent url(<?php echo "https://kajinonline.com/images/" . $cou["photo"]?>) no-repeat center center"></div>
                            <div style="text-align:center; padding: 15px 0 15px 30px;" class="col-sm-6">
                                <h3 class="position"><?php echo $cou["title"]?></h3>
                                <h1 class="name"><?php echo $cou["name_ch"]?></h1>
                                <h3 class="name-en"><?php echo $cou["name_en"]?></h3>
                                <div>
                                <a href=<?php echo "https://kajinonline.com/reserve.php?counselor_id=" . $_GET["counselor"]?> target="_blank" class="green-btn">預約諮詢</a>
                                <a href="https://kajinonline.com/board.php" target="_blank" class="green-btn">我要留言</a>
                                </div>
                            </div>
                        </div>
                        <div class="info-block2">
                            <div class="title">學歷與經歷</div>
                            <?php
                                while ($edu = mysql_fetch_array($education, MYSQL_ASSOC)){
                            ?>
                            <div style="text-align: left; margin: 20px 40px">
                                <h5 class="green"><?php echo $edu["title"]?></h5>
                                <h5 class="grey"><?php echo $edu["subtitle"]?></h5></div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="info-block2">
                            <div class="title">老師的話</div>
                            <div class="word"><?php echo $cou["words"]?></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="info-block2">
                            <div class="title">專業執照</div>
                            <?php
                                while ($cert = mysql_fetch_array($certificate, MYSQL_ASSOC)){
                            ?>
                            <div style="flex-direction:column; display:flex; align-items:center; justify-content:center; margin:60px 0;">
                            <h5 class="certificate"><?php echo $cert["title"] ?></h5>
                            <h5 class="certificate-content"><?php echo $cert["description"] ?></h5></div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="info-block2">
                            <div class="title">諮詢專長</div>
                            <ul class="speciality-ul">
                            <?php
                                $speciality = explode("::", $cou["profile_speciality"]);
                                for($i=0;$i<count($speciality);$i++){
                            ?>
                                <li><?php echo $speciality[$i]?></li>
                            <?php
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="info-block2">
                            <div class="title">相關著作</div>
                            <ul class="book-ul">
                            <?php
                                while ($pub = mysql_fetch_array($publication, MYSQL_ASSOC)){
                            ?>
                                <li><?php echo $pub["description"]?></li>
                            <?php
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                            <?php
                            }
                            ?>
                <div style="text-align:center;" class="row"><a href="https://kajinonline.com/our_team.php" target="_blank" style="color:#67C4CB; font-size: 22px;">回「心理諮詢師」↩</a></div>
                <input name="rating" value="0" id="rating_star" type="hidden" postID="1" />
                <div id="disqus_thread"></div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <div class="col-sm-4">
                <div class="icon kajin-consult"></div>
                <div class="greyline"></div>
                <h5>support@kajinhealth.com</h5>
                <h5>台北市松山區敦化北路170號3樓</h5>
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

    <!-- Disqus -->
    <script>
        /**
        *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
        *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
        */
        /*
        var disqus_config = function () {
            this.page.url = https://kajinonline.com/profile_Staging.php;  // Replace PAGE_URL with your page's canonical URL variable
            this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };*/
        
        (function() {  // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            
            s.src = '//wwwkajinonlinecom.disqus.com/embed.js';
            
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
    <script id="dsq-count-scr" src="//wwwkajinonlinecom.disqus.com/count.js" async></script>

</body>
<script src="./js/jquery-2.1.4.min.js"></script>
<script src="./js/bootstrap.min.js"></script>


</html>
