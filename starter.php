<?php
//echo "1";die;
//file_put_contents("starter.txt",date("d.m.Y H:i:s")." строка ".__LINE__."  \r\n".var_export($_SERVER,true)." \r\n",FILE_APPEND | LOCK_EX);
require_once $_SERVER['DOCUMENT_ROOT']."/classes/Http_proxy.php";

// обращаемся к mysql
// цепляем файл настроек
include $_SERVER['DOCUMENT_ROOT']."/config/settings.php";

// коннектимся к базе 
include $_SERVER['DOCUMENT_ROOT']."/config/mysql_connect.php";
// цепляем склад запросов
include $_SERVER['DOCUMENT_ROOT']."/config/mysql_query_vkbot.php";

$http= new HttpTools();

// код доступа для синхронизации с сервером интерфейса
$code="fred123jndfkb";
$baselink="http://vkbot24.ru/php/system/robotsync.php?code=$code";

// проваерка запущен ли уже скрипт
$sname="starter";
if(!mysql_fetch_array(sql_query("sql_script_in_use_select1",__LINE__))){echo " уже запущен"; die;}

// отмечаем что стартанул
$sec1=30;
$sec=30; 
sql_query("sql_script_in_use_update2",__LINE__);

ini_set('default_socket_timeout', 10);

 
   
 // теперь ждеи рахрешения на запуск с проверкой внеочередного запуска 
 while($sec1>0 || $sec2>0)
    {
                ///////////////////////////////////////////////////////////////////////////////////////////  
        // шлем запрос на получения со стороны сервера клиентов новых ботов и обновления данных
        ///////////////////////////////////////////////////////////////////////////////////////////
        updates_from_clients_server();
        
        // проврка на принудительную остановку
        stopme();
        
        // перезапуск взглючнувших ботов
        restart_bot();
        
        // проверяем внеочередной запуск после ребута
        $res=sql_query("sql_robots_select3_1",__LINE__);
        
        // находим временя ближайщего старта бота в секундах 
        if(!$start=mysql_fetch_array($res))
           {
                // проверяем внеочередной запуск для поиска прокcи
                $res=sql_query("sql_robots_select3_1_2",__LINE__);
                $start=mysql_fetch_array($res);
           }
       
        $sec=$start[next_time];
        
        $restart=0;
        if($sec<0)
            {
                $restart=1;
                goto st;
            }
        
        // получаем ближайщеее бота ожидающего старта 
        $res=sql_query("sql_robots_select3",__LINE__);
        
        // находим временя ближайщего старта бота в секундах 
        $start=mysql_fetch_array($res);
        $sec1=$start[next_time];
        
        if($sec1>20)
            {
                $sec1=20;
                
            }
        
        
        if($sec1>0)
            {
                $sec=$sec1;
                sql_query("sql_script_in_use_update2",__LINE__);
                sleep($sec); 
            }
            
        // получаем ближайщеее допустимое время старта скрипта vkbot 
        $res=sql_query("sql_script_in_use_select4",__LINE__);
        
        // находим временя ближайщего старта бота в секундах 
        $startST=mysql_fetch_array($res);
        $sec2=$startST[next_time];
        
       
        if($sec2>20)
            {
                $sec2=20;
                
            }
        
        if($sec2>0)
            {
                $sec=$sec2;
                sql_query("sql_script_in_use_update2",__LINE__);
                sleep($sec); 
            }
        
        

    }

  



st:


        
// запускаем бота
$bot_id=$start[id];
//if($start[status]>10)file_put_contents("starter$bot_id.txt",date("d.m.Y H:i:s")."\r\n". var_export($start,true).".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
// сразу ставим следующее врем старта чтобы случайно не запустился дважды
if($start[status]<10)
   {
      ///////////////////////////////////////////////////////////////
      // отvетить что бот можно запускать только через 12 часов 5 мин 
      $next_time=time()+13*60*60+5;
      
      
      // обновляем время след старта
      $res=sql_query("sql_robots_update9",__LINE__);
   }
   
$handle=popen('start /b /i /d Y:\home\test1.ru\www\ Y:\usr\local\php5\php Y:\home\test1.ru\www\bot\vkbot3.php '.$bot_id.' '.$restart.' > nul ','r');


pclose($handle);
        
       // $handle = popen('start /b /d Y:\home\test1.ru\www\bot\ Y:\usr\local\php5\php Y:\home\test1.ru\www\bot\t2.php  > nul ','r');
       // pclose($handle);
           
        
       
           
sleep(3);
       
 


// сбрасываем задержку
        $sec=0;
        sql_query("sql_script_in_use_update2",__LINE__);
        
        
// перезапускаем стартер
$handle=popen('start /b /i /d Y:\home\test1.ru\www\ Y:\usr\local\php5\php Y:\home\test1.ru\www\bot\starter.php  > nul ','r');
pclose($handle);

ini_set('default_socket_timeout', 60);
// убиваем эту копию стартера
mysql_close($db);
die;


///////////////////////////////////////////////
// подпрограммыэ
///////////////////////////////////////////////

function updates_from_clients_server()
{
    global $db,$baselink,$bot_id,$more,$more2,$http;
    
    $url="$baselink&action=select";

    $response = @file_get_contents($url, false, $context);
    
    $bot = json_decode($response,true);
  // file_put_contents("starter.txt",date("d.m.Y H:i:s").$url."\r\n". var_export($bot,true).".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
    // перебираем ботов на добадние
    if(count($bot['bot_data']['insert'])>0)
        {
            $more="";
            for($i=0; $i<count($bot['bot_data']['insert']);$i++)
                {
              
                    // формируем sql запрос на добаление ботов
                    $more.="(
                        '".$bot['bot_data']['insert'][$i]['id']."',
                        '".$bot['bot_data']['insert'][$i]['botnet_num']."',
                        '".$bot['bot_data']['insert'][$i]['login']."',
                        '".$bot['bot_data']['insert'][$i]['pass']."',
                        '".iconv("utf-8","windows-1251",$bot['bot_data']['insert'][$i]['familia'])."',
                        '".$bot['bot_data']['insert'][$i]['age']."',
                        '".$bot['bot_data']['insert'][$i]['sex']."',
                        '".$bot['bot_data']['insert'][$i]['proxy']."',
                        '".$bot['bot_data']['insert'][$i]['proxylogin']."',
                        '".$bot['bot_data']['insert'][$i]['proxy_problem']."',
                        '".$bot['bot_data']['insert'][$i]['status']."',
                        '".$bot['bot_data']['insert'][$i]['user_agent']."',
                        '".$bot['bot_data']['insert'][$i]['next_time']."',
                        FLOOR(RAND()*10000000),
                        FLOOR(RAND()*10000000),
                        FROM_UNIXTIME(UNIX_TIMESTAMP()+4*24*60*60)
                        ),";
            
            
                        
                        
                    // формируем json отвте об успешном довалении ботов
                    $answer_ok.= '{"id": "'.$bot['bot_data']['insert'][$i]['id'].'"},';
                }
                
            // удяляем лишнюю  запятую
            $more=substr($more, 0, -1);
            
            /// вносим ботов в базу
            if($more) sql_query("sql_robots_insert2",__LINE__);
            //file_put_contents("starter2.txt",date("d.m.Y H:i:s"). $more.".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
         // echo "insert".$more;  
    
        }
        
        
    ////////////////////////////////////////////    
    // перебираем ботов c обновлением данных 
    ////////////////////////////////////////////
    if(count($bot['bot_data']['update'])>0)
        {
            
            $more="";$more2="";
            for($i=0; $i<count($bot['bot_data']['update']);$i++)
                {
              
                    // формируем sql запрос на добавление ботов
                    $more.="(
                        '".$bot['bot_data']['update'][$i]['id']."',
                        '".$bot['bot_data']['update'][$i]['botnet_num']."',
                        '".$bot['bot_data']['update'][$i]['vk_id']."',
                        '".$bot['bot_data']['update'][$i]['login']."',
                        '".$bot['bot_data']['update'][$i]['pass']."',
                        '".iconv("utf-8","windows-1251",$bot['bot_data']['update'][$i]['familia'])."',
                        '".$bot['bot_data']['update'][$i]['age']."',
                        '".$bot['bot_data']['update'][$i]['sex']."',
                        '".$bot['bot_data']['update'][$i]['proxy']."',
                        '".$bot['bot_data']['update'][$i]['proxylogin']."',
                        '".$bot['bot_data']['update'][$i]['proxy_problem']."',
                        '".$bot['bot_data']['update'][$i]['status']."',
                        '".$bot['bot_data']['update'][$i]['last_time']."',
                        '".$bot['bot_data']['update'][$i]['next_time']."',
                        '".$bot['bot_data']['update'][$i]['user_agent']."',
                        FROM_UNIXTIME(UNIX_TIMESTAMP()+4*24*60*60),
                        RAND()
                        
                        ),";
            

                        
                        
                    // формируем json отвте об успешном довалении ботов
                    $answer_ok.= '{"id": "'.$bot['bot_data']['update'][$i]['id'].'"},';
                    
                    // если пришел статус 5 то
                    if($bot['bot_data']['update'][$i]['status']==5)
                        {
                            // очищаем ьаблицу invited  от этого бота
                            // формируе5м строку запроса
                            $more2 .= "'".$bot['bot_data']['update'][$i]['id']."',";
                        }
                }
                
            
            /// вносим ботов в бызу
        if($more)
            {
                       // file_put_contents("starter2.txt",date("d.m.Y H:i:s"). $more.".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                // удяляем лишнюю  запятую
                $more=substr($more, 0, -1);
                sql_query("sql_robots_insert3",__LINE__);
            }
           
        if($more2)
            {
                // удяляем лишнюю  запятую
                $more2=substr($more2, 0, -1);
                sql_query("sql_invited_update1",__LINE__);
                sql_query("sql_invited_update2",__LINE__);
                sql_query("sql_invited_update3",__LINE__);
            }
   
            
    
        }
        
        // синхронизация настроек групп
        if(count($bot['group_sett']['insert'])>0)
        {
             //file_put_contents("starter.txt",date("d.m.Y H:i:s"). var_export($bot['group_sett']['insert'],true).".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            $more=""; $more2="";
            for($i=0; $i<count($bot['group_sett']['insert']);$i++)
                {
                    // если пришел статус 5 то удаляем группу
                    if($bot['group_sett']['insert'][$i]['repost_status']==5)
                        {
                            // формируе5м строку запроса
                            $more2 .= "'".$bot['group_sett']['insert'][$i]['id']."',";
                        }
                    else
                        {
                            // формируем sql запрос на добавление/обновление настроек групп
                            $more.="(
                                '".$bot['group_sett']['insert'][$i]['id']."',
                                '".$bot['group_sett']['insert'][$i]['botnet']."',
                                '".$bot['group_sett']['insert'][$i]['group_id']."',
                                '".iconv("utf-8","windows-1251",$bot['group_sett']['insert'][$i]['group_name'])."',
                                '".$bot['group_sett']['insert'][$i]['group_screen_name']."',
                                '".$bot['group_sett']['insert'][$i]['repost_status']."',
                                '".$bot['group_sett']['insert'][$i]['repost_deep']."',
                                '".$bot['group_sett']['insert'][$i]['repost_veroiat']."',
                                '".$bot['group_sett']['insert'][$i]['invite_status']."',
                                '".$bot['group_sett']['insert'][$i]['invite_kol']."',
                                '".$bot['group_sett']['insert'][$i]['join_status']."'
                                ),";
                        }
            
                        
                        
                    // формируем json отвте об успешной синхронизации
                    $answer_group_ok.= '{"id": "'.$bot['group_sett']['insert'][$i]['id'].'"},';
                    

                }
                
            
            //file_put_contents("starter.txt",date("d.m.Y H:i:s"). $more.".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            /// вносим uгруппы в базу
            if($more)
                {
                    // удяляем лишнюю  запятую
                    $more=substr($more, 0, -1);
                    sql_query("sql_groups_insert1",__LINE__);
                }
            
            if($more2)
                {
                    // удалить группы 
                    // удяляем лишнюю  запятую
                    $more2=substr($more2, 0, -1);
                   sql_query("sql_groups_delete1",__LINE__);
                }
        }
        
        /////////////////////////////////////////////////////
        // получаем настроки ботнетов
        //////////////////
        if(count($bot['bot_sett']['insert'])>0)
        {
            $more="";
            for($i=0; $i<count($bot['bot_sett']['insert']);$i++)
                {
              
                    // формируем sql запрос на добаление ботов
                    $more.="(
                        '".$bot['bot_sett']['insert'][$i]['botnet']."',
                        '".$bot['bot_sett']['insert'][$i]['country']."',
                        '".$bot['bot_sett']['insert'][$i]['city']."',
                        '".$bot['bot_sett']['insert'][$i]['friending_status']."',
                        '".$bot['bot_sett']['insert'][$i]['friends_reaction_if_nomore']."',
                        '".$bot['bot_sett']['insert'][$i]['friends_add_speed']."',
                        '".$bot['bot_sett']['insert'][$i]['my_friends_add_speed']."',
                        '".$bot['bot_sett']['insert'][$i]['friends_add_live_only']."',
                        '".$bot['bot_sett']['insert'][$i]['friends_need_for_start_repost']."',
                        '".$bot['bot_sett']['insert'][$i]['friends_add_while_less_than']."'
                        ),";
            
            
                        
                        
                    // формируем json отвте об успешном довалении ботов
                    $answer_sett_ok.= '{"id": "'.$bot['bot_sett']['insert'][$i]['botnet'].'"},';
                }
                
            // удяляем лишнюю  запятую
            $more=substr($more, 0, -1);
            
            /// вносим ботов в бызу
            if($more) sql_query("sql_botnets_settings_insert1",__LINE__);
          //echo "insert".$more;  
    
        }
        
        
    ////////////////////////////////////////////    
    // перебираем ботов c обновлением данных 
    ////////////////////////////////////////////
    if(count($bot['bot_sett']['update'])>0)
        {
            
            $more="";
            for($i=0; $i<count($bot['bot_sett']['update']);$i++)
                {
              
                    // формируем sql запрос на добавление ботов
                    $more.="(
                        '".$bot['bot_sett']['update'][$i]['botnet']."',
                        '".$bot['bot_sett']['update'][$i]['country']."',
                        '".$bot['bot_sett']['update'][$i]['city']."',
                        '".$bot['bot_sett']['update'][$i]['friending_status']."',
                        '".$bot['bot_sett']['update'][$i]['friends_reaction_if_nomore']."',
                        '".$bot['bot_sett']['update'][$i]['friends_add_speed']."',
                        '".$bot['bot_sett']['update'][$i]['my_friends_add_speed']."',
                        '".$bot['bot_sett']['update'][$i]['friends_add_live_only']."',
                        '".$bot['bot_sett']['update'][$i]['friends_need_for_start_repost']."',
                        '".$bot['bot_sett']['update'][$i]['friends_add_while_less_than']."'
                        ),";
            
            
                        
                        
                    // формируем json отвте об успешном довалении ботов
                    $answer_sett_ok.= '{"id": "'.$bot['bot_sett']['update'][$i]['botnet'].'"},';
                }
                
            // удяляем лишнюю  запятую
            $more=substr($more, 0, -1);
            //file_put_contents("starter.txt",date("d.m.Y H:i:s"). $more.".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            /// вносим ботов в бызу
           if($more) sql_query("sql_botnets_settings_insert1",__LINE__);
        //echo "update".$more;
            
    
        }
        
      //die;  
    // формируем ответ для подвтерждения синхроницайии
    if($answer_ok || $answer_sett_ok || $answer_group_ok)
        {
            // удаляем последнюю заптую
            $answer_ok      =substr($answer_ok,0,-1);
            $answer_sett_ok =substr($answer_sett_ok,0,-1);
            $answer_group_ok =substr($answer_group_ok,0,-1);
            
            /// формируем окончательный json ответ
            $answer='{"sync_ok":{"bot_data":['.$answer_ok.'],"bot_sett":['.$answer_sett_ok.'],"group_sett":['.$answer_group_ok.']}}';
            
            //file_put_contents("starter.txt",date("d.m.Y H:i:s"). $answer.".строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            
             // посылаем ответ для подвтерждения синхроницайии


            $url="$baselink&action=sync_ok";
            $param="data=$answer";
            
            list($headers, $response) = $http->sendPostRequest($url,$param);
            
   // echo $response;die;
        }



            
       // синхронизируем заблокированых ботов 
        $res2=sql_query("sql_robots_select11",__LINE__);
        $bot=mysql_fetch_array($res2);
        
        // проверяем есть ли несинхронизированные боты
        if($bot[id])
            {
                $bot_id=$bot[id];
                $syncdata ="&botid=".$bot[id];
                $syncdata.="&botvkid=".$bot[vk_id];
                $syncdata.="&botfamilia=".urlencode($bot[familia]);
                $syncdata.="&botname=".urlencode($bot[name]);
                $syncdata.="&botfriends=".$bot[friends];
                $syncdata.="&botinvited=".$bot[invited];
                $syncdata.="&proxy=".urlencode($bot[proxy]);
                $syncdata.="&proxylogin=".urlencode($bot[proxylogin]);
                $syncdata.="&status=".$bot[status];
                $syncdata.="&nexttime=".$bot[next_time];
                $syncdata.="&lasttime=".$bot[last_time];
                $syncdata.="&botnet=".$bot[botnet_num];
                

                // синхронизируем
                $url="$baselink&action=update&dbase=robots".$syncdata;
                $response = @file_get_contents($url, false, $context);
                
                //file_put_contents("starter.txt",date("d.m.Y H:i:s")." $url строка".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                            
                if($response == "ok")
                   {
                      // сбрасываем признак необходимости синхронизации с сервером интерфейса
                      sql_query("sql_robots_update20",__LINE__);
                   }
            }

}

function restart_bot()
{
    global $db,$bot_id;
            // перезапускаем взгдючнувщих ботов 
        $res2=sql_query("sql_robots_select12",__LINE__);
        $bot2=mysql_fetch_array($res2);
        
        // проверяем есть ли взглючнувшие боты
        if($bot2[id])
            {
                $bot_id=$bot2[id];
                
                // перезапуск путем установки ближайщего нексттайма и сбрасываем признак необходимости синхронизации с сервером интерфейса
                sql_query("sql_robots_update2_1",__LINE__);
            }
}

function stopme()
{
    global $db,$sec,$botnet_num;
    $res2=sql_query("sql_script_in_use_select3",__LINE__);
    $stopme=mysql_fetch_array($res2);
    if($stopme[stopme]>0)
        {
            //file_put_contents("$sname.txt",date("h:i:sa")." остановка по требованию ".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);
            // сьрасываем метку
            sql_query("sql_script_in_use_update3",__LINE__);
            
            // обнуляем время
            $sec=-120;
            sql_query("sql_script_in_use_update2",__LINE__);
             
            //echo "требование остановить прокси";
           
           ini_set('default_socket_timeout', 60);
            // убиваем эту копию стартера
            mysql_close($db);
            die;
            //file_put_contents("$sname.txt",date("h:i:sa")." не остановился  ".__LINE__."\r\n",FILE_APPEND |  LOCK_EX);
        }
        
    /////////////////////////////////////////////////
    // в подходящее время стартуем сбор прокси

            
            //if(mysql_fetch_array(sql_query("sql_script_in_use_select1_1",__LINE__)))
            $botnet_num=1;
            $res22=sql_query("sql_proxy_select4_1",__LINE__);
            if(!mysql_num_rows($res22))
             {
               start('http://test1.ru/proxyaddauto.php');
             }
             else
              {
               if(mysql_fetch_array(sql_query("sql_script_in_use_select1_1",__LINE__)))
                {
                  start('http://test1.ru/proxyaddauto.php');
                }
              } 
            

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
?>