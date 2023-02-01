<?php


abstract  class VK_functions_abstract
{

    public $GenFunc;
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
        $this->GenFunc  =$GenFunc;

        $this->bot_id   = $GenFunc->bot_id;
        $this->bot_vk_id= $GenFunc->bot_vk_id;

        // создаем экземпляр класса логирования с указание вызвващего дочернего класса
        $this->Log      =   new LogClass($GenFunc->bot_id,get_called_class());

        $this->alphabet_rus = array('а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', 'я');
    }
}