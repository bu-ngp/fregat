<?php

namespace app\controllers;

use app\func\OSHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use yii\web\Session;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //   'only' => ['logout', 'setsession', 'index', 'setwindowguid'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['error', 'setsession', 'setwindowguid', 'delete-excel-file', 'delete-tmp-file', 'gohome'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest)
            return $this->goHome();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
            return $this->goBack();

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSetsession()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = '0';
        $modelclass = (string)filter_input(INPUT_POST, 'modelclass');
        $field = (string)filter_input(INPUT_POST, 'field');
        $value = (string)filter_input(INPUT_POST, 'value');
        $data = (string)filter_input(INPUT_POST, 'data');


        if (!empty($modelclass) && !empty($field)) {
            $session = new Session;
            $session->open();

            $res = $session['breadcrumbs'];
            end($res);

            if (isset($res[key($res)]['dopparams'][$modelclass])) {
                $res[key($res)]['dopparams'][$modelclass][$field] = $value;
                $result = '1';
            }

            $session['breadcrumbs'] = $res;
            $session->close();
        } elseif (!empty($data)) {
            $data = json_decode($data);
            $session = new Session;
            $session->open();

            $res = $session['breadcrumbs'];
            end($res);

            foreach ($data as $obj)
                if (isset($res[key($res)]['dopparams'][$obj->modelclass]))
                    $res[key($res)]['dopparams'][$obj->modelclass][$obj->field] = $obj->value;

            $result = '1';
            $session['breadcrumbs'] = $res;
            $session->close();
        }

        return $result;
    }

    // Для определения секущей вкладки, если вкладка сменилась, то перейти на домашнюю страницу
    public function actionSetwindowguid()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $guid = (string)filter_input(INPUT_POST, 'guid');
        $pathname = (string)filter_input(INPUT_POST, 'path');
        $search = (string)filter_input(INPUT_POST, 'search');
        $session = new Session;
        $session->open();
        $res = isset($session['WindowsGUIDs']) ? $session['WindowsGUIDs'] : [];
        $currentguid = $session['WindowsGUIDCurrent'];
        $gohome = false;
        $homeurls = [
            "",
            "?r=site%2Findex",
            "?r=site%2Flogin",
            "?r=site%2Flogout",
            "?r=site%2Ferror",
        ];

        $ishome = in_array($search, $homeurls);

        if (empty($guid)) {        // Если новая вкладка
            for ($i = 0; $i < 6; $i++)
                $guid .= dechex(rand(0, 15));

            $res[$guid] = 1;

            $session['WindowsGUIDs'] = $res;
            $session['WindowsGUIDCurrent'] = $guid;
            $gohome = !$ishome;
        } else {        // Если существующая вкладка
            if ($session['WindowsGUIDCurrent'] === $guid) {     // Если текущая вкладка
            } else {        // Если другая существующая вкладка
                $session['WindowsGUIDCurrent'] = $guid;
                $gohome = !$ishome;
            }
            $session['WindowsGUIDs'] = $res;
        }
        $session->close();

        return (object)['guid' => $guid, 'gohome' => $gohome];
    }

    public function actionGohome()
    {
        if (Yii::$app->request->isAjax)
            return $this->goHome();
    }

    public function actionDeleteExcelFile()
    {
        if (YII_ENV !== 'test') {
            $FileName = OSHelper::setFileNameByOS('files/' . (string)filter_input(INPUT_POST, 'filename'));
            unlink($FileName);
        }
    }

    public function actionDeleteTmpFile()
    {
        $FileName = OSHelper::setFileNameByOS('tmpfiles/' . (string)filter_input(INPUT_POST, 'filename'));
        unlink($FileName);
    }

}
