<?php

namespace app\controllers\Fregat;

use app\models\Fregat\Cabinet;
use Yii;
use app\models\Fregat\TrOsnov;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Material;
use app\models\Fregat\Employee;
use app\models\Fregat\TrOsnovSearch;

/**
 * TrOsnovController implements the CRUD actions for TrOsnov model.
 */
class TrOsnovController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selectinputfortrosnov', 'filltrosnov', 'matvid-count', 'forosmotrakt', 'selectinputforosmotrakt', 'assign-to-select2', 'fillinstalledmat'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['InstallEdit'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionForosmotrakt()
    {
        $searchModel = new TrOsnovSearch();
        $dataProvider = $searchModel->searchforosmotrakt(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new TrOsnov();
        $Mattraffic = new Mattraffic;
        $Material = new Material;
        $Employee = new Employee;
        $mattraffic_number_max = NULL;

        // Если форма отправлена на сервер, получаем выбранную материальную ценность
        $id_mattraffic = isset(Yii::$app->request->post('TrOsnov')['id_mattraffic']) ? Yii::$app->request->post('TrOsnov')['id_mattraffic'] : '';

        $transaction = Yii::$app->db->beginTransaction();
        try {

            // Если форма отправлена на сервер, то создать запись перемещения мат цен-ти в mattraffic
            if (!empty($id_mattraffic)) {
                $Mattrafficcurrent = Mattraffic::findOne($id_mattraffic);

                $Mattraffic->attributes = $Mattrafficcurrent->attributes;

                $Mattraffic->mattraffic_date = date('Y-m-d');
                $Mattraffic->mattraffic_number = isset(Yii::$app->request->post('Mattraffic')['mattraffic_number']) ? Yii::$app->request->post('Mattraffic')['mattraffic_number'] : NULL;
                $Mattraffic->mattraffic_tip = 3;

                if (isset($Mattraffic->scenarios()['traffic']))
                    $Mattraffic->scenario = 'traffic';

                if ($Mattraffic->validate()) {
                    $Mattraffic->save(false);
                    $model->id_mattraffic = $Mattraffic->mattraffic_id;
                }

                //Акт установки уже создан и берется из URL параметра
                $model->id_installakt = (string)filter_input(INPUT_GET, 'idinstallakt');

                //Сохраняем кабинет в модель из отправленной формы
                $model->id_cabinet = isset(Yii::$app->request->post('TrOsnov')['id_cabinet']) ? Yii::$app->request->post('TrOsnov')['id_cabinet'] : NULL;
            }

            // Сохраняем модель с отправленными данными и сохраненным mattraffic
            if (!$Mattraffic->isNewRecord && $model->save()) {
                $transaction->commit();
                return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else { // иначе
                // Берет значение сначала из справочника (посредством перехода на страницу выбора), если нет, то вытаскивает из сессии (для простого обновления страницы)
                // выводит $PreviusBC - для последующей передачи в функцию Proc::SetSessionValuesFromAR, т.е. установить в последнюю сессию или предыдущую (хлебных крошек)
                $PreviusBC = Proc::GetValueForFillARs($id_mattraffic, 'TrOsnov', 'id_mattraffic');

                // Очистить ошибку id_mattraffic, если есть ошибка по mattraffic_number (Превышено допустимое кол-во для перемещения матер. цен-ти)
                //if (isset($Mattraffic->errors['mattraffic_number']))
                //    $model->clearErrors('id_mattraffic');

                // Если выбрана мат. цен-ть, то заполнить информацию для отображения на форме по мат. цен-ти и МОЛ
                if (!empty($id_mattraffic)) {
                    $Material = Material::find()->joinWith('mattraffics')->where(['mattraffic_id' => $id_mattraffic])->one();
                    $Employee = Employee::find()->joinWith('mattraffics')->where(['mattraffic_id' => $id_mattraffic])->one();
                    // GetMaxNumberMattrafficForInstallAkt - Определяем максимально допустимое кол-во материала для перемещения (Общее кол-во материала минус уже перемещенное кол-во)
                    $mattraffic_number_max = 'Не более ' . doubleval(Mattraffic::GetMaxNumberMattrafficForInstallAkt($id_mattraffic));

                    // Сохраняем модель мат. цен-ти и МОЛ'а в сессию (т.к. эти модели только для отображения)
                    Proc::SetSessionValuesFromAR($Material, $PreviusBC);
                    Proc::SetSessionValuesFromAR($Employee, $PreviusBC);
                }

                // Откатываем транзакцию
                $transaction->rollBack();

                return $this->render('create', [
                    'model' => $model,
                    'Mattraffic' => $Mattraffic,
                    'Material' => $Material, // Для просмотра
                    'Employee' => $Employee, // Для просмотра
                    'mattraffic_number_max' => $mattraffic_number_max, //максимально допустимое кол-во материала
                ]);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $Mattraffic = Mattraffic::findOne($model->id_mattraffic);
        $Material = Material::find()->joinWith('mattraffics')->where(['mattraffic_id' => $model->id_mattraffic])->one();
        $Employee = Employee::find()->joinWith('mattraffics')->where(['mattraffic_id' => $model->id_mattraffic])->one();
        $mattraffic_number_max = NULL;
        $id_mattraffic = isset(Yii::$app->request->post('TrOsnov')['id_mattraffic']) ? Yii::$app->request->post('TrOsnov')['id_mattraffic'] : '';

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Если форма отправлена на сервер, то создать запись перемещения мат цен-ти в mattraffic
            if (!empty($id_mattraffic)) {
                $Mattrafficcurrent = Mattraffic::findOne($id_mattraffic);

                $Mattraffic->mattraffic_date = $Mattrafficcurrent->mattraffic_date;
                $Mattraffic->id_mol = $Mattrafficcurrent->id_mol;
                $Mattraffic->id_material = $Mattrafficcurrent->id_material;
                $Mattraffic->mattraffic_number = isset(Yii::$app->request->post('Mattraffic')['mattraffic_number']) ? Yii::$app->request->post('Mattraffic')['mattraffic_number'] : NULL;
                if (isset($Mattraffic->scenarios()['traffic']))
                    $Mattraffic->scenario = 'traffic';


                //Сохраняем кабинет в модель из отправленной формы
                $model->id_cabinet = isset(Yii::$app->request->post('TrOsnov')['id_cabinet']) ? Yii::$app->request->post('TrOsnov')['id_cabinet'] : NULL;

                if ($Mattraffic->save() && $model->save()) {
                    $transaction->commit();
                    return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
                } else {
                    $transaction->rollBack();

                    return $this->render('update', [
                        'model' => $model,
                        'Mattraffic' => $Mattraffic,
                        'Material' => $Material, // Для просмотра
                        'Employee' => $Employee, // Для просмотра
                        'mattraffic_number_max' => $mattraffic_number_max, //максимально допустимое кол-во материала

                    ]);
                }

            } else {
                $transaction->rollBack();

                return $this->render('update', [
                    'model' => $model,
                    'Mattraffic' => $Mattraffic,
                    'Material' => $Material, // Для просмотра
                    'Employee' => $Employee, // Для просмотра
                    'mattraffic_number_max' => $mattraffic_number_max, //максимально допустимое кол-во материала

                ]);
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfortrosnov($q = null, $idinstallakt = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new Mattraffic,
                'q' => $q,
                'methodquery' => 'selectinputfortrosnov',
                'methodparams' => ['idinstallakt' => $idinstallakt],
            ]);
    }

    // Заполнение полей формы после выбора материальной ценности по инвентарнику
    public function actionFilltrosnov()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $mattraffic_id = Yii::$app->request->post('mattraffic_id');
            if (!empty($mattraffic_id)) {
                $query = Mattraffic::findOne($mattraffic_id);
                if (!empty($query)) {
                    return [
                        'material_tip' => $query->idMaterial->material_tip,
                        'material_name' => $query->idMaterial->material_name,
                        'material_writeoff' => $query->idMaterial->material_writeoff,
                        'material_install_cabinet' => $query->idMaterial->material_install_cabinet,
                        'auth_user_fullname' => $query->idMol->idperson->auth_user_fullname,
                        'dolzh_name' => $query->idMol->iddolzh->dolzh_name,
                        'podraz_name' => $query->idMol->idpodraz->podraz_name,
                        'build_name' => $query->idMol->idbuild->build_name,
                        'mattraffic_number' => doubleval(Mattraffic::GetMaxNumberMattrafficForInstallAkt($query->mattraffic_id)), // Максимльно возможное кол-во для перемещения
                    ];
                }
            }
        }
    }

    // Заполнение полей формы после выбора материальной ценности по инвентарнику
    public function actionMatvidCount()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $mattraffic_id = Yii::$app->request->post('mattraffic_id');
            $cabinet_id = Yii::$app->request->post('cabinet_id');
            $Mattraffic = Mattraffic::findOne($mattraffic_id);
            $matvid_id = $Mattraffic->idMaterial->id_matvid;
            $build_id = $Mattraffic->idMol->id_build;

            if (!(empty($mattraffic_id) || empty($cabinet_id) || empty($matvid_id) || empty($build_id))) {
                $sum = Mattraffic::find()
                    ->select('sum(mt1.mattraffic_number) as summ')
                    ->from('mattraffic mt1')
                    ->leftJoin('mattraffic mt2', 'mt1.id_material = mt2.id_material and (mt1.mattraffic_date < mt2.mattraffic_date or mt1.mattraffic_id < mt2.mattraffic_id)')
                    ->leftJoin('tr_osnov os', 'mt1.mattraffic_id = os.id_mattraffic')
                    ->leftJoin('material m', 'm.material_id = mt1.id_material')
                    ->leftJoin('employee e', 'e.employee_id = mt1.id_mol')
                    ->andWhere(['mt2.mattraffic_date' => null])
                    ->andWhere(['like', 'os.id_cabinet', $cabinet_id])
                    ->andWhere(['m.id_matvid' => $matvid_id])
                    ->andWhere(['e.id_build' => $build_id])
                    ->asArray()
                    ->one();

                if ($sum !== null) {
                    $sum['summ'] = $sum['summ'] === null ? 0 : $sum['summ'];
                    $cabinet = Cabinet::findOne($cabinet_id);

                    return [
                        'message' => "В кабинете \"{$cabinet->idbuild->build_name}, каб. {$cabinet->cabinet_name}\" уже имеется вид материальной ценности \"{$Mattraffic->idMaterial->idMatv->matvid_name}\" в количестве: {$sum['summ']}"
                    ];
                }
            } else {
                return [
                    'message' => "",
                ];
            }
        }
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforosmotrakt($q = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new TrOsnov,
                'q' => $q,
                'methodquery' => 'selectinputforosmotrakt',
            ]);
    }

    public function actionAssignToSelect2()
    {
        Proc::AssignToModelFromGrid();
    }

    // Заполнение полей формы после выбора материальной ценности по инвентарнику
    public function actionFillinstalledmat()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id_tr_osnov = Yii::$app->request->post('id_tr_osnov');
            if (!empty($id_tr_osnov)) {
                $query = TrOsnov::findOne($id_tr_osnov);
                if (!empty($query)) {
                    return [
                        'material_name' => $query->idMattraffic->idMaterial->material_name,
                        'material_inv' => $query->idMattraffic->idMaterial->material_inv,
                        'material_serial' => $query->idMattraffic->idMaterial->material_serial,
                        'build_name' => $query->idMattraffic->idMol->idbuild->build_name,
                        'cabinet_name' => $query->idCabinet->cabinet_name,
                        'auth_user_fullname' => $query->idMattraffic->idMol->idperson->auth_user_fullname,
                        'dolzh_name' => $query->idMattraffic->idMol->iddolzh->dolzh_name,
                    ];
                }
            }
        }
    }

    // Удаление перемещаемой мат. цен-ти из акта установки
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tr_osnov = $this->findModel($id);
                $id_mattraffic = $tr_osnov->id_mattraffic;

                if ($tr_osnov->delete() === false)
                    throw new Exception('Не удалось удалить акт установки');

                $Mattraffic = Mattraffic::findOne($id_mattraffic)->delete();

                if ($Mattraffic === false)
                    throw new Exception('Не удалось удалить акт установки');

                echo $Mattraffic;

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * Finds the TrOsnov model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrOsnov the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrOsnov::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
