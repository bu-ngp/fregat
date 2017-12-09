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
 * // Вывод акта отправки материала от сторонней организации
 */
class RecoverysendaktmatReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('recoverysendaktmat');
    }

    /**
     * @param $objPHPExcel
     * @param null $ID
     */
    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт передачи материалов сторонней организации №' . $ID);

        $Recoverysendakt = Recoverysendakt::findOne($ID);
        $Recoveryrecieveaktmat = Recoveryrecieveaktmat::findAll(['id_recoverysendakt' => $ID]);
        $Mols = Recoveryrecieveaktmat::getMolsByRecoverysendakt($ID);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'сторонней организации № ' . $Recoverysendakt->recoverysendakt_id . ' от ' . Yii::$app->formatter->asDate($Recoverysendakt->recoverysendakt_date));
        $objPHPExcel->getActiveSheet()->setCellValue('C4', $Recoverysendakt->idOrgan->organ_name);

        $material_tip = Material::VariablesValues('material_tip');

        $num = 9;
        foreach ($Recoveryrecieveaktmat as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 8);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $material_tip[$ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_tip]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->idTrMatOsmotr->tr_mat_osmotr_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idTrMatOsmotr->idReason->reason_text . (empty($ar->idTrMatOsmotr->idReason->reason_text) ? '' : '. ') . $ar->idTrMatOsmotr->tr_mat_osmotr_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, TrMatOsmotr::getBuildandCabinetByTrMatOsmotr($ar->idTrMatOsmotr->tr_mat_osmotr_id));
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':J' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($Recoveryrecieveaktmat);
        $num = 10;
        foreach ($Mols as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + $crows, 'Материально ответственное лицо');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num + $crows, 1, $num + $crows);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num + $crows, 6, $num + $crows);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num + $crows, 9, $num + $crows);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num + $crows, $ar['dolzh_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows, $ar['auth_user_fullname']);
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num + $crows + 1);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num + $crows);
    }

}