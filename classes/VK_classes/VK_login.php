<?php
/*
 ������ ����� ������� ��� �����
 'code' => 1,  'msg' => "connect_error"
 'code' => 2,  'msg' => "login_error"
 'code' => 3,  'msg' => "change_phone"
 'code' => 6,  'msg' => "bad_browser"
 'code' => 7,  'msg' => "wrong login/password"
 'code' => 8,  'msg' => "restore"
 'code' => 9,  'msg' => "bad_proxy"

 ������ ��������
 'code' => 10, 'msg' => "string_not_found"
 'code' => 11, 'msg' => 'bad_html'
 'code' => 12, 'msg' => 'no_entry_start'
 'code' => 13, 'msg' => 'no_entry_stop'
 'code' => 14, 'msg' => 'parsing_error'
 'code' => 15, 'msg' => 'looping_error'
 'code' => 19, 'msg' => 'parsing_success'

 ���� ������� ��� �������� ������
 'code' => 20, 'msg' => "success_friended"
 'code' => 21, 'msg' => "already_friended"

 ���� ������� ��� ��������� ����� ������
 'code' => 30, 'msg' => "success_get_wall"
 'code' => 31, 'msg' => "not_complete_get_wall"

 ���� ������� ��� ������ �� �������� ������
 'code' => 40, 'msg' => "friends_kol_success"
 'code' => 41, 'msg' => 'friend_not_found'
 'code' => 42, 'msg' => 'incoming_friend_requsts_not_found'
 'code' => 43, 'msg' => 'wrong_vk_id'
 'code' => 44, 'msg' => 'wrong_hash'
 'code' => 45, 'msg' => 'success_decline'

  ���� ������� ��� ��������
 'code' => 50, 'msg' => "success_repost"

 ���� ������� ��� ������
 'code' => 60, 'msg' => "search_success"

  ���� ������� ��� ������ � ��������
 'code' => 70, 'msg' => "search_success"
 'code' => 71, 'msg' => "can`t_invite"
 'code' => 72, 'msg' => "invite_success"
 'code' => 73, 'msg' => "invite_alredy"
 'code' => 74, 'msg' => "invite_forbidden"
 'code' => 75, 'msg' => "day_limit"

  ���� ������� ��� ����������� ������ �������� ���������
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
            file_put_contents("errlog" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ �������� ���������� ��� ������������� ������ Instagram ��� ���� " . $this->bot_id . ". login: $login, pass: $pass. ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            return false;
        }
        $this->login = $login;
        $this->pass = $pass;

        $this->bot_id = $bot_id;
        $this->bot_vk_id = $bot_vk_id;
        $this->client_id = $client_id;
        $this->user = array('username' => $login, 'password' => $pass);

        //��� ������. ����� ����� ������������
        $this->initOK = true;

        $this->lastPageHtml = '';
        $this->MobileVersion = false;


        // ������� ��������� ������ �����������
        $this->Log      =   new LogClass($bot_id,__FILE__);

    }


    function get_token($next_time, $unblock_date, $proxy_alive, $url_timeout, $trying_time)
    {
        global $client_id;
//require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Http.php';
//$proxy="";
        //$http= new HttpTools();


        //����������� ���������� �����
        //$scope = "notify,friends,photos,audio,video,docs,notes,pages,status,offers,questions,wall,groups,mail,messages,notifications,stats,ads,offline,nohttps";
        //$scope = 'messages,friends,wall,groups';
        $scope = 'friends,wall,groups,offline';
        // if($this->bot_id==9) $scope = 'messages,friends,wall,groups,offline';

        $callback = '&redirect_uri=http://oauth.vk.com/blank.html';
        $cl_id_num = 50; // ���������� ������� ������� ������ client_id


        $trying_time = 1;
        s137:

        do {
            //������ ������
            $url = 'authorize?';
            $url .= 'client_id=' . $this->client_id;
            $url .= '&scope=' . $scope;
            $url .= $callback;
            $url .= '&display=mobile';
            $url .= '&v=5.73';
            $url .= '&response_type=token';
            $url .= '&revoke=1';
            //https://oauth.vk.com/authorize?client_id=5681624&&scope=messages,friends,wall,groups&&redirect_uri=http://oauth.vk.com/blank.html&display=mobile&v=5.24&response_type=token&revoke=1

            // �������� ��� ��� �������� ��������� timestamp+ 3���
            $res = sql_query("sql_robots_update2", __LINE__);

            $html = $this->httpCall($url, array('postdata' => ''), array('ssl' => true, 'httpPrefix' => "oauth.", 'dump' => false));


            $url1 = $url;
            $html1 = "���������:\r\n" . var_export($html[header], TRUE) . "\r\n\r\n body \r\n$html[html]";

            file_put_contents("dump1_$this->bot_id.txt", date("d.m.Y H:i:s") . "vkbot_v4_class.php ������ " . __LINE__ . "\r\n\r\n $url1 \r\n\r\n" . $html1, FILE_APPEND | LOCK_EX);
            //die;

            //https://oauth.vk.com/authorize?client_id=1232233&redirect_uri=http%3A%2F%2Foauth.vk.com%2Fblank.html&response_type=token&scope=335874&v=5.73&state=&revoke=1&display=mobile&slogin_h=56f63ef4f26fd70a07.6faf0a758689c7bce2&__q_hash=74321ab6bb4a4e87197e02fb48aebb76

            // �������� ��� ��� �������� ��������� timestamp+ 3���
            sql_query("sql_robots_update2", __LINE__);

            // ��������� �������� �� client_id
            $res = json_decode($html[html], true);
            $err = $res['error'];
            $err_desc = $res['error_description'];

            // ��������� ������������ ������ ������
            $proxy_ok = $this->parser->parseStr($html['header'], 'HTTP', 'OK');


            // �������� �� ���������� ���� ��� ������� ������
            if ($err['error_msg'] == "connect_error") {
                // ������ ��������� ������
                waiting(__LINE__);
                // file_put_contents("dump1_$this->bot_id.txt","\r\n\r\n $url1 \r\n\r\n".$html1."\r\n=================================================\r\n",FILE_APPEND | LOCK_EX);
            } else {
                $trying_time = 1;// ��������� �������� ����� ������ ������� client_id �� ����������� ������ ��������

                if ($err) {
                    // ���� ����� ������ ������
                    if ($err != 'HTTP/1.1 401 Unauthorized' && $err != 'invalid_request' && $err_desc != 'application is blocked') {


                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ������ ������ . ������� �����������. ������ ������ � �������������.  ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);

                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ������ ������ ��� �����. �������: $url_timeout \r\n $url\r\n\r\n =".var_export($err, TRUE)."= ������ ".__LINE__."\r\n==========================\r\n",FILE_APPEND | LOCK_EX);
                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ������ ������ ��� �����. �������: $url_timeout \r\n $url\r\n\r\n res=$res= ������ ".__LINE__."\r\n==========================\r\n",FILE_APPEND | LOCK_EX);
                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").".  ������ ������ ��� �����. �������: $url_timeout \r\n $url\r\n\r\n err=$err= ������ ".__LINE__."\r\n==========================\r\n",FILE_APPEND | LOCK_EX);

                        // ������ ������� ������� ������
                        file_put_contents("log$this->bot_id.txt", date("d.m.Y H:i:s") . " $proxy \r\n" . var_export($res, TRUE) . "\r\n ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                        return array("status" => "reboot", "time" => 100, "line" => __LINE__); // ���������� ������� ����������� �������� ���������� ������

                    }


                    // ���������� ����� client_id �� 1 000 000 �� 6 000 000
                    $client_id = rand(1000000, 6000000);
                    $this->client_id = $client_id;


                    // ��������� ������� ������ �� ���������
                    $cl_id_num--;
                    file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "vkbot_v4_class.php ������ " . __LINE__ . " $cl_id_num client_id:" . $this->client_id . " \r\n", FILE_APPEND | LOCK_EX);
                    // ���� �������� ������ �������� �������� ������ � ���������������
                    if ($cl_id_num == 0) {
                        file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "vkbot_v4_class.php ������ " . __LINE__ . ". ������ ������� client_id.\r\n", FILE_APPEND | LOCK_EX);
                        die;
                    }
                }


            }
            // ������������ �� ��������� ������ ���� ���� ������
        } while ($err || false === $html[html] || '' === $html[html] || $err === "");


        // ���� ��� ������������ ����� client_ id ��������� ���
        if ($cl_id_num < 50) {
            // ��������� �������������� client_id
            sql_query("sql_robots_update5", __LINE__);
        }


        $captcha_num = 3; // ������� ����� ������ ���������
        s1686:
        $_origin = $this->parser->parseStr($html[html], '<input type="hidden" name="_origin" value="', '">');
        $ip_h = $this->parser->parseStr($html[html], '<input type="hidden" name="ip_h" value="', '" />');
        $lg_domain_h = $this->parser->parseStr($html[html], '<input type="hidden" name="lg_domain_h" value="', '" />');
        $to = $this->parser->parseStr($html[html], '<input type="hidden" name="to" value="', '">');


        // �������� �� ������ ����������� ��������
        if (!$_origin[html] || !$ip_h[html] || !$lg_domain_h[html] || !$to[html]) {
            //������ �������� �� ����������� �� ����
            $grant = $this->parser->parseStr($html[html], 'action="https://login.vk.com/?act', 'grant_access');

            if ($grant[status]) goto  s420;

            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "������ " . __LINE__ . ".�������� _origin:$_origin, �������� lg_domain_h:$lg_domain_h \r\n", FILE_APPEND | LOCK_EX);
            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "������ " . __LINE__ . ".�������� ip_h:$ip_h, �������� to:$to \r\n", FILE_APPEND | LOCK_EX);
            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . "������ " . __LINE__ . "\r\n\r\n $url1 \r\n\r\n" . $html1 . "\r\n\r\n", FILE_APPEND | LOCK_EX);
            die;
            waiting(__LINE__);// ���� ����� ��� ����������
            goto  s137;

        }


        // �������� ��� ��� �������� ��������� timestamp+ 3���
        $res = sql_query("sql_robots_update2", __LINE__);

        //������ ������
        $url2 = '?act=login&soft=1&utf8=1';
        $param[ip_h] = $ip_h[html];
        $param[lg_domain_h] = $lg_domain_h[html];
        $param[to] = $to[html];
        $param[_origin] = 'https%3A%2F%2Foauth.vk.com';
        $param[email] = $this->login;
        $param[pass] = $this->pass;

        // ���� ��������� ����������� � ������
        if ($captcha_sid) {
            if ($captcha_key) {
                $param[captcha_sid] = $captcha_sid;
                $param[captcha_key] = $captcha_key;
            } else {
                file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". ������ ���������  �����. ��������� captcha_url:$captcha_url, captcha_sid:$captcha_sid, captcha_key:$captcha_key.  ������:" . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                die;
            }
        }
        //  echo $param."<br>";

// �������� ��� ��� �������� ��������� timestamp+ 3���
        $res = sql_query("sql_robots_update2", __LINE__);


        $trying_time = 1;
        do {
            $html = $this->httpCall($url2, array('postdata' => $param), array('ssl' => true, 'httpPrefix' => "login.", 'dump' => false));

            $url2 = $url2 . "\r\n\r\n ��������� :" . var_export($param, TRUE) . "\r\n\r\n";

            $html2 = "���������:\r\n" . var_export($html[header], TRUE) . "\r\n\r\n body \r\n." . $html[html];

            file_put_contents("dump2_$this->bot_id.txt", date("d.m.Y H:i:s") . " vkbot_v4_class.php ������ " . __LINE__ . "\r\n\r\n $url2 \r\n\r\n" . $html2, FILE_APPEND | LOCK_EX);

            $res = json_decode($html[html], true);
            $err = $res['error'];

            // �������� �� ���������� ���� ��� ������� ������
            if ($err['error_msg'] == "connect_error") {
                // ������ ��������� ������
                waiting(__LINE__);
            } else {
                // ���� �������������� ������
                if ($err) {
                    // ��������� �������� � ������. ������ ������� �������� � ������
                    // var_dump($err);
                    // file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")."vkbot_v4_class.php ������ ".__LINE__.". �������������� ������ ����������� ".var_export($err, TRUE)." ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")."vkbot_v4_class.php ������ ".__LINE__.". $url2 ��������� $param ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);

                    // �������� � ������� � ����� ������
                    file_put_contents("log$this->bot_id.txt", date("d.m.Y H:i:s") . " $proxy \r\n" . var_export($res, TRUE) . "\r\n ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                    reboot(100, __LINE__); // ���������� ������� �� ������ ������
                    die;
                }
            }
        } while ($err || false === $html[html] || '' === $html[html] || $err === "");

        // ������ ������� ���� ��� ������ ���� � � ip ���������� �� ������� ��
        $proxy_alive = 1;
        sql_query("sql_robots_update27", __LINE__);
        s1636:


// �������� �� �������� �����������
        $access_denied = $this->parser->parseStr($html[html], 'Invalid login', 'password');
        $access_denied2 = $this->parser->parseStr($html[html], 'service_msg_warning">', '</div>');

        $captcha_err = $this->parser->parseStr($html[html], 'CAPTCHA', '</div>');
        $captcha_err_rus = $this->parser->parseStr($html[html], iconv("windows-1251", "utf-8", '���'), iconv("windows-1251", "utf-8", '�������'));


// �������� ����� �����.
        $captcha_url2 = $this->parser->parseStr($html[html], '<img id="captcha" alt="" src="', '" class="captcha_img" />');
        $captcha_sid2 = $this->parser->parseStr($html[html], '<input type="hidden" name="captcha_sid" value="', '" />');


//���� ������ ����� �����
        if ($captcha_err[status] || $captcha_err_rus[status] || ($captcha_url2[status] && $access_denied2[status])) {
            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " vkbot_v4_class.php ������ " . __LINE__ . " ������ ����������� ����� " . $captcha_url[html] . " \r\n" . $captcha_key[html] . " \r\n" . $captcha_id[html] . $captcha_url2[html] . $captcha_err[html] . $captcha_err_rus[html] . "\r\n" . $access_denied2[html] . "\r\n", FILE_APPEND | LOCK_EX);
            die;
            reportbad($captcha_id); // ���� ����� ������� ������� � ������ ������������
            // ��������� �� ��������� �����������.
            goto s438;

        }

        if ($access_denied[status] || $access_denied2[status] || $location_denied_bool) {


            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " ������ �����������. location=$location .location_denied_bool=$location_denied_bool, access_denied=$access_denied, access_denied2=$access_denied2. ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            file_put_contents("dump1_$this->bot_id.txt", "$url1 \r\n\r\n $html1", FILE_APPEND | LOCK_EX);
            file_put_contents("dump2_$this->bot_id.txt", "$url2 \r\n\r\n $html2", FILE_APPEND | LOCK_EX);
            file_put_contents("dump3_$this->bot_id.txt", "$url3 \r\n\r\n $html3", FILE_APPEND | LOCK_EX);
            file_put_contents("dump31_$this->bot_id.txt", "$url31 \r\n\r\n $html31", FILE_APPEND | LOCK_EX);
            file_put_contents("dump32_$this->bot_id.txt", "$url32 \r\n\r\n $html32", FILE_APPEND | LOCK_EX);

            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " ������ �����������. ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            die;

            $access_denied_date = $this->parser->parseStr($html[html], iconv("windows-1251", "utf-8", '���������� �� <b>'), '</b>');

            if ($access_denied_date[html]) {
                // ������ ����� "�������"
                $access_denied_date = str_replace(iconv("windows-1251", "utf-8", '�������,'), date("d.m.y"), $access_denied_date[html]);

                // ������ ����� "������"
                $access_denied_date = str_replace(iconv("windows-1251", "utf-8", '������,'), date("d.m.y", time() + 86400), $access_denied_date[html]);

                file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . " ������ �����������. access_denied_date=" . $access_denied_date[html] . " ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                $dateb = DateTime::createFromFormat('d.m.y H:i', $access_denied_date[html]);
                if ($dateb) {//file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")." ������ �����������. ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    $unblock_date = $dateb->format('Y-m-d H:i:s');
                    // file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s")." ������ �����������. ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    file_put_contents("dump4_$this->bot_id.txt", "$url4 \r\n\r\n $unblock_date", FILE_APPEND | LOCK_EX);
                    sql_query("sql_robots_update16", __LINE__);
                }
            }


            return false;
        }

        // ���� ����� ���
        if ($this->bot_vk_id < 10000000) {
            $value = $this->parser->parseStr($html[html], 'window.vk = {"id":', ',"__debug');

            // ������� � ���� ��� id
            $this->bot_vk_id = $value;
            sql_query("sql_robots_update17", __LINE__);

        }
        s420:
        // �������� ��� ��� �������� ��������� timestamp+ 3���
        $res = sql_query("sql_robots_update2", __LINE__);

        // ����������� �������������
        $confirmUrl1 = $this->parser->parseStr($html[html], '<form method="post" action="', '">');
        $confirmUrl = $confirmUrl1[html];

        // ���� ���������� https://login.vk.com/?act=login&soft=1&utf8=1 �� ��������� ��������� �����������

        if ($confirmUrl == "https://login.vk.com/?act=login&soft=1&utf8=1") {
            // �������� ������ ������������ ��� ����������� �����
            if ($captcha_num == 0) {
                file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". ������������ ��� ����������� ����� ��� �����������. ��������� " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                die;
            }
            s438:
            // ��������� ������� ������ �� ������������
            $captcha_num--;

            // �������� �����.
            $captcha_url = $this->parser->parseStr($html[html], '<img id="captcha" alt="" src="', '" class="captcha_img" />');
            $captcha_sid = $this->parser->parseStr($html[html], '<input type="hidden" name="captcha_sid" value="', '" />');

            $captcha_url = $captcha_url[html];
            $captcha_sid = $captcha_sid[html];

            // ������� ������ �� ����������� �����
            if ($captcha_url) {
                for ($cap_i = 0; $cap_i < 3; $cap_i++) {

                    $res_cap = recognize($captcha_url);
                    $captcha_key = $res_cap[0];
                    $captcha_id = $res_cap[1];

                    if ($captcha_key) break; // ��������� ���� �������� ����� ���� ����� �������

                    // ���� ����� �� �������� ��� ���� ����
                    if ($cap_i == 2) {
                        //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". ������ ������������ ��������� �����. ����������. captcha_url:$captcha_url, captcha_sid:$captcha_sid ������:".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                        // �������� ��� ��� �������� ��������� timestamp+ 3���
                        $res = sql_query("sql_robots_update2", __LINE__);

                        // ������ ����� �������� � ������. ������ �������
                        reboot(1); // ���������� ������� ����������� �������� ���������� ������

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
                                       file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". ���������� ������ ��������� ��� ����� ��� �����. ����������. ������ " .__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                                  die;
                                  }
                */
                // ������ ����� �������� � ������. ������ �������
                reboot(1); // ���������� ������� ����������� �������� ���������� ������

                //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". ������ ��������� ��� ����� ��� �����. ����������. ������ " .__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                die;
            }

            //          echo $captcha_url."<br>";
            //          echo "captcha_key=$captcha_key, captcha_sid=$captcha_sid";
            // die;

            // ��������� �� ���� �����������
            goto s1686;
        }

        // �������� ��� ��� �������� ��������� timestamp+ 3���
        $res = sql_query("sql_robots_update2", __LINE__);


        if (!$confirmUrl) {
            file_put_contents("dump2_$this->bot_id.txt", date("d.m.Y H:i:s") . " vkbot_v4_class.php ������ " . __LINE__ . "\r\n\r\n $url2 \r\n\r\n" . $html2, FILE_APPEND | LOCK_EX);

            die;
            // �������� �� ������ ������������� ����� ������ ��������
            $restoreUrl = $this->parser->parseStr($html[html], '/restore?act=view', "';");

            if ($restoreUrl) {
                $restoreUrl = 'http://vk.com/restore?act=view' . $restoreUrl;
                // �������� ��� ��� �������� ��������� timestamp+ 3���
                $res = sql_query("sql_robots_update2", __LINE__);

                $trying_time = 1;

                do {
                    list($headers, $reply) = $http->sendGetRequest($restoreUrl, '', true, '', $proxy, $proxylogin);

                    $res = json_decode($reply, true);
                    $err = $res['error'];

                    // �������� �� ���������� ���� ��� ������� ������
                    if ($err['error_msg'] == "connect_error") {
                        // ������ ��������� ������
                        waiting(__LINE__);
                    } else {
                        // ���� �������������� ������
                        if ($err) {
                            file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". �������������� ������ ������������� " . var_export($err, TRUE) . " ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                            die;
                        }
                    }
                } while ($err || false === $reply || '' === $reply || $err === "");

                file_put_contents("dumpRestore_$this->bot_id.txt", "$restoreUrl \r\n\r\n $headers \r\n\r\n $reply", FILE_APPEND | LOCK_EX);
                die;
            }
            //file_put_contents("errlog$this->bot_id.txt",date("d.m.Y H:i:s").". �� ������� confirmUrl. ����������. ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
            file_put_contents("dump1_$this->bot_id.txt", "������ " . __LINE__ . "\r\n $url1 \r\n\r\n " . $html, FILE_APPEND | LOCK_EX);
            file_put_contents("dump2_$this->bot_id.txt", "$url2 \r\n\r\n $html2", FILE_APPEND | LOCK_EX);
            file_put_contents("dump3_$this->bot_id.txt", "$url3 \r\n\r\n $html3", FILE_APPEND | LOCK_EX);
            file_put_contents("dump31_$this->bot_id.txt", "$url31 \r\n\r\n $html31", FILE_APPEND | LOCK_EX);
            file_put_contents("dump32_$this->bot_id.txt", "$url32 \r\n\r\n $html32", FILE_APPEND | LOCK_EX);

            // ������ ������� ������� ������
            file_put_contents("log$this->bot_id.txt", date("d.m.Y H:i:s") . " $proxy \r\n" . var_export($res, TRUE) . "\r\n ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            reboot(100, __LINE__); // ���������� ������� ����������� �������� ���������� ������
            die;
        }


// �������� ��� ��� �������� ��������� timestamp+ 3���
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

            // �������� �� ���������� ���� ��� ������� ������
            if ($err['error_msg'] == "connect_error") {
                // ������ ��������� ������
                waiting(__LINE__);
            } else {
                // ���� �������������� ������
                if ($err) {
                    file_put_contents("errlog$this->bot_id.txt", date("d.m.Y H:i:s") . ". �������������� ������ ����������� " . var_export($err, TRUE) . " ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                    die;
                }
            }
        } while ($err || false === $reply || '' === $reply || $err === "");

// echo "5";


        $url4 = $confirmUrl . "\r\n\r\n ����:" . $cocky;
        $html4 = "���������:\r\n" . var_export($headers, TRUE) . "\r\n\r\nbody\r\n$reply";
//var_dump($headers);
        file_put_contents("dump4_$this->bot_id.txt", $url4 . "\r\n\r\n" . $html4, FILE_APPEND | LOCK_EX);
        die;
        $headers4 = $http->formatHeadersArray($headers);
        $location = trim(@$headers4['Location']);

        // �������� �������������������
        $err_auth = $this->parser->parseStr($location, 'https://oauth.vk.com/error', 'err');
        if ($err_auth) {
            list($headers, $html) = $http->sendGetRequest($location, '', true, '', $proxy, $proxylogin);

            $res = json_decode($html, true);
            $err = $res['error'];

            // ��� ������ ����������� �����
            if ($res['error_description'] == "Security Error") {

                // if($this->bot_id==333) file_put_contents("SCERR_AUTH$this->bot_id.txt","������ ".__LINE__."\r\n$proxy\r\n$client_id\r\n\r\n$url4\r\n\r\n$location \r\n\r\n ".$html."\r\n\r\n ���������� ������\r\n\r\n",FILE_APPEND | LOCK_EX);
                /* file_put_contents("SCdump1_$this->bot_id.txt","$url1 \r\n\r\n $html1",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump2_$this->bot_id.txt","$url2 \r\n\r\n $html2",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump3_$this->bot_id.txt","$url3 \r\n\r\n $html3",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump31_$this->bot_id.txt","$url31 \r\n\r\n $html31",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump32_$this->bot_id.txt","$url32 \r\n\r\n $html32",FILE_APPEND | LOCK_EX);
                 file_put_contents("SCdump4_$this->bot_id.txt","$url4 \r\n\r\n $html4",FILE_APPEND | LOCK_EX);
                 */
                $client_id = rand(1000000, 6000000);

                // ��������� �������������� client_id
                sql_query("sql_robots_update5", __LINE__);

                reboot(1); // �������������

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
            // ���������� ����� client_id �� 1 000 000 �� 6 000 000
            $this->client_id = rand(1000000, 6000000);

            // ��������� �������������� client_id
            sql_query("sql_robots_update5", __LINE__);


            file_put_contents("newtoken$this->bot_id.txt", date("d.m.Y H:i:s") . " ������ client_pd ������\r\n" . var_export($res, TRUE) . "\r\n ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
            reboot('new_token'); // ���������� ������� ��� ������ ������ � �� ������� ������
            die;

        }


        $token = $this->parser->parseStr($location, 'https://oauth.vk.com/blank.html#access_token=', '&expires_in');
        //echo "<br>�����: $token"; die;

        if (!$token) {
            file_put_contents("dump1_$this->bot_id.txt", "$url1 \r\n\r\n $html1", FILE_APPEND | LOCK_EX);
            file_put_contents("dump2_$this->bot_id.txt", "$url2 \r\n\r\n $html2", FILE_APPEND | LOCK_EX);
            file_put_contents("dump3_$this->bot_id.txt", "$url3 \r\n\r\n $html3", FILE_APPEND | LOCK_EX);
            file_put_contents("dump31_$this->bot_id.txt", "$url31 \r\n\r\n $html31", FILE_APPEND | LOCK_EX);
            file_put_contents("dump32_$this->bot_id.txt", "$url32 \r\n\r\n $html32", FILE_APPEND | LOCK_EX);
            file_put_contents("dump4_$this->bot_id.txt", "$url4 \r\n\r\n $html4", FILE_APPEND | LOCK_EX);

            die;
        } else {
            //file_put_contents("token$this->bot_id.txt",date("d.m.Y H:i:s")."������ �����\r\n",FILE_APPEND |  LOCK_EX);
        }
        /*
        file_put_contents("dump1_$this->bot_id.txt","$url1 \r\n\r\n $html1",FILE_APPEND | LOCK_EX);
                file_put_contents("dump2_$this->bot_id.txt","$url2 \r\n\r\n $html2",FILE_APPEND | LOCK_EX);
                file_put_contents("dump3_$this->bot_id.txt","$url3 \r\n\r\n $html3",FILE_APPEND | LOCK_EX);
                file_put_contents("dump31_$this->bot_id.txt","$url31 \r\n\r\n $html31",FILE_APPEND | LOCK_EX);
                file_put_contents("dump32_$this->bot_id.txt","$url32 \r\n\r\n $html32",FILE_APPEND | LOCK_EX);
                file_put_contents("dump4_$this->bot_id.txt","$url4 \r\n\r\n $html4",FILE_APPEND | LOCK_EX);
        */
        // ��������� ����� � ����
        sql_query("sql_robots_update25", __LINE__);
        return array('token' => $token, 'client_id' => $this->client_id, 'vk_id' => $this->bot_vk_id);

    }

    function login($get_name = 0, $start_page = '')
    {
        $login_zavis = 0;

        $html = $this->httpCall($start_page, array('postdata' => $user), array('ssl' => true, 'httpPrefix' => 'm.', 'dump' => false));

        //if($this->bot_id==359)
        //{
        file_put_contents("logHtml" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);

        //}

        if ($html['status']) {
            $html['proxy_alive'] = 1;
            login_recap:
            // ����������� ������ �� ����� || strpos($html['lasturl'],$start_page)>0
            if (strpos($html['lasturl'], 'feed') > 0 || strpos($html['lasturl'], $start_page) > 0) {
                preg_match_all('/"uid":(\d*),/i', $html['html'], $parse);
                $uid = $parse[1][0];


                if (!$uid) {
                    // �������� �������� �� �������
                    $uid_from_header = $this->parser->parseStr($html['header'], 'l="', ';');
                    if ($uid_from_header[status]) $uid = $uid_from_header[html];
                }

                if (!$uid) {
                    // �������� �������� �� �������
                    $uid_from_html = $this->parser->parseStr($html['html'], '&vk_id=', '&');
                    if ($uid_from_html[status]) $uid = $uid_from_html[html];
                }

                if (!$uid) {
                    // �������� �������� �� �������
                    $uid_from_html2 = $this->parser->parseStr($html['html'], 'data-href="/id', '"');
                    if ($uid_from_html2[status]) $uid = $uid_from_html2[html];
                }

                if (!$uid) {
                    // �������� �������� �� �������
                    $uid_from_html3 = $this->parser->parseStr($html['html'], 'window.vk = {"id":', ',"__debug"');
                    if ($uid_from_html3[status]) $uid = $uid_from_html3[html];
                }

                if ($uid && strlen($uid) < 11) {
                    $this->bot_vk_id = $uid;
                    $html['uid'] = $uid;
                } else {
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . " ������ ��������� uid \r\n" . var_export($parse, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . " ������ ��������� uid \r\n" . var_export($uid_from_header, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . " ������ ��������� uid \r\n" . var_export($uid_from_html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . " ������ ��������� uid \r\n" . var_export($uid_from_html2, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . " ������ ��������� uid \r\n" . var_export($uid_from_html3, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . " ������ ��������� uid \r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                }

                if ($get_name) {
                    // �������� ��� �������
                    $name = $this->parser->parseStr($html['html'], 'data-name="', '" data-photo');
                    if ($name[status]) {
                        list($html['name'], $html['family']) = preg_split(' ', $name[html]);
                    } else {
                        $html['name'] = 'undefined';
                        $html['family'] = 'undefined';
                    }
                }


                // ��������  �� ���� ����� ��������� ������
                $login_link_pref_lasturl = $this->parser->parseStr($html['lasturl'], 'https://', self::$apiURL);

                if ($login_link_pref_lasturl[html] == "m.") {
                    // �������� �� �������� ���� � ��������� ������
                    $this->MobileVersion = true;
                    $this->Pref = "m.";
                }
                return $html;
            }

            // ��� ������� ����������� �� ����� �������� ��������� � ����������
            if (strpos($html['lasturl'], 'blocked') > 0) {
                file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "=========== \r\n  ������ " . __LINE__ . "\r\n" . var_export($login_link_pref, true) . "\r\n" . var_export($login_link_method, true) . "\r\n" . var_export($login_page_allform_params, true) . "\r\n postdata: " . var_export($postdata, true) . "\r\n �����: " . var_export($res_cap, true) . "\r\n uid2: " . var_export($uid2, true) . "\r\n", FILE_APPEND | LOCK_EX);
                file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "������ ������ " . $login_page_allform_params[msg] . ". login: " . $this->user['username'] . ", pass: " . $this->user['password'] . " ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                return array('status' => false, 'code' => 2, 'msg' => "login_error");
            }

            //�������� �� �������� ����� ������ 'code' => 7 ��� ������ ������ �������� �������� �� ��� 2
            if (strpos($html['lasturl'], 'login?role') > 0) {

                // ���� �������������� � �������� ������
                //<div class="service_msg service_msg_warning"><b>Bejelentkez&#233;s sikertelen.</b> // �������� ���� ������
                $wrong_pass_warn = $this->parser->parseStr($html['html'], 'service_msg', 'service_msg_warning');
                if ($wrong_pass_warn[status]) {
                    $wrong_pass_warn2 = $this->parser->parseStr($html['html'], 'service_msg_warning"><b>', '</b>');

                    $code = 0;

                    switch ($wrong_pass_warn[html]) {
                        case "Login failed.":
                            $code = 7;
                            break;

                        case "�� ������ �����.":
                            $code = 7;
                            break;


                        //�� ������ �����. � ����������
                        case "&#30331;&#24405;&#22833;&#36133;":
                            $code = 7;
                            break;

                        // �������� ��� ���� ������� � ������ � ����� ��� 9. ���������
                        case "Bejelentkez&#233;s sikertelen.":
                            $code = 7;
                            break;

                        // �������� ��� ���� ������� � ������ � ����� ��� 9. ���������
                        case "La connexion a &#233;chou&#233;.":
                            $code = 7;
                            break;
                    }

                    //��������� ������ ��������� ������ "������� ����� �������." "Zu viele Versuche" ��� ������ � ��� �� ������������

                    if ($code != 7) {
                        // ���������������� ��� 7.�����������
                        file_put_contents("LoginErrStatus7_" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "=========== \r\n ������ " . __LINE__ . "\r\n" . var_export($login_link_pref, true) . "\r\n" . var_export($login_link_method, true) . "\r\n" . var_export($login_page_allform_params, true) . "\r\n postdata: " . var_export($postdata, true) . "\r\n �����: " . var_export($res_cap, true) . "\r\n uid2: " . var_export($uid2, true) . "\r\n", FILE_APPEND | LOCK_EX);
                        file_put_contents("LoginErrStatus7_" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " �������� ����� ������ ��������� ����������� �� ����� " . $login_page_allform_params[msg] . ". login: " . $this->user['username'] . ", pass: " . $this->user['password'] . " ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
                        file_put_contents("LoginErrStatus7_" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    }

                    return array('status' => false, 'code' => 7, 'msg' => "wrong login/password");
                }


                if ($logrole > 0) {
                    // if($this->bot_id==16){
                    // file_put_contents("LoginErrStatus9".$this->bot_id.".txt",date("d.m.Y H:i:s")."=========== \r\n ������ ".__LINE__."\r\n".var_export($login_link_pref,true)."\r\n".var_export($login_link_method,true)."\r\n".var_export($login_page_allform_params,true)."\r\n postdata: ".var_export($postdata,true)."\r\n �����: ".var_export($res_cap,true)."\r\n uid2: ".var_export($uid2,true)."\r\n",FILE_APPEND | LOCK_EX);
                    // file_put_contents("LoginErrStatus9".$this->bot_id.".txt",date("d.m.Y H:i:s")." �������� ����� ������ ��������� ����������� �� ����� ".$login_page_allform_params[msg].". login: ".$this->user['username'].", pass: ".$this->user['password']." ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                    // file_put_contents("LoginErrStatus9".$this->bot_id.".txt",date("d.m.Y H:i:s")." ������ ".__LINE__."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);
                    //  }
                    return array('status' => false, 'code' => 9, 'msg' => "bad_proxy");
                }

                $logrole++;
            }


            // ����� ��������� �� �����
            $login_page_allform_params = $this->parser->getAllFormsParams($html['html']);

            file_put_contents("errlogHtml" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "������ ������ " . $login_page_allform_params[msg] . ". login: " . $this->user['username'] . ", pass: " . $this->user['password'] . " ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);


            // �������� �� ������
            if ($login_page_allform_params[status] === false) {

                //   file_put_contents("errlogHtml".$this->bot_id.".txt",date("d.m.Y H:i:s")."������ ������ ".$login_page_allform_params[msg].". login: ".$this->user['username'].", pass: ".$this->user['password']." ������ ".__LINE__."\r\n",FILE_APPEND | LOCK_EX);
                //  file_put_contents("errlogHtml".$this->bot_id.".txt",date("d.m.Y H:i:s")." ������ ".__LINE__."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);
                //die;
                return array('status' => false, 'code' => 14, 'msg' => 'parsing_error');
            }

            // �������� ����� quick_login_form
            $i = 0;
            while ($login_page_allform_params[forms][$i][id] && $login_page_allform_params[forms][$i][id] != 'quick_login_form') $i++;

            //var_dump($login_page_allform_params[forms][$i]);
            $login_link_method = $this->parser->parseStr($login_page_allform_params[forms][$i][action], 'vk.com/', '');
            $login_link_pref = $this->parser->parseStr($login_page_allform_params[forms][$i][action], 'https://', self::$apiURL);

            // �������� ������ ���������� ��� �������� �� ������
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
                        // ������ ����� �������� � ������. ������ �������
                        //file_put_contents("CAPloginErr".$this->bot_id.".txt",date("d.m.Y H:i:s")." ������ ".__LINE__." captcha_sid=".$login_page_allform_params[forms][$i][params][value][$y].",captcha_key=$captcha_key \r\n",FILE_APPEND | LOCK_EX);

                        reboot(1, __LINE__); // ���������� ������� c ����������� �������� �������� ������
                        die;
                    }
                }

                if ($login_page_allform_params[forms][$i][params][name][$y] == 'captcha_key') {
                    $postdata[$login_page_allform_params[forms][$i][params][name][$y]] = $captcha_key;
                }

                $y++;
            }

            // ���������
            $html = $this->httpCall($login_link_method[html], array('postdata' => $postdata), array('ssl' => true, 'httpPrefix' => $login_link_pref[html], 'dump' => false));

            // ��������� ������ ������ ��� ������ �� ������ ����. ��������� �������
            $html['proxy_alive'] = 1;

            //if($this->bot_id==409)  file_put_contents("log2Html".$this->bot_id.".txt",date("d.m.Y H:i:s")." ������ ".__LINE__."\r\n".var_export($login_link_pref,true)."\r\n".var_export($login_link_method,true)."\r\n".var_export($login_page_allform_params,true)."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);

            if ($html[status]) {
                // ������������ �������� � ������ ������ ����� ����� �������� ��� ���������������
                $restore_check = $this->parser->parseStr($html['html'], 'restore?act=', '&');
                if ($restore_check[html] == 'view') {

                    file_put_contents("LoginRes" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . "\r\n" . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);
                    return array('status' => false, 'code' => 8, 'msg' => "restore");
                }

                $login_link_pref = $this->parser->parseStr($html['lasturl'], 'https://', self::$apiURL);

                // ��������  �� ���� ����� ��������� ������

                if ($login_link_pref[html] == "m.") {
                    // �������� �� �������� ���� � ��������� ������
                    $this->MobileVersion = true;
                    $this->Pref = "m.";
                    // ������� uid ������ ������� �.�. � ��������� ��� ���
                    $uid2 = $this->parser->parseStr($html['html'], '"uid":', ',');
                    //file_put_contents("log2Html".$this->bot_id.".txt",date("d.m.Y H:i:s")." ������ ".__LINE__."\r\n".var_export($login_link_pref,true)."\r\n".var_export($login_link_method,true)."\r\n".var_export($login_page_allform_params,true)."\r\n".var_export($postdata,true)."\r\n".var_export($res_cap,true)."\r\n".var_export($uid2,true)."\r\n".var_export($html,true)."\r\n",FILE_APPEND | LOCK_EX);

                    if ($uid2[status] && $uid2[html] > 0) {
                        $html['uid'] = $uid2[html];
                        $this->bot_vk_id = $uid2[html];
                    } else {
                        // �������� ������ ��� ������������� ����� ��� ������� ���������� (��������� ������ ������)
                        $login_zavis++;
                        if ($login_zavis < 3) goto login_recap;

                        // ������ ������ ���� �����. ���� ���������,
                        file_put_contents("LoginErr" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . " ������ " . __LINE__ . "\r\n" . var_export($login_link_pref, true) . "\r\n" . var_export($login_link_method, true) . "\r\n" . var_export($login_page_allform_params, true) . "\r\n postdata: " . var_export($postdata, true) . "\r\n �����: " . var_export($res_cap, true) . "\r\n uid2: " . var_export($uid2, true) . "\r\n html: " . var_export($html, true) . "\r\n", FILE_APPEND | LOCK_EX);

                        return array('status' => false, 'code' => 2, 'msg' => "login_error");
                    }

                    if ($get_name) {
                        // �������� ��� �������
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
                    // �������� �� �������� ���� � ������� ������
                    // ������� uid
                    preg_match_all('/"uid":"(\d*)"/i', $html['html'], $parse);

                    $uid = $parse[1][0];
                    if ($uid) $this->bot_vk_id = $uid;

                    // ������� short_link
                    preg_match_all("/onLoginDone\('\/(\w*)\'/i", $html['html'], $parse2);
                    $slink = $parse2[1][0];


                    if ($slink) {
                        // ��� ��������� �������� ��������� �� ��������


                        if (!$login_link_pref[status]) {

                            file_put_contents("err2log$this->bot_id.txt", date("d.m.Y H:i:s") . "������ ��������. ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
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
                        // ��������� �� ������ ������
                        $proxy_bad = $this->parser->parseStr($html['html'], 'Client', 'IP');
                        if ($proxy_bad[status]) return array('status' => false, 'code' => 1, 'msg' => "connect_error");

                        // ���-�� ����� �� ���
                        file_put_contents("ErrLogin" . $this->bot_id . ".txt", date("d.m.Y H:i:s") . "\r\n" . var_export($html, TRUE) . "\r\n ������ " . __LINE__ . "\r\n", FILE_APPEND | LOCK_EX);
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