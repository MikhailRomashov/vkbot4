<?php


class HTML_class
{
    protected string $url;
    protected string $html;
    protected int $HTMLsize;

    protected function LoadHtml($url)
    {
        $this->url=$url;

        $this->setHtml(file_get_contents($this->url));

    }

    protected function setHtml($html)
    {
        if(! isset($html) || ! is_string($html)) return false;

        $this->html = $html;
        $this->HTMLsize = strlen($this->html);

        return true;
    }

}
