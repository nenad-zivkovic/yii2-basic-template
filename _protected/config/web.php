<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'My application',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) 
            // - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        // you can set your theme here (template comes with 'default' and 'cool')
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/cool'],
                'baseUrl' => '@web/themes/cool',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
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
        // if you decide to use bootstrap and jquery from CDN, uncomment assetManager settings
        // 'assetManager' => [
        //     'bundles' => [
        //         // use bootstrap css from CDN
        //         'yii\bootstrap\BootstrapAsset' => [
        //             'sourcePath' => null,   // do not use file from our server
        //             'css' => [
        //                 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css']
        //         ],
        //         // use bootstrap js from CDN
        //         'yii\bootstrap\BootstrapPluginAsset' => [
        //             'sourcePath' => null,   // do not use file from our server
        //             'js' => [
        //                 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js']
        //         ],
        //         // use jquery from CDN
        //         'yii\web\JqueryAsset' => [
        //             'sourcePath' => null,   // do not publish the bundle
        //             'js' => [
        //                 '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
        //             ]
        //         ],
        //     ],
        // ],

        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
