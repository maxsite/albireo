<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Strona w języku polskim
description: 
parser: simple
slug: lang
lang: pl

**/

?>

h1 Strona w języku polskim

<ul>
    <?php

    $favoriteLang = detectUserLang();

    $langCurrent = getPageData('lang', '');
    $langArray = getPageData('__langs', []);

    foreach($langArray as $lang => $link){
        if ($langCurrent != $lang) {
            $langName = 'This page is ' . strtoupper($lang);

            if ($lang == 'uk') $langName = 'Ця сторінка українською';
            if ($lang == 'en') $langName = 'This page is English';
            if ($lang == 'de') $langName = 'Diese Seite ist auf Deutsch';
            if ($lang == 'fr') $langName = 'Cette page est en français';
            if ($lang == 'pl') $langName = 'Ta strona jest w języku polskim';

            if ($lang == $favoriteLang) $langName .= ' *';

            echo '<li><a href="' . SITE_URL . $link . '">' . $langName . '</a></li>';
        }
    }
    ?>
</ul>


