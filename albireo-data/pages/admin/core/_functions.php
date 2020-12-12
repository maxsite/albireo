<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

// путь к каталогу админки — работаем относительно него
define('ADMIN_DIR', DATA_DIR . 'pages/admin/');

// адреса админки
define('ADMIN_URL', DATA_URL . 'pages/admin/');
define('ADMIN_ASSETS_URL', ADMIN_URL . 'assets/');

// запуск механизма сессий
getSessionId();

/**
 * Декодирует адрес из base64 с учетом проблемных сисмовлов URL
 **/
function decodeURL64(string $str) {
    return @base64_decode(strtr($str, '._-', '+/='));
}

/**
 * Кодирует адрес в base64, заменяя проблемные символы URL
 **/
function encodeURL64(string $str) {
    return strtr(base64_encode($str), '+/=', '._-');
}

/**
 * получение текущей сессии
 * если нужно, запускается механизм php-сессий
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
 * Проверяет пользователя на разрешения и делает редирект
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
 * проверка уровня юзера
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
 * проверка юзера по логину и паролю
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
 * получить юзера из сессии
 *
 **/
function getUser()
{
    getSessionId(); // запуск механизма сессий

    // проверим текущую сессию
    $username = $_SESSION['username'] ?? '';
    $password = $_SESSION['password'] ?? '';

    if ($username and $password)
        return checkUser($username, $password);
    else
        return false;
}

# end of file
