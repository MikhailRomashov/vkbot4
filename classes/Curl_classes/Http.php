<?php
require_once "CurlClass.php"; // ������ � curl

class Http extends CurlClass
{

    public function httpCall($method,
                             $params = array('postdata'  => ''),
                             $options = array('ssl' => true),
                             $debug = array('dump' => false)
                            )
    {

        global $httpPrefix;
        $pref   =	$options['httpPrefix'] ?? $httpPrefix;

        $ssl    =	$options['ssl'];
        $test   =	$options['test'];
        $reloc  =	$options['reloc'] === false ??  true;

        $dump   =	$debug['dump'];

        $server = ($ssl ? 'https://' : 'http://').$pref.self::$apiURL;
        $link =	$server.($method ? "/$method":"");

        // �������� ���� � �����
        $link=str_replace("amp;","",$link);

        // ���������
        //file_put_contents("C:/WebServers/home/test1.ru/www/httpCall/call".$this->bot_id.".txt",date("d.m.Y H:i:s")."  ������ ".__LINE__.". proxy : ".$this->proxy." ������  $link \r\n",FILE_APPEND | LOCK_EX);
        //if($params['postdata']) file_put_contents("C:/WebServers/home/test1.ru/www/httpCall/call".$this->bot_id.".txt",date("d.m.Y H:i:s")."  ������ ".__LINE__.". ��������� \r\n".var_export($params['postdata'],true)." \r\n",FILE_APPEND | LOCK_EX);


        $result = $this->SendRequest(array('url' => $link, 'header' => $params['headers'], 'postdata' => $params['postdata'] , 'dump' => $dump,'reloc' => $reloc));


        if($result['status'])
        {
            $this->lastPageHtml = $result['html'];

            // �������� �� ����������
            if(strpos($result['lasturl'],'blocked')>0)
            {
                $result['code']=2;
                $result['msg'] = "login_error";
                $result['status']=false;
            }

            // ������� �� ������ ������ ��� ������� ������ ������

            $length = $this->parser->parseStrAll($result['header'], 'Content-Length: ','C');

            // file_put_contents("packet.txt",date("d.m.Y H:i:s").". ������ ".__LINE__.var_export($length,true)."  �����7 ������ � ���������: ".trim(array_pop($length[html]))." ����� ������ ����: ".strlen($result['html'])."\r\n ���������:\r\n".$result['header']." ////////////////////////////////////////// \r\n  \r\n ",FILE_APPEND | LOCK_EX);

            // �������� ������ � ����� ������ �� ���������
            $head_pack_len=trim(array_pop($length[html]));

            // ���������� ����� ������ �� ���������� ��������� � ����� ��������� ������ ��� �������� �������� ������
            if($head_pack_len> strlen($result['html']))
            {
                // file_put_contents("packetHTML".$this->bot_id.".txt",date("d.m.Y H:i:s").". ������ ".__LINE__."  ����� ������ � ���������: ".$head_pack_len." ����� ������ ����: ".strlen($result['html'])."\r\n ���������:\r\n".$result['header']." \r\n ����:\r\n".$result['html']." \r\n ////////////////////////////////////////// \r\n  \r\n ",FILE_APPEND | LOCK_EX);
                return array('status' => false ,'code' => 1, 'msg' => "connect_error");
            }
        }

        return $result;
    }
}