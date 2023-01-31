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

        // �������� ������ �� ��������� � CeneralClassesInit ����� ���������� �������
        $this->Call     =$GenFunc->HttpCall;
        $this->Parser   =$GenFunc->Parser;
        $this->Captcha  =$GenFunc->Captcha;
        $this->Services =$GenFunc->Services;

        $this->bot_id   = $GenFunc->bot_id;
        $this->bot_vk_id= $GenFunc->bot_vk_id;

        // ������� ��������� ������ ����������� � �������� ���������� ��������� ������
        $this->Log      =   new LogClass($GenFunc->bot_id,get_called_class());

        $this->alphabet_rus = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
    }
}