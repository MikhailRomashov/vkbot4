<?php


class GroupInvite extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {

///////////////////////
// получам список друзей для рассыоки приглашение в группу
////////////////////////
/// /function group_invite($group_screen_name,$user_for_invite_id,$user_for_invite_hash)


       // адаптируем из новой форма передачи праметров
        $user_for_invite_id     =$RequestParam['user_for_invite_id']    ?? 0;
        $user_for_invite_hash   =$RequestParam['user_for_invite_hash']  ?? '';

        $this->Captcha->action++;


        //https://m.vk.com/powerdom?act=a_invite&mid=$user_for_invite_id&hash=$user_for_invite_hash

        // открывем страницу со списком друзей
        $html=$this->Call->httpCall("$Method?act=a_invite&mid=$user_for_invite_id&hash=$user_for_invite_hash", $PostData, $CurlData, $DebugOptions);
        $this->Log->save("GrInv_",__LINE__," \r\n запрос $Method?act=a_invite&mid=$user_for_invite_id&hash=$user_for_invite_hash :\r\n страница целиком :\r\n". var_export($html,true));

        if($html[status])
        {

            // если успешно пригласи то act=invite?g="idгруппы"  или ?act=invite&m=380&g="idгруппы"&u=$bot_vk_id&h=.....
            if(strpos($html['lasturl'],"m=380")>0) return array('status' => true , 'code' => 72, 'msg' => "invite_success" );

            // если уже приглашали то last_url = ?act=invite&m=381
            if(strpos($html['lasturl'],"m=381")>0) return array('status' => false , 'code' => 73, 'msg' => "invite_alredy" );

            // если запретил приглашать то last_url == ?act=invite&m=383
            if(strpos($html['lasturl'],"m=383")>0) return array('status' => false , 'code' => 74, 'msg' => "invite_forbidden" );

            // если превышен дневной лимит то last_url == ?act=invite&m=386
            if(strpos($html['lasturl'],"m=386")>0) return array('status' => false , 'code' => 75, 'msg' => "day_limit" );

        }
        else
        {
            return $html;
        }


        return array('status' => true , 'code' => 72, 'msg' => "invite_success" );
    }


    }