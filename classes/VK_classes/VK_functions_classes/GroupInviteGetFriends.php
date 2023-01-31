<?php


class GroupInviteGetFriends extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
    ///////////////////////
    // ������� ������ ������ ��� �������� ����������� � ������
    ////////////////////////
    //function group_invite_get_friends($group_screen_name,$kol=10)

        $kol= $RequestParam['kol'] ?? 10;

        $data = array();
        $friends_out_vkid= array();
        $friends_out_hash= array();

        //������������ �������
        shuffle($this->alphabet_rus);

        // �������� ������ ������ ��� �����������
        $zavis=3;

        do{
            //https://m.vk.com/powerdom?act=invite&q=

            $zavis--;

            $query=array_pop($this->alphabet_rus);
            $data[zavis]=$zavis;


            // �������� �������� �� ������� ������

            $html=$this->Call->httpCall($Method."?act=invite&q=$query", $PostData, $CurlData, $DebugOptions);

            if($html[status])
            {
                list($ht,$ref)=explode("vk.com/",$html[lasturl]);

                // �������� ��������� �� ������������� � ����� ������ ���������� ��� �����������
                if($ref != $Method."?act=invite&q=$query")
                    return array('status' => false , 'code' => 71, 'msg' => "can`t_invite", 'html' =>$html);

                // ������� ������ vk_id
                $friends_out_link = $this->Parser->parseStrAll($html['html'], '?act=a_invite&mid=','">');

                if(!$friends_out_link[status])
                {
                    if($friends_out_link[code]!=12) $this->Log->save("FrGrInvErr",__LINE__,var_export($friends_out_link,true)." \r\n html:  \r\n ". var_export($html,true));
                }
                else
                {
                    $zavis=3;

                    // ��������� �� ������� vkid � hash
                    while(count($friends_out_link[html])>0)
                    {

                        list($vkid,$hash)=explode("&hash=",array_shift($friends_out_link[html]));
                        $friends_out_vkid[$vkid]=$hash;
                    }

                }

            }
            else
            {
                return $html;
            }
        }while(count($this->alphabet_rus)>0 && count($friends_out_vkid)<$kol && $zavis>0); //�������� ���� �� �������� ������� ��� �� ������� ����� ����� ������

        array_push($data,$friends_out_vkid);
        //array_push($data,$friends_out_hash);

        return array('status' => true , 'code' => 70, 'msg' => "search_success" ,'data' => $data);
    }

}