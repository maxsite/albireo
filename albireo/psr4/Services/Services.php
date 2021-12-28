<?php

namespace Services;

/*

Простой контейнер (почти PSR 11), позволяющий сделать из любого класса единственный объект.
Использовать там, где класс должен быть в единственном экземпляре и/или нужно работать с классом через псевдоним.

Использование
-------------
Вместо:

$cache = new Cache\Cache();

можно использовать контейнер:

$services = Services\Services::getInstance();
$cache = $services->get(Cache\Cache::class); // указываем имя класса

или

$cache = Services\Services::getInstance()->get(Cache\Cache::class);

// дальше работаем с классом как обычно
$cache->set(...);


Можно передать один (!) аргумент в конструктор класса:

$services = Services\Services::getInstance();
$my = $services->get('My\Myclass', 'text'); // указываем имя класса и аргумент

будет эквивалентно:

$my = new My\Myclass('text');

Если требуемый объект не доступен, то будет возвращено null.

$cache = Services\Services::getInstance()->get(Cache\Cache::class);

if ($cache !== null) {
    $cache->...
}

------ Псевдонимы классов ------

// где-то в конфигурации
// указываем псевдоним для класса
Services\Services::getInstance()->setAlias('cache', 'Cache\Cache');
    или
Services\Services::getInstance()->setAlias('cache', Cache\Cache::class);
    или
Services\Services::getInstance()->setAlias('cache', Cache\Cache::class, 'аргумент конструктора');
    или сразу несколько
\Services\Services::getInstance()
    ->setAlias('cache', Cache\Cache::class)
    ->setAlias('my1', A::class)
    ->setAlias('my2', B::class)
    ->setAlias('my3', С::class);

// использование — если объекта нет, то возвращается null
$cache = Services\Services::getInstance()->getAlias('cache'); // используем псевдоним
    будет эквивалентно
$cache = Services\Services::getInstance()->get(Cache\Cache::class);

Добавление через setAlias() не создаёт объект, а только формирует массив данных. Реальный объект будет создан только в момент получения класса через getAlias() или get().

*/

class Services
{
    // этот класс Singleton
    use \Pattern\Singleton;

    // хранилище созданных объектов
    private $repository = [];

    // псевдонимы классов
    private $aliases = [];

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
        $obj = ($config === null) ? new $className() : new $className($config);

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

    /**
    * Сохраняем псевдоним класса в массиве
    */
    public function setAlias(string $alias, string $className, $config = null)
    {
        $this->aliases[$alias] = [
            'className' => $className,
            'config' => $config
        ];

        return $this;
    }

    /**
    * Получить класс по его псевдониму
    * Если псевдонима нет, то возвращается null
    */
    public function getAlias(string $alias)
    {
        // если такой псевдоним есть, то получаем его данные из массива
        // и выполняем get() как обычно
        return isset($this->aliases[$alias])
                ? $this->get($this->aliases[$alias]['className'], $this->aliases[$alias]['config']) 
                : null;
    }
}

# end of file