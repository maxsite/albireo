<?php

namespace admin\log;

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

        // вывод записей
        if ($rows) {
            $timezone = getConfigAdmin('timezone');

            if (!$timezone) $timezone = '+2 hours';

            foreach ($rows as $row) {
                extract($row);

                $log_message = htmlspecialchars($log_message);

                // поскольку sqlite время хранит в UTC, нужно внести поправку
                $dateLocal = date('Y-m-d H:i:s', strtotime($log_date . $timezone));

                $classBg = 'hover-bg-gray100';

                if ($log_level == 'emergency') {
                    $classBg = 'bg-red250 hover-bg-red350';
                } elseif ($log_level == 'alert') {
                    $classBg = 'bg-red200 hover-bg-red200';
                } elseif ($log_level == 'critical') {
                    $classBg = 'bg-red150 hover-bg-red250';
                } elseif ($log_level == 'error') {
                    $classBg = 'bg-red100 hover-bg-red200';
                } elseif ($log_level == 'warning') {
                    $classBg = 'bg-orange100 hover-bg-orange200';
                } elseif ($log_level == 'notice') {
                    $classBg = 'bg-yellow200 hover-bg-yellow300';
                } elseif ($log_level == 'info') {
                    $classBg = 'hover-bg-gray200';
                } elseif ($log_level == 'debug') {
                    $classBg = 'bg-blue100 hover-bg-blue200';
                }

                echo <<<EOF
<div class="flex pad3 t90 {$classBg}">
<div class="w20px mar10-r">{$log_id}.</div>
<div class="w3col pad10-r">{$dateLocal}</div>
<div class="w2col pad10-r">{$log_level}</div>
<div class="w8col pad10-r">{$log_message}</div>
<div class="w1col">{$log_group}</div>
</div>
EOF;
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
