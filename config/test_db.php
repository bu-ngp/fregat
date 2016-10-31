<?php
//$db = require(__DIR__ . '/db.php');
// test database! Important not to run tests on production or development databases

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=baseportal;charset=UTF8',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'enableSchemaCache' => true, // php C:\www\yii2test_ps\yii cache/flush-schema db
];
//$db['dsn'] = 'mysql:host=localhost;dbname=baseportal';

return $db;