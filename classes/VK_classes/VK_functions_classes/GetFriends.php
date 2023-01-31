<?php


class GetFriends extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
///////////////////////
// получаем  друзей
////////////////////////
//function get_friends($offset=0,$online=false, $vk_id=0)

        $data = array();
        $friends_vkid= array();
        $friends_short_link= array();
        $param='';

        // параметры исользуется однократно, но возможно потребуется использовать чаще
        $online  =$RequestParam['online']   ?? false;
        $offset  =$RequestParam['offset']   ?? 0;
        $vk_id   =$RequestParam['vk_id']    ?? 0;

        // праметры запроса
        if($vk_id)  $param .= "id=$vk_id&";
        if($offset) $param .= "offset=$offset&";
        if($online) $param .= "section=online&";

        // удаляем концевой амперсанд
        $param=substr($param, 0, -1);

        // открывем страницу со списком друзей
        $html=$this->Call->httpCall('friends'.($param ? "?$param":""),$PostData, $CurlData, $DebugOptions);
        if($html['status'])
        {


            // создаем массив vk_id
            $friends = $this->Parser->parseStrAll($html['html'], 'si_owner" href="/','?mvk_entrypoint');
            if(!$friends['status'])
            {
                //if($friends[code]!=12)
                $this->Log->save("FrOutErr",__LINE__,var_export($friends,true)." \r\n html:  \r\n ". var_export($html,true));
            }
            else
            {
                // разделяем на массивы short_link И vkid
                while(count($friends[html])>0)
                {
                    list($short_link ,$last_part)	=preg_split("\?from=friends",array_shift($friends[html]));

                    list($trash ,$vkid) 		=preg_split('href="/write',$last_part);

                    if(!$vkid)
                    {
                        // выделем vk_id из $short_link если можно
                        list($pref ,$maybevkid)	=preg_split("id",$short_link);
                        if(!$pref  && is_numeric($maybevkid)) $vkid=$maybevkid;
                    }
                    $friends_short_link[$short_link]=($vkid ? $vkid:0);
                }

                array_push($data,$friends_short_link);
            }
        }
        else
        {
            return $html;
        }

        return array('status' => true ,'code' => 40, 'msg' => "friends_kol_success",'data' => $data) ;

    }



}