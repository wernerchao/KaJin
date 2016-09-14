<?

define('ZOOMAPI_KEY' , 'bfc4d277599f3cc135e57c8295b4d995BMAM');			//ด๚ธี API_KEY
define('ZOOMAPI_SECRET' , '9c7ebda8858fb48bdcc65d65f359584c2P3J');		//ด๚ธี API_SECRET
define('ZOOMAPI_SERVICE_URL' , 'https://zoomnow.net/API/zntw_api.php');
define('ZOOMAPI_USER_ID' , 'dd6d7547d4e44faf971da0ebd311bb');			//ด๚ธี USER_ID

function MakeMacValue($aryData)
{
    $aryAPI=array();
    foreach($aryData as $k => $v)
    {
        if(trim($v)=="")
            continue;
        $aryAPI[$k]=trim($v);
    }
    
	$encode_str = "API_Key=" . ZOOMAPI_KEY . "&" . urldecode(http_build_query($aryAPI)) . "&API_Secret=" . ZOOMAPI_SECRET;
	$encode_str = strtolower($encode_str);
	$CheckMacValue = strtoupper(md5($encode_str));
	
	return $CheckMacValue;
}

function create_meeting()
{
	$data["api"] = "meeting_create";
	$data["host_id"] = ZOOMAPI_USER_ID;
	$data["topic"] = "Kajin Meeting";
	$data["type"] = "3";
	$data["start_time"] = "2016-01-01T00:00:00Z";
	$data["duration"] = "240";
	$data["timezone"] = "";
	$data["password"] = "";
	$data["option_jbh"] = true;
	$data["option_start_type"] = "";
	$data["option_host_video"] = "";
	$data["option_participants_video"] = "";
	$data["option_audio"] = "";

	ksort($data);
	$data["check_value"] = MakeMacValue($data);
	$data["API_Key"] = ZOOMAPI_KEY;

	$postFields = http_build_query($data);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_URL, ZOOMAPI_SERVICE_URL);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	$errorMsg = curl_error($ch);
	curl_close($ch);

	if (!$response) 
	{
		return FALSE;
	}

	$rData=json_decode($response,true);
	foreach($rData as $k => $v)
	{
		if(is_array($v))
		{
			//foreach($v as $kk => $vv) echo "rData[\"".$k."\"][\"".$kk."\"] = ".$vv."<br>";
		}
		else
		{
			//echo "rData[\"".$k."\"] = ".$v."<br>";
		}
	}

	return $rData["data"]["id"];
}

?>