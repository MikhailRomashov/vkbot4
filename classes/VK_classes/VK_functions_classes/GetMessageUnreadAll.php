<?php


class GetMessageUnreadAll extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        ///////////////////////
// получаем список непрочтианных сообщегий
////////////////////////
/// function messages_get_all_unread()

       // $messages_out_link=array();

        $html=$this->httpCall('mail',$PostData, $CurlData, $DebugOptions);

        if($html[status])
        {

            // находимнепрочитанные мессаги
            $messages_out_link = $this->Parser->parseStrAll($html['html'], 'di_unread_inbox" href="/mail?act=show&','"');

            if(!$messages_out_link[status])
            {
                if($messages_out_link[code]!=12)
                    $this->Log->save("MesInErr",__LINE__, var_export($messages_out_link,true)." \r\n html:  \r\n ". var_export($html,true));
            }

        }
        else
        {
            return $html;
        }

        return array('status' => true ,'code' => 80, 'msg' => "message_list_get_success", 'data' => $messages_out_link[html]) ;

    }
}