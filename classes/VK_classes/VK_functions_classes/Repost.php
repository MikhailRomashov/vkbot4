<?php


class Repost extends VK_functions_abstract implements VK_functions_interface
{
    //function repost($link)
    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        /// ���� ���������� ������� ������� ��� ���������� �������� ����� ���� �� 1 ��������� �������
        $this->Captcha->action++;
        // ��������� object
        $object = $this->Parser->parseStr($Method, 'object=','&');
        if($object[status])
        {
            // ������������ ���� ����� �� �������� ����
            $postdata['object']=$object[html];

            // ������� ������ & � �����
            $Method=substr($Method, 0, -1);

            // �������� object=wall �� post_from=
            $Method= str_replace("object=wall", "post_from=", $Method);

            // ��������
            $Method= str_replace("&amp;", "&", $Method);

            $final_link="like?act=add_repost&to=".$this->bot_vk_id."&from_publish=1".$Method;

            $cap_zavis=0;
            $captcha_id=0;

            do {
                $html = $this->Call->httpCall("$final_link", $PostData, $CurlData, $DebugOptions);
                if (!$html[status]) return $html;

                if($cap_zavis>0)
                {
                    // ����������   ��� ��������� �������
                    // ������ �������� �� ������ �������������
                    $captcha_form[status] = false;
                    $captcha_form[params][name][1] = '';
                    $captcha_form[params][value][1] = '';
                }

                // ������ �������� �� �����
                //  ��������� �� �����
                $captcha_form = $this->Parser->getParamAll($html['html']);

                // ����� �� ����������, �������� ������
                if(!$captcha_form[status])
                {
                    // ���� ����� ���� ���� ���������� ����� �� ��������� ���
                    if($cap_zavis)
                    {
                        // �������� �������� ����� ��� �������� ��� �������� � ������� � ����������� �������
                        if($this->Captcha->TooMuchCaptchaReaction())
                        {
                            $this->Log->save("ChangePhone", __LINE__ ,". �������� �����:" . $this->Captcha->captcha . ". ��������� ������� " . $this->Captcha->action . " \r\n");
                            return array('status' => false, 'code' => 3, 'msg' => "change_phone");
                        }
                    }

                    // ����� ������� ������ ��������
                    return array('status' => true, 'code' => 50, 'msg' => "success_repost");
                }


                if ($captcha_form[params][name][1] == 'captcha_sid')
                {
                    // ��� ��������� ������� �����
                    if($cap_zavis>0)
                    {
                        $this->Captcha->reportbad($captcha_id);

                        $this->Log->save("RepostCapErr", __LINE__, " ������ ����������� ����� ��� �������. �����:\r\n" . var_export($captcha_form, true) . " \r\n postdata:\r\n" . var_export($postdata, true) . " \r\n");


                    }

                    // !!! ��������� ������������ �������� �����, �������� � ������� frending
                    $postdata[$captcha_form[params][name][1]] = $captcha_form[params][value][1];
                    $postdata[$captcha_form[params][name][2]] = $captcha_form[params][value][2];

                    $res_cap = $this->Captcha->recognize('https://' . $this->Pref . $this->apiURL . "/captcha.php?s=1&sid=" . $captcha_form[params][value][1]);
                    $captcha_key = $res_cap[0];
                    $captcha_id = $res_cap[1];
                    $cap_zavis++;

                    if ($captcha_key == false)
                    {
                        // ������ ����� �������� � ������. ������ �������
                        $this->Services->reboot(1); // ���������� ����
                    }

                    // �������� ���� if ($captcha_form[params][name][2] == 'captcha_key')
                    if ($captcha_form[params][name][3] == 'captcha_key')
                    {
                        // �������� ���� $postdata[$captcha_form[params][name][2]] = $captcha_key;
                        $postdata[$captcha_form[params][name][3]] = $captcha_key;
                    }

                    $this->Log->save("CAPlogRepost",__LINE__," ����� ��� ������ � ������. �����:\r\n".var_export($captcha_form,true)." \r\n postdata:\r\n".var_export($postdata,true));

                }
                else
                {
                    if($captcha_form[params][name][0] == 'is_board' || $captcha_form[params][name][2] == 'is_board')
                        return array('status' => true, 'code' => 50, 'msg' => "success_repost");

                    if ($captcha_form[params][name][0] == 'phone')
                    {
                        //�������� �� ���������� ����
                        return array('status' => false, 'code' => 2, 'msg' => "login_error");
                    }
                    else
                    {
                        // ���������� �������, ��������
                        $this->Log->save("CAPlogRepost" , __LINE__," final_link $final_link \r\n" . var_export($postdata, true) . "\r\n" . var_export($captcha_form, true) . "\r\n" . var_export($html, true) . "\r\n");
                        die;
                    }
                }
            }while($cap_zavis<3);

            // ��������� ��������� ��� ������������� �����. ������� ������ ����
            $this->Log->save("FrAddCapErr", __LINE__, " ����� ��� ����������� ����� cap_zavis=$cap_zavis \r\n");
            die;

        }
        else
        {
            $this->Log->save("ErrParsing",__LINE__," \r\n �������� ���� $Method ��������� �������� :\r\n". var_export($object,true). " \r\n");
            return array('status' => false ,'code' => 14, 'msg' => 'parsing_error') ;
        }
    }
}