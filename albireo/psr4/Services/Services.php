<?php

namespace Services;

/*

Простой контейнер (почти PSR 11), позволяющий сделать из любого класса единственный объект.
Использовать там, где класс должен быть в единственном экземпляре

Использование
-------------
Вместо:

$cache = new Cache\Cache();

нужно использовать контейнер:

$services = Services\Services::getInstance();
$cache = $services->get('Cache\Cache'); // указываем имя класса

или

$cache = Services\Services::getInstance()->get('Cache\Cache');

// дальше работаем с классом как обычно
$cache->set(...);


Можно передать один (!) параметр в конструктор класса:

$services = Services\Services::getInstance();
$my  = $services->get('My\Myclass', 'text'); // указываем имя класса и параметр

будет эквивалентно:

$my = new My\Myclass('text');

Если требуемый класс не доступен, то будет возвращено null.

$my = new My\Myclass('text');

if ($my !== null) {
    $my->...
}

*/

class Services
{
    // этот класс Singleton
    use \Pattern\Singleton;

    // хранилище объектов
    private $repository = [];

    /**
    * Получаем объект из хранилища
    * $config используется только в момент первого создания объекта
    */
    public function get(string $className, $config = null)
    {
        // проверяем существование объекта
        // если есть отдаём что уже есть
        if (isset($this->repository[$className]))
            return $this->repository[$className];

        // если такой класс недоступен, то возвращает null
        if (!class_exists($className, true)) return null;

        // инстанцируем класс с аргументом или без (чтобы не затирать дефолтный)
        $obj = ($config === null)? new $className() : new $className($config);

        // сохраняем во внутреннем хранилище объектов
        $this->repository[$className] = $obj;

        return $obj;
    }

    /**
    * Проверить существование класса в контейнере
    */
    public function has(string $className)
    {
        return isset($this->repository[$className]);
    }
}

# end of file