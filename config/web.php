<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'cCpteC2aYWC6HKZmAQ-x78FfOKMw7kJS',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            //   'encodeParams' => FALSE,
            //  'enablePrettyUrl' => true,
            //  'enableStrictParsing' => true,
            //  'showScriptName' => false,
            'rules' => [
                'Fregat/<controller:\w+>/<action:\w+>' => 'Fregat/<controller>/<action>',
                'Config/<controller:\w+>/<action:\w+>' => 'Config/<controller>/<action>',
            //  'encodeParams' => FALSE,
            // my rules
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',
            'nullDisplay' => '',
        ],
    ],
    'modules' => [
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
            'cookieSettings' => [
                'httpOnly' => true,
                'expire' => time() + 8640000
            ],
            'defaultTheme' => 'panel-default',
            'dynaGridOptions' => [
                'gridOptions' => [
                    'pjax' => true,
                    'export' => false,
                    'exportConfig' => [
                        \kartik\grid\GridView::EXCEL => [
                            'label' => 'EXCEL',
                            'filename' => 'EXCEL',
                            'options' => ['title' => 'EXCEL List'],
                        ],
                    ],
                    'toolbar' => [
                        'base' => ['content' => '{export}{dynagrid}'],
                    ],
                    'pager' => [
                        'firstPageLabel' => 'Первая',
                        'lastPageLabel' => 'Последняя',
                    ],
                ],
            ]
        // other module settings
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        // other module settings
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];
}

return $config;
