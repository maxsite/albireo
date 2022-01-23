<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Albireo Doc
description:
slug: doc
layout: doc.php
parser: simple

menu[title]: Описание
menu[group]: Основы
menu[order]: 1

-articleClass: pad30

**/

?>

h1(mar0 bg-lime200 pad30-rl pad10-tb) Albireo Doc

<div class="pad30-rl mar30-tb">

    _ *Albireo Doc* — это готовое решение для Albireo, с помощью которого вы можете создавать и поддерживать документацию своего проекта без особых затрат.

    _ Работает _Albireo Doc_ работает точно так же, как и любые другие страницы Albireо. Разница только в том, что в _Albireo Doc_ использует особый layout-шаблон вывода.

    bqq Для своей работы _Albireo Doc_ использует css-фреймворк <a href="https://maxsite.org/berry">Berry CSS</a>.

    _ Примеры страниц вы найдёте в каталоге @pages/doc-sample@.

    <?php snippet('next-prev', ['', 'doc1']); ?>

</div>
