<!DOCTYPE html>
<html lang="zh-tw">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="KaJin Health 遠端線上心理諮詢服務 挑選諮詢師">
        <meta name="generator" content="2015.0.2.310">
        <link rel="shortcut icon" href="http://kajinonline.com/images/favicon.ico?452768698">
        <title>預約成功</title>
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="./css/site_global.css">
        <link rel="stylesheet" type="text/css" href="./css/master_main1.css">
        <link rel="stylesheet" type="text/css" href="./css/main.css">
    </head>

    <body>
        <div id="navbar">
            <div class="container">
                <a href="http://kajinonline.com/index.html" class="logo"></a><a href="http://kajinonline.com/index.html" class="word">KAJIN HEALTH 線上心理諮詢</a><a href="https://kajinonline.com/panel.php" class="word right">會員中心</a></div>
        </div>
        <style>
            #navbar {
                position: absolute;
                z-index: 1001;
                top: 0;
                left: 0;
                width: 100%;
                padding: 15px 0;
                border: #e5e5e5 none 1px;
                border-bottom-style: solid;
                background: $white;
            }

            #navbar .container {
                width: 960px;
                margin-left: auto;
                margin-right: auto;
            }

            #navbar .logo {
                float: left;
                width: 45px;
                height: 45px;
                margin-right: 20px;
                opacity: 0.85;
                background: transparent url("../images/kajin.png") no-repeat center center;
            }

            #navbar .word {
                position: relative;
                top: 5px;
                float: left;
                background-color: transparent;
                opacity: 0.9;
                color: #66C5CC;
                line-height: 36px;
                text-align: left;
                letter-spacing: 1px;
                text-transform: uppercase;
                font-size: 18px;
                font-family: Helvetica, Helvetica Neue, Arial, sans-serif;
                font-weight: bold;
            }

            #navbar .word.right {
                float: right;
            }
            .grey-container1 .content .col1 .photo {
              background: transparent <?php echo $_POST['counselor_photo']; ?> no-repeat center center;
               }
        </style>
        <div id="page">
            <div id="step4" class="step-container show active">
                <div id="colelem1" class="colelem">
                    <div class="container"><span><div class="words">Step1.挑選諮詢師</div><div class="words dot"></div><div class="words">Step2.挑選時間</div><div class="words dot"></div><div class="words active">Step3.付款資訊</div></span></div>
                </div>
                <div id="colelem2" class="colelem">
                    <div class="container">
                        <div class="book-title green">
                            <div class="icon"></div>
                            <div class="word">感謝你的預約</div>
                        </div>
                        <div class="grey-container green">親愛的用戶您好，感謝您的預約。如果要查看預約的資訊，請登入「<span class="green-word"><a href="http://kajinonline.com/login.php">會員登入</a></span>」或您的個人信箱。</div>
                        <div class="book-title green">
                            <div class="icon"></div>
                            <div class="word">預約項目</div>
                        </div>
                        <div class="grey-container1 step4">
                            <div class="title">
                                <div class="col col1">預約諮詢 </div>
                                <div class="col col2">協助傾向</div>
                                <div class="col col3">困擾狀況</div>
                                <div class="col col4">諮詢堂數</div>
                            </div>
                            <div class="content">
                                <div class="col col1">
                                    <div class="photo"></div>
                                    <div class="name-block">
                                        <div class="name"><?php echo $_POST['counselor_name']; ?></div>
                                        <div class="profession">心理諮詢師</div>
                                    </div>
                                </div>
                                <div id="need-help" class="col col2"><?php echo $_POST['need_help']; ?></div>
                                <div id="sad-situation" class="col col3"><?php echo $_POST['sad_situation']; ?></div>
                                <div class="col col4">
                                    <div>1堂課</div>
                                    <div>(50分鐘/堂)</div>
                                </div>
                            </div>
                        </div>
                        <div class="book-title green">
                            <div class="icon"></div>
                            <div class="word">預約時間</div>
                        </div>
                        <div class="grey-container3">
                            <div class="confirm-time">預約諮詢時間</div>
                            <div class="select-date"><?php echo $_POST['select_date']; ?></div>
                            <div class="time-zone">( 時區: UTC +8 )</div>
                        </div>
                        <div class="book-title green">
                            <div class="icon"></div>
                            <div class="word">注意事項</div>
                        </div>
                        <div class="grey-container5">
                            <div class="suggest-words">本次會談已確認，如果想要修改時間內容，請在預定會談12小時前更改。</div>
                            <div class="suggest-words">會談當日30分鐘前，系統會以E-mail通知您上線。</div>
                            <div class="suggest-words">您可以隨時登入「會員登入」，查詢目談會談進度，以及管理個人資料。</div>
                            <div class="suggest-words">如果諮詢師無法依約會談，我們將主動通知您，並協助您下次預約或取消該次會談。</div>
                            <div class="suggest-words">如果您有任何問題，請您聯絡客服<a href="mailto:support@kajinhealth.com" target="_blank">support@kajinhealth.com</a></div>
                        </div>
                        <div class="step-div">
                            <button class="prev-step"><a href="http://kajinonline.com/">回首頁</a></button>
                            <button class="next-step"><a href="http://kajinonline.com/login.php">會員登入</a></button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="footer-colelem">
                <div id="footer-background">
                    <p>Copyright © 2015 KAJIN, Inc. All rights reserved.</p>
                </div>
            </div>
            <style>
                #footer-colelem {
                    position: absolute;
                    height: 100px;
                    bottom: 0;
                    width: 100%;
                    min-height: initial;
                }

                #footer-background {
                    background: #404040;
                    height: 100px;
                }

                #footer-background p {
                    position: relative;
                    top: 40px;
                    width: 100%;
                    text-align: center;
                    color: #999999;
                    font-size: 15px;
                    font-size: 0.9375rem;
                }
            </style>
        </div>
        <script src="./js/jquery-1.11.0.min.js"></script>
        <script src="./js/jquery-migrate-1.2.1.min.js"></script>
        <script src="./js/jquery-ui.min.js"></script>
    </body>

</html>
