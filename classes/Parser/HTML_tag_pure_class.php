<?php
require_once "HTML_tag_raw_class.php";



class HTML_tag_pure_class extends HTML_tag_raw_class
{


    public function TagPure($start = Null): array
    {
        $tag_raw=$this->TagRaw($start);

        if($tag_raw['status'])
        {
            $last_tag='';

            // выделяем имя тега
            $tag = preg_split("/['>',' ']/", $tag_raw['string']);

            //учитываем тег если он не закрывающий
            if (!str_starts_with($tag[0], "/") && !str_contains($tag[0],'!')) $last_tag=$tag[0];

            return array('status'=>true, 'position'=>$tag_raw['position'],'tag'=>strtolower($last_tag));
        }
        else
        {
            return array('status'=>false, 'msg' => 'tag_not_found');
        }

    }


}
