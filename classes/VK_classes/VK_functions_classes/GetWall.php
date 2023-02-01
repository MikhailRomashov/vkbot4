<?php


class GetWall extends VK_functions_abstract implements VK_functions_interface
{
    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        //function get_wall($screen_name,$group_id, $post_kol=1,$all_post=false)

        $group_id=$RequestParam['group_id'];
        $post_kol=$RequestParam['post_kol'] ?? 1;
        $all_post=$RequestParam['all_post'] ?? false;

            $post_link 	= array();
            $post_id	= array();
            //https://m.vk.com/like?act=publish&object=wall-51699403_4633&from=wall-51699403
            //https://m.vk.com/like?act=del&object=wall-51699403_4627&from=wall-51699403&hash=8a6e0ac9c510432035&one=0  это линк на дизлайк. используем как признак уже сделаного репоста
            //https://m.vk.com/like?act=add_repost&post_from=-51699403_4647&from=wall-51699403&hash=8a6e0ac9c510432035&to=6827658&from_publish=1
            $html=$this->Call->httpCall($Method, $PostData, $CurlData, $DebugOptions);

            if($html[status])
            {

                $correction=0; // корректор на случай если полученая строка нам не нраивтьься
                for($i=1;$i<=$post_kol;$i++)
                {
                    // получить очередную запись со стены

                    $link = $this->Parser->parseStr($html['html'], 'like?act=set_reaction','amp;one=0"',$i+$correction);

                    // если ничего не найдено то возможно имеем дело со старой версией отображения
                    if(!$link[status]) $link = $this->Parser->parseStr($html['html'], 'like?act=add','one=0"',$i+$correction);

                    // если запись успешно получена
                    if($link[status])
                    {


                        // исключаем рекламые записи
                        if(!$all_post)
                        {
                            // проверяем вхождение id данной группы в ссылку
                            if(strpos($link[html],$group_id)==0)
                            {
                                // вхождения нет значит запись рекламная
                                // получили ненужный нам линк. игнорим
                                $correction++;
                                $i--;
                                continue;
                            }

                        }

                        // вычленяем id поста

                        $postid= $this->Parser->parseStr($link[html], 'wall-'.$group_id.'_','&',1);

                        // проверка дубликата
                        if(in_array($postid[html], $post_id))
                        {
                            // получили ненужный нам линк. игнорим
                            $correction++;
                            $i--;
                            continue;
                        }

                        // добавляем ее в массив актуальных записей
                        array_push($post_link,$link[html]);

                        // заносим id записи
                        array_push($post_id,$postid[html]);



                    }
                    else
                    {
                        // на стене группы  не найдено ни очередной записи
                        if(count($post_link)==0) return array('status' => true ,'code' => 31, 'msg' => "not_complete_get_wall",'links' => $post_link, 'post_id' =>$post_id) ;
                    }
                }
                // возравщаем массив актуальных к репосту записей
                return array('status' => true ,'code' => 30, 'msg' => "success_get_wall",'links' => $post_link, 'post_id' =>$post_id) ;
            }
            else
            {
                // если данные ссо стены группы не получены
                return $html;
            }

        }
}