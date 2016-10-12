<?php

use kartik\datecontrol\Module;

return [
    'adminEmail' => 'admin@example.com',
    'panelStyle' => 'info',
    'GridHeadingStyle' => 'primary',
    // format settings for displaying each date attribute (ICU format example)
    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'dd.MM.yyyy',
        Module::FORMAT_TIME => 'hh:mm:ss',
        Module::FORMAT_DATETIME => 'dd.MM.yyyy hh:mm:ss',
    ],
    // format settings for saving each date attribute (PHP format example)
    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:Y-m-d',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],
    'icon-framework' => 'fa',  // Font Awesome Icon framework
];
