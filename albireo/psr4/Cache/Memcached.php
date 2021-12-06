<?php
/**
 * (c) Ichi, https://ichiblog.ru, 2021
 */

namespace Cache;

class Memcached extends Base
{
    private bool $use = false;
    private ?\Memcached $memcached = null;
    private array $config = [];

    protected function __construct()
    {
        // проверяем есть ли настройка memcached в конфиге
        $this->use = getConfig('cache') == 'memcached' && !getConfig('noCache', false);
        if ($this->use) {
            $this->config = getConfig('stores')['memcached'];
            $this->memcached = new \Memcached();
            // если не смог подключаться, то отключаем кеш
            $this->memcached->addServers($this->config['servers']) or $this->use = false;
        }
        parent::__construct();
    }

    public function setCache($key, $value): bool
    {
        if (!$this->use) {
            return false;
        }
        return $this->memcached->set($key, $value, time() + 86400);
    }

    public function getCache($key, $default = null)
    {
        if (!$this->use) {
            return $default;
        }
        $cache = $this->memcached->get($key);
        return $cache ?: $default;
    }

    public function flush(): bool
    {
        return isset($this->memcached) && $this->memcached->flush();
    }

}
