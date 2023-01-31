<?php
require_once "HTML_string_raw_class.php";

class HTML_VK_Parser extends HTML_string_raw_class
{

    public function parseStr($html, $prefix, $suffix)
	{
        if(!$this->setHtml($html))  return array('status' => false, 'code' => 11, 'msg' => 'bad_html');

        $RawStringArr=$this->getString(0, $prefix, $suffix);

        // если все прошло без ошибок то возращаем  результта в нужном нам виде, инчча возвращаем ответ с ошибкой от родитеоскго класса
		return $RawStringArr['status'] ? array('status' => true, 'html' => $RawStringArr['string']) : $RawStringArr;
	}

 
 
 // получение массива строк
    public function parseStrAll($html, $prefix, $suffix)
	{
	    if(!$this->setHtml($html))  return array('status' => false, 'code' => 11, 'msg' => 'bad_html');

	    $i=0;
	    do
		{
            $RawStringArr=$this->getString(0, $prefix, $suffix);

            if($RawStringArr['status'])
                {
                    $str[$i]=$RawStringArr['string'];
                    $i++;
                }
		}
	    while($RawStringArr['status']);

	    if(count($str))
	        return array('status' => true,  'code' => 19, 'msg' => 'parsing_success', 'html' => $str);
        else
            return $RawStringArr;
	}  
 

    // получение массива параметров формы
    public function getParamAll($html)
	{
	    $i=0;

        if(!$this->setHtml($html))  return array('status' => false, 'code' => 11, 'msg' => 'bad_html');

	    $prefix1="<input";
	    $prefix2='name="';
	    $prefix3='value="';
	    
	    $suffix1=">";
	    $suffix2='"';
	    $suffix3='"';
	    
	    while($i<30)
		{
		    ///////////////////////////////////////////////////////////////////////////
		    // находим строку параметров формы
		    // начало строки параметров
		    $start =   strpos($html, $prefix1, 0);
		    
		    if($start === false )  break;

		    $start += strlen($prefix1);
		    
		    // находим конец строки параметров. цикл работает в этом диапазоне
		    $stop =   strpos($html, $suffix1, $start);
		    
		    // работаем с фрагментом
		    $html_fr=substr($html, $start, $stop - $start);
		    
		    $start_fr=0;
		    // находим начало назхвания параметра
		    $start_fr = strpos($html_fr, $prefix2, 0);
		    
		    //  если форма имеет параметры
                    if($start_fr>0)
                        {
                            $start_fr += strlen($prefix2);
                            
                            $stop_fr= strpos($html_fr, $suffix2, $start_fr);
                            
                            $param_len=$stop_fr - $start_fr;
                            
                            // если обнаружен параметр
                            if($param_len>0)
                                {
                                    
                                    $param['name'][$i]=substr($html_fr, $start_fr, $param_len);
                            
                                    //$start_fr=$stop_fr;
                                     $start_fr=0;
                                    // находим начало значения параметра
                                    $start_fr = strpos($html_fr, $prefix3, $start_fr);
                                    
                                    // если значние параметра не найдено
                                    if($start_fr == false)
                                        {
                                            $param['value'][$i]="";
                                          
                                        }
                                    else
                                        {
                                            $start_fr += strlen($prefix3);
                                    
                                            $stop_fr= strpos($html_fr, $suffix3, $start_fr);
                                            
                                            $param['value'][$i]=substr($html_fr, $start_fr, $stop_fr - $start_fr);  
                                        }
                                    
                                    
                                }
                        }
			
		    // укорачиваем обрабатываемый фрагмент
                    $html=substr($html, $stop, strlen($html) - $stop);
			    
		    $i++;
		}
	    
	    ////////////////////////////////////////////////////////////////////////////////
	    
	    
	    // если параметры не найдны вернем ошибку
	   
	    if(count($param[name])>0)
		{
		    return array('status' => true, 'params' => $param);
		}
	    else
	    {
		 return array('status' => false, 'code' => 10, 'msg' => "string_not_found");
	    }
	}

    // получение массива параметров всех форм страницы
    function getAllFormsParams($html)
	{
	   // echo $html;
	    $i=0;
	    $start=0;

	    if(!$this->setHtml($html))  return array('status' => false, 'code' => 11, 'msg' => 'bad_html');

        $stop =   strlen($html);

	    
	    //удвляем все пробелы, табы и переносы
	    $html=preg_replace('/\s/', '', $html);
	    
	    
	    // вычленияем  формы в цикле
	    $i=0;
	    do
		{
		    $form_err=true;
		    $form = $this->parseStr($html, '<form','</form>',$i+1); // получаем очередную форму
	     
		    // обрабатываем форму получаем имя , action и параметры
		    if($form[status] && strlen($form[html])>0)
			{
			    // форма обнаружена
			   $form_err=false;
			   
			   // вычления имя и action
			   $res = $this->parseStr($form[html], '','>');
			   
			   // заменям одинарные скобки
			   $form_head=@str_replace("'", '"', $res[html]);
			   
			   $res= $this->parseStr($form_head, 'id="','"');   	if($res[status]) $form_array[$i]['id']		=$res[html]; 
			   $res= $this->parseStr($form_head, 'name="','"'); 	if($res[status]) $form_array[$i]['name']	=$res[html];
			   $res= $this->parseStr($form_head, 'action="','"'); 	if($res[status]) $form_array[$i]['action'] 	=$res[html];
			   

			   // вычленяем все параметры
			   $form_params = $this->getParamAll($form[html]);
			   
			   // объединяем массивы
			   $form_array[$i]  += $form_params;
			    
			}
		    $i++;	
		}
	    while(!$form_err);
	    
	    
	    
	    // если параметры не найдны вернем ошибку
	   
	    if(count($form_array[0])>0)
		{
		    return array('status' => true, 'forms' => $form_array);
		}
	    else
	    {
		 return array('status' => false, 'code' => 10, 'msg' => "string_not_found");
	    }
	}	
}
?>