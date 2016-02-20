<?php

namespace app\controllers\Config;

use Yii;
use app\models\Config\Authitemchild;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * AuthitemchildController implements the CRUD actions for Authitemchild model.
 */
class AuthitemchildController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['RoleEdit'],
                    ],
                ],
            ],
        ];
    }

    public function actionDelete($parent, $child) {
        $this->findModel($parent, $child)->delete();
        return $this->redirect(['Config/authitem/update', 'id' => $parent]);
    }

    /**
     * Finds the Authitemchild model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $parent
     * @param string $child
     * @return Authitemchild the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($parent, $child) {
        if (($model = Authitemchild::findOne(['parent' => $parent, 'child' => $child])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
