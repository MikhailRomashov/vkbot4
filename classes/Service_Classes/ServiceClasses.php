<?php


class ServiceClasses
{

    function reboot($proxy_problem,$line='')
    {
        global $bot_id,$next_time,$db,$token,$ch_response,$proxy;

        // file_put_contents("reboot$bot_id.txt",date("d.m.Y H:i:s")." $proxy_problem ������ ".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);


        //$proxy_problem==0 ���������� ��� ����� ������
        if($proxy_problem==0)
        {
            // �� ����������� ������� � ��������� � ������ �� ������ =1 ��� �������� ����������
            sql_query("sql_robots_update7_2",__LINE__);
        }

        if($proxy_problem==1)
        {
            //if($line) file_put_contents("RebotV2_$bot_id.txt",date("d.m.Y H:i:s")."������ ".$line."\r\n �������� ������ \r\n",FILE_APPEND | LOCK_EX);


            // ����������� ������� � ��������� � ������
            sql_query("sql_robots_update7",__LINE__);
        }

        if($proxy_problem>=100)
        {
            file_put_contents("reboot$bot_id.txt",date("d.m.Y H:i:s")."����� ����� ������. ������ $line-".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);
            // ������ ������� ������� ������
            sql_query("sql_robots_update7_1",__LINE__);
        }


        // ���������� ������� ������������� ������������� � �������� ����������
        sql_query("sql_robots_update20",__LINE__);

        // ������ ��������� ����� ����. �������
        $next_time=time();

        // �������� ��� ��� ������ ��������
        $res=sql_query("sql_robots_update2",0);

        sql_query("sql_robots_update9",__LINE__);

        // ����� �������� �����
        mysql_close($db);
        die;
    }


    function rnd($veroiat)
    {
        $rndMax=1000000;

        $rndDiapazon=$rndMax*($veroiat/100);

        // ���������� ������� � ������ ������� ����������� �� ������ ���� /��� ������� ����������� (����� ������� �������� � ���� �����
        $rndCurMin=rand(0,$rndMax-$rndDiapazon);

        // ���������� ����� ������� ��� ��������� � ��� �������� ������� � �������
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

// mixed object_from_file - ������� �������������� ������ ������� �� �����

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
        // file_put_contents("errlog$bot_id.txt",date("d.m.Y H:i:s").".  ������ ������ ��� �����. �������� �� $url_timeout ���. ������ $line\r\n",FILE_APPEND | LOCK_EX);


        // �������� ��� ��� �������� ��������� timestamp+ $url_timeout
        sql_query("sql_robots_update3_1",$line."-".__LINE__);

        sleep($url_timeout);
        $url_timeout =$url_timeout+60;
        if($url_timeout>$max_timeout)
        {
            reboot(1,__LINE__); // ���������� ������� ����������� �������� ���������� ������
        }
        return $url_timeout;
    }


///////////////////
// ��������� ����
//////////////////
   public function stop_bot($line)
    {

        sql_query("sql_robots_update1_1",__LINE__);

        // ������� ����
        //   unlink("vkbot_v2_cookies/cookies$bot_id.txt");
    }

}