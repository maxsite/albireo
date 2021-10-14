<?php
/*
    (c) Parsedown - https://parsedown.org/
    Markdown Syntax: https://spec.commonmark.org/
    
    Использование в Albireo
    -----------------------
    В настройках страницы укажите
    
        parser: md
        parser-content-only: 1
    
    Для сжатия итогогового html-кода:
        compress: 1
    
*/

function md($text)
{
    $fn = SYS_DIR . 'lib/Parsedown/Parsedown.php';
    
    if (file_exists($fn)) 
        require_once $fn; 
    else
        return $text; 
    
    $Parsedown = new Parsedown();

    return $Parsedown->text($text);
}

# end of file