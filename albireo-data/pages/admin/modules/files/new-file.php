<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Add a new file
description: 
slug: admin/new-file
slug-static: -
layout: pages/admin/core/_layout.php
menu[title]: <i class="im-clone"></i>New file
menu[group]: General
menu[order]: 3
parser: -
compress: 1

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Add a new file</h1>

<div class="pad30-rl pad10-rl-tablet">
    <form class="mar30-t b-flex flex-vcenter" onsubmit="sendAjax(this); return false;">
        <div class="w50">
            <input type="hidden" name="_method" value="AJAX">
            <input class="form-input w100" type="text" name="file" placeholder="new file..." required>
        </div>

        <button class="mar10-l button button1 pad5-tb pad20-rl im-plus" type="submit">Create</button>
    </form>
    <div class="t-gray600 t90 mar10-tb">
        For example: <i class="t-primary600">about</i> or <i class="t-primary600">about.php</i>, <i class="t-primary600">pages/map.php</i>
    </div>

    <div id="result"></div>

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