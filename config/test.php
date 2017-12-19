<?php
$params = require(__DIR__ . '/params.php');
$dbParams = require(__DIR__ . '/test_db.php');

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Yekaterinburg',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'db' => $dbParams,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            //  'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'Fregat/<controller:\w+>/<action:\w+>' => 'Fregat/<controller>/<action>',
                'Config/<controller:\w+>/<action:\w+>' => 'Config/<controller>/<action>',
                'Base/<controller:\w+>/<action:\w+>' => 'Base/<controller>/<action>',
                'Glauk/<controller:\w+>/<action:\w+>' => 'Glauk/<controller>/<action>',
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
