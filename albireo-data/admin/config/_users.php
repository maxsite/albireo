<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

// список пользователей

// это может быть файл: CONFIG_DIR/users.php — приоритет
// либо в ADMIN_DIR/config/_users.php

/*
nik - ник для вывода на сайте
username -  логин - должен быть уникальным среди всех пользователей
password -  пароль

level - уровень доступа. Это массив строк. Могут быть произвольными. 
    уровень admin - это доступ в админ-панель
    уровень admin-change-files - разрешение на изменение файлов в админ-панели

expiration - действует до указанной даты. Это метка времени UNIX. Если не указывать поле, то будет постоянный доступ. Указывается через функцию mktime()
         mktime(0, 0, 0, 12,   1,  2020)
                h  m  s  month day year
*/

return [
/*        
    '1' => [ // уникальный ID - пока не используется
        'nik' => 'Admin',
        'username' => 'admin',
        'password' => '123456',
        'level' => ['admin', 'admin-change-files'],
        'expiration' => mktime(0, 0, 0, 1, 1, 2030),
    ],
    
    '2' => [
        'nik' => 'User',
        'username' => 'user',
        'password' => '123456',
        'level' => ['pages'],
        'expiration' => mktime(0, 0, 0, 12, 1, 2021), 
    ],
    
    '3' => [
        'nik' => 'User1',
        'username' => 'user1',
        'password' => '123456',
        'level' => ['secret content'],
        'expiration' => mktime(10, 12, 0, 11, 19, 2021), 
    ],
*/
];

# end of file