<?php

namespace Cache;

class File extends Base
{
    public function getCache($key)
    {
        $file = $key . crc32($key) . '.txt';
        if (file_exists(CACHE_DIR . $file)) {
            // проверим не устарел ли кэш

            // если всё, ок, то отдаём кэш из файла
            $content = file_get_contents(CACHE_DIR . $file); // загрузили содержимое

            // обратная серилизация с @подавлением ошибок
            $content = @unserialize($content);

            return $content ?: false;
        }
        // файла кэша вообще нет
        return false;
    }

    public function setCache($key, $value)
    {
        $file = $key . crc32($key) . '.txt';
        return file_put_contents(CACHE_DIR . $file, serialize($value));
    }

    public function flush()
    {

    }
}
