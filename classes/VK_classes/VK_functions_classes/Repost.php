<?php


class Repost extends VK_functions_abstract implements VK_functions_interface
{
    //function repost($link)
    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        /// учет количества запуска функции для вычесление среднего числа капч на 1 запущеную функцию
        $this->Captcha->action++;
        // извлекаем object
        $object = $this->Parser->parseStr($Method, 'object=','&');
        if($object[status])
        {
            // корректируем линк сразу до рабочего вида
            $postdata['object']=$object[html];

            // удаляем лишний & в конце
            $Method=substr($Method, 0, -1);

            // заменяем object=wall на post_from=
            $Method= str_replace("object=wall", "post_from=", $Method);

            // заменяем
            $Method= str_replace("&amp;", "&", $Method);

            $final_link="like?act=add_repost&to=".$this->bot_vk_id."&from_publish=1".$Method;

            $cap_zavis=0;
            $captcha_id=0;

            do {
                $html = $this->Call->httpCall("$final_link", $PostData, $CurlData, $DebugOptions);
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
                $captcha_form = $this->Parser->getParamAll($html['html']);

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


                if ($captcha_form[params][name][1] == 'captcha_sid')
                {
                    // при повторном запросе капчи
                    if($cap_zavis>0)
                    {
                        $this->Captcha->reportbad($captcha_id);

                        $this->Log->save("RepostCapErr", __LINE__, " ошибка распочнания капчи при репосте. Форма:\r\n" . var_export($captcha_form, true) . " \r\n postdata:\r\n" . var_export($postdata, true) . " \r\n");


                    }

                    // !!! проверить правильность оработки капчи, сравнить с классом frending
                    $postdata[$captcha_form[params][name][1]] = $captcha_form[params][value][1];
                    $postdata[$captcha_form[params][name][2]] = $captcha_form[params][value][2];

                    $res_cap = $this->Captcha->recognize('https://' . $this->Pref . $this->apiURL . "/captcha.php?s=1&sid=" . $captcha_form[params][value][1]);
                    $captcha_key = $res_cap[0];
                    $captcha_id = $res_cap[1];
                    $cap_zavis++;

                    if ($captcha_key == false)
                    {
                        // скорее всего проблема с прокси. ставим признак
                        $this->Services->reboot(1); // перезапуск бота
                    }

                    // возможно надо if ($captcha_form[params][name][2] == 'captcha_key')
                    if ($captcha_form[params][name][3] == 'captcha_key')
                    {
                        // возможно надо $postdata[$captcha_form[params][name][2]] = $captcha_key;
                        $postdata[$captcha_form[params][name][3]] = $captcha_key;
                    }

                    $this->Log->save("CAPlogRepost",__LINE__," Капча при заявке в друзья. Форма:\r\n".var_export($captcha_form,true)." \r\n postdata:\r\n".var_export($postdata,true));

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
        else
        {
            $this->Log->save("ErrParsing",__LINE__," \r\n парсился линк $Method результат парсинга :\r\n". var_export($object,true). " \r\n");
            return array('status' => false ,'code' => 14, 'msg' => 'parsing_error') ;
        }
    }
}