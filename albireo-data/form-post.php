<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Форма (приём POST-запроса)
slug: form
description: Форма (приём POST-запроса)
method: POST
slug-static: -

**/
?>

<div class="layout-center-wrap mar50-tb">
    <div class="layout-wrap t-center">
        <h1>Пример формы</h1>
        <h5>Приём POST-запроса</h5>
        <h6 class="t-center"><a href="">Отправить ещё раз</a></h6>
    </div>
</div>

<div class="layout-center-wrap">
    <div class="layout-wrap">
        <?php pr($_POST); ?>
    </div>
</div>