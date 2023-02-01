<?php


class CurlClass
{
    protected $cookie;
    public static $userAgent = 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.0; en-GB; rv:1.7.6) Gecko/20050321 Firefox/1.0.2\r\nConnection: Close\r\n';

    public function __construct($bot_id = null, $user_agent, $proxy = null, $proxylogin = null)
    {
        $this->proxy	=	$proxy;
        $this->proxylogin = $proxylogin;
        $this->cookie = '';
        $this->cookies_arr = array();
        $this->user_agent = ($user_agent ? $user_agent: self::$userAgent);
        $this->referer ="";
        $this->bot_id	=	$bot_id;
        $this->header_size=0;
    }



    public function HandleHeaderLine( $curl, $header_line )
    {
        $this->header_size+=strlen($header_line);
        return strlen($header_line);
    }

    public function SendRequest($data = array())
    {
        $url		=	$data['url'];
        $header     = 	$data['header'];
        $post_data 	= 	$data['postdata'];
        $dump		=	$data['dump'];
        $reloc  	=	$data['reloc'];

        $this->header_size=0;
        $start_coockies=$this->cookie;

        $ch = curl_init();
        if(is_array($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_URL, $url);


        $coocklink="C:/WebServers/home/test1.ru/www/vkbot_v4_cookies/";
        curl_setopt($ch, CURLOPT_COOKIEJAR, $coocklink.'cookies'.$this->bot_id.'.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, $coocklink.'cookies'.$this->bot_id.'.txt');


        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_REFERER, $this->referer);

        if($this->proxy)
        {

            curl_setopt($ch, CURLOPT_PROXY , $this->proxy);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
            if($this->proxylogin)
            {
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
        }


        if($reloc)
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain', 'Content-length: 100'));
        //curl_setopt($ch, CURLOPT_PROXYHEADER, array("Cache-Control: no-store"));

        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);


        //curl_setopt ($ch, CURLOPT_FAILONERROR, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE , false);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this,"HandleHeaderLine"));


        $best_before=time()+120;
        do
        {
            $this->header_size=0;
            $answer =  curl_exec($ch);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
        }
        while(!$answer && time()<$best_before);




        if($answer)
        {
            if($this->proxylogin)
            {
                file_put_contents($_SERVER['SCRIPT_FILENAME']."-ilog".$this->bot_id.".txt",date("d.m.Y H:i:s")." ".var_export($answer, TRUE)."  строка ".__LINE__." \r\n",FILE_APPEND | LOCK_EX);
            }

            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $answer, $matches);

            foreach($matches[1] as $item)
            {
                parse_str($item, $cookie);
                $this->cookies_arr = array_merge($this->cookies_arr,$cookie );
            }

            $this->cookie=http_build_query($this->cookies_arr, '', ';');

            $header_size=$this->header_size;
            $http_response_header = substr($answer, 0, $header_size);

            $response = substr($answer, $header_size);

            //последняя посещенная или переадресованная страница используется как referer
            $this->referer = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

            curl_close($ch);

            if($dump)
            {
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.". строка запроса: $url \r\n\r\n",FILE_APPEND | LOCK_EX);
                if($this->referer != $url) file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.". переадресовано: ".$this->referer." \r\n\r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($http_response_header, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($post_data, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($start_coockies, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__." размер заголовка:$header_size-".$this->header_size." \r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($answer, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s")." строка ".__LINE__.var_export($this->cookies_arr, TRUE)."\r\n",FILE_APPEND | LOCK_EX);
                file_put_contents("logCurl.txt",date("d.m.Y H:i:s").". ======================================================================================================================================= \r\n\r\n",FILE_APPEND | LOCK_EX);

            }



            return array('status' => true, 'lasturl' => $this->referer, 'header' => $http_response_header, 'html' =>$response);
        }
        else
        {
            return array('status' => false ,'code' => 1, 'msg' => "connect_error");
        }
    }



}