<?php
use kartik\mpdf\Pdf;

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Yekaterinburg',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'cCpteC2aYWC6HKZmAQ-x78FfOKMw7kJS',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
            'enablePrettyUrl' => true,
            //  'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'Fregat/<controller:\w+>/<action:\w+>' => 'Fregat/<controller>/<action>',
                'Config/<controller:\w+>/<action:\w+>' => 'Config/<controller>/<action>',
                'Base/<controller:\w+>/<action:\w+>' => 'Base/<controller>/<action>',
                'Glauk/<controller:\w+>/<action:\w+>' => 'Glauk/<controller>/<action>',
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
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',
            'nullDisplay' => '',
            'timeZone' => 'UTC', // в dynagrid прибавляет еще timezone
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '172.19.17.3',
                'username' => 'portal',
                'password' => '123654',
                'port' => '25',
            ],
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
        'datecontrol' => [
            'class' => '\kartik\datecontrol\Module',
            'autoWidgetSettings' => [
                \kartik\datecontrol\Module::FORMAT_DATE => ['pluginOptions' => ['autoclose' => true, 'todayHighlight' => true]], // example
                \kartik\datecontrol\Module::FORMAT_DATETIME => ['pluginOptions' => ['autoclose' => true]], // setup if needed
                \kartik\datecontrol\Module::FORMAT_TIME => ['pluginOptions' => ['autoclose' => true]], // setup if needed
            ],
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
