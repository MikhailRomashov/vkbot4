<?php


class GetPage extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        // TODO: Implement doit() method.
        ///////////////////////
        // открываем заданую страницу
        ////////////////////////

        return $this->Call->httpCall($Method, $PostData, $CurlData, $DebugOptions);


    }
}