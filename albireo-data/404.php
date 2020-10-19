<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: 404 - ничего не найдено
slug-static: -

 **/

header('HTTP/1.0 404 Not Found');

?>
<div class="b-flex flex-jc-center mar100-t">
    <div class="b-flex flex-vcenter">
        <div class="t200 t-white t-bold pad30 bg-red700">404</div>
        <div class="pad20">
            <div class="t200 t-gray600">Page not found</div>
            <div class=""><a href="<?= SITE_URL ?>">Home →</a></div>
        </div>
    </div>
</div>