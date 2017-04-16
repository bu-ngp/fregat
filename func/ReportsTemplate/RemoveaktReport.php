<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Employee;
use app\models\Fregat\Material;
use app\models\Fregat\Removeakt;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use Yii;
use app\func\BaseReportPortal;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 */
// Вывод акта снятия комплектующих с материальных ценностей по id
class RemoveaktReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('removeakt');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт снятия комплектующих с матер-ых цен-тей №' . $ID);

        $Removeakt = Removeakt::findOne($ID);

        $Trmat = TrMat::find()
            ->innerJoinWith(['trRmMats'])
            ->andWhere(['trRmMats.id_removeakt' => $ID])
            ->GroupBy('id_parent')
            ->all();

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'комплектующих № ' . $Removeakt->removeakt_id . ' от ' . Yii::$app->formatter->asDate($Removeakt->removeakt_date));

        $num = 5;
        $c_Trmat = count($Trmat);
        if ($c_Trmat > 0) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Снятие комплектующих');
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

                $Matparent = TrOsnov::find()
                    ->joinWith(['idMattraffic', 'idInstallakt'])
                    ->andWhere(['idMattraffic.id_material' => $arm->id_parent])
                    ->orderBy(['idInstallakt.installakt_date' => SORT_DESC])
                    ->one();

                $material_tip = Material::VariablesValues('material_tip');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $arm->idParent->idMaterial->idMatv->matvid_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $arm->idParent->idMaterial->material_name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $arm->idParent->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4, $num, $arm->idParent->idMaterial->material_serial, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, Yii::$app->formatter->asDate($arm->idParent->idMaterial->material_release, 'y'));
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(6, $num, $arm->idParent->idMaterial->material_price, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $arm->idParent->idMol->idbuild->build_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $arm->idParent->trOsnovs[0]->tr_osnov_kab);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $arm->idParent->idMol->idperson->auth_user_fullname . ', ' . $arm->idParent->idMol->iddolzh->dolzh_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$arm->idParent->idMaterial->material_tip]);

                $this->setStyle(self::$DATA, 'A' . $num . ':K' . $num);
                $this->CellsWrapAndTop('A' . $num . ':K' . $num);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Снятые комплектующие');
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
                    ->joinWith(['trRmMats'])
                    ->andWhere([
                        'id_parent' => $arm->idParent->primaryKey,
                        'trRmMats.id_removeakt' => $Removeakt->removeakt_id,
                    ])
                    ->all();

                $startrow = $num;
                foreach ($MatbyParent as $ar) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - $startrow + 1);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->idMatv->matvid_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idMattraffic->idMaterial->material_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4, $num, $ar->idMattraffic->idMaterial->material_serial, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->idMattraffic->mattraffic_number);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, Yii::$app->formatter->asDate($ar->idMattraffic->idMaterial->material_release, 'y'));
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

        $Mols = Removeakt::getMolsByRemoveakt($Removeakt->removeakt_id);
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

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Демонтажник');
        $this->setStyle(self::$TITLELEFT, 'A' . $num . ':B' . $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);
        $this->setStyle(self::$SIGNDATA, 'C' . $num . ':K' . $num);

        $Remover = Employee::findOne($Removeakt->id_remover);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $Remover->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $Remover->idperson->auth_user_fullname);

        $this->CellsWrapAndTop('A' . $num . ':K' . $num);
        $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);
    }

}