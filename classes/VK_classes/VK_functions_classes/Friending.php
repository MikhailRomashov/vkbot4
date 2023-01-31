<?php


class Friending extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        ///////////////////////
            // шлем заявку в друзья
            ////////////////////////
            /// function friending($vk_id)
        global $VKfunc;
        $vk_id   =$RequestParam['vk_id']    ?? 0;

        $this->Captcha->action++;

        //https://m.vk.com/friends?act=accept&id=17556568&from=profile&hash=60d59e6e50ac7a20bc


        // зайти в профиль юзера
        $html=$VKfunc("GetProfile",'id'.$vk_id);

        if($html[status])
        {
            // страница профиля удачно получена. парсим
            // найти ссылку на добавление в друзья
            //https://m.vk.com/friends?act=decline&id=17556568&from=profile&hash=725d0330fe9bdda04e
            $add_friend_link = $this->Parser->parseStr($html['html'], '/friends?','"');


            if($add_friend_link[status]===true)
            {

                // Проверка на случай неверного парсинга
                if(strlen($add_friend_link[html])>100)
                {
                    $this->Log->save("ErrParsing_",__LINE__," \r\n результат парсинга юзера $vk_id:\r\n". $add_friend_link[html]. " \r\n страница целиком :\r\n". $html['html']);
                    return array('status' => false ,'code' => 14, 'msg' => 'parsing_error') ;
                }

                // проверяем была ли послана заявка ранее
                $add_friend_link_decline = $this->Parser->parseStr($add_friend_link[html], 'act=','&');

                if($add_friend_link_decline[status]===true)
                {
                    //  парсинг прошел удачно

                    if($add_friend_link_decline[html]=='accept')
                    {
                        $cap_zavis=0;
                        $captcha_id=0;

                        do {
                            // послать запрос на добавления в друзья
                            $html=$this->Call->httpCall('friends?'.$add_friend_link[html], $PostData, $CurlData, $DebugOptions);
                            if (!$html[status]) return $html;

                            if($cap_zavis>0)
                            {
                                // СБРАСЫВАЕм   при повторном запроск
                                // делаем проверку на ошибку распознавания
                                $captcha_form[status] = false;
                                $captcha_form[params][name][1] = '';
                                $captcha_form[params][value][1] = '';
                            }

                            // делаем проверку на капчу
                            //  разбираем на формы
                            $captcha_form	= $this->Parser->getParamAll($html['html']);

                            // капча не обнаружена, успешный репост
                            if(!$captcha_form[status])
                            {
                                // если перед этим была обработана капча то учитываем это
                                if($cap_zavis)
                                {
                                    // провряем ситуацию когда бот работает без телефона с профиде с постоянными капчами
                                    if($this->Captcha->TooMuchCaptchaReaction())
                                    {
                                        $this->Log->save("ChangePhone", __LINE__ ,". запросов капчи:" . $this->Captcha->captcha . ". выполнено функций " . $this->Captcha->action . " \r\n");
                                        return array('status' => false, 'code' => 3, 'msg' => "change_phone");
                                    }
                                }

                                // иначе считаем репост успешным
                                return array('status' => true, 'code' => 50, 'msg' => "success_repost");
                            }


                            $this->Log->save("FrAdd5_",__LINE__," \r\n запрос профиля юзера $vk_id :\r\n страница целиком :\r\n". var_export($captcha_form,true));

                            if ($captcha_form[params][name][1] == 'captcha_sid')
                            {
                                // при повторном запросе капчи
                                if($cap_zavis>0)
                                {
                                    $this->Captcha->reportbad($captcha_id);

                                    $this->Log->save("RepostCapErr", __LINE__, " ошибка распочнания капчи при репосте. Форма:\r\n" . var_export($captcha_form, true) . " \r\n postdata:\r\n" . var_export($postdata, true) . " \r\n");


                                }

                                // !!! проверить правильность оработки капчи, сравнить с классом repost
                                $postdata[$captcha_form[params][name][0]]=$captcha_form[params][value][0];

                                $res_cap = $this->Captcha->recognize('https://'.$this->Pref.self::$apiURL."/captcha.php?s=1&sid=".$captcha_form[params][value][0]);
                                $captcha_key=$res_cap[0];
                                $captcha_id=$res_cap[1];
                                $cap_zavis++;

                                if($captcha_key==false)
                                {
                                    // скорее всего проблема с прокси. ставим признак
                                    $this->Services->reboot(1); // перезапуск бота
                                }


                                if($captcha_form[params][name][1] == 'captcha_key')
                                {

                                    $postdata[$captcha_form[params][name][1]]=$captcha_key;
                                }

                                $this->Log->save("CAPlogFrAdd",__LINE__," Капча при заявке в друзья. Форма:\r\n".var_export($captcha_form,true)." \r\n postdata:\r\n".var_export($postdata,true));
                            }
                            else
                            {
                                if($captcha_form[params][name][0] == 'is_board' || $captcha_form[params][name][2] == 'is_board')
                                    return array('status' => true, 'code' => 50, 'msg' => "success_repost");

                                if ($captcha_form[params][name][0] == 'phone')
                                {
                                    //проверка на блокировки бота
                                    return array('status' => false, 'code' => 2, 'msg' => "login_error");
                                }
                                else
                                {
                                    // непонятная ситуаця, логируем
                                    $this->Log->save("CAPlogRepost" , __LINE__," final_link $final_link \r\n" . var_export($postdata, true) . "\r\n" . var_export($captcha_form, true) . "\r\n" . var_export($html, true) . "\r\n");
                                    die;
                                }
                            }

                        }while($cap_zavis<3);

                        // произошло зависание при распознавании капчи. убиваем скрипт бота
                        $this->Log->save("FrAddCapErr", __LINE__, " завис при распознании капчи cap_zavis=$cap_zavis \r\n");
                        die;

                    }
                    elseif($add_friend_link_decline[html]=='decline')
                    {
                        // заявка была послана ранее
                                        return array('status' => true ,'code' => 21, 'msg' => "already_friended") ;
                    }
                    else
                    {
                        //что то не так
                        $this->Log->save("ErrParsing_$vk_id.txt",__LINE__," \r\n результат парсинга :\r\n". $add_friend_link[html]. " \r\n страница целиком :\r\n");
                        return array('status' => true ,'code' => 14, 'msg' => 'parsing_error') ;
                    }
                }
                else
                {
                    $this->Log->save("ErrParsing_$vk_id.txt",__LINE__," \r\n результат парсинга :\r\n". $add_friend_link[html]. " \r\n страница целиком :\r\n". $html['html']);

                    return array('status' => true ,'code' => 10, 'msg' => "string_not_found") ;
                }


            }
            else
            {
                // запращивамая страниуа либо удалена либо юзер не допускает заявок в друзья
                return array('status' => true ,'code' => 10, 'msg' => "string_not_found") ;
            }
        }
        else
        {
            return $html;
        }

    }



}