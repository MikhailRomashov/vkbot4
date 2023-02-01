<?php
/*
 Список кодов ответов при входе
 'code' => 1,  'msg' => "connect_error"
 'code' => 2,  'msg' => "login_error"
 'code' => 3,  'msg' => "change_phone"
 'code' => 6,  'msg' => "bad_browser"
 'code' => 7,  'msg' => "wrong login/password"
 'code' => 8,  'msg' => "restore"
 'code' => 9,  'msg' => "bad_proxy"

 ошибки парсинга
 'code' => 10, 'msg' => "string_not_found"
 'code' => 11, 'msg' => 'bad_html'
 'code' => 12, 'msg' => 'no_entry_start'
 'code' => 13, 'msg' => 'no_entry_stop'
 'code' => 14, 'msg' => 'parsing_error'
 'code' => 15, 'msg' => 'looping_error'
 'code' => 19, 'msg' => 'parsing_success'

 коды ответов при запросах дружбы
 'code' => 20, 'msg' => "success_friended"
 'code' => 21, 'msg' => "already_friended"

 коды ответов при обработке стены группы
 'code' => 30, 'msg' => "success_get_wall"
 'code' => 31, 'msg' => "not_complete_get_wall"

 коды ответов при работе со списками друзей
 'code' => 40, 'msg' => "friends_kol_success"
 'code' => 41, 'msg' => 'friend_not_found'
 'code' => 42, 'msg' => 'incoming_friend_requsts_not_found'
 'code' => 43, 'msg' => 'wrong_vk_id'
 'code' => 44, 'msg' => 'wrong_hash'
 'code' => 45, 'msg' => 'success_decline'

  коды ответов при репостах
 'code' => 50, 'msg' => "success_repost"

 коды ответов при поиске
 'code' => 60, 'msg' => "search_success"

  коды ответов при работе с группами
 'code' => 70, 'msg' => "search_success"
 'code' => 71, 'msg' => "can`t_invite"
 'code' => 72, 'msg' => "invite_success"
 'code' => 73, 'msg' => "invite_alredy"
 'code' => 74, 'msg' => "invite_forbidden"
 'code' => 75, 'msg' => "day_limit"

  коды ответов при фомировании списка входящих сообщений
 'code' => 80, 'msg' => "messagelist_get_success"
*/



class VK_login
{



    // User-Agent Constants.
    const USER_AGENT_LOCALE = 'en_US'; // "language_COUNTRY".

    // HTTP Protocol Constants.
    const ACCEPT_LANGUAGE = 'en-US'; // "language-COUNTRY".
    const ACCEPT_ENCODING = 'gzip, deflate, sdch';
    const CONTENT_TYPE = 'application/x-www-form-urlencoded; charset=UTF-8';

    public $Log;
    public $initOK;
    public $user;




    public function __construct($bot_id = null, $bot_vk_id = null, $client_id = null, $login = null, $pass = null)
    {
        if (!$login || !$pass) {
            file_put_contents("errlog" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ошибка передачи параметров при инициализации класса Instagram для бота " . $this->bot_id . ". login: $login, pass: $pass. строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            return false;
        }
        $this->login = $login;
        $this->pass = $pass;

        $this->bot_id = $bot_id;
        $this->bot_vk_id = $bot_vk_id;
        $this->client_id = $client_id;
        $this->user = array('username' => $login, 'password' => $pass);

        //нет ошибок. класс может существовать
        $this->initOK = true;

        $this->lastPageHtml = '';
        $this->MobileVersion = false;


        // создаем экземпляр класса логирования
        $this->Log      =   new LogClass($bot_id,__FILE__);

    }


    function get_token($next_time, $unblock_date, $proxy_alive, $url_timeout, $trying_time)
    {
        global $client_id;
//require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Http.php';
//$proxy="";
        //$http= new HttpTools();


        //Необходимые приложению права
        //$scope = "notify,friends,photos,audio,video,docs,notes,pages,status,offers,questions,wall,groups,mail,messages,notifications,stats,ads,offline,nohttps";
        //$scope = 'messages,friends,wall,groups';
        $scope = 'friends,wall,groups,offline';
        // if($this->bot_id==9) $scope = 'messages,friends,wall,groups,offline';

        $callback = '&redirect_uri=http://oauth.vk.com/blank.html';
        $cl_id_num = 50; // количество попытко подбора нового client_id


        $trying_time = 1;
        s137:

        do {
            //Первый запрос
            $url = 'authorize?';
            $url .= 'client_id=' . $this->client_id;
            $url .= '&scope=' . $scope;
            $url .= $callback;
            $url .= '&display=mobile';
            $url .= '&v=5.73';
            $url .= '&response_type=token';
            $url .= '&revoke=1';
            //https://oauth.vk.com/authorize?client_id=5681624&&scope=messages,friends,wall,groups&&redirect_uri=http://oauth.vk.com/blank.html&display=mobile&v=5.24&response_type=token&revoke=1

            // ответить что бот работает поставивь timestamp+ 3мин
            $res = sql_query("sql_robots_update2", __LINE__);

            $html = $this->httpCall($url, array('postdata' => ''), array('ssl' => true, 'httpPrefix' => "oauth.", 'dump' => false));


            $url1 = $url;
            $html1 = "заголовок:\r\n" . var_export($html[header], TRUE) . "\r\n\r\n body \r\n$html[html]";

            file_put_contents("dump1_$this->bot_id.txt", date("d.m.Y H:i:s") . "vkbot_v4_class.php строка " . __LINE__ . "\r\n\r\n $url1 \r\n\r\n" . $html1, FILE_APPEND | LOCK_EX);
            //die;

            //https://oauth.vk.com/authorize?client_id=1232233&redirect_uri=http%3A%2F%2Foauth.vk.com%2Fblank.html&response_type=token&scope=335874&v=5.73&state=&revoke=1&display=mobile&slogin_h=56f63ef4f26fd70a07.6faf0a758689c7bce2&__q_hash=74321ab6bb4a4e87197e02fb48aebb76

            // ответить что бот работает поставивь timestamp+ 3мин
            sql_query("sql_robots_update2", __LINE__);

            // проверяем актуален ли client_id
            $res = json_decode($html[html], true);
            $err = $res['error'];
            $err_desc = $res['error_description'];

            // проверяем корректность ответа прокси
            $proxy_ok = $this->parser->parseStr($html['header'], 'HTTP', 'OK');


            // проверка на отсутствие свзи или плохъой прокси
            if ($err['error_msg'] == "connect_error") {
                // ошибка получения данных
                waiting(__LINE__);
                // file_put_contents("dump1_$this->bot_id.txt","\r\n\r\n $url1 \r\n\r\n".$html1."\r\n=================================================\r\n",FILE_APPEND | LOCK_EX);
            } else {
                $trying_time = 1;// обнуление таймаута чтобы ошибка подбора client_id не накапливала ошибку таймаута

                if ($err) {
                    // если любая другая ошибка
                    if ($err != 'HTTP/1.1 401 Unauthorized' && $err != 'invalid_request' && $err_desc != 'application is blocked') {


                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ошибка прокси . требует авторизации. меняем прокси и перезапускаем.  строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);

                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ошибка прокси или связи. таймаут: $url_timeout \r\n $url\r\n\r\n =".var_export($err, TRUE)."= строка ".__LINE__."\r\n==========================\r\n",FILE_APPEND | LOCK_EX);
                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ошибка прокси или связи. таймаут: $url_timeout \r\n $url\r\n\r\n res=$res= строка ".__LINE__."\r\n==========================\r\n",FILE_APPEND | LOCK_EX);
                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ошибка прокси или связи. таймаут: $url_timeout \r\n $url\r\n\r\n err=$err= строка ".__LINE__."\r\n==========================\r\n",FILE_APPEND | LOCK_EX);

                        // ставим признак мертвой прокси
                        file_put_contents("log$this->bot_id.txt", date("d.m.Y H:i:s") . " $proxy \r\n" . var_export($res, TRUE) . "\r\n строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                        return array("status" => "reboot", "time" => 100, "line" => __LINE__); // перезапуск скрипта сувеличение признака проблемной прокси

                    }


                    // генерируем новый client_id от 1 000 000 до 6 000 000
                    $client_id = rand(1000000, 6000000);
                    $this->client_id = $client_id;


                    // уменьшаем счетчик защиты от зависания
                    $cl_id_num--;
                    file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "vkbot_v4_class.php строка " . __LINE__ . " $cl_id_num client_id:" . $this->client_id . " \r\n", FILE_APPEND | LOCK_EX);
                    // если достигут предел перебора логируем ошибку и останавливаемся
                    if ($cl_id_num == 0) {
                        file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "vkbot_v4_class.php строка " . __LINE__ . ". ошибка подбора client_id.\r\n", FILE_APPEND | LOCK_EX);
                        die;
                    }
                }


            }
            // возвращаемся на генерацию ссылки если есть ошибка
        } while ($err || false === $html[html] || '' === $html[html] || $err === "");


        // если был сгенерирован НОВЫЙ client_ id сохраняем его
        if ($cl_id_num < 50) {
            // сохраняем сгенерированый client_id
            sql_query("sql_robots_update5", __LINE__);
        }


        $captcha_num = 3; // счетчик капчи против зависания
        s1686:
        $_origin = $this->parser->parseStr($html[html], '<input type="hidden" name="_origin" value="', '">');
        $ip_h = $this->parser->parseStr($html[html], '<input type="hidden" name="ip_h" value="', '" />');
        $lg_domain_h = $this->parser->parseStr($html[html], '<input type="hidden" name="lg_domain_h" value="', '" />');
        $to = $this->parser->parseStr($html[html], '<input type="hidden" name="to" value="', '">');


        // проверка на случай непрогрузки страницы
        if (!$_origin[html] || !$ip_h[html] || !$lg_domain_h[html] || !$to[html]) {
            //делаем проверку на авторизацую по куки
            $grant = $this->parser->parseStr($html[html], 'action="https://login.vk.com/?act', 'grant_access');

            if ($grant[status]) goto  s420;

            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "строка " . __LINE__ . ".параметр _origin:$_origin, параметр lg_domain_h:$lg_domain_h \r\n", FILE_APPEND | LOCK_EX);
            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "строка " . __LINE__ . ".параметр ip_h:$ip_h, параметр to:$to \r\n", FILE_APPEND | LOCK_EX);
            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "строка " . __LINE__ . "\r\n\r\n $url1 \r\n\r\n" . $html1 . "\r\n\r\n", FILE_APPEND | LOCK_EX);
            die;
            waiting(__LINE__);// надо нахер все переделать
            goto  s137;

        }


        // ответить что бот работает поставивь timestamp+ 3мин
        $res = sql_query("sql_robots_update2", __LINE__);

        //Второй запрос
        $url2 = '?act=login&soft=1&utf8=1';
        $param[ip_h] = $ip_h[html];
        $param[lg_domain_h] = $lg_domain_h[html];
        $param[to] = $to[html];
        $param[_origin] = 'https%3A%2F%2Foauth.vk.com';
        $param[email] = $this->login;
        $param[pass] = $this->pass;

        // если повторная авторизация с капчей
        if ($captcha_sid) {
            if ($captcha_key) {
                $param[captcha_sid] = $captcha_sid;
                $param[captcha_key] = $captcha_key;
            } else {
                file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". ошибка получения  капчи. проверить captcha_url:$captcha_url, captcha_sid:$captcha_sid, captcha_key:$captcha_key.  строка:" . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                die;
            }
        }
        //  echo $param."<br>";

// ответить что бот работает поставивь timestamp+ 3мин
        $res = sql_query("sql_robots_update2", __LINE__);


        $trying_time = 1;
        do {
            $html = $this->httpCall($url2, array('postdata' => $param), array('ssl' => true, 'httpPrefix' => "login.", 'dump' => false));

            $url2 = $url2 . "\r\n\r\n параметры :" . var_export($param, TRUE) . "\r\n\r\n";

            $html2 = "заголовок:\r\n" . var_export($html[header], TRUE) . "\r\n\r\n body \r\n." . $html[html];

            file_put_contents("dump2_$this->bot_id.txt", date("d.m.Y H:i:s") . " vkbot_v4_class.php строка " . __LINE__ . "\r\n\r\n $url2 \r\n\r\n" . $html2, FILE_APPEND | LOCK_EX);

            $res = json_decode($html[html], true);
            $err = $res['error'];

            // проверка на отсутствие свзи или плохъой прокси
            if ($err['error_msg'] == "connect_error") {
                // ошибка получения данных
                waiting(__LINE__);
            } else {
                // иная непредвиденная ошибка
                if ($err) {
                    // вероятная проблема с прокси. ставим признак проблемы с прокси
                    // var_dump($err);
                    // file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")."vkbot_v4_class.php строка ".__LINE__.". непредвиденная ошибка авторизации ".var_export($err, TRUE)." строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")."vkbot_v4_class.php строка ".__LINE__.". $url2 параметры $param строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);

                    // помечаем в таблице о смене прокси
                    file_put_contents("log$this->bot_id.txt", date("d.m.Y H:i:s") . " $proxy \r\n" . var_export($res, TRUE) . "\r\n строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                    reboot(100, __LINE__); // перезапуск скрипта со сменой прокси
                    die;
                }
            }
        } while ($err || false === $html[html] || '' === $html[html] || $err === "");

        // ставим признак того что прокси жива и её ip засветился на сервере вк
        $proxy_alive = 1;
        sql_query("sql_robots_update27", __LINE__);
        s1636:


// проверка на проблему авторизации
        $access_denied = $this->parser->parseStr($html[html], 'Invalid login', 'password');
        $access_denied2 = $this->parser->parseStr($html[html], 'service_msg_warning">', '</div>');

        $captcha_err = $this->parser->parseStr($html[html], 'CAPTCHA', '</div>');
        $captcha_err_rus = $this->parser->parseStr($html[html], iconv("windows-1251", "utf-8", 'Код'), iconv("windows-1251", "utf-8", 'неверно'));


// выделить новую капчу.
        $captcha_url2 = $this->parser->parseStr($html[html], '<img id="captcha" alt="" src="', '" class="captcha_img" />');
        $captcha_sid2 = $this->parser->parseStr($html[html], '<input type="hidden" name="captcha_sid" value="', '" />');


//если ошибка ввода капчи
        if ($captcha_err[status] || $captcha_err_rus[status] || ($captcha_url2[status] && $access_denied2[status])) {
            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " vkbot_v4_class.php строка " . __LINE__ . " ошибка распознания капчи " . $captcha_url[html] . " \r\n" . $captcha_key[html] . " \r\n" . $captcha_id[html] . $captcha_url2[html] . $captcha_err[html] . $captcha_err_rus[html] . "\r\n" . $access_denied2[html] . "\r\n", FILE_APPEND | LOCK_EX);
            die;
            reportbad($captcha_id); // шлем отчет серверу рекапчи о плохом распозовании
            // переходим на повторную авторизацию.
            goto s438;

        }

        if ($access_denied[status] || $access_denied2[status] || $location_denied_bool) {


            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " ошибка авторизации. location=$location .location_denied_bool=$location_denied_bool, access_denied=$access_denied, access_denied2=$access_denied2. строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            file_put_contents("dump1_$this->bot_id.txt", "$url1 \r\n\r\n $html1", FILE_APPEND | LOCK_EX);
            file_put_contents("dump2_$this->bot_id.txt", "$url2 \r\n\r\n $html2", FILE_APPEND | LOCK_EX);
            file_put_contents("dump3_$this->bot_id.txt", "$url3 \r\n\r\n $html3", FILE_APPEND | LOCK_EX);
            file_put_contents("dump31_$this->bot_id.txt", "$url31 \r\n\r\n $html31", FILE_APPEND | LOCK_EX);
            file_put_contents("dump32_$this->bot_id.txt", "$url32 \r\n\r\n $html32", FILE_APPEND | LOCK_EX);

            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " ошибка авторизации. строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            die;

            $access_denied_date = $this->parser->parseStr($html[html], iconv("windows-1251", "utf-8", 'заморожена до <b>'), '</b>');

            if ($access_denied_date[html]) {
                // замена слова "сегодня"
                $access_denied_date = str_replace(iconv("windows-1251", "utf-8", 'сегодня,'), date("d.m.y"), $access_denied_date[html]);

                // замена слова "завтра"
                $access_denied_date = str_replace(iconv("windows-1251", "utf-8", 'завтра,'), date("d.m.y", time() + 86400), $access_denied_date[html]);

                file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " ошибка авторизации. access_denied_date=" . $access_denied_date[html] . " строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                $dateb = DateTime::createFromFormat('d.m.y H:i', $access_denied_date[html]);
                if ($dateb) {//file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")." ошибка авторизации. строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    $unblock_date = $dateb->format('Y-m-d H:i:s');
                    // file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")." ошибка авторизации. строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    file_put_contents("dump4_$this->bot_id.txt", "$url4 \r\n\r\n $unblock_date", FILE_APPEND | LOCK_EX);
                    sql_query("sql_robots_update16", __LINE__);
                }
            }


            return false;
        }

        // еСЛИ НОВЫЙ бот
        if ($this->bot_vk_id < 10000000) {
            $value = $this->parser->parseStr($html[html], 'window.vk = {"id":', ',"__debug');

            // заносим в базу его id
            $this->bot_vk_id = $value;
            sql_query("sql_robots_update17", __LINE__);

        }
        s420:
        // ответить что бот работает поставивь timestamp+ 3мин
        $res = sql_query("sql_robots_update2", __LINE__);

        // стандартное подтверждение
        $confirmUrl1 = $this->parser->parseStr($html[html], '<form method="post" action="', '">');
        $confirmUrl = $confirmUrl1[html];

        // если конфирмурл https://login.vk.com/?act=login&soft=1&utf8=1 то требуется повторная авторизация

        if ($confirmUrl == "https://login.vk.com/?act=login&soft=1&utf8=1") {
            // проверка ошибки зацикливания при распознании капчи
            if ($captcha_num == 0) {
                file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". зацикливание при распознании капчи при авторизации. проверить " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                die;
            }
            s438:
            // уменьшаем счетчик защиты от зацикливания
            $captcha_num--;

            // выделить капчу.
            $captcha_url = $this->parser->parseStr($html[html], '<img id="captcha" alt="" src="', '" class="captcha_img" />');
            $captcha_sid = $this->parser->parseStr($html[html], '<input type="hidden" name="captcha_sid" value="', '" />');

            $captcha_url = $captcha_url[html];
            $captcha_sid = $captcha_sid[html];

            // послать запрос на распознание капчи
            if ($captcha_url) {
                for ($cap_i = 0; $cap_i < 3; $cap_i++) {

                    $res_cap = recognize($captcha_url);
                    $captcha_key = $res_cap[0];
                    $captcha_id = $res_cap[1];

                    if ($captcha_key) break; // прерываем цикл получени капчи если капча получен

                    // если капча не получена три раза стоп
                    if ($cap_i == 2) {
                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". ошибка трехкратного получения капчи. перезапуск. captcha_url:$captcha_url, captcha_sid:$captcha_sid строка:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                        // ответить что бот работает поставивь timestamp+ 3мин
                        $res = sql_query("sql_robots_update2", __LINE__);

                        // скорее всего проблема с прокси. ставим признак
                        reboot(1); // перезапуск скрипта сувеличение признака проблемной прокси

                    }
                }
            } else {
                /*
                                  if($this->bot_id==216)
                                  {
                                         file_put_contents("dump1_$this->bot_id.txt","$url1 \r\n\r\n $html1",FILE_APPEND | LOCK_EX);
                                        file_put_contents("dump2_$this->bot_id.txt","$url2 \r\n\r\n $html2",FILE_APPEND | LOCK_EX);
                                        file_put_contents("dump3_$this->bot_id.txt","$url3 \r\n\r\n $html3",FILE_APPEND | LOCK_EX);
                                        file_put_contents("dump31_$this->bot_id.txt","$url31 \r\n\r\n $html31",FILE_APPEND | LOCK_EX);
                                       file_put_contents("dump32_$this->bot_id.txt","$url32 \r\n\r\n $html32",FILE_APPEND | LOCK_EX);
                                       file_put_contents("dumpCAP_$this->bot_id.txt","$html",FILE_APPEND | LOCK_EX);
                                       file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". непонятная ошибка получения урл капчи при входе. перезапуск. строка " .__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                                  die;
                                  }
                */
                // скорее всего проблема с прокси. ставим признак
                reboot(1); // перезапуск скрипта сувеличение признака проблемной прокси

                //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". ошибка получения урл капчи при входе. перезапуск. строка " .__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                die;
            }

            //          echo $captcha_url."<br>";
            //          echo "captcha_key=$captcha_key, captcha_sid=$captcha_sid";
            // die;

            // вернуться на этап авторизации
            goto s1686;
        }

        // ответить что бот работает поставивь timestamp+ 3мин
        $res = sql_query("sql_robots_update2", __LINE__);


        if (!$confirmUrl) {
            file_put_contents("dump2_$this->bot_id.txt", date("d.m.Y H:i:s") . " vkbot_v4_class.php строка " . __LINE__ . "\r\n\r\n $url2 \r\n\r\n" . $html2, FILE_APPEND | LOCK_EX);

            die;
            // проверка на запрос подтверждения смены номера телефона
            $restoreUrl = $this->parser->parseStr($html[html], '/restore?act=view', "';");

            if ($restoreUrl) {
                $restoreUrl = 'http://vk.com/restore?act=view' . $restoreUrl;
                // ответить что бот работает поставивь timestamp+ 3мин
                $res = sql_query("sql_robots_update2", __LINE__);

                $trying_time = 1;

                do {
                    list($headers, $reply) = $http->sendGetRequest($restoreUrl, '', true, '', $proxy, $proxylogin);

                    $res = json_decode($reply, true);
                    $err = $res['error'];

                    // проверка на отсутствие свзи или плохъой прокси
                    if ($err['error_msg'] == "connect_error") {
                        // ошибка получения данных
                        waiting(__LINE__);
                    } else {
                        // иная непредвиденная ошибка
                        if ($err) {
                            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". непредвиденная ошибка подтверждения " . var_export($err, TRUE) . " строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                            die;
                        }
                    }
                } while ($err || false === $reply || '' === $reply || $err === "");

                file_put_contents("dumpRestore_$this->bot_id.txt", "$restoreUrl \r\n\r\n $headers \r\n\r\n $reply", FILE_APPEND | LOCK_EX);
                die;
            }
            //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". не получен confirmUrl. перезапуск. строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            file_put_contents("dump1_$this->bot_id.txt", "строка " . __LINE__ . "\r\n $url1 \r\n\r\n " . $html, FILE_APPEND | LOCK_EX);
            file_put_contents("dump2_$this->bot_id.txt", "$url2 \r\n\r\n $html2", FILE_APPEND | LOCK_EX);
            file_put_contents("dump3_$this->bot_id.txt", "$url3 \r\n\r\n $html3", FILE_APPEND | LOCK_EX);
            file_put_contents("dump31_$this->bot_id.txt", "$url31 \r\n\r\n $html31", FILE_APPEND | LOCK_EX);
            file_put_contents("dump32_$this->bot_id.txt", "$url32 \r\n\r\n $html32", FILE_APPEND | LOCK_EX);

            // ставим признак мертвой прокси
            file_put_contents("log$this->bot_id.txt", date("d.m.Y H:i:s") . " $proxy \r\n" . var_export($res, TRUE) . "\r\n строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            reboot(100, __LINE__); // перезапуск скрипта сувеличение признака проблемной прокси
            die;
        }


// ответить что бот работает поставивь timestamp+ 3мин
        $res = sql_query("sql_robots_update2", __LINE__);

        $trying_time = 1;
        do {

            https://login.
            $confirmUrl2 = $this->parser->parseStr($confirmUrl1, 'login.', '');
            $confirmUrl = $confirmUrl2[html];


            $html = $this->httpCall($confirmUrl, array('postdata' => ''), array('ssl' => true, 'httpPrefix' => "login.", 'dump' => true));

            $headers = $html[header];
            $reply = $html[html];

            $res = json_decode($reply, true);
            $err = $res['error'];

            // проверка на отсутствие свзи или плохъой прокси
            if ($err['error_msg'] == "connect_error") {
                // ошибка получения данных
                waiting(__LINE__);
            } else {
                // иная непредвиденная ошибка
                if ($err) {
                    file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". непредвиденная ошибка авторизации " . var_export($err, TRUE) . " строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                    die;
                }
            }
        } while ($err || false === $reply || '' === $reply || $err === "");

// echo "5";


        $url4 = $confirmUrl . "\r\n\r\n куки:" . $cocky;
        $html4 = "заголовок:\r\n" . var_export($headers, TRUE) . "\r\n\r\nbody\r\n$reply";
//var_dump($headers);
        file_put_contents("dump4_$this->bot_id.txt", $url4 . "\r\n\r\n" . $html4, FILE_APPEND | LOCK_EX);
        die;
        $headers4 = $http->formatHeadersArray($headers);
        $location = trim(@$headers4['Location']);

        // проверка наошибкуавторизации
        $err_auth = $this->parser->parseStr($location, 'https://oauth.vk.com/error', 'err');
        if ($err_auth) {
            list($headers, $html) = $http->sendGetRequest($location, '', true, '', $proxy, $proxylogin);

            $res = json_decode($html, true);
            $err = $res['error'];

            // это ошибка запароленой пркси
            if ($res['error_description'] == "Security Error") {

                // if($this->bot_id==333) file_put_contents("SCERR_AUTH$this->bot_id.txt","строка ".__LINE__."\r\n$proxy\r\n$client_id\r\n\r\n$url4\r\n\r\n$location \r\n\r\n ".$html."\r\n\r\n непонятная ошибка\r\n\r\n",FILE_APPEND | LOCK_EX);
                /* file_put_contents("SCdump1_$this->bot_id.txt","$url1 \r\n\r\n $html1",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump2_$this->bot_id.txt","$url2 \r\n\r\n $html2",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump3_$this->bot_id.txt","$url3 \r\n\r\n $html3",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump31_$this->bot_id.txt","$url31 \r\n\r\n $html31",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump32_$this->bot_id.txt","$url32 \r\n\r\n $html32",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump4_$this->bot_id.txt","$url4 \r\n\r\n $html4",FILE_APPEND | LOCK_EX);
                 */
                $client_id = rand(1000000, 6000000);

                // сохраняем сгенерированый client_id
                sql_query("sql_robots_update5", __LINE__);

                reboot(1); // перезапускаем

                die;
            }


            /*
                        file_put_contents("CLdump1_$this->bot_id.txt","$url1 \r\n\r\n $html1",FILE_APPEND | LOCK_EX);
                        file_put_contents("CLdump2_$this->bot_id.txt","$url2 \r\n\r\n $html2",FILE_APPEND | LOCK_EX);
                        file_put_contents("CLdump3_$this->bot_id.txt","$url3 \r\n\r\n $html3",FILE_APPEND | LOCK_EX);
                        file_put_contents("CLdump31_$this->bot_id.txt","$url31 \r\n\r\n $html31",FILE_APPEND | LOCK_EX);
                        file_put_contents("CLdump32_$this->bot_id.txt","$url32 \r\n\r\n $html32",FILE_APPEND | LOCK_EX);
                        file_put_contents("CLdump4_$this->bot_id.txt","$url4 \r\n\r\n $html4",FILE_APPEND | LOCK_EX);

                         die;
            */
            // генерируем новый client_id от 1 000 000 до 6 000 000
            $this->client_id = rand(1000000, 6000000);

            // сохраняем сгенерированый client_id
            sql_query("sql_robots_update5", __LINE__);


            file_put_contents("newtoken$this->bot_id.txt", date("d.m.Y H:i:s") . " ошибка client_pd меняем\r\n" . var_export($res, TRUE) . "\r\n строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            reboot('new_token'); // перезапуск скрипта без сменой прокси и со сбросом токена
            die;

        }


        $token = $this->parser->parseStr($location, 'https://oauth.vk.com/blank.html#access_token=', '&expires_in');
        //echo "<br>токен: $token"; die;

        if (!$token) {
            file_put_contents("dump1_$this->bot_id.txt", "$url1 \r\n\r\n $html1", FILE_APPEND | LOCK_EX);
            file_put_contents("dump2_$this->bot_id.txt", "$url2 \r\n\r\n $html2", FILE_APPEND | LOCK_EX);
            file_put_contents("dump3_$this->bot_id.txt", "$url3 \r\n\r\n $html3", FILE_APPEND | LOCK_EX);
            file_put_contents("dump31_$this->bot_id.txt", "$url31 \r\n\r\n $html31", FILE_APPEND | LOCK_EX);
            file_put_contents("dump32_$this->bot_id.txt", "$url32 \r\n\r\n $html32", FILE_APPEND | LOCK_EX);
            file_put_contents("dump4_$this->bot_id.txt", "$url4 \r\n\r\n $html4", FILE_APPEND | LOCK_EX);

            die;
        } else {
            //file_put_contents("token$this->bot_id.txt",date("d.m.Y H:i:s")."сменил токен\r\n",FILE_APPEND |  LOCK_EX);
        }
        /*
        file_put_contents("dump1_$this->bot_id.txt","$url1 \r\n\r\n $html1",FILE_APPEND | LOCK_EX);
                file_put_contents("dump2_$this->bot_id.txt","$url2 \r\n\r\n $html2",FILE_APPEND | LOCK_EX);
                file_put_contents("dump3_$this->bot_id.txt","$url3 \r\n\r\n $html3",FILE_APPEND | LOCK_EX);
                file_put_contents("dump31_$this->bot_id.txt","$url31 \r\n\r\n $html31",FILE_APPEND | LOCK_EX);
                file_put_contents("dump32_$this->bot_id.txt","$url32 \r\n\r\n $html32",FILE_APPEND | LOCK_EX);
                file_put_contents("dump4_$this->bot_id.txt","$url4 \r\n\r\n $html4",FILE_APPEND | LOCK_EX);
        */
        // сохраняем токен в базе
        sql_query("sql_robots_update25", __LINE__);
        return array('token' => $token, 'client_id' => $this->client_id, 'vk_id' => $this->bot_vk_id);

    }

    function login($get_name = 0, $start_page = '')
    {
        $login_zavis = 0;

        $html = $this->httpCall($start_page, array('postdata' => $user), array('ssl' => true, 'httpPrefix' => 'm.', 'dump' => false));

        //if($this->bot_id==359)
        //{
        file_put_contents("logHtml" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);

        //}

        if ($html['status']) {
            $html['proxy_alive'] = 1;
            login_recap:
            // авторизация прошла по кукам || strpos($html['lasturl'],$start_page)>0
            if (strpos($html['lasturl'], 'feed') > 0 || strpos($html['lasturl'], $start_page) > 0) {
                preg_match_all('/"uid":(\d*),/i', $html['html'], $parse);
                $uid = $parse[1][0];


                if (!$uid) {
                    // пытаемся получить по другомц
                    $uid_from_header = $this->parser->parseStr($html['header'], 'l="', ';');
                    if ($uid_from_header[status]) $uid = $uid_from_header[html];
                }

                if (!$uid) {
                    // пытаемся получить по другомц
                    $uid_from_html = $this->parser->parseStr($html['html'], '&vk_id=', '&');
                    if ($uid_from_html[status]) $uid = $uid_from_html[html];
                }

                if (!$uid) {
                    // пытаемся получить по другомц
                    $uid_from_html2 = $this->parser->parseStr($html['html'], 'data-href="/id', '"');
                    if ($uid_from_html2[status]) $uid = $uid_from_html2[html];
                }

                if (!$uid) {
                    // пытаемся получить по другомц
                    $uid_from_html3 = $this->parser->parseStr($html['html'], 'window.vk = {"id":', ',"__debug"');
                    if ($uid_from_html3[status]) $uid = $uid_from_html3[html];
                }

                if ($uid && strlen($uid) < 11) {
                    $this->bot_vk_id = $uid;
                    $html['uid'] = $uid;
                } else {
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . " ошибка получения uid \r\n" . var_export($parse, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . " ошибка получения uid \r\n" . var_export($uid_from_header, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . " ошибка получения uid \r\n" . var_export($uid_from_html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . " ошибка получения uid \r\n" . var_export($uid_from_html2, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . " ошибка получения uid \r\n" . var_export($uid_from_html3, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . " ошибка получения uid \r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                }

                if ($get_name) {
                    // выделяем имя фамилию
                    $name = $this->parser->parseStr($html['html'], 'data-name="', '" data-photo');
                    if ($name[status]) {
                        list($html['name'], $html['family']) = preg_split(' ', $name[html]);
                    } else {
                        $html['name'] = 'undefined';
                        $html['family'] = 'undefined';
                    }
                }


                // проверка  на вход через мобильную версию
                $login_link_pref_lasturl = $this->parser->parseStr($html['lasturl'], 'https://', self::$apiURL);

                if ($login_link_pref_lasturl[html] == "m.") {
                    // проверка на успешный вход в мобильной версии
                    $this->MobileVersion = true;
                    $this->Pref = "m.";
                }
                return $html;
            }

            // при попытке авторизации по кукам получили извещение о блокировке
            if (strpos($html['lasturl'], 'blocked') > 0) {
                file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "=========== \r\n  строка " . __LINE__ . "\r\n" . var_export($login_link_pref, true) . "\r\n" . var_export($login_link_method, true) . "\r\n" . var_export($login_page_allform_params, true) . "\r\n postdata: " . var_export($postdata, true) . "\r\n Капча: " . var_export($res_cap, true) . "\r\n uid2: " . var_export($uid2, true) . "\r\n", FILE_APPEND | LOCK_EX);
                file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "ошибка логина " . $login_page_allform_params[msg] . ". login: " . $this->user['username'] . ", pass: " . $this->user['password'] . " строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                return array('status' => false, 'code' => 2, 'msg' => "login_error");
            }

            //проверка на неверные логин пароль 'code' => 7 или плохую прокси времнено испрален на код 2
            if (strpos($html['lasturl'], 'login?role') > 0) {

                // ищем предупреждение о неверном пароле
                //<div class="service_msg service_msg_warning"><b>Bejelentkez&#233;s sikertelen.</b> // возможно изза прокси
                $wrong_pass_warn = $this->parser->parseStr($html['html'], 'service_msg', 'service_msg_warning');
                if ($wrong_pass_warn[status]) {
                    $wrong_pass_warn2 = $this->parser->parseStr($html['html'], 'service_msg_warning"><b>', '</b>');

                    $code = 0;

                    switch ($wrong_pass_warn[html]) {
                        case "Login failed.":
                            $code = 7;
                            break;

                        case "Не удаётся войти.":
                            $code = 7;
                            break;


                        //Не удаётся войти. в иероглифах
                        case "&#30331;&#24405;&#22833;&#36133;":
                            $code = 7;
                            break;

                        // возможно это изза проблем с прокси и нужен код 9. проверить
                        case "Bejelentkez&#233;s sikertelen.":
                            $code = 7;
                            break;

                        // возможно это изза проблем с прокси и нужен код 9. проверить
                        case "La connexion a &#233;chou&#233;.":
                            $code = 7;
                            break;
                    }

                    //непонятно почему возникает ошибка "Слишком много попыток." "Zu viele Versuche" при логине и как ее обрабатывать

                    if ($code != 7) {
                        // неподтвержденный код 7.разбираться
                        file_put_contents("LoginErrStatus7_" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "=========== \r\n строка " . __LINE__ . "\r\n" . var_export($login_link_pref, true) . "\r\n" . var_export($login_link_method, true) . "\r\n" . var_export($login_page_allform_params, true) . "\r\n postdata: " . var_export($postdata, true) . "\r\n Капча: " . var_export($res_cap, true) . "\r\n uid2: " . var_export($uid2, true) . "\r\n", FILE_APPEND | LOCK_EX);
                        file_put_contents("LoginErrStatus7_" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " неверные логин пароль ПРОВЕРИТЬ ОТОБРАЖЕНИЕ НА САЙТЕ " . $login_page_allform_params[msg] . ". login: " . $this->user['username'] . ", pass: " . $this->user['password'] . " строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                        file_put_contents("LoginErrStatus7_" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    }

                    return array('status' => false, 'code' => 7, 'msg' => "wrong login/password");
                }


                if ($logrole > 0) {
                    // if($this->bot_id==16){
                    // file_put_contents("LoginErrStatus9".$this->bot_id.".txt",date("d.m.Y H:i:s")."=========== \r\n строка ".__LINE__."\r\n".var_export($login_link_pref,true)."\r\n".var_export($login_link_method,true)."\r\n".var_export($login_page_allform_params,true)."\r\n postdata: ".var_export($postdata,true)."\r\n Капча: ".var_export($res_cap,true)."\r\n uid2: ".var_export($uid2,true)."\r\n",FILE_APPEND | LOCK_EX);
                    // file_put_contents("LoginErrStatus9".$this->bot_id.".txt",date("d.m.Y H:i:s")." неверные логин пароль ПРОВЕРИТЬ ОТОБРАЖЕНИЕ НА САЙТЕ ".$login_page_allform_params[msg].". login: ".$this->user['username'].", pass: ".$this->user['password']." строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    // file_put_contents("LoginErrStatus9".$this->bot_id.".txt",date("d.m.Y H:i:s")." строка ".__LINE__."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);
                    //  }
                    return array('status' => false, 'code' => 9, 'msg' => "bad_proxy");
                }

                $logrole++;
            }


            // иначе разбираем на формы
            $login_page_allform_params = $this->parser->getAllFormsParams($html['html']);

            file_put_contents("errlogHtml" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "ошибка логина " . $login_page_allform_params[msg] . ". login: " . $this->user['username'] . ", pass: " . $this->user['password'] . " строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);


            // проверка на ошибку
            if ($login_page_allform_params[status] === false) {

                //   file_put_contents("errlogHtml".$this->bot_id.".txt",date("d.m.Y H:i:s")."ошибка логина ".$login_page_allform_params[msg].". login: ".$this->user['username'].", pass: ".$this->user['password']." строка ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                //  file_put_contents("errlogHtml".$this->bot_id.".txt",date("d.m.Y H:i:s")." строка ".__LINE__."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);
                //die;
                return array('status' => false, 'code' => 14, 'msg' => 'parsing_error');
            }

            // выбираем форму quick_login_form
            $i = 0;
            while ($login_page_allform_params[forms][$i][id] && $login_page_allform_params[forms][$i][id] != 'quick_login_form') $i++;

            //var_dump($login_page_allform_params[forms][$i]);
            $login_link_method = $this->parser->parseStr($login_page_allform_params[forms][$i][action], 'vk.com/', '');
            $login_link_pref = $this->parser->parseStr($login_page_allform_params[forms][$i][action], 'https://', self::$apiURL);

            // собираем список параметров для передачи на сервер
            $y = 0;
            while ($login_page_allform_params[forms][$i][params][name][$y]) {
                if ($login_page_allform_params[forms][$i][params][name][$y] == 'email') {
                    $postdata[$login_page_allform_params[forms][$i][params][name][$y]] = $this->user[username];
                } elseif ($login_page_allform_params[forms][$i][params][name][$y] == 'pass') {
                    $postdata[$login_page_allform_params[forms][$i][params][name][$y]] = $this->user[password];
                } else {
                    $postdata[$login_page_allform_params[forms][$i][params][name][$y]] = $login_page_allform_params[forms][$i][params][value][$y];
                }

                if ($login_page_allform_params[forms][$i][params][name][$y] == 'captcha_sid') {
                    $res_cap = recognize('https://' . $login_link_pref_lasturl[html] . self::$apiURL . "/captcha.php?s=0&sid=" . $login_page_allform_params[forms][$i][params][value][$y]);
                    $captcha_key = $res_cap[0];
                    $captcha_id = $res_cap[1];

                    if ($captcha_key == false) {
                        // скорее всего проблема с прокси. ставим признак
                        //file_put_contents("CAPloginErr".$this->bot_id.".txt",date("d.m.Y H:i:s")." строка ".__LINE__." captcha_sid=".$login_page_allform_params[forms][$i][params][value][$y].",captcha_key=$captcha_key \r\n",FILE_APPEND | LOCK_EX);

                        reboot(1, __LINE__); // перезапуск скрипта c увеличением признака проблеиы прокси
                        die;
                    }
                }

                if ($login_page_allform_params[forms][$i][params][name][$y] == 'captcha_key') {
                    $postdata[$login_page_allform_params[forms][$i][params][name][$y]] = $captcha_key;
                }

                $y++;
            }

            // логинимся
            $html = $this->httpCall($login_link_method[html], array('postdata' => $postdata), array('ssl' => true, 'httpPrefix' => $login_link_pref[html], 'dump' => false));

            // поскольку первый запрос уже прошел то прокси жива. обновляем признак
            $html['proxy_alive'] = 1;

            //if($this->bot_id==409)  file_put_contents("log2Html".$this->bot_id.".txt",date("d.m.Y H:i:s")." строка ".__LINE__."\r\n".var_export($login_link_pref,true)."\r\n".var_export($login_link_method,true)."\r\n".var_export($login_page_allform_params,true)."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);

            if ($html[status]) {
                // обрабатываем ситуацию с первым входом после смены телефона при воссианеовлении
                $restore_check = $this->parser->parseStr($html['html'], 'restore?act=', '&');
                if ($restore_check[html] == 'view') {

                    file_put_contents("LoginRes" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    return array('status' => false, 'code' => 8, 'msg' => "restore");
                }

                $login_link_pref = $this->parser->parseStr($html['lasturl'], 'https://', self::$apiURL);

                // проверка  на вход через мобильную версию

                if ($login_link_pref[html] == "m.") {
                    // проверка на успешный вход в мобильной версии
                    $this->MobileVersion = true;
                    $this->Pref = "m.";
                    // находим uid другим методом т.к. в заголовке его нет
                    $uid2 = $this->parser->parseStr($html['html'], '"uid":', ',');
                    //file_put_contents("log2Html".$this->bot_id.".txt",date("d.m.Y H:i:s")." строка ".__LINE__."\r\n".var_export($login_link_pref,true)."\r\n".var_export($login_link_method,true)."\r\n".var_export($login_page_allform_params,true)."\r\n".var_export($postdata,true)."\r\n".var_export($res_cap,true)."\r\n".var_export($uid2,true)."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);

                    if ($uid2[status] && $uid2[html] > 0) {
                        $html['uid'] = $uid2[html];
                        $this->bot_vk_id = $uid2[html];
                    } else {
                        // возможно ошибка при распознавании капчи или синдром Меркуловой (повторный запрос логина)
                        $login_zavis++;
                        if ($login_zavis < 3) goto login_recap;

                        // ошибка логина либо капчи. надо проверять,
                        file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " строка " . __LINE__ . "\r\n" . var_export($login_link_pref, true) . "\r\n" . var_export($login_link_method, true) . "\r\n" . var_export($login_page_allform_params, true) . "\r\n postdata: " . var_export($postdata, true) . "\r\n Капча: " . var_export($res_cap, true) . "\r\n uid2: " . var_export($uid2, true) . "\r\n html: " . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);

                        return array('status' => false, 'code' => 2, 'msg' => "login_error");
                    }

                    if ($get_name) {
                        // выделяем имя фамилию
                        $name = $this->parser->parseStr($html['html'], 'data-name="', '" data-photo');
                        if ($name[status]) {
                            list($html['name'], $html['family']) = preg_split(' ', $name[html]);
                        } else {
                            $html['name'] = 'undefined';
                            $html['family'] = 'undefined';
                        }
                    }

                    if ($html['status']) return $html;
                } else {
                    // проверка на успешный вход в обычной версии
                    // находим uid
                    preg_match_all('/"uid":"(\d*)"/i', $html['html'], $parse);

                    $uid = $parse[1][0];
                    if ($uid) $this->bot_vk_id = $uid;

                    // находим short_link
                    preg_match_all("/onLoginDone\('\/(\w*)\'/i", $html['html'], $parse2);
                    $slink = $parse2[1][0];


                    if ($slink) {
                        // для иммитации человека переходим на страницу


                        if (!$login_link_pref[status]) {

                            file_put_contents("err2log$this->bot_id.txt", date("d.m.Y H:i:s") . "Ошибка парсинга. строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                            return array('status' => false, 'code' => 14, 'msg' => 'parsing_error');
                        }

                        $html = $this->httpCall($slink, '', array('ssl' => true, 'httpPrefix' => $login_link_pref[html], 'dump' => false));
                        $html['uid'] = $uid;
                        $html['slink'] = $slink;
                        $html['proxy_alive'] = 1;

                        if ($html['status']) {

                            return $html;
                        }

                    } else {
                        // проверяем на ошибку прокси
                        $proxy_bad = $this->parser->parseStr($html['html'], 'Client', 'IP');
                        if ($proxy_bad[status]) return array('status' => false, 'code' => 1, 'msg' => "connect_error");

                        // что-то пошло не так
                        file_put_contents("ErrLogin" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "\r\n" . var_export($html, TRUE) . "\r\n строка " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                        return array('status' => false, 'code' => 2, 'msg' => "login_error");

                    }
                }
            } else {
                return $html;
            }
        } else {
            return $html;
        }


    }


}