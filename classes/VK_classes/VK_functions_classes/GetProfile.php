<?php


class GetProfile extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        // TODO: Implement doit() method.
        ///////////////////////
        // открываем заданую страницу
        ////////////////////////
        ///https://m.vk.com/id$vk_id
        //call("$link", array('postdata'  => ''), array('ssl' => true, 'httpPrefix' =>$this->Pref, 'dump' => false));

        return $this->Call->httpCall('id'.$Method, $PostData, $CurlData, $DebugOptions);
    }
}