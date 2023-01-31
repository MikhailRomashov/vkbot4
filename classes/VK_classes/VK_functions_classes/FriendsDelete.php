<?php


class FriendsDelete extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
 ///////////////////////
// удаление исходящих запросов дружбы
////////////////////////
//function friends_delete($vk_id=0,$hash=0)

        $vk_id  =$RequestParam['vk_id']  ?? 0;
        $hash   =$RequestParam['hash']   ?? 0;

        if(!$vk_id) return array('status' => false ,'code' => 43, 'msg' => 'wrong_vk_id') ;
        if(!$hash)  return array('status' => false ,'code' => 44, 'msg' => 'wrong_hash') ;

        // шлем запрос на удаление
        //https://vk.com/al_friends.php?act=remove
        // post :al=1,from_section=out_requests, hash=$hash,mid=$vk_id,report_spam=1
        $param[al]=1;
        $param[from_section]="out_requests";
        $param[hash]=$hash;
        $param[mid]=$vk_id;
        $param[report_spam]=1;

        $html=$this->Call->httpCall('friends?act=remove', $PostData, $CurlData, $DebugOptions);
        if($html[status])
        {
            return array('status' => true ,'code' => 45, 'msg' => 'success_decline') ;
        }
        else
        {
            return $html;
        }
    }



}