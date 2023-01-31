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

        // ���������� ��� ������ ������ ��
        foreach (glob($this->basedir."/*.php") as $filename)
        {
            $this->ClassNameArray[pathinfo($filename)['filename']]=true;
        }
        // ������� ������ �������� ������� �������
        return $this->ClassNameArray;


    }

    // ����������� ���������� ����� �� �������
    public function __invoke(string $classname,
                             $Method        = array(),
                             $RequestParam  = array(),
                             $PostData      = array(),
                             $CurlData      = array(),
                             $DebugOptions  = array()
                            )
    {
        // ��������� ���������� �� ��������� ����������� ������. ��� ������������� �������
        if(!isset($this->$classname))  $this->MakeClass($classname);

        // �������� ������� ������� ������ � ���������� ��������� ������
        return $this->$classname->doit($Method,$RequestParam,$PostData,$CurlData,$DebugOptions);
    }

    public function MakeClass($classname)
    {
        // ��������� ���� �� ���������� �����
        if(!$this->ClassNameArray[$classname]) return false;

        // ���������� �����
        require_once $this->basedir."/$classname.php";

        // �������  �� ������� ��������� ������ ��������� ������� vk
        $this->$classname= new $classname($this->GenFunc);
    }

}