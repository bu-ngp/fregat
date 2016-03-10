<?php

$file = file(dirname(__FILE__) . DIRECTORY_SEPARATOR . "db", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dbname = $file[0];
$dbuser = $file[1];
$dbpassword = $file[2];

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname='.$dbname,
    'username' => $dbuser,
    'password' => $dbpassword,
    'charset' => 'utf8',
];
