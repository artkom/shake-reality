<?php
include_once(dirname(__FILE__).'/connect.php');
if(is_resource($link)){
    $r=mysql_query('
        delete from
            `'.mysql_escape_string($DB['table']).'`
        where
            `type`="data"
        ',$link);
    echo$r?'Очищено':('Ошибка очистки: '.mysql_error($link));
    mysql_close($link);
}