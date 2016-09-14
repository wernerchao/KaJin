<?php

function insert_user_answer($user_id, $answer_POST = array())
{
    global $mysql;

    // ARRAY to SET string
    $answer_POST['answer1'] = implode(',', $answer_POST['answer1']);
    $answer_POST['answer2'] = implode(',', $answer_POST['answer2']);

    $sql = "INSERT INTO `user_answer`(user_id, answer0, answer1, answer2, answer3, record_time) VALUES($user_id, '$answer_POST[answer0]', '$answer_POST[answer1]', '$answer_POST[answer2]', '$answer_POST[answer3]', NOW())";
    if ($mysql->query($sql)) {
        return true;
    } else {
        return false;
    }
}

function find_match_counselor($counselor_list = array(), $user_answer = array())
{
    global $mysql;
    // 搜尋傳進來的諮商師清單
    foreach ($counselor_list as $c_id) {
        // echo "ID: ".$c_id.PHP_EOL;
        $sql = "SELECT id, topics FROM `counselor` WHERE id = '$c_id'";
		// 有選擇性別
		// if ($user_answer['answer3'] == 0 || $user_answer['answer3'] == 1) {
		// 	$sql .= " AND gender = '$user_answer[answer3]'";
		// 	echo $sql;
		// }
        $c_result = $mysql->query($sql);
        while ($row = mysqli_fetch_assoc($c_result)) {
            // 擅長主題資料轉成 array 比較，取與user答案的交集數量
            $c_topics = explode(',', $row['topics']);
            $match_topic_num = count(array_intersect($user_answer['answer1'], $c_topics));
            // 至少有一個符合的主題才放進有 match 的結果
            if ($match_topic_num > 0) {
                $match_counselor[$c_id] = $match_topic_num;
                // echo $c_id.'/num: '.$match_topic_num.PHP_EOL;
            } else {
                // 避免 merge 時有同樣 index 被蓋掉，還是把 id 放在 index 裡
                $not_match_counselor[$c_id] = 0;
                // echo 'Not match: '.$c_id.PHP_EOL;
            }
        }
    }
    // 根據符合數量做反序的 sort
    if (count($match_counselor) > 0) {
        arsort($match_counselor);
        // 取回有 match 的 id
        $match_counselor = array_keys($match_counselor);
        // print_r($match_counselor);
    }

    if (count($not_match_counselor) > 0) {
		// 沒有 match 的id
        $not_match_counselor = array_keys($not_match_counselor);
        shuffle($not_match_counselor);
        // print_r($not_match_counselor);
    }

    $final_match_result = array_merge((array)$match_counselor, (array)$not_match_counselor);

    return $final_match_result;
}
