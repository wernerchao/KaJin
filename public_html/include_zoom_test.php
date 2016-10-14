<?php

define('ZOOMAPI_KEY', 'bfc4d277599f3cc135e57c8295b4d995BMAM');            //���� API_KEY
define('ZOOMAPI_SECRET', '9c7ebda8858fb48bdcc65d65f359584c2P3J');        //���� API_SECRET
define('ZOOMAPI_SERVICE_URL', 'https://api.zoom.us/v1/');
define('ZOOMAPI_USER_ID', 'dd6d7547d4e44faf971da0ebd311bb');            //���� USER_ID

// class ZoomAPI
// {
    /*The API Key, Secret, & URL will be used in every function.*/
    //  $api_key = 'bfc4d277599f3cc135e57c8295b4d995BMAM';
    //  $api_secret = '9c7ebda8858fb48bdcc65d65f359584c2P3J';
    //  $api_url = 'https://api.zoom.us/v1/';
    //  $user_id = 'dd6d7547d4e44faf971da0ebd311bb';

    /*Function to send HTTP POST Requests*/
    /*Used by every function below to make HTTP POST call*/
    function sendRequest($calledFunction, $data)
    {
        /*Creates the endpoint URL*/
        $request_url = ZOOMAPI_SERVICE_URL.$calledFunction;

        /*Adds the Key, Secret, & Datatype to the passed array*/
        $data['api_key'] = ZOOMAPI_KEY;
        $data['api_secret'] = ZOOMAPI_SECRET;
        $data['data_type'] = 'JSON';

        $postFields = http_build_query($data);
        /*Check to see queried fields*/
        /*Used for troubleshooting/debugging*/
        // echo $postFields;

        /*Preparing Query...*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        /*Check for any errors*/
        $errorMessage = curl_exec($ch);
        // echo $errorMessage;
        curl_close($ch);

        /*Will print back the response from the call*/
        /*Used for troubleshooting/debugging		*/
        // echo $request_url;
        // var_dump($data);
        // var_dump($response);
        if (!$response) {
            return false;
        }
        /*Return the data in JSON format*/
        return json_encode($response);
    }

    function create_meeting()
    {
        $createAMeetingArray = array();
        $createAMeetingArray['host_id'] = ZOOMAPI_USER_ID;
        $createAMeetingArray['topic'] = 'Kajin Meeting';
        $createAMeetingArray['type'] = '3';
        $createAMeetingArray['option_jbh'] = true;
        $response = json_decode(sendRequest('meeting/create', $createAMeetingArray));
        // echo $response;

        echo $response;
    }

// function create_meeting()
// {
//     $data['api'] = 'meeting_create';
//     $data['host_id'] = ZOOMAPI_USER_ID;
//     $data['topic'] = 'Kajin Meeting';
//     $data['type'] = '3';
//     $data['start_time'] = '2016-01-01T00:00:00Z';
//     $data['duration'] = '240';
//     $data['timezone'] = '';
//     $data['password'] = '';
//     $data['option_jbh'] = true;
//     $data['option_start_type'] = '';
//     $data['option_host_video'] = '';
//     $data['option_participants_video'] = '';
//     $data['option_audio'] = '';
//
//     ksort($data);
//     $data['check_value'] = MakeMacValue($data);
//     $data['API_Key'] = ZOOMAPI_KEY;
//
//     $postFields = http_build_query($data);
//
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_URL, ZOOMAPI_SERVICE_URL);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//     $response = curl_exec($ch);
//     $errorMsg = curl_error($ch);
//     curl_close($ch);
//
//     if (!$response) {
//         return false;
//     }
//
//     $rData = json_decode($response, true);
//     foreach ($rData as $k => $v) {
//         if (is_array($v)) {
//             //foreach($v as $kk => $vv) echo "rData[\"".$k."\"][\"".$kk."\"] = ".$vv."<br>";
//         } else {
//             //echo "rData[\"".$k."\"] = ".$v."<br>";
//         }
//     }
//     echo $response;
//
//     return $rData['data']['id'];
// }
