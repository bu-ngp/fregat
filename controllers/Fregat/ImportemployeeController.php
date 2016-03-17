<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Importemployee;
use app\models\Fregat\ImportemployeeSearch;
use app\models\Fregat\Impemployee;
use app\models\Fregat\ImpemployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * ImportemployeeController implements the CRUD actions for Importemployee model.
 */
class ImportemployeeController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'toexcel'],
                        'allow' => true,
                        'roles' => ['FregatImport'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new ImportemployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Importemployee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->importemployee_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $Impemployee = new Impemployee;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $Impemployee->load(Yii::$app->request->get(), 'Impemployee');
            $Impemployee->id_importemployee = $model->primaryKey;
            if ($Impemployee->validate())
                $Impemployee->save(false);

            $searchModel = new ImpemployeeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'Impemployee' => $Impemployee,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionToexcel() {
        $searchModel = new ImportemployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $objPHPExcel = new \PHPExcel;

        /* Границы таблицы */
        $ramka = array(
            'borders' => array(
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        );
        /* Жирный шрифт для шапки таблицы */
        $font = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );


        /*     $models = $dataProvider->getModels();
          $model = reset($models);
          if (is_array($model) || is_object($model)) {
          foreach ($model as $name => $value) {
          var_dump( (string) $name);
          }
          } */

        var_dump(Yii::$app->request->queryParams);
        $fields = Yii::$app->request->queryParams;

        foreach ($dataProvider->getModels() as $ar) {
            $data = Proc::GetAllDataFromAR($ar, $fields['ImportemployeeSearch']);
            var_dump($data);
            //      var_dump($ar->getRelatedRecords());
            //  var_dump($ar->extraFields());
            //    $mas = $ar::find()->joinWith(array_keys($ar->extraFields()))->asArray()->all();
            //   var_dump($mas);
            //     $mas = $ar::find()->asArray()->all();            
            //   var_dump($ar);
        }



        /*    if (!empty($searchModel)) {
          foreach ($rows->values as $i => $row)
          foreach ($rows->fields as $col => $attr) {
          if ($i == 0) {// заполняем шапку таблицы
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $i + 1, $attr->label);
          $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $i + 1)->applyFromArray($ramka);
          $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $i + 1)->applyFromArray($font);
          }

          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $i + 2, $rows->values[$i][$attr->fieldname]);
          $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $i + 2)->applyFromArray($ramka);
          }
          } */

        /* присваиваем имя файла от имени модели */
        $FileName = 'Выгрузка';

        // Устанавливаем имя листа
        $objPHPExcel->getActiveSheet()->setTitle($FileName);

        // Выбираем первый лист
        $objPHPExcel->setActiveSheetIndex(0);
        /* Формируем файл Excel */
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $FileName = DIRECTORY_SEPARATOR === '/' ? $FileName : mb_convert_encoding($FileName, 'Windows-1251', 'UTF-8');
        /* Proc::SaveFileIfExists() - Функция выводит подходящее имя файла, которое еще не существует. mb_convert_encoding() - Изменяем кодировку на кодировку Windows */
        $fileroot = Proc::SaveFileIfExists('files/' . $FileName . '.xlsx');
        /* Сохраняем файл в папку "files" */
        //      $objWriter->save('files/' . $fileroot);
        /* Возвращаем имя файла Excel */
        if (DIRECTORY_SEPARATOR === '/')
            echo $fileroot;
        else
            echo mb_convert_encoding($fileroot, 'UTF-8', 'Windows-1251');
    }

    /**
     * Finds the Importemployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Importemployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Importemployee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
