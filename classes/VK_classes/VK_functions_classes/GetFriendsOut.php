<?php


class GetFriendsOut extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
///////////////////////
// �������� ������ ��������� ������ � �����
////////////////////////
//function get_friends_out($offset=0)
        {
            $data = array();
            $friends_out_vkid= array();
            $friends_out_hash= array();

            $offset  =$RequestParam['offset']  ?? 0;

            // ��� �� ������ ������ ������ �����
            // �������� �������� �� ������� ��������� ������ � ������ ����
            $html=$this->Call->httpCall('friends?section=out_requests&offset='.$offset, $PostData, $CurlData, $DebugOptions);
            if($html[status])
            {

                // ������� ���������� ������
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

                if($fr_out_kol)
                {
                    array_push($data,$fr_out_kol);
                }
                else
                {
                    array_push($data,0);
                }

                // ������� ������ vk_id

                $friends_out = $this->Parser->parseStrAll($html['html'], 'Friends.declineRequest(','&#39;, this)');
                if(!$friends_out[status])
                {
                    //if($friends[code]!=12)
                    $this->Log->save("FrOutErr",__LINE__,var_export($friends,true)." \r\n html:  \r\n ". var_export($html,true));
                }
                else
                {

                    // ��������� �� �������  vkid � hash
                    while(count($friends_out[html])>0)
                    {
                        list($vkid,$hash)=preg_split(", &#39;",array_shift($friends_out[html]));

                        array_push($friends_out_vkid,$vkid);
                        array_push($friends_out_hash,$hash);
                    }

                    array_push($data,$friends_out_vkid);
                    array_push($data,$friends_out_hash);
                }

            }
            else
            {
                return $html;
            }
            return array('status' => true ,'code' => 40, 'msg' => "friends_kol_success",'data' => $data) ;
        }
    }
}