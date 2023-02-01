<?php
// склад запросов к mysql для более легкого изменения
class MysqlQueryVkbotV4
{
  public $DBH;
  public $botnet_num;
  public $bot_id;
  public $sname;
  public $Log;

  public function __construct(int $bot_id,string $sname)
  {
     // работаем только с данным ботом
     $this->bot_id=$bot_id;
     $this->sname=$sname;
     // подключение к базе mysql
     // данные доступа к sql
     $sqlhost="localhost";
     $sqllogin="root";
     $sqlpass="root";
     $sqldbase="vkbot";

      $this->Log  =  new LogClass($bot_id,__FILE__);

     try
      {
      # MySQL через PDO_MYSQL
      $this->DBH = new PDO("mysql:host=$sqlhost;dbname=$sqldbase", $sqllogin, $sqlpass);
      $this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );


      }
    catch(PDOException $e)
      {
          $this->Log->save("ErrMySQL_",$e->getMessage());
          die;
      }

      // работаем только с данным ботнетом
      $this->botnet_num=$this->sql_robots_select0()[0]["botnet_num"];
  }


  ////////////
  // azenv
  ////////////
  public function sql_azenv_select($status=0,$limit=0)
  {
   //sql_azenv_select1 лимит =3
   //sql_azenv_select2 лимит =0

   // выбираем из функции query_warehouse запрос одноименный вызыаемой  функции и отправляем с параметрами на выполнение в фугкцию execute
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'status' => $status,'limit'=>$limit));
  }
  public function sql_azenv_update1($url)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'url' => $url));
  }


  ////////////
  // proxy
  ////////////
  public function  sql_proxy_select1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_proxy_select2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_proxy_select3()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_proxy_select4()
  {
    return $this->execute($this->query_warehouse(__FUNCTION__),array( 'botnet_num' => $this->botnet_num));
  }
  public function  sql_proxy_select4_1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'botnet_num' => $this->botnet_num));
  }
  public function  sql_proxy_select5(string $proxy)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'proxy' => $proxy));
  }
  public function  sql_proxy_select6(string $proxy)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'proxy' => $proxy));
  }
  public function  sql_proxy_select7()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
 // проверить как рабоатета LIKE :ip1%
  public function  sql_proxy_select8($ip1)
  {
    return $this->execute($this->query_warehouse(__FUNCTION__),array( 'ip1' => $ip1,'botnet_num' => $this->botnet_num));
  }
 // проверить как рабоатета LIKE :ip1%
  public function  sql_proxy_select8_1($ip1)
  {

   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'ip1' => $ip1,'botnet_num' => $this->botnet_num));
  }
  public function  sql_proxy_select8_2($ip1,$ip1_len,$ip2,$ip2_len)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'ip1' => $ip1,
           'ip2' => $ip2,
           'ip1_len' => $ip1_len,
           'ip2_len' => $ip2_len,
           'botnet_num' => $this->botnet_num));
  }
  public function  sql_proxy_select8_3($ip1,$ip1_len,$ip2,$ip2_len,$ip3,$ip3_len)
  {

   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'ip1' => $ip1,
           'ip2' => $ip2,
           'ip3' => $ip3,
           'ip1_len' => $ip1_len,
           'ip2_len' => $ip2_len,
           'ip3_len' => $ip3_len,
           'botnet_num' => $this->botnet_num));
  }
  public function  sql_proxy_select9($ip1,$ip2,$ip3)
  {
    return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'ip1' => $ip1,
           'ip2' => $ip2,
           'ip3' => $ip3,
           'botnet_num' => $this->botnet_num));
  }

  public function  sql_proxy_update1(string $proxy,$prstatus,$timeout)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'proxy'    => $proxy,
           'prstatus' => $prstatus,
           'timeout'  => $timeout));
  }
  public function  sql_proxy_update2(string $proxy,$prstatus)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'proxy'    => $proxy,
           'prstatus' => $prstatus));
  }
  public function  sql_proxy_update3(string $proxy,$prstatus)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'proxy'    => $proxy,
           'prstatus' => $prstatus));
  }
  public function  sql_proxy_update4(string $proxy,string $remoteip,$timeout,$pranonim,$num,$ya)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'proxy'    => $proxy,
           'remoteip' => $remoteip,
           'timeout'  => $timeout,
           'pranonim' => $pranonim,
           'num'      => $num,
           'ya'       => $ya ));
  }

  // ПРОВЕРИТЬ как сработает more
  public function  sql_proxy_insert1(string $more)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'more' => $more));
  }
  // ПРОВЕРИТЬ как сработает more
  public function  sql_proxy_insert2(string $more)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'more' => $more));
  }

  public function  sql_proxy_delete1($proxy)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'proxy' => $proxy));
  }
  public function  sql_proxy_delete2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_proxy_delete3()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }

  ////////////
  // robots
  ////////////

    public function  sql_robots_select0()
    {
        return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id));
    }
    public function  sql_robots_select1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select1_1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select1_1_2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select1_2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select1_3()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id));
  }
  public function  sql_robots_select2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select3()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select3_1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select3_1_2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select4()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select5()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select6()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select7()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id));
  }
  public function  sql_robots_select7_1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id));
  }
  public function  sql_robots_select8(string $remoteip)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'botnet_num' => $this->botnet_num,'remoteip' => $remoteip));
  }
  public function  sql_robots_select8_1(string $remoteip)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id, 'botnet_num' => $this->botnet_num,'remoteip' => $remoteip));
  }
  public function  sql_robots_select8_2(string $proxy)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('botnet_num' => $this->botnet_num,'proxy' => $proxy));
  }
  public function  sql_robots_select9()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select10()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select11()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_robots_select12(int $proxy_try_times)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('proxy_try_times' => $proxy_try_times));
  }
  public function  sql_robots_select13()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id));
  }
  public function  sql_robots_select14(int $friends_add_while_less_than)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'botnet_num' => $this->botnet_num,'' => $friends_add_while_less_than));
  }
  public function  sql_robots_select15()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'bot_id' => $this->bot_id));
  }
  public function  sql_robots_select16(int $script_id)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id,'script_id' => $script_id));
  }

  public function  sql_robots_update1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id));
  }
  // проверить встраивается ли строка $tk=",line='0'"
  public function  sql_robots_update1_1(int $status)
  {
   if($status>0) $tk=",line='0'";
   return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id,'status' => $status,'tk' => $tk));
  }
  public function  sql_robots_update2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),
       array(
           'bot_id' => $this->bot_id,
           'line' => debug_backtrace()[0]["line"]
       ));
  }
  public function  sql_robots_update2_1()
  {
    return $this->execute($this->query_warehouse(__FUNCTION__),array(
        'bot_id' => $this->bot_id,
        'line' => 0
    ));
  }
  public function  sql_robots_update2_2()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id));
  }
  public function  sql_robots_update3()
 {
  return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id));
 }
  public function  sql_robots_update3_1(int $url_timeout)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),
       array(
           'bot_id' => $this->bot_id,
           'line' => debug_backtrace()[0]["line"],
           'url_timeout' => $url_timeout));
  }
  public function  sql_robots_update4()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id));
  }
  public function  sql_robots_update5(int $client_id)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array('bot_id' => $this->bot_id,'client_id' => $client_id));
  }
  public function  sql_robots_update6(string $proxy,string $proxylogin,string $remoteip)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy'    => $proxy,
           'remoteip' => $remoteip,
           'proxylogin'  => $proxylogin));
  }
  public function  sql_robots_update6_1(string $remoteip)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'remoteip' => $remoteip));
  }
  public function  sql_robots_update7(int $proxy_eating)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy_eating' => $proxy_eating));
  }
  public function  sql_robots_update7_1(int $proxy_eating, int $proxy_try_times)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy_eating' => $proxy_eating,
           'proxy_try_times' => $proxy_try_times));
  }
  public function  sql_robots_update7_2(int $proxy_eating, int $proxy_try_times)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy_eating' => $proxy_eating));
  }
  public function  sql_robots_update8()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update9($next_time)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'next_time' => $next_time));
  }
  public function  sql_robots_update9_1()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update10($count_my_friends,$count_out_friends,$invited_plus,$bot_invitedplus,$bot_percent)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'            => $this->bot_id,
           'count_my_friends'  => $count_my_friends,
           'count_out_friends' => $count_out_friends,
           'invited_plus'      => $invited_plus,
           'bot_invitedplus'   => $bot_invitedplus,
           'bot_percent'       => $bot_percent
       ));
  }
  public function  sql_robots_update10_1($count_my_friends,$count_out_friends)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'            => $this->bot_id,
           'count_my_friends'  => $count_my_friends,
           'count_out_friends' => $count_out_friends
       ));
  }
  public function  sql_robots_update11()
  {
  return $this->execute($this->query_warehouse(
      __FUNCTION__),
      array(
          'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update12()
  {
  return $this->execute($this->query_warehouse(
      __FUNCTION__),
      array(
          'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update13()
  {
  return $this->execute($this->query_warehouse(
      __FUNCTION__),
      array(
          'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update14()
  {
  return $this->execute($this->query_warehouse(
      __FUNCTION__),
      array(
          'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update15()
  {
  return $this->execute($this->query_warehouse(
      __FUNCTION__),
      array(
          'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update16($unblock_date)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'unblock_date' => $unblock_date));
  }
  public function  sql_robots_update17($bot_vk_id)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'bot_vk_id' => $bot_vk_id));
  }
  public function  sql_robots_update18($bot_familia,$bot_name)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'            => $this->bot_id,
           'bot_familia'  => $bot_familia,
           'bot_name' => $bot_name
       ));
  }
  public function  sql_robots_update19()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update20()
 {
  return $this->execute($this->query_warehouse(
      __FUNCTION__),
      array(
          'bot_id'   => $this->bot_id));
 }
  public function  sql_robots_update21($i_want_add_friends)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'i_want_add_friends' => $i_want_add_friends));
  }
  public function  sql_robots_update22($work_cycle,$i_want_add_friends,$left_to_group_invite)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'               => $this->bot_id,
           'work_cycle'           => $work_cycle,
           'i_want_add_friends'   => $i_want_add_friends,
           'left_to_group_invite' => $left_to_group_invite
       ));
  }
  public function  sql_robots_update23($left_to_group_invite)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'left_to_group_invite' => $left_to_group_invite));
  }
  public function  sql_robots_update24($proxy_eating)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy_eating' => $proxy_eating));
  }
  public function  sql_robots_update26($line)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'line' => $line));
  }
  public function  sql_robots_update27($proxy_alive)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy_alive' => $proxy_alive));
  }
  public function  sql_robots_update28($proxy_alive,$proxy_eating)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'proxy_alive' => $proxy_alive,
           'proxy_eating' => $proxy_eating
       ));
  }
  public function  sql_robots_update29(int $del_out_invites)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'del_out_invites' => $del_out_invites));
  }
  public function  sql_robots_update31()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update32()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update33()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update34()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update35()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update36()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }
  public function  sql_robots_update37($cycle4)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'cycle4' => $cycle4));
  }
  public function  sql_robots_update38($script_id)
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id,
           'script_id' => $script_id));
  }
  public function  sql_robots_update39()
  {
   return $this->execute($this->query_warehouse(
       __FUNCTION__),
       array(
           'bot_id'   => $this->bot_id));
  }

  // ПРОВЕРИТЬ как сработает more
  public function  sql_robots_insert1(string $more)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'more' => $more));
  }
  // ПРОВЕРИТЬ как сработает more
  public function  sql_robots_insert2(string $more)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'more' => $more));
  }

  ////////////////
  // botnets_settings
  ///////////////////
  public function  sql_botnets_settings_select1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'botnet_num' => $this->botnet_num));
  }
  // ПРОВЕРИТЬ как сработает more
  public function  sql_botnets_settings_insert1(string $more)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),array( 'more' => $more));
  }

  ////////////////////
  //id_grab_male_setting
  ///////////////////
  public function  sql_id_grab_male_setting_select1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_id_grab_male_setting_insert1(int $age,int $birth_month,int $birth_day)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),
       array(
           'age'         => $age,
           'birth_month' => $birth_month,
           'birth_day'   => $birth_day
       ));
  }

  ////////////////////
  //id_grab_female_setting
  //////////////////
  public function  sql_id_grab_female_setting_select1()
  {
   return $this->execute($this->query_warehouse(__FUNCTION__));
  }
  public function  sql_id_grab_female_setting_insert1(int $age,int $birth_month,int $birth_day)
  {
   return $this->execute($this->query_warehouse(__FUNCTION__),
       array(
           'age'         => $age,
           'birth_month' => $birth_month,
           'birth_day'   => $birth_day
       ));
  }

  ////////////////////
  //vk_users
  //////////////////
  public function  sql_vk_users_select1($more,$more3)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'bot_id'     => $this->bot_id,
           'botnet_num' => $this->botnet_num,
           'more'       => $more,
           'more3'      => $more3
           ));
  }
  public function  sql_vk_users_select2($more,$more3)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'botnet_num' => $this->botnet_num,
           'more'       => $more,
           'more3'      => $more3
       ));
  }
  public function  sql_vk_users_select2_1($more,$more3)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'botnet_num' => $this->botnet_num,
           'more'       => $more,
           'more3'      => $more3
       ));
  }
  public function  sql_vk_users_select2_4_3($more,$more3)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'botnet_num' => $this->botnet_num,
           'more'       => $more,
           'more3'      => $more3
       ));
  }
  public function  sql_vk_users_select3($user_for_invite_id)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'user_for_invite_id' => $user_for_invite_id
       ));
  }
  public function  sql_vk_users_select4($user_id)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'user_id' => $user_id
       ));
  }
  public function  sql_vk_users_select5($age)
  {
   return $this->execute(
       $this->query_warehouse(__FUNCTION__),
       array(
           'age' => $age
       ));
  }
    // ПРОВЕРИТЬ как сработает more
  public function  sql_vk_users_select6($bot_anons_id_for_send,$more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more'       => $more,
                'bot_anons_id_for_send'      => $bot_anons_id_for_send
            ));
    }
    public function  sql_vk_users_select7($bot_anons_id_for_send)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'bot_anons_id_for_send' => $bot_anons_id_for_send
            ));
    }
    // ПРОВЕРИТЬ как сработает more
    public function  sql_vk_users_selec8($bot_anons_id_for_send,$more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more'       => $more,
                'bot_anons_id_for_send'      => $bot_anons_id_for_send
            ));
    }
    public function  sql_vk_users_select9()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id
            ));
    }
    public function  sql_vk_users_select10()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__));
    }
    public function  sql_vk_users_select11($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'group_vk_id' => $group_vk_id
            ));
    }

    // ПРОВЕРИТЬ как сработает more
    public function  sql_vk_users_insert1($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more'       => $more
            ));
    }
    public function  sql_vk_users_insert2($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more'       => $more
            ));
    }
    public function  sql_vk_users_insert3($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more'       => $more
            ));
    }

    public function  sql_vk_users_delete1($user_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'user_id' => $user_id
            ));
    }

    public function  sql_vk_users_update5($user_for_invite_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'user_for_invite_id'       => $user_for_invite_id
            ));
    }
    public function  sql_vk_users_update9($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more2'       => $more2
            ));
    }

    ////////////////////
    //vk_users_all
    //////////////////
    /// // ПРОВЕРИТЬ как сработает more
    public function  sql_vk_users_all_insert1($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'more2' => $more2
            ));
    }

    ////////////////////
    //vk_users_tmp
    //////////////////
    public function  sql_vk_users_tmp_select1($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'botnet_num' => $this->botnet_num,
                'more' => $more
            ));
    }
    public function  sql_vk_users_tmp_select2($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'botnet_num' => $this->botnet_num,
                'more' => $more
            ));
    }
    public function  sql_vk_users_tmp_delete1($user_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'user_id' => $user_id
            ));
    }

    ////////////////////
    //invited
    //////////////////
    public function  sql_invited_insert1($user_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'botnet_num' => $this->botnet_num,
                'user_id' => $user_id
            ));
    }
    // ПРОВЕРИТЬ как сработает more
    public function  sql_invited_insert2($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'more2' => $more2
            ));
    }
    // ПРОВЕРИТЬ как сработает more
    public function  sql_invited_insert3($more3)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'more3' => $more3
            ));
    }

    // ПРОВЕРИТЬ как сработает more
    public function  sql_invited_update1($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more2' => $more2
            ));
    }
    // ПРОВЕРИТЬ как сработает more
    public function  sql_invited_update2($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more2' => $more2
            ));
    }
    // ПРОВЕРИТЬ как сработает more
    public function  sql_invited_update3($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more2' => $more2
            ));
    }
    public function  sql_invited_update4()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'botnet_num' => $this->botnet_num,
                'more2' => $more2
            ));
    }
    public function  sql_invited_update5()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'more2' => $more2
            ));
    }

    ////////////////////
    //groups
    //////////////////
    ///
    ///  // ПРОВЕРИТЬ как сработает more
    public function  sql_groups_insert1($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more2' => $more
            ));
    }

    public function  sql_groups_select1()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'botnet_num' => $this->botnet_num,
            ));
    }
    public function  sql_groups_select2()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'botnet_num' => $this->botnet_num,
            ));
    }
    public function  sql_groups_select3()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'botnet_num' => $this->botnet_num,
            ));
    }
    public function  sql_groups_select4($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'botnet_num' => $this->botnet_num,
                'group_vk_id' => $group_vk_id
            ));
    }

    public function  sql_groups_update1($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'group_vk_id' => $group_vk_id
            ));
    }
    public function  sql_groups_update2($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'group_vk_id' => $group_vk_id
            ));
    }
    public function  sql_groups_update3($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'group_vk_id' => $group_vk_id
            ));
    }
    // ПРОВЕРИТЬ как сработает more
    public function  sql_groups_delete1($more2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'more2' => $more2
            ));
    }

    ////////////////////
    //groups_ignore
    //////////////////
    public function  sql_groups_ignore_select1($bot_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_vk_id' => $bot_vk_id
            ));
    }

    ////////////////////
    //groups_invite_wait
    //////////////////
    public function  sql_groups_invite_wait_select1($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'group_vk_id' => $group_vk_id
            ));
    }
    public function  sql_groups_invite_wait_insert1($more)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'more' => $more
            ));
    }
    public function  sql_groups_invite_wait_delete1($group_vk_id,$user_for_invite_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'user_for_invite_id' => $user_for_invite_id,
                'group_vk_id' => $group_vk_id
            ));
    }

    ////////////////////
    //groups_invited
    //////////////////
    public function  sql_groups_invited_select1($group_vk_id,$bot_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_vk_id' => $bot_vk_id,
                'group_vk_id' => $group_vk_id
            ));
    }
    public function  sql_groups_invited_select2($group_vk_id,$user_for_invite_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'user_for_invite_id' => $user_for_invite_id,
                'group_vk_id' => $group_vk_id
            ));
    }
    public function  sql_groups_invited_insert1($group_vk_id,$user_for_invite_id,$user_group_invite_status)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'user_for_invite_id' => $user_for_invite_id,
                'user_group_invite_status' => $user_group_invite_status,
                'group_vk_id' => $group_vk_id
            ));
    }

    ////////////////////
    //groups_bots_joined
    //////////////////
    public function  sql_groups_bots_joined_select1($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'group_vk_id' => $group_vk_id
            ));
    }
    public function  sql_groups_bots_joined_insert1($group_vk_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'group_vk_id' => $group_vk_id
            ));
    }

    //////////////////
    // база учета репостов
    //////////////////////////
    public function  sql_reposted_select1($group_vk_id,$post_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'group_vk_id' => $group_vk_id,
                'post_id' => $post_id
            ));
    }
    public function  sql_reposted_insert1($group_vk_id,$post_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
                'group_vk_id' => $group_vk_id,
                'post_id' => $post_id
            ));
    }
    public function  sql_reposted_insert2()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_id' => $this->bot_id,
            ));
    }

    ////////////////////
    //anons
    //////////////////
    public function  sql_anons_select1($bot_anons_id_for_send)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'bot_anons_id_for_send' => $bot_anons_id_for_send
            ));
    }

    ////////////////////
    //anons_suffix
    //////////////////
    public function  sql_anons_suffix_select1($suffix_num)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'suffix_num' => $suffix_num
            ));
    }

    //////////////////////////////////////////////////////
    // общение с ботом
    //////////////////////////////////////////////////////

    ////////////////////
    //message_in
    //////////////////
    public function  sql_message_in_select1($chel_mes)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chel_mes' => $chel_mes
            ));
    }
    public function  sql_message_in_insert1($chel_mes)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chel_mes' => $chel_mes
            ));
    }
    public function  sql_message_in_update1($mess_in_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'mess_in_id' => $mess_in_id
            ));
    }

    ////////////////////
    //answer
    //////////////////
    public function  sql_answer_select1($mess_in_answer_id)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'mess_in_answer_id' => $mess_in_answer_id
            ));
    }

    ////////////////////
    //chat_quest
    //////////////////
    public function  sql_chat_quest_select1($chat_quest,$chel_in,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'chel_in'   => $chel_in,
                'chel_sex'  => $chel_sex
            ));
    }
    public function  sql_chat_quest_select2($chat_quest,$chel_mes,$chel_sex,$bot_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'chel_mes'   => $chel_mes,
                'chel_sex'  => $chel_sex,
                'bot_sex'   => $bot_sex
            ));
    }
    public function  sql_chat_quest_select3($chat_quest,$bot_mes,$bot_sex,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' =>$chat_quest ,
                'bot_mes'   => $bot_mes,
                'chel_sex'   => $chel_sex,
                'bot_sex'  => $bot_sex
            ));
    }
    public function  sql_chat_quest_select4($chat_quest,$bot_sex,$chel_sex,$prev_id2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'bot_sex'   => $bot_sex,
                'prev_id2'   => $prev_id2,
                'chel_sex'  => $chel_sex
            ));
    }
    public function  sql_chat_quest_select5($chat_quest,$bot_sex,$chel_sex,$prev_id2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'bot_sex'   => $bot_sex,
                'prev_id2'   => $prev_id2,
                'chel_sex'  => $chel_sex
            ));
    }
    public function  sql_chat_quest_select6($chat_quest,array $best_res2,$bot_sex,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'best_res2'   => $best_res2[0],
                'bot_sex'  => $bot_sex,
                'chel_sex'  => $chel_sex
            ));
    }
    public function  sql_chat_quest_select7($chat_quest,array $ord_id,$rnd_mes)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'ord_id'   => $ord_id[$rnd_mes]
            ));
    }
    public function  sql_chat_quest_select8($chat_quest,array $best_res)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'best_res'   => $best_res[0]
            ));
    }
    public function  sql_chat_quest_select9($chat_quest,$chel_mes_prev,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'chel_mes_prev'   => $chel_mes_prev,
                'chel_sex'  => $chel_sex
            ));
    }

    public function  sql_chat_quest_insert1($chat_quest,$chel_mes,$chel_sex,$bot_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'chel_mes'   => $chel_mes,
                'chel_sex'  => $chel_sex,
                'bot_sex'   => $bot_sex
            ));
    }
    public function  sql_chat_quest_insert2($chat_quest,$chel_mes_prev,$chel_sex,$bot_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'chel_mes_prev'   => $chel_mes_prev,
                'bot_sex'   => $bot_sex,
                'chel_sex'  => $chel_sex
            ));
    }
    public function  sql_chat_quest_update1($chat_quest,$bot_mes,$chel_sex,$bot_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_quest' => $chat_quest,
                'bot_mes'   => $bot_mes,
                'chel_sex'  => $chel_sex,
                'bot_sex'   => $bot_sex
            ));
    }

    ////////////////////
    //chat_sign
    //////////////////
    public function  sql_chat_sign_select1($chat_sign,$csign,$id,$prev_id_in,$bot_sex,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'csign'     => $csign,
                'id'        => $id,
                'prev_id_in'=> $prev_id_in,
                'bot_sex'   =>$bot_sex,
                'chel_sex'  =>$chel_sex
            ));
    }
    public function  sql_chat_sign_select2($chat_sign,$csign,$id,$bot_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'csign'     => $csign,
                'id'        => $id,
                'bot_sex'   => $bot_sex
            ));
    }
    public function  sql_chat_sign_select3($chat_sign,$csign,$prev_id,$bot_sex,$chel_sex,$prev_id2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'csign'     =>$csign ,
                'prev_id'   => $prev_id,
                'bot_sex'   => $bot_sex,
                'chel_sex'  =>$chel_sex,
                'prev_id2'  =>$prev_id2
            ));
    }
    public function  sql_chat_sign_select3_1($chat_sign,$csign,$bot_sex,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'csign'     =>$csign ,
                'bot_sex'   => $bot_sex,
                'chel_sex'  =>$chel_sex
            ));
    }
    public function  sql_chat_sign_select4($chat_sign,$csign,$bot_sex,$chel_sex,$prev_id2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'csign'     =>$csign ,
                'bot_sex'   => $bot_sex,
                'chel_sex'  =>$chel_sex,
                'prev_id2'  =>$prev_id2
            ));
    }
    public function  sql_chat_sign_select5($chat_sign,$csign,$item,$bot_sex,$chel_sex,$prev_id2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'csign'     =>$csign ,
                'item'      => $item,
                'bot_sex'   => $bot_sex,
                'chel_sex'  =>$chel_sex,
                'prev_id2'  =>$prev_id2
            ));
    }
    public function  sql_chat_sign_select6($chat_sign,$prev_id,$item,$bot_sex,$chel_sex,$prev_id2)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'prev_id'   =>$prev_id ,
                'item'      => $item,
                'bot_sex'   => $bot_sex,
                'chel_sex'  =>$chel_sex,
                'prev_id2'  =>$prev_id2
            ));
    }

    public function  sql_chat_sign_insert1($chat_sign,$csign,$csign_full,$id,$prev_id_in,$bot_sex,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign'    => $chat_sign,
                'csign'        => $csign,
                'csign_full'   => $csign_full,
                'id'           => $id,
                'prev_id_in'   => $prev_id_in,
                'bot_sex'      => $bot_sex,
                'chel_sex'     => $chel_sex
            ));
    }
    public function  sql_chat_sign_update1($chat_sign,$prev_id_in,$csign,$id,$bot_sex,$chel_sex)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'chat_sign' => $chat_sign,
                'prev_id_in'=> $prev_id_in,
                'csign'     => $csign,
                'id'        => $id,
                'bot_sex'   => $bot_sex,
                'chel_sex'  => $chel_sex
            ));
    }


    // призанки запущенности срикпта
    /////////////////////
    //script_in_use
    //////////////////////
    public function  sql_script_in_use_select1()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' => $this->sname
            ));
    }
    public function  sql_script_in_use_select1_1()
    {
        return $this->execute($this->query_warehouse(__FUNCTION__));
    }
    public function  sql_script_in_use_select1_2()
    {
        return $this->execute($this->query_warehouse(__FUNCTION__));
    }
    public function  sql_script_in_use_select2()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' =>$this->sname
            ));
    }
    public function  sql_script_in_use_select3()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' =>$this->sname
            ));
    }
    public function  sql_script_in_use_select4()
    {
        return $this->execute($this->query_warehouse(__FUNCTION__));
    }

    public function  sql_script_in_use_update1()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' =>$this->sname
            ));
    }
    public function  sql_script_in_use_update2($sec)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' =>$this->sname,
                'sec'   => $sec
            ));
    }
    public function  sql_script_in_use_update2_1($sec)
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' =>$this->sname,
                'sec'   => $sec
            ));
    }
    public function  sql_script_in_use_update3()
    {
        return $this->execute(
            $this->query_warehouse(__FUNCTION__),
            array(
                'sname' =>$this->sname
            ));
    }



    // склад запросов
  private function query_warehouse($req)
  {

    switch ($req) {

        ////////////
        // azenv
        ////////////
        case "sql_azenv_select": $s = "SELECT link FROM azenv WHERE status=:status ORDER BY RAND() limit :limit"; break;
        case "sql_azenv_update1": $s = "UPDATE azenv SET status='0' WHERE link=:url limit 1";break; // изначально было UPDATE azenv SET status='$status' WHERE link='$url' limit 1

        ////////////
        // proxy
        ////////////
        case "sql_proxy_select1": $s = "SELECT proxy FROM proxy";break;
        case "sql_proxy_select2": $s = "SELECT proxy FROM proxy WHERE status=1 OR ISNULL(checktime)  OR UNIX_TIMESTAMP(checktime)+6*24*60*60<UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) ORDER BY RAND() limit 1";break;
        case "sql_proxy_select3":$s = "SELECT proxy,remoteip FROM proxy WHERE remoteip NOT IN (select remoteip from robots  where remoteip<>'') AND time>0 and status=0 and anonim>0 and used=0 ORDER BY RAND() AND time limit 1";break;
        case "sql_proxy_select4":$s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip WHERE  proxy.time>0 and proxy.status=0 and proxy.anonim>1 and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) GROUP BY proxy.remoteip ORDER BY RAND() limit 5000) as a  ORDER BY time,RAND()";break;
        case "sql_proxy_select4_1": $s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip WHERE   proxy.status<2  and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) ORDER BY RAND() limit 5) as a  ORDER BY time,RAND()";break;
        case "sql_proxy_select5": $s = "SELECT proxy FROM proxy WHERE proxy=:proxy and used='0'  limit 1";break;
        case "sql_proxy_select6": $s = "SELECT remoteip FROM proxy WHERE proxy=:proxy  limit 1";break;
        case "sql_proxy_select7": $s = "SELECT proxy FROM proxy WHERE anonim>0 AND `status`=0 AND used=0  and remoteip not in (select remoteip from robots where remoteip<>'') GROUP BY remoteip ORDER BY time limit 1000";break;
        case "sql_proxy_select8": $s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip   WHERE  proxy.proxy LIKE :ip1% and proxy.time>0 and proxy.status=0 and proxy.anonim>1 and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) GROUP BY proxy.remoteip  ) as a  ORDER BY RAND()";break;
        case "sql_proxy_select8_1": $s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip   WHERE  proxy.proxy LIKE :ip1% and proxy.status<2 and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) GROUP BY proxy.remoteip  ) as a  ORDER BY RAND()";break;
        case "sql_proxy_select8_2": $s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip WHERE  SUBSTRING(proxy.proxy,1,:ip1_len) =:ip1 and SUBSTRING(proxy.proxy,(:ip1_len+2),:ip2_len) =:ip2 and proxy.time>0 and proxy.status=0 and proxy.anonim>1 and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) GROUP BY proxy.remoteip  ) as a  ORDER BY time";break;
        case "sql_proxy_select8_3": $s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip WHERE  SUBSTRING(proxy.proxy,1,:ip1_len) =:ip1 and SUBSTRING(proxy.proxy,(:ip1_len+2),:ip2_len) =:ip2 and  SUBSTRING(proxy.proxy,(:ip1_len+:ip2_len+3),:ip3_len) =:ip3 and proxy.time>0 and proxy.status=0 and proxy.anonim>1 and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) GROUP BY proxy.remoteip  ) as a  ORDER BY time";break;
        case "sql_proxy_select9": $s = "SELECT * from (SELECT proxy.proxy as proxy, proxy.remoteip as remoteip, proxy.time as time FROM proxy LEFT JOIN robots on proxy.remoteip=robots.remoteip WHERE  proxy.proxy LIKE :ip1.:ip2.:ip3.%  and proxy.time>0 and proxy.status=0 and proxy.anonim>1 and proxy.used=0 and (ISNULL(robots.botnet_num) or robots.botnet_num<>:botnet_num ) GROUP BY proxy.remoteip  ) as a  ORDER BY time";break;

        case "sql_proxy_update1": $s = "UPDATE proxy SET time=:timeout,status=:prstatus WHERE proxy=:proxy limit 1";break;
        case "sql_proxy_update2": $s = "UPDATE proxy SET anonim=:prstatus WHERE proxy=:proxy limit 1";break;
        case "sql_proxy_update3": $s = "UPDATE proxy SET used=:prstatus WHERE proxy=:proxy limit 1";break;
        case "sql_proxy_update4": $s = "UPDATE proxy SET try=:num,checktime=CURRENT_TIMESTAMP(),time=:timeout,anonim=:pranonim,remoteip=:remoteip,yandex=:ya WHERE proxy=:proxy limit 1";break;

        case "sql_proxy_insert1": $s = "INSERT INTO proxy (proxy) VALUES :more ON DUPLICATE KEY UPDATE status= IF((anonim<'0' OR status='2' OR UNIX_TIMESTAMP(checktime)+5*24*60*60<UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) ),'1', status)";break;
        case "sql_proxy_insert2": $s = "INSERT INTO proxy (proxy) VALUES :more ON DUPLICATE KEY UPDATE status='1',used='0'";break;

        case "sql_proxy_delete1": $s = "DELETE FROM proxy WHERE proxy=:proxy limit 1";break;
        case "sql_proxy_delete2": $s = "delete from proxy where status='0' and anonim<'1'";break;
        case "sql_proxy_delete3": $s = "delete from proxy where used='1'";break;

        ////////////
        // robots
        ////////////
        case "sql_robots_select0": $s = "SELECT botnet_num FROM robots WHERE id=:bot_id  limit 1";break;
        case "sql_robots_select1": $s = "SELECT * FROM robots WHERE UNIX_TIMESTAMP(next_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())  AND status='0'  ORDER BY next_time LIMIT 1";break;
        case "sql_robots_select1_1": $s = "SELECT * FROM robots WHERE status='0' and proxy_problem>0 and UNIX_TIMESTAMP(next_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())  and UNIX_TIMESTAMP(last_time)>UNIX_TIMESTAMP(CURRENT_TIMESTAMP())-240*60 ORDER BY next_time ASC limit 1";break;
        case "sql_robots_select1_1_2": $s = "SELECT * FROM robots WHERE status>10 and status<20 ORDER BY next_time ASC limit 1";break;
        case "sql_robots_select1_2": $s = "SELECT * FROM robots WHERE id=2 limit 1";break;
        case "sql_robots_select1_3": $s = "SELECT * FROM robots WHERE id=:bot_id  limit 1";break;

        case "sql_robots_select2": $s = "SELECT id,vk_id,login,pass,age,sex,autoanswer,target_bot FROM robots WHERE autoanswer>'0' AND status<>'1' ";break;
        case "sql_robots_select3": $s = "SELECT id,status,new_bot,botnet_num,(UNIX_TIMESTAMP(next_time)-UNIX_TIMESTAMP(CURRENT_TIMESTAMP())) as next_time ,friending_time,friending_trigger FROM robots WHERE status='0'  ORDER BY next_time ASC limit 1";break;
        case "sql_robots_select3_1": $s = "SELECT id,status,new_bot,botnet_num, (UNIX_TIMESTAMP(next_time)-UNIX_TIMESTAMP(CURRENT_TIMESTAMP())) as next_time  ,friending_time,friending_trigger FROM robots WHERE status='0' and proxy_problem>0 and UNIX_TIMESTAMP(next_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) and UNIX_TIMESTAMP(last_time)>UNIX_TIMESTAMP(CURRENT_TIMESTAMP())-240*60 ORDER BY next_time ASC limit 1";break;
        case "sql_robots_select3_1_2": $s = "SELECT id,status,new_bot,botnet_num,(-1) as next_time  ,friending_time,friending_trigger FROM robots WHERE status>10 and status<20 ORDER BY next_time ASC limit 1";break;

        case "sql_robots_select4": $s = "SELECT COUNT(*) as kol  FROM robots WHERE UNIX_TIMESTAMP(last_time)>UNIX_TIMESTAMP(CURRENT_TIMESTAMP())";break;
        case "sql_robots_select5": $s = "SELECT proxy FROM robots";break;
        case "sql_robots_select6": $s = "SELECT UNIX_TIMESTAMP(next_time) as next_time FROM robots ORDER BY next_time DESC LIMIT 1";break;
        case "sql_robots_select7": $s = "SELECT vk_id,login,pass,age,sex,target_bot,autoanswer,proxy,user_agent,proxy_problem,invited,invitedplus,anons_id_for_send FROM robots WHERE UNIX_TIMESTAMP(next_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())  AND status='1'   AND  id=:bot_id  LIMIT 1";break;
        case "sql_robots_select7_1": $s = "SELECT vk_id,login,pass,age,sex,target_bot,autoanswer,proxy,user_agent,proxy_problem,invited,invitedplus,anons_id_for_send FROM robots WHERE   status='0'   AND  id=:bot_id  LIMIT 1";break;
        case "sql_robots_select8": $s = "SELECT proxy FROM robots WHERE  remoteip=:remoteip and botnet_num=:botnet_num limit 1";break;
        case "sql_robots_select8_1": $s = "SELECT proxy FROM robots WHERE  remoteip=:remoteip and id<>:bot_id  and botnet_num=:botnet_num limit 1";break;
        case "sql_robots_select8_2": $s = "SELECT proxy FROM robots WHERE  proxy=:proxy and botnet_num=:botnet_num limit 1";break;
        case "sql_robots_select9": $s = "SELECT familia, name, age, user_agent from robots where status ='5'";break;
        case "sql_robots_select10": $s = "SELECT user_agent FROM robots where status >'2' and user_agent<>''";break;
        case "sql_robots_select11": $s = "SELECT id,`status`,UNIX_TIMESTAMP(last_time) as last_time,UNIX_TIMESTAMP(next_time) as next_time,vk_id,friends,invited,name,familia,proxy,proxylogin,botnet_num FROM robots where status>0 and status<10 and sync='1' and last_time<FROM_UNIXTIME(UNIX_TIMESTAMP()-30*60) limit 1";break;
        case "sql_robots_select12": $s = "SELECT id FROM robots where last_time<FROM_UNIXTIME(UNIX_TIMESTAMP()-30*60)  and line<>'0' and status='0' and proxy_problem<:proxy_try_times limit 1";break;
        case "sql_robots_select13": $s = "SELECT COUNT(id) as kol  FROM robots WHERE status='0' ";break;
        case "sql_robots_select14": $s = "SELECT count(id) as kol  FROM robots WHERE botnet_num=:botnet_num and status='0' and friends<:friends_add_while_less_than";break;
        case "sql_robots_select15": $s = "SELECT work_trigger FROM robots WHERE  id=:bot_id  limit 1";break;
        case "sql_robots_select16": $s = "SELECT script_id FROM robots WHERE script_id=:script_id and id=:bot_id  limit 1";break;

        case "sql_robots_update1": $s = "UPDATE robots SET status='1' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update1_1": $s = "UPDATE robots SET status=:status :tk WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update2": $s = "UPDATE robots SET last_time=FROM_UNIXTIME(UNIX_TIMESTAMP()+6*60),line=:line,next_time=if(UNIX_TIMESTAMP()+2*60*60>UNIX_TIMESTAMP(next_time),FROM_UNIXTIME(UNIX_TIMESTAMP()+3*60*60),next_time) WHERE id=:bot_id limit 1";break;
        case "sql_robots_update2_1": $s = "UPDATE robots SET next_time=FROM_UNIXTIME(UNIX_TIMESTAMP()),proxy_problem=proxy_problem+1, line='0',sync='0' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update2_2": $s = "UPDATE robots SET next_time=FROM_UNIXTIME(UNIX_TIMESTAMP()), line='0',sync='0' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update3": $s = "UPDATE robots SET next_time=FROM_UNIXTIME(UNIX_TIMESTAMP()+12*60*60+3*60) WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update3_1": $s = "UPDATE robots SET last_time=FROM_UNIXTIME(UNIX_TIMESTAMP()+:url_timeout),line=:line WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update4": $s = "UPDATE robots SET status='6' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update5": $s = "UPDATE robots SET client_id=:client_id WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update6": $s = "UPDATE robots SET  proxy=:proxy,proxylogin=:proxylogin, remoteip=:remoteip,proxy_problem=0,proxy_eating=proxy_eating+1 WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update6_1": $s = "UPDATE robots SET remoteip=:remoteip WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update7": $s = "UPDATE robots SET proxy_problem=proxy_problem+1,proxy_eating=:proxy_eating WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update7_1": $s = "UPDATE robots SET proxy_problem=:proxy_try_times,proxy_eating=:proxy_eating WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update7_2": $s = "UPDATE robots SET proxy_problem='1',proxy_eating=:proxy_eating WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update8": $s = "UPDATE robots SET proxy_problem='0' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update9": $s = "UPDATE robots SET next_time=FROM_UNIXTIME(:next_time) WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update9_1": $s = "UPDATE robots SET next_time=FROM_UNIXTIME(UNIX_TIMESTAMP(start_time)+13*60*60) WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update10": $s = "UPDATE robots SET friends=:count_my_friends, invited=:count_out_friends+:invited_plus,invitedplus=:invited_plus,invitedplus2=:bot_invitedplus, percent=:bot_percent WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update10_1": $s = "UPDATE robots SET friends=:count_my_friends, invited=:count_out_friends WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update11": $s = "UPDATE robots SET kol_anons_for_send=kol_anons_for_send+30  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update12": $s = "UPDATE robots SET kol_anons_for_send='30'  WHERE id<:bot_id  and status='0' and kol_anons_for_send='0' ORDER BY id DESC limit 1";break;
        case "sql_robots_update13": $s = "UPDATE robots SET all_sent=all_sent+'1'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update14": $s = "UPDATE robots SET anons_id_for_send='0'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update15": $s = "UPDATE robots SET proxy_eating=proxy_eating+'1'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update16": $s = "UPDATE robots SET next_time=:unblock_date WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update17": $s = "UPDATE robots SET vk_id=:bot_vk_id, new_bot='0' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update18": $s = "UPDATE robots SET familia=:bot_familia ,name=:bot_name WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update19": $s = "UPDATE robots SET sync='1' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update20": $s = "UPDATE robots SET sync='0' WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update21": $s = "UPDATE robots SET left_to_add_friends=:i_want_add_friends WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update22": $s = "UPDATE robots SET work_cycle=:work_cycle,left_to_add_friends=:i_want_add_friends,left_to_group_invite=:left_to_group_invite WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update23": $s = "UPDATE robots SET left_to_group_invite=:left_to_group_invite WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update24": $s = "UPDATE robots SET proxy_eating=:proxy_eating  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update26": $s = "UPDATE robots SET first_start_time=CURRENT_TIMESTAMP(),start_time=CURRENT_TIMESTAMP(),last_time=FROM_UNIXTIME(UNIX_TIMESTAMP()+3*60),line=:line  WHERE UNIX_TIMESTAMP(start_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())-12*60*60 and id=:bot_id   limit 1";break;
        case "sql_robots_update27": $s = "UPDATE robots SET proxy_alive=:proxy_alive  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update28": $s = "UPDATE robots SET proxy_alive=:proxy_alive,proxy_eating=:proxy_eating WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update29": $s = "UPDATE robots SET del_out_invites=:del_out_invites  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update31": $s = "UPDATE robots SET start_time=FROM_UNIXTIME(UNIX_TIMESTAMP(next_time)-13*60*60)  WHERE UNIX_TIMESTAMP(next_time)>UNIX_TIMESTAMP(start_time)+13*60*60 and id=:bot_id   limit 1";break;
        case "sql_robots_update32": $s = "UPDATE robots SET work_trigger='1'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update33": $s = "UPDATE robots SET work_trigger='0'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update34": $s = "UPDATE robots SET  friending_trigger= IF(friending_trigger='0',1,friending_trigger-1)  WHERE id=:bot_id   limit 1";break;
        case "sql_robots_update35": $s = "UPDATE robots SET friends_get_all='1'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update36": $s = "UPDATE robots SET friends_get_all='0'  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update37": $s = "UPDATE robots SET cycle_timeout=:cycle4  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update38": $s = "UPDATE robots SET script_id=:script_id  WHERE id=:bot_id  limit 1";break;
        case "sql_robots_update39": $s = "UPDATE robots SET friending_time = FROM_UNIXTIME(UNIX_TIMESTAMP()+3*24*60*60)  WHERE id=:bot_id  limit 1";break;

        case "sql_robots_insert1": $s = "INSERT INTO robots (vk_id,login,pass,user_agent) VALUES :more ";break;
        case "sql_robots_insert2": $s = "INSERT INTO robots (id,botnet_num,login,pass,familia,age,sex,proxy, proxylogin, proxy_problem,status,user_agent,next_time,vk_id,remoteip,friending_time) VALUES :more ";break;
        case "sql_robots_insert3": $s = "INSERT INTO robots (id,botnet_num,vk_id,login,pass,familia,age,sex,proxy, proxylogin, proxy_problem,status,last_time,next_time,user_agent,friending_time,remoteip) VALUES  :more ON DUPLICATE KEY UPDATE vk_id=VALUES(vk_id), friending_time = if(pass<>VALUES(pass) , FROM_UNIXTIME(UNIX_TIMESTAMP()+5*24*60*60),friending_time), botnet_num = VALUES(botnet_num),  login = VALUES(login), pass = VALUES(pass), familia= VALUES(familia), age = VALUES(age), sex = VALUES(sex),  proxy= VALUES(proxy),  proxylogin= VALUES(proxylogin), proxy_problem= VALUES(proxy_problem), status = VALUES(status),  last_time= VALUES(last_time),  next_time= VALUES(next_time),  user_agent= VALUES(user_agent), line=0";break;

        ////////////////
        // botnets_settings
        ///////////////////
        case "sql_botnets_settings_select1": $s = "SELECT * FROM botnets_settings WHERE  botnet=:botnet_num LIMIT 1";break;
        case "sql_botnets_settings_insert1": $s = "INSERT INTO botnets_settings (botnet,country,city,friending_status,friends_reaction_if_nomore,friends_add_speed,my_friends_add_speed,friends_add_live_only,friends_need_for_start_repost,friends_add_while_less_than) VALUES  :more ON DUPLICATE KEY UPDATE botnet = VALUES(botnet), country = VALUES(country), city = VALUES(city), friending_status = VALUES(friending_status),friends_reaction_if_nomore = VALUES(friends_reaction_if_nomore),friends_add_speed = VALUES(friends_add_speed),my_friends_add_speed = VALUES(my_friends_add_speed),  friends_add_live_only= VALUES(friends_add_live_only),friends_need_for_start_repost= VALUES(friends_need_for_start_repost),friends_add_while_less_than= VALUES(friends_add_while_less_than)";break;

        ////////////////////
        //id_grab_male_setting
        ///////////////////
        case "sql_id_grab_male_setting_select1": $s = "SELECT  age, bmonth, bday FROM id_grab_male_setting WHERE id='1'  LIMIT 1";break;
        case "sql_id_grab_male_setting_update1": $s = "UPDATE id_grab_male_setting SET age=:age, bmonth=:birth_month, bday=:birth_day WHERE id='1' limit 1";break;


        ////////////////////
        //id_grab_female_setting
        //////////////////
        case "sql_id_grab_female_setting_select1": $s = "SELECT  age, bmonth, bday FROM id_grab_female_setting  WHERE id='1'  LIMIT 1";break;
        case "sql_id_grab_female_setting_update1": $s = "UPDATE id_grab_female_setting SET age=:age, bmonth=:birth_month, bday=:birth_day WHERE id='1' limit 1";break;

        ////////////////////
        //vk_users
        //////////////////
         case "sql_vk_users_select1": $s = "insert into vk_users_tmp (SELECT vk.vk_id, i.botnet, vk.city, vk.country, i.target_bot  from vk_users vk INNER JOIN  invited i ON i.vk_id = vk.vk_id WHERE  i.botnet=:botnet_num and i.invited_bot='0'  and i.friended_bot='0' AND i.target_bot=:bot_id   :more limit :more3)"; break;
         case "sql_vk_users_select2":  $s = "insert into vk_users_tmp (SELECT vk.vk_id, i.botnet, vk.city, vk.country, i.target_bot  from vk_users vk INNER JOIN  invited i ON i.vk_id = vk.vk_id WHERE  i.botnet=:botnet_num and i.invited_bot='0'  and i.friended_bot='0' AND i.target_bot='0' :more limit :more3)"; break;
         case "sql_vk_users_select2_1": $s = "insert into vk_users_tmp (SELECT vk.vk_id, i.botnet, vk.city, vk.country, '0'           from vk_users vk INNER JOIN  invited i ON i.vk_id = vk.vk_id WHERE  i.botnet=:botnet_num and i.invited_bot='0'  and i.friended_bot='0'  :more limit :more3)"; break;
         case "sql_vk_users_select2_4_3": $s = "insert into vk_users_tmp (SELECT vk_id,:botnet_num,city, country, '0' from vk_users where not EXISTS (SELECT vk_id from invited where vk_users.vk_id=invited.vk_id and invited.botnet=:botnet_num limit 1)  :more  limit :more3)"; break;
         case "sql_vk_users_select3": $s = "SELECT vk_id FROM vk_users WHERE vk_id=:user_for_invite_id AND UNIX_TIMESTAMP(group_invited_date)+30*24*60*60<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())   LIMIT 1"; break;
         case "sql_vk_users_select4": $s = "SELECT sex FROM vk_users WHERE vk_id=:user_id LIMIT 1"; break;
         case "sql_vk_users_select5": $s = "SELECT COUNT(*) as kol FROM vk_users WHERE age=:age"; break;
         case "sql_vk_users_select6": $s = "SELECT vk_id from vk_users where last_anons_num_sent<:bot_anons_id_for_send and (:more) "; break;
         case "sql_vk_users_select7": $s = "SELECT vk_id from vk_users WHERE friended_by_robot_id=:bot_id  and last_anons_num_sent<:bot_anons_id_for_send and do_not_send='0' limit 40"; break;
         case "sql_vk_users_select8": $s = "SELECT vk_id from vk_users where last_anons_num_sent<:bot_anons_id_for_send and (:more)"; break;
         case "sql_vk_users_select9": $s = "SELECT vk_id FROM vk_users WHERE invited_by_robot_id2='0'  and friended_by_robot_id=0 AND target_bot=:bot_id  and online_times=0 limit 2400"; break;
         case "sql_vk_users_select10": $s = "SELECT vk_id FROM vk_users WHERE invited_by_robot_id2='0'  and friended_by_robot_id=0 AND target_bot in (select id from robots where status=0) and online_times=0 ORDER BY RAND() limit 4800"; break;
         case "sql_vk_users_select11": $s = "SELECT vk.vk_id  from vk_users vk INNER JOIN  invited i ON i.vk_id = vk.vk_id  where  i.friended_bot=:bot_id  and not EXISTS (SELECT * from groups_invited where vk.vk_id=groups_invited.user_vk_id and (group_vk_id=:group_vk_id or status='2')) limit " . rand(0, 100) . ",1"; break;

         case "sql_vk_users_insert1": $s = "INSERT IGNORE INTO vk_users (vk_id, sex, can_write_private_message, can_send_friend_request, age) VALUES :more"; break;
         case "sql_vk_users_insert2": $s = "INSERT IGNORE INTO vk_users (vk_id, sex, can_write_private_message, can_send_friend_request, target_bot) VALUES :more"; break;
         case "sql_vk_users_insert3": $s = "INSERT INTO vk_users ( vk_id, sex, can_write_private_message,  age, country,city,online_times) VALUES :more ON DUPLICATE KEY UPDATE  country=VALUES(country),city=VALUES(city), online_times=online_times+1 ";break;

         case "sql_vk_users_delete1": $s = "DELETE FROM vk_users WHERE vk_id=:user_id "; break;

         case "sql_vk_users_update5": $s = "UPDATE vk_users SET group_invited_date = FROM_UNIXTIME(UNIX_TIMESTAMP()) WHERE vk_id=:user_for_invite_id "; break;
         case "sql_vk_users_update9": $s = "UPDATE vk_users SET online_times=online_times+1 WHERE vk_id IN (:more2)"; break;

         ////////////////////
         //vk_users_all
         //////////////////
         case "sql_vk_users_all_insert1": $s = "INSERT INTO vk_users_all ( vk_id, sex, can_write_private_message,  age, target_bot,country,city) VALUES :more2 ON DUPLICATE KEY UPDATE target_bot=:bot_id , country=VALUES(country),city=VALUES(city)";break;


         ////////////////////
         //vk_users_tmp
         //////////////////
         case "sql_vk_users_tmp_select1": $s = "SELECT vk_id FROM vk_users_tmp WHERE botnet=:botnet_num AND target_bot=:bot_id  :more  LIMIT 1"; break;
         case "sql_vk_users_tmp_select2": $s = "SELECT vk_id FROM vk_users_tmp WHERE botnet=:botnet_num AND target_bot='0'       :more  ORDER BY RAND() LIMIT 1"; break;

         case "sql_vk_users_tmp_delete1": $s = "DELETE FROM vk_users_tmp WHERE vk_id=:user_id "; break;

         ////////////////////
         //invited
         //////////////////
         case "sql_invited_insert1": $s = "INSERT INTO invited  ( vk_id,botnet,invited_bot)  VALUES (:user_id,:botnet_num,:bot_id ) ON DUPLICATE KEY UPDATE invited_bot=:bot_id "; break;
         case "sql_invited_insert2": $s = "INSERT INTO invited  ( vk_id,botnet,friended_bot) VALUES :more2 ON DUPLICATE KEY UPDATE friended_bot=:bot_id  ";break;
         case "sql_invited_insert3": $s = "INSERT INTO invited  ( vk_id,botnet,target_bot) VALUES :more3 ON DUPLICATE KEY UPDATE target_bot=:bot_id  ";break;

         case "sql_invited_update1": $s = "UPDATE invited SET invited_bot='0'  WHERE invited_bot IN (:more2)"; break;
         case "sql_invited_update2": $s = "UPDATE invited SET friended_bot='0' WHERE friended_bot IN (:more2)"; break;
         case "sql_invited_update3": $s = "UPDATE invited SET target_bot='0'   WHERE target_bot IN (:more2)"; break;
         case "sql_invited_update4": $s = "UPDATE invited SET invited_bot='0'  where friended_bot='0' and invited_bot>'0' and invited_bot<>target_bot and botnet=:botnet_num"; break;
         case "sql_invited_update5": $s = "UPDATE invited SET invited_bot='0', target_bot='0' where friended_bot='0' and invited_bot>'0' and invited_bot=target_bot and botnet=:botnet_num"; break;

         ////////////////////
         //groups
         //////////////////
         case "sql_groups_insert1": $s = "INSERT INTO groups  ( id,botnet,group_id,group_name,group_screen_name,repost_status,repost_deep,repost_veroiat,invite_status,invite_kol,join_status)  VALUES :more ON DUPLICATE KEY UPDATE group_name=VALUES(group_name),group_screen_name=VALUES(group_screen_name),repost_status=VALUES(repost_status),repost_deep=VALUES(repost_deep),repost_veroiat=VALUES(repost_veroiat),invite_status=VALUES(invite_status),invite_kol=VALUES(invite_kol),join_status=VALUES(join_status)"; break;

         case "sql_groups_select1": $s = "SELECT * FROM groups where repost_status>0 and repost_veroiat>0 and botnet=:botnet_num order by rand() limit 10"; break;
         case "sql_groups_select2": $s = "SELECT * FROM groups where invite_status>0 and invite_kol>0 and botnet=:botnet_num order by rand() limit 20"; break;
         case "sql_groups_select3": $s = "SELECT group_id FROM groups where join_status>0 and botnet=:botnet_num and not EXISTS (SELECT id from groups_bots_joined where groups.group_id=groups_bots_joined.group_vk_id and bot_id=:bot_id  limit 1)  limit 20"; break;
         case "sql_groups_select4": $s = "SELECT invite_status,all_invites,success_invites FROM groups where group_id=:group_vk_id and botnet=:botnet_num limit 1"; break;

         case "sql_groups_update1": $s = "update groups set all_invites=(SELECT count(group_vk_id) from groups_invited where group_vk_id=:group_vk_id and status<'3') where group_id=:group_vk_id limit 1"; break;
         case "sql_groups_update2": $s = "update groups set success_invites=(SELECT count(group_vk_id) from groups_invited where group_vk_id=:group_vk_id and status<'2') where group_id=:group_vk_id limit 1"; break;
         case "sql_groups_update3": $s = "update groups set invite_status=0 where group_id=:group_vk_id limit 1"; break;

         case "sql_groups_delete1": $s = "DELETE FROM groups WHERE id IN(:more2)"; break;


          ////////////////////
          //groups_ignore
          //////////////////
         case "sql_groups_ignore_select1": $s = "SELECT group_vk_id FROM groups_ignore WHERE bot_vk_id=:bot_vk_id"; break;


         ////////////////////
         //groups_invite_wait
         //////////////////
         case "sql_groups_invite_wait_select1": $s = "select * from groups_invite_wait where bot_id=:bot_id  and group_vk_id=:group_vk_id limit 1"; break;
         case "sql_groups_invite_wait_insert1": $s = "INSERT INTO groups_invite_wait ( bot_id, group_vk_id,user_vk_id,hash) VALUES :more ON DUPLICATE KEY UPDATE  bot_id=:bot_id "; break;
         case "sql_groups_invite_wait_delete1": $s = "DELETE FROM groups_invite_wait WHERE user_vk_id=:user_for_invite_id and group_vk_id=:group_vk_id"; break;

          ////////////////////
          //groups_invited
          //////////////////
         case "sql_groups_invited_select1": $s = "SELECT user_vk_id FROM groups_invited WHERE group_vk_id=:group_vk_id AND bot_vk_id=:bot_vk_id"; break;
         case "sql_groups_invited_select2": $s = "SELECT user_vk_id FROM groups_invited WHERE (group_vk_id=:group_vk_id AND user_vk_id=:user_for_invite_id) OR (user_vk_id=:user_for_invite_id AND status='2')"; break;

         case "sql_groups_invited_insert1": $s = "INSERT INTO groups_invited (group_vk_id, user_vk_id, status) VALUES (:group_vk_id,:user_for_invite_id,':user_group_invite_status') ON DUPLICATE KEY UPDATE status=VALUES(status) "; break;

         ////////////////////
         //groups_bots_joined
         //////////////////
         case "sql_groups_bots_joined_select1": $s = "SELECT id FROM groups_bots_joined WHERE group_vk_id=:group_vk_id AND bot_id=:bot_id "; break;
         case "sql_groups_bots_joined_insert1": $s = "INSERT INTO groups_bots_joined  ( bot_id, group_vk_id)  VALUES (:bot_id ,:group_vk_id) ON DUPLICATE KEY UPDATE bot_id=VALUES(bot_id),group_vk_id=VALUES(group_vk_id)"; break;

         //////////////////
         // база учета репостов
         //////////////////////////
         case "sql_reposted_select1": $s = "SELECT post_id FROM reposted WHERE bot_id=:bot_id  and group_id=:group_vk_id and post_id=:post_id "; break;
         case "sql_reposted_insert1": $s = "INSERT INTO reposted ( bot_id, group_id,post_id,date)  VALUES (:bot_id ,:group_vk_id,:post_id,CURRENT_TIMESTAMP())  ON DUPLICATE KEY UPDATE bot_id=VALUES(bot_id),group_id=VALUES(group_id),post_id=VALUES(post_id)"; break;
         case "sql_reposted_insert2": $s = "INSERT INTO reposted ( bot_id, group_id,post_id,date)  VALUES (:bot_id ,'51699403','192',CURRENT_TIMESTAMP()) ON DUPLICATE KEY UPDATE bot_id=VALUES(bot_id),group_id=VALUES(group_id),post_id=VALUES(post_id), date=VALUES(date)"; break;



        ////////////////////
         //anons
         //////////////////
         case "sql_anons_select1": $s = "select SUBSTRING(MAX(CONCAT(TRUNCATE(RAND(),4),text)),7) AS text FROM anons where anons_num=:bot_anons_id_for_send GROUP BY phrase_id"; break;

         ////////////////////
         //anons_suffix
         //////////////////
         case "sql_anons_suffix_select1": $s = "select SUBSTRING(MAX(CONCAT(TRUNCATE(RAND(),4),text)),7) AS text FROM anons_suffix where anons_num=:suffix_num GROUP BY phrase_id"; break;



          //////////////////////////////////////////////////////
          // общение с ботом
          //////////////////////////////////////////////////////

          ////////////////////
          //message_in
          //////////////////
         case "sql_message_in_select1": $s = "SELECT id,answer_id FROM message_in WHERE text=:chel_mes LIMIT 1"; break;
         case "sql_message_in_insert1": $s = "INSERT INTO message_in ( text,popularity) VALUES(:chel_mes,'1')";break;
         case "sql_message_in_update1": $s = "UPDATE message_in SET popularity=popularity+1 WHERE id=:mess_in_id LIMIT 1"; break;


         ////////////////////
         //answer
         //////////////////
         case "sql_answer_select1": $s = "SELECT text FROM answer WHERE answer_id=:mess_in_answer_id ORDER BY RAND() LIMIT 1"; break;

         ////////////////////
         //chat_quest
         //////////////////
         case "sql_chat_quest_select1": $s = "SELECT * FROM :chat_quest WHERE quest=:chel_in  AND sex=:chel_sex"; break;
         case "sql_chat_quest_select2": $s = "SELECT * FROM :chat_quest WHERE quest=:chel_mes AND sex=:chel_sex AND to_sex=:bot_sex"; break;
         case "sql_chat_quest_select3": $s = "SELECT * FROM :chat_quest WHERE quest=:bot_mes  AND sex=:bot_sex  AND to_sex=:chel_sex"; break;
         case "sql_chat_quest_select4": $s = "SELECT * FROM :chat_quest WHERE sootv='0' AND sex=:bot_sex AND to_sex=:chel_sex AND id<>:prev_id2"; break;
         case "sql_chat_quest_select5": $s = "SELECT * FROM :chat_quest WHERE sex=:bot_sex AND to_sex=:chel_sex AND id<>:prev_id2"; break;
         case "sql_chat_quest_select6": $s = "SELECT * FROM :chat_quest WHERE id=:best_res2 and sootv='0' and sex=:bot_sex AND to_sex=:chel_sex"; break;
         case "sql_chat_quest_select7": $s = "SELECT * FROM :chat_quest WHERE id=:ord_id"; break;
         case "sql_chat_quest_select8": $s = "SELECT * FROM :chat_quest WHERE id=:best_res"; break;
         case "sql_chat_quest_select9": $s = "SELECT id FROM :chat_quest WHERE quest=:chel_mes_prev AND sex=:chel_sex"; break;

         case "sql_chat_quest_insert1": $s = "insert into :chat_quest values('',:chel_mes,'0',:chel_sex,:bot_sex)"; break;
         case "sql_chat_quest_insert2": $s = "insert into :chat_quest values('',:chel_mes_prev,'0',:chel_sex,:bot_sex)"; break;

         case "sql_chat_quest_update1": $s = "UPDATE :chat_quest SET sootv='1' WHERE quest=:bot_mes AND sex=:bot_sex AND to_sex=:chel_sex"; break;

         ////////////////////
         //chat_sign
         //////////////////
         case "sql_chat_sign_select1": $s = "SELECT * FROM :chat_sign WHERE sign=:csign and id=:id and prev_id=:prev_id_in AND sex=:bot_sex AND prev_sex=:chel_sex"; break;
         case "sql_chat_sign_select2": $s = "SELECT * FROM :chat_sign WHERE sign=:csign and id=:id and prev_id='0' AND sex=:bot_sex"; break;
         case "sql_chat_sign_select3": $s = "SELECT * FROM :chat_sign WHERE sign=:csign and prev_id=:prev_id AND prev_sex=:bot_sex AND sex=:chel_sex AND id<>:prev_id2"; break;
         case "sql_chat_sign_select3_1": $s = "SELECT * FROM :chat_sign WHERE sign=:csign AND sex=:chel_sex AND prev_sex=:bot_sex"; break;
         case "sql_chat_sign_select4": $s = "SELECT * FROM :chat_sign WHERE sign=:csign AND sex=:chel_sex AND prev_sex=:bot_sex AND id<>:prev_id2 "; break;
         case "sql_chat_sign_select5": $s = "SELECT * FROM :chat_sign WHERE sign=:item AND sex=:chel_sex  AND prev_sex=:bot_sex AND id<>:prev_id2"; break;
         case "sql_chat_sign_select6": $s = "SELECT * FROM :chat_sign WHERE sign=:item and prev_id=:prev_id AND prev_sex=:bot_sex AND sex=:chel_sex AND id<>:prev_id2"; break;

         case "sql_chat_sign_insert1": $s = "insert into :chat_sign values(:csign,:csign_full,:id,:prev_id_in,:bot_sex,:chel_sex)"; break;
         case "sql_chat_sign_update1": $s = "UPDATE :chat_sign SET prev_id=:prev_id_in WHERE sign=:csign and id=:id and prev_id='0' AND sex=:bot_sex AND prev_sex=:chel_sex"; break;


         ////////////////////
         //chat_dop
         //////////////////
         case "sql_chat_dop_select1": $s="SELECT * FROM chat_dop"; break;


         // призанки запущенности срикпта
         /////////////////////
         //script_in_use
         //////////////////////

         case "sql_script_in_use_select1": $s="SELECT * FROM script_in_use WHERE script=:sname and UNIX_TIMESTAMP(last_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())"; break;
         case "sql_script_in_use_select1_1": $s="SELECT * FROM script_in_use WHERE script='proxyaddauto' and (UNIX_TIMESTAMP(last_time)+5*60*60)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())"; break;
         case "sql_script_in_use_select1_2": $s="SELECT * FROM script_in_use WHERE script='proxyaddauto' and (UNIX_TIMESTAMP(last_time)+2*60*60)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())"; break;
         case "sql_script_in_use_select2": $s="SELECT * FROM script_in_use WHERE script=:sname limit 1 "; break;
         case "sql_script_in_use_select3": $s="SELECT stopme FROM script_in_use WHERE script=:sname limit 1 "; break;
         case "sql_script_in_use_select4": $s="SELECT UNIX_TIMESTAMP(last_time)-UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) as next_time FROM script_in_use WHERE script='vkbot' limit 1 "; break;

         case "sql_script_in_use_update1": $s="INSERT INTO script_in_use (script, last_time) VALUES (:sname, FROM_UNIXTIME(UNIX_TIMESTAMP()+10*60)) ON DUPLICATE KEY UPDATE last_time=FROM_UNIXTIME(UNIX_TIMESTAMP()+10*60)"; break;
         case "sql_script_in_use_update2": $s="INSERT INTO script_in_use (script, last_time) VALUES (:sname, FROM_UNIXTIME(UNIX_TIMESTAMP()+:sec)) ON DUPLICATE KEY UPDATE last_time=VALUES(last_time)"; break;
         case "sql_script_in_use_update2_1": $s="UPDATE script_in_use set last_time=FROM_UNIXTIME(UNIX_TIMESTAMP()+:sec) WHERE script=:sname AND UNIX_TIMESTAMP(last_time)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP())"; break;
         case "sql_script_in_use_update3": $s="UPDATE script_in_use SET stopme='0' WHERE script=:sname limit 1"; break;

         default : // обработка ошибки
             $this->Log->save("ErrMySQL_","неверный запрос $s из скрипта ".debug_backtrace()[0]["script"]." строки ".debug_backtrace()[0]["line"]);
    }
   return $s;
  }

  // функция исполнения запроса
  private function execute(string $query, array $data=[])
 {
     $from=debug_backtrace();
     $from_file=$from[array_key_last($from)]["file"];
     $from_file_line=$from[array_key_last($from)]["line"];
     try
     {
      $all=[];

      $STH = $this->DBH->prepare($query);
      $STH->execute($data);

      # устанавливаем ассоциативный режим выборки
      $STH->setFetchMode(PDO::FETCH_ASSOC);
      while($res=$STH->fetch()) array_push($all,$res);
      return $all;
     }
    catch(PDOException $e)
    {
    $this->Log->save("ErrMySQL_",$e->getMessage()."\r\n вызов из файла: \r\n ".$from_file."\r\n из строки ".$from_file_line);
    die;
    }
 }
}


