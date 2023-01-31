<?php

require_once $_SERVER['DOCUMENT_ROOT']."/classes/Curl_classes/Http.php"; // рвбота с http
require_once $_SERVER['DOCUMENT_ROOT']."/classes/ruCaptcha/ruCaptchaClass.php";  // распознавание капчи.
require_once $_SERVER['DOCUMENT_ROOT']."/classes/Parser/HTML_VK_Parser.php";// обработка хтмл
require_once $_SERVER['DOCUMENT_ROOT']."/classes/Service_Classes/ServiceClasses.php";// мелкие сервисные функции

class CeneralClassesInit
{

    // создаем экземпл€ры классов общие дл€ всех
    public $HttpCall;
    public $Log;
    public $Parser;
    public $Captcha;
    public $Services;

    public $bot_id;
    public $bot_vk_id;
    public $user_agent;
    public $proxy;
    public $proxylogin;

    /**
     * General constructor.
     */
    public function __construct($bot_id,$bot_vk_id,$user_agent,$proxy,$proxylogin)
    {
        $this->bot_id       =$bot_id;
        $this->bot_vk_id    =$bot_vk_id;
        $this->user_agent   =$user_agent;
        $this->proxy        =$proxy;
        $this->proxylogin   =$proxylogin;

        // создаем экземл€р класс коннекта с вк через который будут работать все функции
        $this->HttpCall =   new Http($bot_id,$user_agent,$proxy,$proxylogin);

        // экземпл€р парсера
        $this->Parser  = new HTML_VK_Parser();

        $this->Captcha =new ruCaptchaClass();

        $this->Services=new ServiceClasses();

    }
    public function AddExistClass($name,$link)
    {
        $this->$name=$link;
    }

}