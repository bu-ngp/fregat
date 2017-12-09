<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Material;
use app\models\Fregat\Recoveryrecieveaktmat;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\TrMatOsmotr;
use Yii;
use app\func\BaseReportPortal;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 * // Вывод акта получения материала у сторонней организации
 */
class RecoveryrecieveaktmatReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('recoveryrecieveaktmat');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт получения материалов у сторонней организации №' . $ID);

        $Recoverysendakt = Recoverysendakt::findOne($ID);
        $Recoveryrecieveaktmat_ok = Recoveryrecieveaktmat::find()
            ->andWhere(['id_recoverysendakt' => $ID, 'recoveryrecieveaktmat_repaired' => 2])->all();
        $Recoveryrecieveaktmat_fail = Recoveryrecieveaktmat::find()
            ->andWhere(['and', ['id_recoverysendakt' => $ID], ['or', ['recoveryrecieveaktmat_repaired' => 1], ['recoveryrecieveaktmat_repaired' => NULL]]])
            ->all();
        $Mols = Recoveryrecieveaktmat::getMolsByRecoverysendakt($ID);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('F4', $Recoverysendakt->idOrgan->organ_name);
        $objPHPExcel->getActiveSheet()->setCellValue('F5', $Recoverysendakt->recoverysendakt_id . ' от ' . Yii::$app->formatter->asDate($Recoverysendakt->recoverysendakt_date));
        $objPHPExcel->getActiveSheet()->setCellValue('F6', Yii::$app->formatter->asDate(date('Y-m-d')));

        $material_tip = Material::VariablesValues('material_tip');

        $num = 11;
        foreach ($Recoveryrecieveaktmat_ok as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 10);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $material_tip[$ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_tip]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->idTrMatOsmotr->tr_mat_osmotr_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->recoveryrecieveaktmat_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, TrMatOsmotr::getBuildandCabinetByTrMatOsmotr($ar->id_tr_mat_osmotr));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, Yii::$app->formatter->asDate($ar->recoveryrecieveaktmat_date));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($Recoveryrecieveaktmat_ok);
        $num = 15;
        foreach ($Recoveryrecieveaktmat_fail as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + $crows, $num + $crows - 15);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num + $crows, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num + $crows, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num + $crows, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num + $crows, $material_tip[$ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_tip]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num + $crows, $ar->idTrMatOsmotr->tr_mat_osmotr_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num + $crows, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows, $ar->recoveryrecieveaktmat_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num + $crows, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num + $crows, TrMatOsmotr::getBuildandCabinetByTrMatOsmotr($ar->id_tr_mat_osmotr));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num + $crows, Yii::$app->formatter->asDate($ar->recoveryrecieveaktmat_date));
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($num + $crows) . ':K' . ($num + $crows))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num + $crows + 1);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num + $crows);

        $crows = count($Recoveryrecieveaktmat_ok) + count($Recoveryrecieveaktmat_fail);
        $num = 17;
        foreach ($Mols as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + $crows, 'Материально ответственное лицо');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num + $crows, 1, $num + $crows);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num + $crows, 6, $num + $crows);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num + $crows, 10, $num + $crows);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num + $crows, $ar['dolzh_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows, $ar['auth_user_fullname']);
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num + $crows + 1);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num + $crows);
    }

}