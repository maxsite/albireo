<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Пример формы (отправка POST/AJAX-запроса)
description: Пример формы (отправка POST/AJAX-запроса)
slug: form

**/
?>

<h1 class="t-center mar50-t">Пример отправки форм</h1>

<div class="layout-center-wrap mar50-tb">
    <div class="layout-wrap">
        <h2 class="t-center">Отправка POST-запроса</h2>

        <form class="mar30-t t-center" method="POST">
            <input class="form-input" type="text" name="myform[name]" placeholder="name...">
            <button class="button button1" type="submit">Отправить</button>
        </form>
    </div>
</div>

<div class="layout-center-wrap mar50-tb bg-gray200 pad50-tb">
    <div class="layout-wrap">
        <h2 class="t-center">Отправка AJAX-запроса</h2>

        <form class="mar30-t t-center" onsubmit="sendAjax(this); return false;">
            <!-- в скрытом поле _method указываем произвольный http-метод для POST-запроса (его ловит роутер) -->
            <input type="hidden" name="_method" value="AJAX"> 
            <input class="form-input" type="text" name="myform[name]" placeholder="name...">
            <button class="button button1" type="submit">Отправить</button>
        </form>

        <div id="result"></div>
    </div>
</div>

<script>
    function sendAjax(f) {
        var xhttp = new XMLHttpRequest();
        var form = new FormData(f);

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML =
                    this.responseText;
            }
        };

        xhttp.open("POST", "<?= getVal('currentUrl')['urlFull'] ?>", true);
        xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhttp.send(form);
    }
</script>