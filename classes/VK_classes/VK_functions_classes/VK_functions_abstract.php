<?php


abstract  class VK_functions_abstract
{

    public $Call;
    public $Log;
    public $Parser;
    public $Captcha;
    public $Services;
    public $alphabet_rus;
    public $bot_id;
    public $bot_vk_id;
    public $apiURL;
    public $httpPrefix;


    public function __construct($GenFunc)
    {
        global $apiURL,$httpPrefix;
        $this->apiURL =$apiURL;
        $this->Pref   =$httpPrefix;

        // передаем ссылки на созданные в CeneralClassesInit общие экземплеры классов
        $this->Call     =$GenFunc->HttpCall;
        $this->Parser   =$GenFunc->Parser;
        $this->Captcha  =$GenFunc->Captcha;
        $this->Services =$GenFunc->Services;

        $this->bot_id   = $GenFunc->bot_id;
        $this->bot_vk_id= $GenFunc->bot_vk_id;

        // создаем экземпл€р класса логировани€ с указание вызвващего дочернего класса
        $this->Log      =   new LogClass($GenFunc->bot_id,get_called_class());

        $this->alphabet_rus = array('а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', '€');
    }
}