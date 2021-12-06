<?php

namespace Interfaces;

interface Cache
{
    public function getCache($key);

    public function setCache($key, $value);

    public function flush();
}
