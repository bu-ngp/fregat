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


        //      var_dump(Yii::$app->request->queryParams);
        $fields = Yii::$app->request->queryParams;
        $dataProvider->pagination = false;
        $labels = Proc::GetAllLabelsFromAR($dataProvider, $fields['ImportemployeeSearch']);


        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Импорт сотрудников');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        ]);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Дата: ' . date('d.m.Y'));
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 2)->applyFromArray([
            'font' => [
                'italic' => true
            ]
        ]);

        $i = -1;
        $r = 4;
        foreach ($labels as $attr => $label) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, $label);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($ramka);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($font);
        }

        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $i, 1);


        foreach ($dataProvider->getModels() as $ar) {
            $r++;
            $data = Proc::GetAllDataFromAR($ar, $fields['ImportemployeeSearch']);
            $i = -1;
            foreach (array_keys($labels) as $attr) {
                $i++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, isset($data[$attr]) ? $data[$attr] : '');
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($ramka);
            }
        }

        /* Авторазмер колонок Excel */
        $i = -1;
        foreach ($labels as $attr => $label) {
            $i++;
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
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
        $FileName = 'Импорт сотрудников';

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
        $objWriter->save('files/' . $fileroot);
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
