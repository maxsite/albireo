<?php
namespace Cache;


class Cache implements CacheInterface
{
    // текущий обработчик кэша
    private $handler = null;


    public function __construct()
    {
        // получаем обработчик кэша — он в указан конфигурации
        // если это не указано то использовать файловый кэш
        $class = getConfig('cache', false);

        if (!$class) $class = 'Cache\Handlers\File';

        // проверяем существование этого класса
        if (class_exists($class, true)) {
            $config = getConfig('cacheHandlers'); // параметры его конструктора
            $config = $config[$class] ?? [];

            $this->handler = new $class($config); // инстанцируем класс
        }

        // проверяем состояние кэша, если обработчика нет, то кэш работать не будет
        // нужно выдать сообщение об ошибке
        if (!$this->state()) {
            pr('Недоступен обработчик кэша. Укажите его в файле конфигурации');
            // exit(); // нужно ли? Сайт будет работать только без кэша
        }
    }

    // проверка доступности кэша
    public function state(): bool
    {
        return $this->handler !== null;
    }

    // дальше все методы, которые будет выполнять текущий handler

    public function has(string $key): bool
    {
        if (!$this->state()) return false;

        return $this->handler->has($key);
    }

    public function get(string $key, $default = null)
    {
        if (!$this->state()) return $default;

        return $this->handler->get($key, $default);
    }

    public function set(string $key, $value, $ttl = null): bool
    {
        if (!$this->state()) return false;

        return $this->handler->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        if (!$this->state()) return false;

        return $this->handler->delete($key);
    }

    public function clear(): bool
    {
        if (!$this->state()) return false;

        return $this->handler->clear();
    }
}

# end of file
