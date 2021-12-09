<?php

namespace admin\modules\options\mvc;

if (!defined('BASE_DIR')) exit('No direct script access allowed');

class View
{
    public function out($data)
    {
        $rows = $data['rows'];
        $currentPaged = $data['currentPaged'];
        $currentUrl = $data['currentUrl'];
        $limit = $data['limit'];
        $pagination = $data['pagination'];

        $path = dirname(__DIR__) . '/tpl/'; // путь к tpl-шаблонам

        // вывод записей
        if ($rows) {
            echo tpl($path . 'top.php', []);

            $readOnly = !verifyLogin(['admin-change-files']);

            echo tpl($path . 'view-script.php', []);

            foreach ($rows as $row) {
                if ($readOnly) {
                    // в режиме readOnly не подключаем альпину и кнопки, то есть просто вывод данных
                    echo tpl($path . 'view-read-only.php', $row);
                } else {
                    // обычный вывод
                    echo tpl($path . 'view.php', $row);
                }
            }

            // вывод блока ссылок пагинации
            if ($pagination['max'] > 1) {
                echo '<div class="mar30-tb">';

                for ($i = 1; $i <= $pagination['max']; $i++) {
                    $queryUrl = '?page=' . $i . '&limit=' . $limit;

                    if ($i == $currentPaged)
                        $class = 'bg-teal600 t-white hover-bg-teal700 hover-t-teal50';
                    else
                        $class = 'bg-teal100 hover-bg-teal700 hover-t-teal50';

                    if ($i == 1)
                        echo '<a class="pad10-rl pad5-tb mar5-r hover-no-underline ' . $class . '" href="' . $currentUrl['urlFull'] . $queryUrl . '">' . $i . '</a>';
                    else
                        echo '<a class="pad10-rl pad5-tb mar5-r hover-no-underline ' . $class . '" href="' . $currentUrl['urlFull'] . $queryUrl . '">' . $i . '</a>';
                }

                echo '</div>';
            }
        } else {
            echo '<div class="mar30-tb">No data</div>';
        }
    }
}
