<?php


class LogClass
{
    private $bot_id;
    private $from_php_script;

    public function __construct(int $bot_id,string $from_php_script)
    {
        $this->bot_id           =   $bot_id;
        $this->from_php_script   =  pathinfo($from_php_script)['filename'];
    }


    public function save(string $prefix, string $log)
    {

        $from=debug_backtrace();
        file_put_contents($prefix. "_".$from[0]["object"]->from_php_script."_botId". $this->bot_id . ".txt", date("d.m.Y H:i:s") ." строка ".$from[0]["line"]." \r\n $log \r\n================================\r\n", FILE_APPEND | LOCK_EX);

    }
}