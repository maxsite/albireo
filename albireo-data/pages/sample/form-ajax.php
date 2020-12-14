<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

slug: form
method: AJAX
layout: empty.php
slug-static: -

**/

// отсекаем всё, что без заголовка AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) exit('Error: AJAX only!');

// вывод входящего POST
pr($_POST);
