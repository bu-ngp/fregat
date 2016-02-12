<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use yii\web\Session;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'setsession','index'],
                'rules' => [
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['setsession'],
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

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
      /*  if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
         //   return $this->goBack();
        }*/
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSetsession() {
        $result = '0';
        $modelclass = (string) filter_input(INPUT_POST, 'modelclass');
        $field = (string) filter_input(INPUT_POST, 'field');
        $value = (string) filter_input(INPUT_POST, 'value');

        if (!empty($modelclass) && !empty($field)) {
            $session = new Session;
            $session->open();

            $res = $session['breadcrumbs'];
            end($res);


            if (isset($res[key($res)]['dopparams'][$modelclass])) {
                $res[key($res)]['dopparams'][$modelclass][$field] = $value;
                $result = '1';
            }

            /*   if (isset($session[$modelclass])) {
              $session[$modelclass] = array_replace_recursive($session[$modelclass], [
              'attributes' => [
              $field => $value,
              ],
              ]);
              $result = '1';
              } */


            $session['breadcrumbs'] = $res;

            $session->close();
        }

        echo $result;
    }

}
