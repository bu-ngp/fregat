<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Recoverysendakt;
use Yii;
use app\func\BaseReportPortal;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 * // Вывод акта отправки материальной ценности от сторонней организации
 */
class RecoverysendaktReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('recoverysendakt');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт передачи матер-ных цен-тей сторонней организации №' . $ID);

        $Recoverysendakt = Recoverysendakt::findOne($ID);
        $Recoveryrecieveakt = Recoveryrecieveakt::findAll(['id_recoverysendakt' => $ID]);
        $Mols = Recoveryrecieveakt::getMolsByRecoverysendakt($ID);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'сторонней организации № ' . $Recoverysendakt->recoverysendakt_id . ' от ' . Yii::$app->formatter->asDate($Recoverysendakt->recoverysendakt_date));
        $objPHPExcel->getActiveSheet()->setCellValue('C4', $Recoverysendakt->idOrgan->organ_name);

        $num = 9;
        foreach ($Recoveryrecieveakt as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 8);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idOsmotrakt->idReason->reason_text . (empty($ar->idOsmotrakt->idReason->reason_text) ? '' : '. ') . $ar->idOsmotrakt->osmotrakt_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idOsmotrakt->idTrosnov->idCabinet->cabinet_name);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':J' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($Recoveryrecieveakt);
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