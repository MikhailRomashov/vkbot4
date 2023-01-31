<?php

require_once "HTML_class.php";

class HTML_string_raw_class extends HTML_class
{


    protected  $start;
    protected  $stop;

    public function __construct()
    {
        $this->start = 0;
        $this->stop =0;
    }

    public function getString($start = Null, $prefix='',$suffix=''): array
    {
        $this->start = is_null($start) ?  $this->stop : $start ;

        // проверяем есть ли что обрабатывать
        if (!isset($this->html) || !is_string($this->html))  return array('status' => false, 'code' => 11, 'msg' => 'bad_html');

        // проверяем выход за пределы размера обрабатываемого кода хтмл
        if($this->start >= $this->HTMLsize-1) return array('status'=>false,'position'=>$this->start,'string'=>'');

        // начало искомой строки
        if(false === $this->start = strpos($this->html, $prefix, $this->start))
            return array('status' => false, 'code'  => 12,   'msg'=> 'no_entry_start');

        // конец искомой строки
        if(false === $this->stop = strpos($this->html, $suffix, $this->start))
            return array('status' => false,  'code'  => 13,   'msg'=>  'no_entry_stop');

        // вычленяием всю сторку
        $raw = substr($this->html, $this->start + strlen($prefix), $this->stop - $this->start);


        // воззращаем тег и позицию
        return array('status'=>true,'position'=>$this->stop,'string'=>$raw);
    }




}
