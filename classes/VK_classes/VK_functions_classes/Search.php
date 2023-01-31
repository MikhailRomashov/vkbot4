<?php


class Search extends VK_functions_abstract implements VK_functions_interface
{

///////////////////////
// поиск людей
////////////////////////
//function search( $country=0,$city=0, $section='people',$online=true,$photo=1,$offset=0,$detail_level=0)
    public function doit(string $Method,
                         array  $RequestParam,
                         array  $PostData,
                         array  $CurlData,
                         array  $DebugOptions)
    {
        $data           = array();
        $data['vk_id']    = array();

        $friends_vkid       = array();
        $friends_short_link = array();

        // параметры исользуется однократно, но возможно потребуется использовать чаще
        $country       =$RequestParam['country']  ?? 0;
        $city          =$RequestParam['city']     ?? 0;
        $section       =$RequestParam['section']  ?? 'people';
        $online        =$RequestParam['online']   ?? true;
        $photo         =$RequestParam['photo']    ?? 1;

        // параметры исользуется неоднократно
        $offset        =$RequestParam['offset']       ?? 0;
        $detail_level  =$RequestParam['detail_level'] ?? 0;

        $zavis=10;

        do{
            $param='';
            $zavis--;
            $null_offset=false;

            $data[zavis]=$zavis;
            $data[detail_level]=$detail_level;
            $data[offset]=$offset;

            // праметры запроса
            if($country) 	$param .= "c[country]=$country&";
            if($city) 	    $param .= "c[city]=$city&";
            if($offset) 	$param .= "offset=$offset&";
            if($section) 	$param .= "c[section]=$section&";
            if($online) 	$param .= "c[online]=$online&";
            if($photo) 	    $param .= "c[photo]=$photo&";

            if($detail_level>=1)
            {
                if(!$month)
                {
                    $month=rand(1,12);

                    // определяем список длинных месяцеы
                    $long_month=array(1,3,5,7,8,10,12);

                    // проверяем выпал ли длянный месяц
                    if(in_array($month,$long_month))
                    {
                        $day=rand(1,31);
                    }
                    elseif($month==2)
                    {
                        $day=rand(1,28);
                    }
                    else
                    {
                        $day=rand(1,30);
                    }
                }

                $param .= "c[bmonth]=$month&";
            }

            if($detail_level>=2)
            {
                $param .= "c[bday]=$day&";
            }

            if($detail_level>=3)
            {
                // год рождения
                if(!$byear) $byear=rand(1960,2005);
                $param .= "c[byear]=$byear&";
            }

            if($detail_level>=4)
            {
                // cемейное положение
                if(!$cstatus) $cstatus=rand(1,8);
                $param .= "c[status]=$cstatus&";
            }


            if($detail_level>=5)
            {
                // пол
                if(!$csex) $csex=rand(1,2);
                $param .= "c[sex]=$csex&";
            }


            // удаляем концевой амперсанд
            $param=substr($param, 0, -1);

            // открывем страницу с результатами поиска
            $html=$this->Call->httpCall('search'.($param ? "?$param":""),$PostData, $CurlData, $DebugOptions);

            // находим количество резултатов поиска
            if($html[status])
            {

                $search_res_kol=null;


                // убеждаемся что станица получена правильно
                $search_res_kol = $this->Parser->parseStr($html['html'], '<span class="page_block_header_count','</span></div>');

                if($search_res_kol[status]===true )
                {

                    $search_res_kol2 = null;
                    $search_res_kol2 = $this->Parser->parseStr($search_res_kol['html'], '">','<span class="num_delim">');

                    // убеждаемся что результатов поиска больше 1000
                    if($search_res_kol2[status]===true  && is_numeric(trim($search_res_kol2[html])))
                    {
                        $data[search_kol]=1000;
                        $detail_level++;
                        $data[detail_level]=$detail_level;

                        // при изменениии детализации поиска сбрасываем
                        $null_offset=true;
                    }
                    else
                    {
                        $sk=trim(str_replace('">','',$search_res_kol[html]));

                        if(is_numeric($sk))
                        {

                            // выяснеям сколько результов меньше 1000
                            if(!$data[search_kol] || $data[search_kol] ==1000)
                            {
                                // вычисляем количество циклов после которых возможно зависание
                                $zavis = ceil($sk/18)-1;
                            }

                            $data[search_kol]=  $sk;
                        }

                    }
                }
                else
                {
                    // возможно необъодимо анализировать не произошел ли переход на мобильную страницу
                    $reloc_check = $this->Parser->parseStr($html['lasturl'], '://','/search?');
                    if($reloc_check['html'] =='m.vk.com' || strpos($html['lasturl'],'badbrowser')>0)
                    {
                        return array('status' => false , 'code' => 6,  'msg' => "bad_browser",'data' => $data);

                    }
                    else
                    {
                        //ошибка получения страницы

                        // проверяем на ошибку прокси
                        $proxy_bad = $this->Parser->parseStr($html['html'], 'Client','IP');
                        if($proxy_bad['status']) return array('status' => false ,'code' => 1, 'msg' => "connect_error");

                        return array('status' => false , 'code' => 14, 'msg' => 'parsing_error','data' => $data);
                    }
                }



                // создаем массив  vk_id
                $friends = $this->Parser->parseStrAll($html['html'], 'data-id="','"');
                if(!$friends['status'])
                {
                    //if($friends[code]!=12)
                     return array('status' => false , 'code' => 14, 'msg' => 'parsing_error','data' => $data);
                }
                else
                {

                    if($null_offset)
                    {
                        $offset=0;
                        $null_offset=false;

                    }
                    else
                    {
                        $offset += count($friends['html']);
                    }

                    $data['vk_id']=array_merge($data['vk_id'],$friends['html']);
                }
            }
            else
            {
                $html['data'] = $data;
                return $html;
            }
        }while($zavis>0 && $offset <$data['search_kol']-1);



        return array('status' => true ,'code' => 60, 'msg' => "search_success",'data' => $data) ;

    }
}