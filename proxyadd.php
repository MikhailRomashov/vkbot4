<?php
// ���������� � mysql
// ������� ���� ��������
include $_SERVER['DOCUMENT_ROOT']."/config/settings.php";

// ����������� � ���� 
include $_SERVER['DOCUMENT_ROOT']."/config/mysql_connect.php";

// ������� ����� ��������
include $_SERVER['DOCUMENT_ROOT']."/config/mysql_query_vkbot.php";
/*
// ��������� ������������ ������ �� ������� robot
$res=sql_query("sql_robots_select5",__LINE__);

// ��������� ������
while($pr=mysql_fetch_array($res))
    {
        // ����������
        $proxy_used[$pr[proxy]]=true;
    }
*/   
    
// ��������� ����
$proxy_list = file("proxy.txt");
$count = count($proxy_list);

$i2=0;

for ($i = 1; $i < $count; $i++)
{
    $proxy_new=trim(htmlspecialchars($proxy_list[$i]));
    
    // ��������� ������������ �� ��� ����� ������
    
    if(strlen($proxy_new)>6) 
        {
            // ��������� �� �������� �� ���� 9999
             list($pr,$po) = explode(":",$proxy_new);
            if($po!="9999") 
            {
               // �������� ���� �� ������ ������
                $more.="('$proxy_new'),"; 
            }
            
       }
        
    $i2++;
    if($i2>1000)
        {
           $more=substr($more, 0, -1);

            sql_query("sql_proxy_insert1",__LINE__);
            $more='';
            $i2=0;
        }

}
$more=substr($more, 0, -1);

if($more)  sql_query("sql_proxy_insert1",__LINE__);
mysql_close($db);
?>