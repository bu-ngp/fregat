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
 */
class RecoveryrecieveaktReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('recoveryrecieveakt');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт получения матер-ных цен-тей от сторонней организации №' . $ID);

        $Recoverysendakt = Recoverysendakt::findOne($ID);
        $Recoveryrecieveakt_ok = Recoveryrecieveakt::findAll(['id_recoverysendakt' => $ID, 'recoveryrecieveakt_repaired' => 2]);
        $Recoveryrecieveakt_fail = Recoveryrecieveakt::find()
            ->andWhere(['and', ['id_recoverysendakt' => $ID], ['or', ['recoveryrecieveakt_repaired' => 1], ['recoveryrecieveakt_repaired' => NULL]]])
            ->all();
        $Mols = Recoveryrecieveakt::getMolsByRecoverysendakt($ID);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('F4', $Recoverysendakt->idOrgan->organ_name);
        $objPHPExcel->getActiveSheet()->setCellValue('F5', $Recoverysendakt->recoverysendakt_id . ' от ' . Yii::$app->formatter->asDate($Recoverysendakt->recoverysendakt_date));
        $objPHPExcel->getActiveSheet()->setCellValue('F6', Yii::$app->formatter->asDate(date('Y-m-d')));

        $num = 11;
        foreach ($Recoveryrecieveakt_ok as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 10);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->recoveryrecieveakt_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idOsmotrakt->idTrosnov->idCabinet->cabinet_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, Yii::$app->formatter->asDate($ar->recoveryrecieveakt_date));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($Recoveryrecieveakt_ok);
        $num = 15;
        foreach ($Recoveryrecieveakt_fail as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + $crows, $num + $crows - 15);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num + $crows, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows, $ar->recoveryrecieveakt_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num + $crows, $ar->idOsmotrakt->idTrosnov->idCabinet->cabinet_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num + $crows, Yii::$app->formatter->asDate($ar->recoveryrecieveakt_date));
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($num + $crows) . ':K' . ($num + $crows))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num + $crows + 1);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num + $crows);

        $crows = count($Recoveryrecieveakt_ok) + count($Recoveryrecieveakt_fail);
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