<?php
include 'include_function.php';
include 'include_setting.php';
include("include_db.php");
mysql_connect("localhost",$db_user,$db_pass);
mysql_select_db("onlinesystem");
mysql_query("set names utf8");

$qid=$_POST['q_Id'];
$cid=$_POST['c_Id'];
$text=htmlentities($_POST['ask']);
$data_user = load_user();
if ($data_user == 'ERROR') {
    exit('{"error":1, "error_type":"NOT_LOGIN"}');
}

if($qid==0){//如果是問題的話
    $maxqid=mysql_query("SELECT c.q_Id FROM comment c order by c.q_Id desc LIMIT 1");
    $max = mysql_fetch_array($maxqid, MYSQL_ASSOC)["q_Id"] + 1;
    $querystr="INSERT INTO comment (Id,q_Id,c_Id,u_Id,text,time) VALUES(NULL,". $max .",". $cid .",". $data_user["id"] .",'". $text ."',CURRENT_TIMESTAMP)";
    mysql_query($querystr);
    $arr = array('user_name' => $data_user['name'], 'cid' => $cid, 'qid' => $max);
    echo json_encode($arr);
}
else{//如果是回覆的話
    $querystr="INSERT INTO comment (Id,q_Id,c_Id,u_Id,text,time) VALUES(NULL,". $qid .",". $cid .",". $data_user["id"] .",'". $text ."',CURRENT_TIMESTAMP)";
    mysql_query($querystr);
    $arr = array('user_name' => $data_user['name'], 'cid' => $cid, 'qid' => $qid);
    echo json_encode($arr);
}
?>
