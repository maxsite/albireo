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
$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Add a new file<?= $readOnly ?></h1>

<div class="pad30-rl pad10-rl-tablet">
    <form class="mar30-t b-flex flex-vcenter" onsubmit="sendAjax(this); return false;">
        <div class="w50">
            <input type="hidden" name="_method" value="AJAX">
            <input class="form-input w100" type="text" name="file" placeholder="new file..." required>
        </div>

        <div class="pad10-l">
            <button class="button button1 pad5-tb pad20-rl im-plus" type="submit">Create</button>

            <label class="form-checkbox mar20-l" title="Create a file in the pages directory">
                <input type="checkbox" name="inpages" value="1">
                <span class="form-checkbox-icon mar10-r bg-blue100"></span><i>pages</i>
            </label>
        </div>
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