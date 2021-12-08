<?php

namespace Cache\Handlers;

class File implements \Cache\CacheInterface
{
    private $dir;
    private $ext;

    public function __construct(array $config = [])
    {
        $this->dir = $config['path'] ?? CACHE_DIR;
        $this->ext = $config['ext'] ?? '.txt';
    }

    // присутствует ли элемент (файл) в кэше
    public function has(string $key): bool
    {
        return file_exists($this->dir . $key . $this->ext);
    }

    // получить данные из файла
    public function get(string $key, $default = null)
    {
        $data = $default;

        if ($this->has($key)) {
            $data = file_get_contents($this->dir . $key . $this->ext);
            $data = @unserialize($data);
        }

        return $data;
    }

    // сохранить данные в файл
    public function set(string $key, $value, $ttl = null): bool
    {
        if (file_put_contents($this->dir . $key . $this->ext, serialize($value)) === false)
            return false;
        else
            return true;
    }

    // удалить файл
    public function delete(string $key): bool
    {
        if ($this->has($key))
            return @unlink($this->dir . $key . $this->ext);

        return true;
    }

    // не реализовано. не стоит полностью очищать кэш — там могут быть служебные файлы
    public function clear(): bool
    {
        return false;
    }
}

# end of file
