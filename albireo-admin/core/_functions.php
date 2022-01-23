<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

// путь к каталогу админки — работаем относительно него
// define('ADMIN_DIR', DATA_DIR . 'admin' . DIRECTORY_SEPARATOR);

// адреса админки
// define('ADMIN_URL', DATA_URL . 'admin/');
define('ADMIN_ASSETS_URL', ADMIN_URL . 'assets/');

// запуск механизма сессий
getSessionId();


/**
 * возвращает массив конфигррации админ-панели
 * файл может быть в CONFIG_DIR/admin.php — приоритетный вариант
 * либо в ADMIN_DIR/config/_admin.php — дополнительный вариан
 * @param $key - если указан ключ, то возвращаем его массив
 **/
function getConfigAdmin($key = '')
{
    if (file_exists(CONFIG_DIR . 'admin.php'))
        $config = require CONFIG_DIR . 'admin.php';
    elseif (file_exists(ADMIN_DIR . 'config/_admin.php'))
        $config = require ADMIN_DIR . 'config/_admin.php';
    else
        return [];

    if (!$config) return [];

    if ($key)
        return $config[$key] ?? [];
    else
        return $config;
}

/**
 * Декодирует адрес из base64 с учетом проблемных символов URL
 **/
function decodeURL64(string $str)
{
    return @base64_decode(strtr($str, '._-', '+/='));
}

/**
 * Кодирует адрес в base64, заменяя проблемные символы URL
 **/
function encodeURL64(string $str)
{
    return strtr(base64_encode($str), '+/=', '._-');
}

/**
 * Получение текущей сессии, если нужно, запускается механизм php-сессий
 */
function getSessionId()
{
    if (defined('SESSION_ID')) return SESSION_ID;

    if (session_status() !== PHP_SESSION_DISABLED) {
        if (!isset($_SESSION)) session_start();
        define('SESSION_ID', hash('sha256', session_id()));
    } else {
        define('SESSION_ID', ''); // none session... ;-(
    }

    return SESSION_ID;
}

/**
 * Проверяет пользователя на разрешения и делает редирект.
 * Если их нет, то редиректит на страницу login или access-denied
 * @param  string|array $levels - уровни доступа
 * @param $message - сообщение для страницы access-denied
 **/
function verifyLoginRedirect($level, string $message)
{
    if (!$user = getUser()) {
        $_SESSION['onLoginRedirect'] = getVal('currentUrl')['urlFull'];
        header('Location:' . SITE_URL . 'admin/login');
        return;
    }

    // проверяем разрешение на доступ
    if (!checkUserAccess($user, $level)) {
        $_SESSION['onAccessDeniedMessage'] = $message;
        header('Location:' . SITE_URL . 'admin/access-denied');
        return;
    }
}

/**
 * Проверяет пользователя на разрешения и возвращает true или false
 * @param  string|array $levels - уровни доступа
 **/
function verifyLogin($level)
{
    if (!$user = getUser()) return false;

    // проверяем разрешение на доступ
    if (!checkUserAccess($user, $level)) return false;

    return true;
}

/**
 * Проверка уровня юзера
 * @param $user - массив пользователя
 * @param string|array $level - уровень доступа
 **/
function checkUserAccess(array $user, $level)
{
    // если уровень пользователя не указан, то доступ закрыт всегда
    if (!$user['level']) return false;

    if (is_array($level)) {
        $ok = false;

        foreach ($level as $lv) {
            if (in_array($lv, $user['level'])) {
                $ok = true;
                break;
            }
        }

        return $ok;
    } else {
        return in_array($level, $user['level']);
    }
}

/**
 * Проверка юзера по логину и паролю
 * здесь нет проверки на уровень доступа!
 **/
function checkUser(string $username, string $password)
{
    // загружаем список юзеров
    // либо в общем каталоге CONFIG_DIR/users.php — приоритетный вариант
    // он может быть в  ADMIN_DIR/config/_users.php — дополнительный вариант
    if (file_exists(CONFIG_DIR . 'users.php'))
        $users = require CONFIG_DIR . 'users.php';
    elseif (file_exists(ADMIN_DIR . '/config/_users.php'))
        $users = require ADMIN_DIR . '/config/_users.php';
    else
        return false;

    if (!$users) return false;

    foreach ($users as $user => $info) {
        // пароль и логин не должны быть пустыми
        if (!$info['password']) continue;
        if (!$info['username']) continue;

        // если пароль и логин совпали
        if ($info['username'] == $username and $info['password'] == $password) {
            // нужно проверить expiration
            if (isset($info['expiration']) and time() > $info['expiration']) return false;
            if (!isset($info['level'])) $info['level'] = [];

            return $info;
        }
    }

    return false;
}

/**
 * Получить юзера из сессии
 * @param $loginOnly - проверить только сам факт логина без проверки на разрешения
 **/
function getUser($loginOnly = false)
{
    getSessionId(); // запуск механизма сессий

    // проверим текущую сессию
    $username = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';

    if ($loginOnly) {
        return $username;
    } else {
        if ($username and $password)
            return checkUser($username, $password);
        else
            return false;
    }
}

/**
 * Функция из MaxSite CMS для преобразования строки к латинице
 * @param string $slug - входная строка
 * @param boolean $deleteSlash - если true, то удаляются слэши \ и /
 *
 **/
function strToSlug(string $slug, $deleteSlash = true, $deleteDot = false)
{
    $repl = [
        "А" => "a", "Б" => "b",  "В" => "v",  "Г" => "g",   "Д" => "d",
        "Е" => "e", "Ё" => "jo", "Ж" => "zh",
        "З" => "z", "И" => "i",  "Й" => "j",  "К" => "k",   "Л" => "l",
        "М" => "m", "Н" => "n",  "О" => "o",  "П" => "p",   "Р" => "r",
        "С" => "s", "Т" => "t",  "У" => "u",  "Ф" => "f",   "Х" => "h",
        "Ц" => "c", "Ч" => "ch", "Ш" => "sh", "Щ" => "shh", "Ъ" => "",
        "Ы" => "y", "Ь" => "",   "Э" => "e",  "Ю" => "ju", "Я" => "ja",

        "а" => "a", "б" => "b",  "в" => "v",  "г" => "g",   "д" => "d",
        "е" => "e", "ё" => "jo", "ж" => "zh",
        "з" => "z", "и" => "i",  "й" => "j",  "к" => "k",   "л" => "l",
        "м" => "m", "н" => "n",  "о" => "o",  "п" => "p",   "р" => "r",
        "с" => "s", "т" => "t",  "у" => "u",  "ф" => "f",   "х" => "h",
        "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "",
        "ы" => "y", "ь" => "",   "э" => "e",  "ю" => "ju",  "я" => "ja",

        # Украина
        "Є" => "ye", "є" => "ye", "І" => "i", "і" => "i",
        "Ї" => "yi", "ї" => "yi", "Ґ" => "g", "ґ" => "g",

        # Беларусь
        "Ў" => "u", "ў" => "u", "'" => "",

        # румынский
        "ă" => 'a', "î" => 'i', "ş" => 'sh', "ţ" => 'ts', "â" => 'a',

        "«" => "", "»" => "", "—" => "-", "`" => "", " " => "-",
        "[" => "", "]" => "", "{" => "", "}" => "", "<" => "", ">" => "",

        "?" => "", "," => "", "*" => "", "%" => "", "$" => "",

        "@" => "", "!" => "", ";" => "", ":" => "", "^" => "", "\"" => "",
        "&" => "", "=" => "", "№" => "",
        // "\\" => "",
        // "/" => "",
        "#" => "",
        "(" => "", ")" => "", "~" => "", "|" => "", "+" => "", "”" => "", "“" => "",
        "'" => "",

        "’" => "",
        "—" => "-", // mdash (длинное тире)
        "–" => "-", // ndash (короткое тире)
        "™" => "tm", // tm (торговая марка)
        "©" => "c", // (c) (копирайт)
        "®" => "r", // (R) (зарегистрированная марка)
        "…" => "", // (многоточие)
        "“" => "",
        "”" => "",
        "„" => "",

        " " => "-",
    ];

    if ($deleteSlash) {
        $slug = str_replace('/', '', $slug);
        $slug = str_replace('\\', '', $slug);
    }

    if ($deleteDot) $slug = str_replace('.', '', $slug);

    $slug = strtr(trim($slug), $repl);
    $slug = htmlentities($slug); // если есть что-то из юникода
    $slug = strtr(trim($slug), $repl);
    $slug = strtolower($slug);

    // заменяем слэши на обычные
    $slug = str_replace('\\', '/', $slug);
    $slug = str_replace('//', '/', $slug);
    $slug = str_replace('//', '/', $slug);

    return $slug;
}

/**
 * Вывод кол-ва байт в читаемый формат
 * https://www.php.net/manual/de/function.filesize.php#120250
 **/
function human_filesize($bytes, $decimals = 2)
{
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

# end of file
