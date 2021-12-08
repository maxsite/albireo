<?php

/*
 почти PSR-16 за исключеним методов getMultiple, setMultiple и deleteMultiple
 убрал mixed, для совместимости с PHP 7.1
*/

namespace Cache;

interface CacheInterface
{
    public function get(string $key, $default = null);
    public function set(string $key, $value, $ttl = null): bool;
    public function delete(string $key): bool;
    public function clear(): bool;
    public function has(string $key): bool;
}

# end of file