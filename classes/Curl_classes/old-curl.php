<?php

class CurlClass
{
		protected $cookie;
		public function __construct($proxy = null,$user_agent,$bot_id = null, $proxylogin = null)
			{
					$this->proxy	=	$proxy;
					$this->proxylogin = $proxylogin;
					$this->cookie = '';
					$this->cookies_arr = array();
					$this->user_agent = $user_agent;
					$this->referer ="";
					$this->bot_id	=	$bot_id;
					$this->header_size=0;
			}

			
			
		function HandleHeaderLine( $curl, $header_line )
			{
				//file_put_contents("headCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.". $header_line \r\n\r\n",FILE_APPEND | LOCK_EX);
				$this->header_size+=strlen($header_line);
				return strlen($header_line);
			}
			
    function SendRequest($data = array())
			{
				$url				=	$data['url'];
				$header 		= $data['header'];
				$post_data 	= $data['postdata'];
				$dump				=	$data['dump'];
				
				$this->header_size=0;
				$start_coockies=$this->cookie;
			//echo $url."<br><br>";
			//echo $this->user_agent."<br><br>";
			
				$ch = curl_init();
		    if(is_array($header))
					{
						//print_r($header);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					}
					
				
				curl_setopt($ch, CURLOPT_URL, $url);
				
			//echo $this->cookie."<br><br>";
		//	curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/instagbot/cookies'.$this->bot_id.'.txt');
 	   //   curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/instagbot/cookies'.$this->bot_id.'.txt');
			
				$coocklink="C:/WebServers/home/test1.ru/www/instagbot/";
				curl_setopt($ch, CURLOPT_COOKIEJAR, $coocklink.'cookies'.$this->bot_id.'.txt');
 	      curl_setopt($ch, CURLOPT_COOKIEFILE, $coocklink.'cookies'.$this->bot_id.'.txt');
			
				
	//		if($this->cookie) curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
				
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
				curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        
        if($this->proxy)
					{
						
						curl_setopt($ch, CURLOPT_PROXY , $this->proxy);
						curl_setopt($ch, CURLOPT_TIMEOUT, 40);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
						//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
						if($this->proxylogin)
							{
								//file_put_contents($_SERVER['SCRIPT_FILENAME']."-ilog".$this->bot_id.".txt",date("d.m.Y H:i:s")." proxy ". $this->proxy." user ".$this->proxylogin."  строка ".__LINE__." \r\n",FILE_APPEND | LOCK_EX);
								curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxylogin); //формат $this->proxylogin = "$username:$password"
								curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
							}
					}
				else
					{
						curl_setopt($ch, CURLOPT_TIMEOUT, 20);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
					}
					
				if($post_data)
					{
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

					//	echo "post:".$post_data."<bR><bR>";
					}
					
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain', 'Content-length: 100'));
        //curl_setopt($ch, CURLOPT_PROXYHEADER, array("Cache-Control: no-store"));
         
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
  
 
        //curl_setopt ($ch, CURLOPT_FAILONERROR, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($ch, CURLOPT_VERBOSE , false);
				curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this,"HandleHeaderLine"));
         
		    
        
        $answer =  curl_exec($ch);
				if($this->proxylogin)
							{
								//file_put_contents($_SERVER['SCRIPT_FILENAME']."-ilog".$this->bot_id.".txt",date("d.m.Y H:i:s")." ".var_export($answer, TRUE)."  строка ".__LINE__." \r\n",FILE_APPEND | LOCK_EX);
								}

		    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $answer, $matches);

		    //$this->cookie="";
		    
        
				foreach($matches[1] as $item)
					{
						parse_str($item, $cookie);
						$this->cookies_arr = array_merge($this->cookies_arr,$cookie );
					}
				
				$this->cookie=http_build_query($this->cookies_arr, '', ';');
				//var_dump($this->cookie);
				//$this->cookie=substr($this->cookie, 0, -1);
    
        //$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_size=$this->header_size;
        $http_response_header = substr($answer, 0, $header_size);
				
				//$http_response_header = explode("\r\n", substr($answer, 0, $header_size)); // преобраз в массив
        $response = substr($answer, $header_size);        
//	var_dump($response);
        curl_close($ch);
				
       // $this->referer=$url;
		
		 	if($dump)
				{
						file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.". $url \r\n\r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($header, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($post_data, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($start_coockies, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__." размер заголовка:$header_size-".$this->header_size." \r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($answer, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($this->cookies_arr, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
					file_put_contents("logCurl.txt",date("d.m.Y H:i:s").". ======================================================================================================================================= \r\n\r\n",FILE_APPEND | LOCK_EX);
					}
		
			
				return $response;
			}
			
		
			
}
?>