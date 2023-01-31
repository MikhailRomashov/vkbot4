<?php


class GetFriendsKol extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {

///////////////////////
// �������� ���������� ������
////////////////////////

        $data = array();
        $friends_short_link= array();

        // �������� �������� �� ������� ������
        $html=$this->Call->httpCall('friends',$PostData, $CurlData, $DebugOptions);
        if($html[status])
        {

            // �������� ����� � ����� ����������� ������
            $friends_all_kol = $this->Parser->parseStr($html['html'], '<a href="/friends?section=all"','</a>');

            // ������� ���������� ������
            $friends_kol1 = $this->Parser->parseStr($friends_all_kol['html'], 'dir="auto">','<span class="num_delim">');
            if($friends_kol1[status]===true )
            {
                // ������ ������ 1000. ������� ����� �����
                $friends_kol2 = $this->Parser->parseStr($friends_all_kol['html'], '<span class="num_delim"> </span>','</span>');
                $fr_kol=trim($friends_kol1[html]).trim($friends_kol2[html]);
            }
            else
            {
                //������ ������ 1000. ���������
                $friends_kol = $this->Parser->parseStr($friends_all_kol['html'], 'dir="auto">','</span>');
                $fr_kol=trim($friends_kol[html]);
            }

            // �������� ����� �  ����������� ��������� �������� � ������
            $friends_out_kol = $this->Parser->parseStr($html['html'], '<a href="/friends?section=out_requests"','</a>');

            // ������� ���������� ������
            $friends_out_kol1 = $this->Parser->parseStr($friends_out_kol['html'], 'dir="auto">','<span class="num_delim">');
            if($friends_out_kol1[status]===true )
            {
                // ������ ������ 1000. ������� ����� �����
                $friends_out_kol2 = $this->Parser->parseStr($friends_out_kol['html'], '<span class="num_delim"> </span>','</span>');
                $fr_out_kol=trim($friends_out_kol1[html]).trim($friends_out_kol2[html]);
            }
            else
            {
                //������ ������ 1000. ���������
                $friends_out_kol = $this->Parser->parseStr($friends_out_kol['html'], 'dir="auto">','</span>');
                $fr_out_kol=trim($friends_out_kol[html]);
            }


            //���� ������ ����� ������.
            if($fr_kol)
            {

                array_push($data,$fr_kol);
                array_push($data,$fr_out_kol);
            }
            else
            {
                $this->Log->save("FrOutErr",__LINE__," ������ �������� ���������� ������ \r\n ".var_export($friends,true)." \r\n html:  \r\n ". var_export($html,true));
                array_push($data,0);
                array_push($data,0);

                // �������� �� ������� � ������� ����������� ������
                $friends_page_loaded = $this->Parser->parseStr($html['html'], 'id="fr_','earch_field');

                if(!$friends_page_loaded[status])
                {
                    // ��������� �� ������ ������
                    $proxy_bad = $this->Parser->parseStr($html['html'], 'Client','IP');
                    if($proxy_bad[status]) return array('status' => false ,'code' => 1, 'msg' => "connect_error");

                    // �������� �� ������� ������� ������������������
                    // ������ ��������� ���� ����� ��� ��������������
                    $proxy_toomany = $this->Parser->parseStr($html['html'], 'Too','Many');
                    if($proxy_toomany[status]) die;

                }
            }


            if($fr_kol>0)
            {
                // ������� ������ vk_id
                $friends = $this->Parser->parseStrAll($html['html'], '<div id="res','"></div>');
                if(!$friends[status])
                {
                    //if($friends[code]!=12)
                    $this->Log->save("FrOutErr",__LINE__,var_export($friends,true)." \r\n html:  \r\n ". var_export($html,true));
                }
                else
                {
                    // ��������� �� ������� short_link � vkid
                    while(count($friends[html])>0)
                    {
                        $vkid=array_shift($friends[html]);
                        $short_link="id$vkid";


                        $friends_short_link[$short_link]=$vkid;


                    }

                }
            }
        }
        else
        {
            return $html;
        }




        // ��������� ������ ������

        array_push($data,$friends_short_link);

        return array('status' => true ,'code' => 40, 'msg' => "friends_kol_success",'data' => $data) ;

    }



}