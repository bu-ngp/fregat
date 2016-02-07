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
                'only' => ['logout', 'setsession', 'selectinput'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['setsession', 'selectinput'],
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
        return $this->render('index');
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

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionSetsession() {
        $result = '0';
        $modelclass = (string) filter_input(INPUT_POST, 'modelclass');
        $field = (string) filter_input(INPUT_POST, 'field');
        $value = (string) filter_input(INPUT_POST, 'value');

        if (!empty($modelclass) && !empty($field)) {
            $session = new Session;
            $session->open();
            if (isset($session[$modelclass])) {
                $session[$modelclass] = array_replace_recursive($session[$modelclass], [
                    'attributes' => [
                        $field => $value,
                    ],
                ]);
                $result = '1';
            }
            $session->close();
        }

        echo $result;
    }

    public function actionSelectinput($model, $field, $q = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $m = new $model;
            $out['results'] = $model::find()
                    ->select([$m->primaryKey()[0] . ' AS id', $field . ' AS text'])
                    ->where(['like', $field, $q])
                    ->limit(20)
                    ->asArray()
                    ->all();
        }
        return $out;
    }

}
