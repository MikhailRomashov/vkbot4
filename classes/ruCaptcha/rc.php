<?php

//распознавание капчи. сайт rucaptcha.com  kazanmic@mail.ru/123456

function recognize($url)
{
	global $proxy,$user_agent,$bot_id,$cookies_st;
	
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
	//file_put_contents("test$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". имя временого ФАЙЛА $filename строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				
	
	do{
		$num++;
		if($num==10)
			{
				//file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". не удалось получить файл капчи url:$url. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				unlink($filename);
				return array(false,$captcha_id);
			}
			
		if($num>1) sleep(30);
		
		$data=@file_get_contents($url, false, $context);
		
	    }while(strlen($data)<1500 && $num<10);
	$num=0;
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
		return array(false,$captcha_id);
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
    	file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". url:$url. CURL returned error: ".curl_error($ch).". ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
        unlink($filename);
	curl_close($ch);
	return array(false,$captcha_id);
    }
    curl_close($ch);
    
    
 //file_put_contents("captcha$bot_id_".date("Ymdhisa").".jpg",$data);
    if (strpos($result, "ERROR")!==false)
	{
	    unlink($filename);
	    
	    $num2++;
	    if($num2==3)
		    {
			    file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". не удалось получить файл капчи. url:$url. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
			    file_put_contents("captcha$bot_id_".date("Ymdhisa").".jpg",$data);
			     file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". server returned error: $result строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
			    return array(false,$captcha_id);
		    }
	    sleep(30);
	    
	    goto s37;	
	    
	}
    else
	{
	    $ex = explode("|", $result);
	    $captcha_id = $ex[1];
	    
	   // file_put_contents("test$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". сохраняем в ФАЙЛ ".$captcha_id.".png строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
	
	    //copy($filename,$bot_id."_".$captcha_id.".png");
	    unlink($filename);
	    
	   // file_put_contents("test$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". успешно . строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
	
	    if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". captcha sent, got captcha ID $captcha_id строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
	    
	    $waittime = 0;
	    
	    if ($is_verbose) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". waiting for $rtimeout seconds строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
	    
	    sleep($rtimeout);
	    
	    while(true)
	    {
		$result2 = file_get_contents("http://$domain/res.php?key=".$apikey.'&action=get&id='.$captcha_id);
		if (strpos($result2, 'ERROR')!==false)
		{
		     if($result2!='ERROR_CAPTCHA_UNSOLVABLE') file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". ответ на запрос распознания:$result2.captcha ID $captcha_id server returned error: $result2 строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
		    file_put_contents("ReCaptchaErrLog.txt",date("d.m.Y H:i:sa").". ответ на запрос распознания: бот $bot_id. сaptcha ID $captcha_id server returned error: $result2 строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
		    
		    return array(false,$captcha_id);
		}
		
		if ($result2=="CAPCHA_NOT_READY")
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
		    $ex = explode('|', $result2);
		    if (trim($ex[0])=='OK')
			    
			    {
				    
				    $ck=trim($ex[1]);
				    
				    if ($is_verbose && $waittime>0 ) file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". капча все таки получена. капча: $ck. строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
				    /*if($bot_id==179 || $bot_id==1113)
				    {
				    
				    file_put_contents("log$bot_id"."Cap.txt",date("d.m.Y H:i:s").". $ck \r\n",FILE_APPEND | LOCK_EX);
				    file_put_contents("captcha$bot_id"."_".date("YmdHis").".jpg",$data);
				    }*/
				    return array($ck,$captcha_id);
			    }
		}
	    }
	}
}

function reportbad($captcha_id)
{
	$url="";
	$apikey='022dfafd50710dc8ad24dd91f6b01883';
	$domain="rucaptcha.com";
	
	$result = file_get_contents("http://$domain/res.php?key=".$apikey.'&action=reportbad&id='.$captcha_id);
	if (strpos($result, 'ERROR')!==false)
            {
            	 file_put_contents("errlog$bot_id"."Cap.txt",date("d.m.Y H:i:sa").". rc.php server returned error: $result строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                return false;
            }
}
?>