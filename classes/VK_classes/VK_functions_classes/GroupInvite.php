<?php


class GroupInvite extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {

///////////////////////
// ������� ������ ������ ��� �������� ����������� � ������
////////////////////////
/// /function group_invite($group_screen_name,$user_for_invite_id,$user_for_invite_hash)


       // ���������� �� ����� ����� �������� ���������
        $user_for_invite_id     =$RequestParam['user_for_invite_id']    ?? 0;
        $user_for_invite_hash   =$RequestParam['user_for_invite_hash']  ?? '';

        $this->Captcha->action++;


        //https://m.vk.com/powerdom?act=a_invite&mid=$user_for_invite_id&hash=$user_for_invite_hash

        // �������� �������� �� ������� ������
        $html=$this->Call->httpCall("$Method?act=a_invite&mid=$user_for_invite_id&hash=$user_for_invite_hash", $PostData, $CurlData, $DebugOptions);
        $this->Log->save("GrInv_",__LINE__," \r\n ������ $Method?act=a_invite&mid=$user_for_invite_id&hash=$user_for_invite_hash :\r\n �������� ������� :\r\n". var_export($html,true));

        if($html[status])
        {

            // ���� ������� �������� �� act=invite?g="id������"  ��� ?act=invite&m=380&g="id������"&u=$bot_vk_id&h=.....
            if(strpos($html['lasturl'],"m=380")>0) return array('status' => true , 'code' => 72, 'msg' => "invite_success" );

            // ���� ��� ���������� �� last_url = ?act=invite&m=381
            if(strpos($html['lasturl'],"m=381")>0) return array('status' => false , 'code' => 73, 'msg' => "invite_alredy" );

            // ���� �������� ���������� �� last_url == ?act=invite&m=383
            if(strpos($html['lasturl'],"m=383")>0) return array('status' => false , 'code' => 74, 'msg' => "invite_forbidden" );

            // ���� �������� ������� ����� �� last_url == ?act=invite&m=386
            if(strpos($html['lasturl'],"m=386")>0) return array('status' => false , 'code' => 75, 'msg' => "day_limit" );

        }
        else
        {
            return $html;
        }


        return array('status' => true , 'code' => 72, 'msg' => "invite_success" );
    }


    }