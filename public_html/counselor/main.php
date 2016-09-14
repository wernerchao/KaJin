<?php

include '../include_function.php';
include '../include_setting.php';

?>
首頁<br>
<br>
現在系統時間：<?=get_date()?> <?=date('H:i:s', $system['time_now']);?>
