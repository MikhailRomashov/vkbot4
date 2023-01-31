<?php


class ServiceClasses
{

    function reboot($proxy_problem,$line='')
    {
        global $bot_id,$next_time,$db,$token,$ch_response,$proxy;

        // file_put_contents("reboot$bot_id.txt",date("d.m.Y H:i:s")." $proxy_problem строка ".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);


        //$proxy_problem==0 перезапуск без смены прокси
        if($proxy_problem==0)
        {
            // не увеличиваем признак о проблемах с прокси но ставим =1 для быстрого перезапуск
            sql_query("sql_robots_update7_2",__LINE__);
        }

        if($proxy_problem==1)
        {
            //if($line) file_put_contents("RebotV2_$bot_id.txt",date("d.m.Y H:i:s")."строка ".$line."\r\n проблема прокси \r\n",FILE_APPEND | LOCK_EX);


            // увеличиваем признак о проблемах с прокси
            sql_query("sql_robots_update7",__LINE__);
        }

        if($proxy_problem>=100)
        {
            file_put_contents("reboot$bot_id.txt",date("d.m.Y H:i:s")."сразу смена прокси. строка $line-".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);
            // ставим признак мертвой прокси
            sql_query("sql_robots_update7_1",__LINE__);
        }


        // сбрасываем признак необходимости синхронизации с сервером интерфейса
        sql_query("sql_robots_update20",__LINE__);

        // ставим ближайшее время след. запуска
        $next_time=time();

        // ответить что бот кончил работать
        $res=sql_query("sql_robots_update2",0);

        sql_query("sql_robots_update9",__LINE__);

        // конец рабочего цикла
        mysql_close($db);
        die;
    }


    function rnd($veroiat)
    {
        $rndMax=1000000;

        $rndDiapazon=$rndMax*($veroiat/100);

        // генерируем верхнюю и нижнюю границу вероятности на данный цикл /для большей случайности (такой вариант работает в разы лучше
        $rndCurMin=rand(0,$rndMax-$rndDiapazon);

        // генениоуем число которое при попадании в наш диапазон приведе к репосту
        $r= rand(0,$rndMax);

        if($rndCurMin< $r && $r < ($rndCurMin+$rndDiapazon)) return true;

        return false;
    }

    function object2file($value, $filename)
    {
        $str_value = serialize($value);

        $f = fopen($filename, 'w');
        fwrite($f, $str_value);
        fclose($f);
    }

// mixed object_from_file - функция восстановления данных объекта из файла

    function object_from_file($filename)
    {
        if(file_exists($filename))
        {
            $file = file_get_contents($filename);
            $value = unserialize($file);
        }

        return $value;
    }

    function start($url)
    {
        if( $curl = curl_init() )
        {
            curl_setopt($curl,CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_exec($curl);
            curl_close($curl);
        }
    }


    function waiting($line)
    {
        global $bot_id,$url_timeout,$next_time;
        $max_timeout=240;
        // file_put_contents("errlog$bot_id.txt",date("d.m.Y H:i:s").".  ошибка прокси или связи. задержка на $url_timeout сек. строка $line\r\n",FILE_APPEND | LOCK_EX);


        // ответить что бот работает поставивь timestamp+ $url_timeout
        sql_query("sql_robots_update3_1",$line."-".__LINE__);

        sleep($url_timeout);
        $url_timeout =$url_timeout+60;
        if($url_timeout>$max_timeout)
        {
            reboot(1,__LINE__); // перезапуск скрипта сувеличение признака проблемной прокси
        }
        return $url_timeout;
    }


///////////////////
// остановка бота
//////////////////
   public function stop_bot($line)
    {

        sql_query("sql_robots_update1_1",__LINE__);

        // удаляем куки
        //   unlink("vkbot_v2_cookies/cookies$bot_id.txt");
    }

}