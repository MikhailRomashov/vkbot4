<?php

//распознавание капчи. сайт rucaptcha.com  kazanmic@mail.ru/123456

function recognize($url)
{
	global $proxy,$user_agent,$bot_id,$cookies_st;
	
	

// параметр k из строки с сайта https://www.google.com/recaptcha/api2/anchor?k=6Lcd8f4SAAAAAAnga19wqdiiR1Za1eiCdvxdE3yU&co=aHR0cHM6Ly9hd21wcm94eS5jb206NDQz&hl=ru&v=r20171109115411&size=normal&cb=he40suq1clg6
$google_key="6Lcd8f4SAAAAAAnga19wqdiiR1Za1eiCdvxdE3yU";

// строка запроса на распознание
$rucaptcha_url = "http://rucaptcha.com/in.php?key=022dfafd50710dc8ad24dd91f6b01883&method=userrecaptcha&googlekey=$google_key&pageurl=$url&json=1";

// распознать капчу
$captcha_in = json_decode(sFileGetContent($rucaptcha_url),true);

// проверка на принятие капчи к распознанию
if(!$captcha_in[status])
    {
        //ошибка запроса in
        file_put_contents("$sname.txt",date("d.m.Y H:i:s")."ошибка запроса in ".var_export($captcha_in, TRUE)." . строка ".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);
        die;
    }
    




$rucaptcha_res_url ="http://rucaptcha.com/res.php?key=022dfafd50710dc8ad24dd91f6b01883&action=get&json=1&id=".$captcha_in[request];
echo $rucaptcha_res_url;

$zavis=0;
do
    {
        // выжидаем 20 сек
        sleep(20);
        // Получаем результат
        $captcha_res = json_decode(sFileGetContent($rucaptcha_res_url),true);
    }
while($captcha_res[request] =='CAPCHA_NOT_READY' && $zavis<3 );

// проверка на принятие капчи к распознанию
if(!$captcha_res[status])
    {
        //ошибка запроса in
        file_put_contents("$sname.txt",date("d.m.Y H:i:s")."ошибка запроса res ".var_export($captcha_res, TRUE)." . строка ".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);
        die;
    }
    
//$captcha_key = recognize($captcha_url);
//http://rucaptcha.com/res.php?key=1abc234de56fab7c89012d34e56fa7b8&action=get&id=2122988149

file_put_contents("$sname.txt",date("d.m.Y H:i:s")."captcha ".var_export($captcha_res, TRUE)."  \r\n",FILE_APPEND |  LOCK_EX);


	$parsedUrl = parse_url($url);
        $host = $parsedUrl['host'];

 
        $header  = "Host: $host\r\n";
        $header .= "User-Agent: $user_agent\r\nConnection: Close\r\n";
        $header .= $headerExtra;
	
	if($cookies_st)
	{
		$cookies = null == $cookies_st || '' == $cookies_st ? $cookies_st :
			'Cookie: ' . (is_array($cookies_st) ?
			    implode("\r\nCookie: ", $cookies_st) :
			    str_replace('Cookie: ', '', $cookies_st)) . "\r\n";
		$header .= $cookies;
		//file_put_contents("$sname.txt",date("h:i:sa")." $s_response \r\n",FILE_APPEND |  LOCK_EX);
		//var_dump($header);
	}
        
        $http = array('method'  => 'GET',
                        'header'  => $header);
        
        if($proxy)
        {
                $http['proxy'] = 'tcp://'.$proxy;
                $http['request_fulluri'] = false;
		$http['timeout'] =  200;
        }
        


        $context = stream_context_create(array('http' => $http));
	
	if(!$url)
		{
			
			file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". пустой урл на капчу ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
			die;
		}
	   
s37:	   
	$filename=tempnam('','');
	
	
	do{
		$num++;
		if($num==10)
			{
				file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". не удалось получить файл капчи url:$url. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				unlink($filename);
				return false;
			}
			
		if($num>1) sleep(30);
		
		$data=@file_get_contents($url, false, $context);
		
	}while(strlen($data)<1500 && $num<10);
	
	/*
	if($cookies_st)
	{
		var_dump($http_response_header);
	}*/
	
        file_put_contents($filename,$data);
	                         

	
	$apikey='022dfafd50710dc8ad24dd91f6b01883';
	$is_verbose = false; // выводить ошибки
	$domain="rucaptcha.com";
	$rtimeout = 10;
	$mtimeout = 120;
	$is_phrase = 0;
	$is_regsense = 0;
	$is_numeric = 0;
	$min_len = 0;
	$max_len = 0;
	$language = 0;
	
	if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". длина файла капчи: ".strlen($data)." строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
       
    
	if (!file_exists($filename))
	{
	   
		if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". file $filename not found ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
		return false;
	}

    $postdata = array(
        'method'    => 'post', 
        'key'       => $apikey, 
        'file'      => '@'.$filename,
        'phrase'	=> $is_phrase,
        'regsense'	=> $is_regsense,
        'numeric'	=> $is_numeric,
        'min_len'	=> $min_len,
        'max_len'	=> $max_len,
	'language'	=> $language
        
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,             "http://$domain/in.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,     1);
    curl_setopt($ch, CURLOPT_TIMEOUT,             60);
    curl_setopt($ch, CURLOPT_POST,                 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,         $postdata);
    $result = curl_exec($ch);

    if (curl_errno($ch)) 
    {
    	if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". url:$url. CURL returned error: ".curl_error($ch).". ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
        unlink($filename);
	curl_close($ch);
	return false;
    }
    curl_close($ch);
    
    unlink($filename);
 
    if (strpos($result, "ERROR")!==false)
    {
		unlink($filename);
		
		$num2++;
		if($num2==10)
			{
				file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". не удалось получить файл капчи. url:$url. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				file_put_contents("captcha$bot_id_".date("Ymdhisa").".jpg",$data);
				if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". server returned error: $result строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				return false;
			}
		sleep(30);
		
		goto s37;	
	
    }
    else
    {
        $ex = explode("|", $result);
        $captcha_id = $ex[1];
    	//if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". captcha sent, got captcha ID $captcha_id строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
        
	$waittime = 0;
        
	//if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". waiting for $rtimeout seconds строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
        
	sleep($rtimeout);
        
	while(true)
	{
            $result = file_get_contents("http://$domain/res.php?key=".$apikey.'&action=get&id='.$captcha_id);
            if (strpos($result, 'ERROR')!==false)
            {
            	if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". server returned error: $result строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                return false;
            }
	    
            if ($result=="CAPCHA_NOT_READY")
            {
            	if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". captcha is not ready yet. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            	
		$waittime += $rtimeout;
            	
		if ($waittime>$mtimeout) 
            	  {
            		if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". timelimit ($mtimeout) hit. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            		break;
            	  }
        		if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". waiting for $rtimeout seconds. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            	sleep($rtimeout);
            }
            else
            {
            	$ex = explode('|', $result);
            	if (trim($ex[0])=='OK')
			
			{
				
				$ck=trim($ex[1]);
				
				if ($is_verbose && $waittime>0 ) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". капча все таки получена. капча: $ck. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				/*if($bot_id==179 || $bot_id==1113)
				{
				
				file_put_contents("log$bot_id"."Cap.txt",date("d.m.Y H:i:s").". $ck \r\n",FILE_APPEND | LOCK_EX);
				file_put_contents("captcha$bot_id"."_".date("YmdHis").".jpg",$data);
				}*/
				return $ck;
			}
            }
        }
        
        return false;
    }
}
?>