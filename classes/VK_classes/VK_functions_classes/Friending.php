<?php


class Friending extends VK_functions_abstract implements VK_functions_interface
{


    public function doit(string $Method,array $RequestParam,array $PostData,array $CurlData,array $DebugOptions)
    {
        ///////////////////////
            // ���� ������ � ������
            ////////////////////////
            /// function friending($vk_id)
        global $VKfunc;
        $vk_id   =$RequestParam['vk_id']    ?? 0;

        $this->Captcha->action++;

        //https://m.vk.com/friends?act=accept&id=17556568&from=profile&hash=60d59e6e50ac7a20bc


        // ����� � ������� �����
        $html=$VKfunc("GetProfile",'id'.$vk_id);

        if($html[status])
        {
            // �������� ������� ������ ��������. ������
            // ����� ������ �� ���������� � ������
            //https://m.vk.com/friends?act=decline&id=17556568&from=profile&hash=725d0330fe9bdda04e
            $add_friend_link = $this->Parser->parseStr($html['html'], '/friends?','"');


            if($add_friend_link[status]===true)
            {

                // �������� �� ������ ��������� ��������
                if(strlen($add_friend_link[html])>100)
                {
                    $this->Log->save("ErrParsing_",__LINE__," \r\n ��������� �������� ����� $vk_id:\r\n". $add_friend_link[html]. " \r\n �������� ������� :\r\n". $html['html']);
                    return array('status' => false ,'code' => 14, 'msg' => 'parsing_error') ;
                }

                // ��������� ���� �� ������� ������ �����
                $add_friend_link_decline = $this->Parser->parseStr($add_friend_link[html], 'act=','&');

                if($add_friend_link_decline[status]===true)
                {
                    //  ������� ������ ������

                    if($add_friend_link_decline[html]=='accept')
                    {
                        $cap_zavis=0;
                        $captcha_id=0;

                        do {
                            // ������� ������ �� ���������� � ������
                            $html=$this->Call->httpCall('friends?'.$add_friend_link[html], $PostData, $CurlData, $DebugOptions);
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
                            $captcha_form	= $this->Parser->getParamAll($html['html']);

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


                            $this->Log->save("FrAdd5_",__LINE__," \r\n ������ ������� ����� $vk_id :\r\n �������� ������� :\r\n". var_export($captcha_form,true));

                            if ($captcha_form[params][name][1] == 'captcha_sid')
                            {
                                // ��� ��������� ������� �����
                                if($cap_zavis>0)
                                {
                                    $this->Captcha->reportbad($captcha_id);

                                    $this->Log->save("RepostCapErr", __LINE__, " ������ ����������� ����� ��� �������. �����:\r\n" . var_export($captcha_form, true) . " \r\n postdata:\r\n" . var_export($postdata, true) . " \r\n");


                                }

                                // !!! ��������� ������������ �������� �����, �������� � ������� repost
                                $postdata[$captcha_form[params][name][0]]=$captcha_form[params][value][0];

                                $res_cap = $this->Captcha->recognize('https://'.$this->Pref.self::$apiURL."/captcha.php?s=1&sid=".$captcha_form[params][value][0]);
                                $captcha_key=$res_cap[0];
                                $captcha_id=$res_cap[1];
                                $cap_zavis++;

                                if($captcha_key==false)
                                {
                                    // ������ ����� �������� � ������. ������ �������
                                    $this->Services->reboot(1); // ���������� ����
                                }


                                if($captcha_form[params][name][1] == 'captcha_key')
                                {

                                    $postdata[$captcha_form[params][name][1]]=$captcha_key;
                                }

                                $this->Log->save("CAPlogFrAdd",__LINE__," ����� ��� ������ � ������. �����:\r\n".var_export($captcha_form,true)." \r\n postdata:\r\n".var_export($postdata,true));
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
                    elseif($add_friend_link_decline[html]=='decline')
                    {
                        // ������ ���� ������� �����
                                        return array('status' => true ,'code' => 21, 'msg' => "already_friended") ;
                    }
                    else
                    {
                        //��� �� �� ���
                        $this->Log->save("ErrParsing_$vk_id.txt",__LINE__," \r\n ��������� �������� :\r\n". $add_friend_link[html]. " \r\n �������� ������� :\r\n");
                        return array('status' => true ,'code' => 14, 'msg' => 'parsing_error') ;
                    }
                }
                else
                {
                    $this->Log->save("ErrParsing_$vk_id.txt",__LINE__," \r\n ��������� �������� :\r\n". $add_friend_link[html]. " \r\n �������� ������� :\r\n". $html['html']);

                    return array('status' => true ,'code' => 10, 'msg' => "string_not_found") ;
                }


            }
            else
            {
                // ������������ �������� ���� ������� ���� ���� �� ��������� ������ � ������
                return array('status' => true ,'code' => 10, 'msg' => "string_not_found") ;
            }
        }
        else
        {
            return $html;
        }

    }



}