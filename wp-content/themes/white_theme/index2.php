<?php

include_once(dirname(__FILE__).'/connect.php');
if(is_resource($link)){
    $checktable = mysql_query('show tables like "'.mysql_escape_string($DB['table']).'"',$link);
    if(is_resource($checktable) && mysql_num_rows($checktable)==1){
        $result = mysql_query('
            select
                `value`
            from
                `'.mysql_escape_string($DB['table']).'`
            where
                `type` = "data"
            limit 1
            ',$link);
        if(is_resource($result) && mysql_num_rows($result)==1){
            $res=mysql_fetch_row($result);
            echo$res[0];
        }
        else{

            $result = mysql_query('
                select
                    `date`
                from
                    `'.mysql_escape_string($DB['table']).'`
                where
                    `type` = "error"
                limit 1
                ',$link);
            if(is_resource($result)){
                $flag=false;
                if(mysql_num_rows($result)==1){

                    $res = mysql_fetch_row($result);
                    if(time()-strtotime($res[0])>REPEAT_AFTER)
                        $flag=true;
                }
                if($flag || mysql_num_rows($result)==0){

                    $data = download(REMOTE_URI);
                    if(is_string($data) && trim($data)!=''){

                        mysql_query('truncate table `'.mysql_escape_string($DB['table']).'`',$link);

                        mysql_query('
                            insert into
                                `'.mysql_escape_string($DB['table']).'`
                            set
                                `type`="data",
                                `value`="'.mysql_escape_string($data).'",
                                `date`=now()
                            ',$link);
                        echo$data;
                    }
                    else{

                        mysql_query('
                            replace
                                `'.mysql_escape_string($DB['table']).'`
                            set
                                `date`=now()
                            where
                                `type`="error",
                                `value`="'.mysql_escape_string('loaderror2').'"
                            limit 1
                            ',$link);
                    }
                }
            }
        }
    }
    else{

        mysql_query("
            CREATE TABLE `".mysql_escape_string($DB['table'])."` (
             `type` enum('data','error') collate utf8_unicode_ci NOT NULL default 'data' COMMENT 'тип данных',
             `value` longtext collate utf8_unicode_ci NOT NULL COMMENT 'данные',
             `date` datetime NOT NULL COMMENT 'дата операции',
             PRIMARY KEY  (`type`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица подгрузки данных с удалённого сервера'
            ",$link);
        $data = download(REMOTE_URI);
        if(is_string($data) && trim($data)!=''){

            mysql_query('truncate table `'.mysql_escape_string($DB['table']).'`',$link);

            mysql_query('
                insert into
                    `'.mysql_escape_string($DB['table']).'`
                set
                    `type`="data",
                    `value`="'.mysql_escape_string($data).'",
                    `date`=now()
                ',$link);
            echo$data;
        }
        else{

            mysql_query('
                replace
                    `'.mysql_escape_string($DB['table']).'`
                set
                    `type`="error",
                    `value`="'.mysql_escape_string('loaderror1').'",
                    `date`=now()
                ',$link);
        }
    }
    mysql_close($link);
}