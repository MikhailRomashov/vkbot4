<?php


class VkFunctionsConstruct
{
    public $GenFunc;

    public $ClassNameArray;
    public $basedir;

    public function __construct($GenFunc)
    {

        $this->GenFunc=$GenFunc;


        $this->basedir ="VK_functions_classes";

        // подключаем все классы фукций вк
        foreach (glob($this->basedir."/*.php") as $filename)
        {
            $this->ClassNameArray[pathinfo($filename)['filename']]=true;
        }
        // вернуть массив названий классов функций
        return $this->ClassNameArray;


    }

    // создаватьс€ экземпл€оы будут по запросу
    public function __invoke(string $classname,
                             $Method        = array(),
                             $RequestParam  = array(),
                             $PostData      = array(),
                             $CurlData      = array(),
                             $DebugOptions  = array()
                            )
    {
        // провер€ем существует ли экземпл€р вызываемого класса. при необходимости создаем
        if(!isset($this->$classname))  $this->MakeClass($classname);

        // вызываем функцию данного класса и возвращаем резщуьтта работы
        return $this->$classname->doit($Method,$RequestParam,$PostData,$CurlData,$DebugOptions);
    }

    public function MakeClass($classname)
    {
        // проверить есть ли вызываемый класс
        if(!$this->ClassNameArray[$classname]) return false;

        // подключает класс
        require_once $this->basedir."/$classname.php";

        // создает  по запросу экземпл€р класса отдельной функции vk
        $this->$classname= new $classname($this->GenFunc);
    }

}