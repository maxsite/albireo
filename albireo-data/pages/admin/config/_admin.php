<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

return [
    // список каталогов для отображения на странице Files (service)
    // каталоги указываются относительно DATA_DIR
    'serviceDirs' => ['lib'],

    // кнопки к текстовому редактору
    // Оформляются в виде групп, где название группы — пункт кнопки dropdown
    // каждая кнопка — этом массив из 3-х элементов: Название, вставка до, вставка после курсора
    // последний (4-й) необязательный элемент — подсказка title
    // если первый элемент равен «-» то это добавляет отступ между кнопками (<hr>)
    // Кавычки нужно заменять на &quot; Перенос строки это \\n
    // После вставки в textatea не работает Отмена (Ctrl+Z) — это стандартное поведение браузера
    
    'editorButton' => [

        'Format' => [
            ['B', '<b>', '</b>'],
            ['I', '<i>', '</i>'],
            ['U', '<u>', '</u>'],
            ['S', '<s>', '</s>'],
            ['-'],
            ['A', '<a href=&quot;&quot;>', '</a>'],
            ['IMG', '<img src=&quot;&quot; width=&quot;&quot; height=&quot;&quot; alt=&quot;&quot; title=&quot;&quot;>', ''],
            ['-'],
            ['UL', '<ul>\\n', '\\n</ul>\\n'],
            ['LI', '<li>', '</li>'],
            ['-'],
            ['HR', '<hr>', ''],
            ['-'],
            ['PRE', '<pre>\\n', '\\n</pre>'],
            ['CODE', '<code>', '</code>'],
            ['KBD', '<kbd>', '</kbd>'],
            ['MARK', '<mark>', '</mark>'],
        ],
        
        'Heading' => [
            ['H1', '<h1>', '</h1>'],
            ['H2', '<h2>', '</h2>'],
            ['H3', '<h3>', '</h3>'],
            ['H4', '<h4>', '</h4>'],
            ['H5', '<h5>', '</h5>'],
            ['H6', '<h6>', '</h6>'],
        ],
        
        'Blocks' => [
            ['P', '<p>', '</p>'],
            ['DIV', '<div class=&quot;&quot;>', '</div>'],
            ['SPAN', '<span class=&quot;&quot;>', '</span>'],
            ['BLOCKQUOTE', '<blockquote>', '</blockquote>'],
            ['-'],
            ['&lt;!-- --&gt;', '<!-- ', ' -->'],
        ],

        // https://max-3000.com/book/simple
        'Simple' => [
            ['B', '*', '*'],
            ['I', '_', '_'],
            ['CODE', '@', '@'],
            ['-'],
            ['H1', 'h1 ', ''],
            ['H2', 'h2 ', ''],
            ['H3', 'h3 ', ''],
            ['H4', 'h4 ', ''],
            ['H5', 'h5 ', ''],
            ['H6', 'h6 ', ''],
            ['-'],
            ['UL', 'ul\\n', '\\n/ul\\n'],
            ['LI', '* ', ''],
            ['-'],
            ['HR', 'hr', ''],
            ['-'],
            ['psimple', '[psimple]\\n', '\\n[/psimple]'],
            ['P', '_ ', ''],
            ['DIV (line)', '__ ', ''],
            ['DIV', 'div()\\n', '\\n/div'],
            ['PRE', 'pre\\n', '\\n/pre'],
            ['BQQ (line)', 'bqq ', '', 'blockquote'],
            ['BQ', 'bq\\n', '\\n/bq', 'blockquote'],
            ['-'],
            ['nosimple', '<!-- nosimple -->', '<!-- /nosimple -->', 'NoSimple'],
        ],
        
        '<i class="im-info-circle"></i>Help' => [
            ['<a class="im-link pad5-tb b-block hover-t-teal700 hover-no-underline t-nowrap" href="https://max-3000.com/book/simple" target="_blank">Simple</a>', '', ''],
            ['<a class="im-link pad5-tb b-block hover-t-teal700 hover-no-underline t-nowrap" href="https://maxsite.org/berry" target="_blank">Berry CSS</a>', '', ''],
            ['<a class="im-link pad5-tb b-block hover-t-teal700 hover-no-underline t-nowrap" href="https://maxsite.org/albireo" target="_blank">Albireo</a>', '', ''],
        ],

    ],
];

# end of file
