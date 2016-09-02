<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Employee;
use app\models\Fregat\Installakt;
use app\models\Fregat\Material;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use Yii;
use app\func\BaseReportPortal;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 * // Вывод акта перемещения материальной ценности по id
 */
class InstallaktReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('installakt');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт перемещения матер-ых цен-тей №' . $ID);

        $Installakt = Installakt::findOne($ID);
        $Trosnov = TrOsnov::findAll(['id_installakt' => $ID]);
        $Trmat = TrMat::find()->andWhere(['id_installakt' => $ID])->GroupBy('id_parent')->all();

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'материальных ценностей № ' . $Installakt->installakt_id . ' от ' . Yii::$app->formatter->asDate($Installakt->installakt_date));

        $num = 5;
        $c_Trosnov = count($Trosnov);
        if ($c_Trosnov > 0) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Перемещение материальных ценностей');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
            $this->setStyle(self::$TITLE, 'A' . $num . ':K' . $num);
            $num++;

            $this->SetTitlebyArray([
                '№',
                'Вид',
                'Наименование',
                'Инвентарный номер',
                'Серийный номер',
                'Кол-во',
                'Единица измерения',
                'Лицо отправитель',
                'Здание, кабинет, откуда перемещено',
                'Лицо получатель',
                'Здание, кабинет, куда перемещено',
            ], $num);

            $this->setStyle(self::$CAPTION, 'A' . $num . ':K' . $num);
            $this->CellsWrapAndTop('A' . $num . ':K' . $num);
            $num++;

            $this->setColumnNumbers($num, 11);
            $this->setStyle(self::$NUMS, 'A' . $num . ':K' . $num);
            $this->CellsWrapAndTop('A' . $num . ':K' . $num);
            $num++;

            $startrow = $num;
            foreach ($Trosnov as $ar) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - $startrow + 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->idMatv->matvid_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idMattraffic->idMaterial->material_name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idMattraffic->idMaterial->material_serial);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);

                $mattraffic_previous = Mattraffic::GetPreviousMattrafficByInstallaktMaterial($ID, $ar->idMattraffic->id_material);

                if (!empty($mattraffic_previous)) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $mattraffic_previous->idMol->idperson->auth_user_fullname . ', ' . $mattraffic_previous->idMol->iddolzh->dolzh_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $mattraffic_previous->idMol->idbuild->build_name . (empty($mattraffic_previous->mattraffic_tip = 1 && $mattraffic_previous->trOsnovs[0]->tr_osnov_kab) ? ', Приход' : (', ' . $mattraffic_previous->trOsnovs[0]->tr_osnov_kab)));
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idMattraffic->idMol->iddolzh->dolzh_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $ar->idMattraffic->idMol->idbuild->build_name . ', ' . $ar->tr_osnov_kab);
                $num++;
            }

            if (count($Trosnov) > 0)
                $this->setStyle(self::$DATA, 'A' . $startrow . ':K' . ($num - 1));
            $this->CellsWrapAndTop('A' . $startrow . ':K' . $num);
            $num++;
        }

        $c_Trmat = count($Trmat);
        if ($c_Trmat > 0) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Установка комплектующих');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
            $this->setStyle(self::$TITLE, 'A' . $num . ':K' . $num);
            $num++;
            foreach ($Trmat as $arm) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Материальная ценность');
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
                $this->setStyle(self::$TITLELEFT, 'A' . $num . ':K' . $num);
                $num++;

                $this->SetTitlebyArray([
                    '№',
                    'Вид',
                    'Наименование',
                    'Инвентарный номер',
                    'Серийный номер',
                    'Год выпуска',
                    'Стоимость',
                    'Здание',
                    'Кабинет',
                    'Материально-ответственное лицо',
                    'Тип',
                ], $num);

                $this->setStyle(self::$CAPTION, 'A' . $num . ':K' . $num);
                $this->CellsWrapAndTop('A' . $num . ':K' . $num);
                $num++;

                $this->setColumnNumbers($num, 11);
                $this->setStyle(self::$NUMS, 'A' . $num . ':K' . $num);
                $this->CellsWrapAndTop('A' . $num . ':K' . $num);
                $num++;

                $material_tip = Material::VariablesValues('material_tip');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $arm->idParent->idMaterial->idMatv->matvid_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $arm->idParent->idMaterial->material_name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $arm->idParent->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $arm->idParent->idMaterial->material_serial);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, Yii::$app->formatter->asDate($arm->idParent->idMaterial->material_release, 'YYYY'));
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(6, $num, $arm->idParent->idMaterial->material_price, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $arm->idParent->idMol->idbuild->build_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $arm->idParent->trOsnovs[0]->tr_osnov_kab);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $arm->idParent->idMol->idperson->auth_user_fullname . ', ' . $arm->idParent->idMol->iddolzh->dolzh_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$arm->idParent->idMaterial->material_tip]);

                $this->setStyle(self::$DATA, 'A' . $num . ':K' . $num);
                $this->CellsWrapAndTop('A' . $num . ':K' . $num);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Установленные комплектующие');
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
                $this->setStyle(self::$TITLELEFT, 'A' . $num . ':K' . $num);
                $num++;

                $this->SetTitlebyArray([
                    '№',
                    'Вид',
                    'Наименование',
                    'Инвентарный номер',
                    'Серийный номер',
                    'Кол-во',
                    'Единица измерения',
                    'Год выпуска',
                    'Стоимость',
                    'Материально-ответственное лицо',
                    'Тип',
                ], $num);

                $this->setStyle(self::$CAPTION, 'A' . $num . ':K' . $num);
                $this->CellsWrapAndTop('A' . $num . ':K' . $num);
                $num++;

                $this->setColumnNumbers($num, 11);
                $this->setStyle(self::$NUMS, 'A' . $num . ':K' . $num);
                $this->CellsWrapAndTop('A' . $num . ':K' . $num);
                $num++;

                $MatbyParent = TrMat::find()
                    ->andWhere([
                        'id_parent' => $arm->idParent->primaryKey,
                        'id_installakt' => $Installakt->installakt_id,
                    ])
                    ->all();

                $startrow = $num;
                foreach ($MatbyParent as $ar) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - $startrow + 1);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->idMatv->matvid_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idMattraffic->idMaterial->material_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idMattraffic->idMaterial->material_serial);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->idMattraffic->mattraffic_number);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, Yii::$app->formatter->asDate($ar->idMattraffic->idMaterial->material_release, 'YYYY'));
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idMattraffic->idMaterial->material_price);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idMattraffic->idMol->iddolzh->dolzh_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$ar->idMattraffic->idMaterial->material_tip]);
                    $num++;
                }
                if (count($MatbyParent) > 0)
                    $this->setStyle(self::$DATA, 'A' . $startrow . ':K' . ($num - 1));
                $this->CellsWrapAndTop('A' . $startrow . ':K' . $num);
                $num++;
            }
        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, '(Подпись)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, '(Должность)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, '(Ф.И.О.)');
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $this->setStyle(self::$SIGN, 'A' . $num . ':K' . $num);
        $num++;

        $Mols = Installakt::getMolsByInstallakt($Installakt->installakt_id);
        $startrow = $num;
        foreach ($Mols as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Материально ответственное лицо');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $ar->dolzh_name_tmp);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->auth_user_fullname_tmp);

            $this->CellsWrapAndTop('A' . $num . ':K' . $num);
            $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);

            $num++;
        }
        $this->setStyle(self::$TITLELEFT, 'A' . $startrow . ':B' . ($num - 1));
        $this->setStyle(self::$SIGNDATA, 'C' . $startrow . ':K' . ($num - 1));

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Мастер');
        $this->setStyle(self::$TITLELEFT, 'A' . $num . ':B' . $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);
        $this->setStyle(self::$SIGNDATA, 'C' . $num . ':K' . $num);

        $Master = Employee::findOne($Installakt->id_installer);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $Master->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $Master->idperson->auth_user_fullname);

        $this->CellsWrapAndTop('A' . $num . ':K' . $num);
        $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);
    }

}