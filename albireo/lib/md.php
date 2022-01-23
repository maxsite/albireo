<?php
/*
    (c) Parsedown - https://parsedown.org/
    Markdown Syntax: https://spec.commonmark.org/

    Использование в Albireo
    -----------------------
    В настройках страницы укажите

        parser: md
        parser-content-only: 1

    Для сжатия итогового html-кода:
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

	// костыль для нормальной обработки <!DOCTYPE HTML> в этом парсере
	$text = str_replace('<!DOCTYPE HTML>', '<!-- <!DOCTYPE HTML> -->', $text);
	$text = $Parsedown->text($text);
	$text = str_replace('<!-- <!DOCTYPE HTML> -->', '<!DOCTYPE HTML>', $text);

    return $text;
}

# end of file
