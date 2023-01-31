<?php

require_once "HTML_string_raw_class.php";

class HTML_tag_raw_class extends HTML_string_raw_class
{
    protected string $prefix;
    protected string $suffix;

    public function __construct()
    {
        parent::__construct();
        $this->prefix = "<";
        $this->suffix = ">";
    }

    public function TagRaw($start = Null): array
    {
        // сущестование этого класса обусловлено возможной необходимостью выделять не сами теги, а их параметры
        return $this->getString($start, $this->prefix, $this->suffix);
    }




}
