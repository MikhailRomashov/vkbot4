<?php

require_once "HTML_tag_all_parseURL_class.php";
$parser = new HTML_tag_all_parseURL_class();

$tag_array = $parser->parser('https://vkbot24.ru');
var_dump($tag_array);


