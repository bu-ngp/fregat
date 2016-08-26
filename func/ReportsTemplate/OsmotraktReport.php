<?php

namespace app\func\ReportsTemplate;

use Yii;
use app\func\BaseReportPortal;
use app\models\Fregat\Osmotrakt;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 */
class OsmotraktReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('osmotrakt');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт осмотра №' . $ID);

        $Osmotrakt = Osmotrakt::findOne($ID);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'вышедшей из строя № ' . $Osmotrakt->osmotrakt_id . ' от ' . Yii::$app->formatter->asDate($Osmotrakt->osmotrakt_date));
        $objPHPExcel->getActiveSheet()->setCellValue('C5', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
        $objPHPExcel->getActiveSheet()->setCellValue('C6', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('C7', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValue('C8', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial);
        $objPHPExcel->getActiveSheet()->setCellValue('C9', $Osmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name . ', ' . $Osmotrakt->idTrosnov->tr_osnov_kab);
        $objPHPExcel->getActiveSheet()->setCellValue('C10', $Osmotrakt->idUser->idperson->auth_user_fullname . ', ' . $Osmotrakt->idUser->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValue('C11', $Osmotrakt->idTrosnov->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $Osmotrakt->idTrosnov->idMattraffic->idMol->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValue('C12', $Osmotrakt->idReason->reason_text . (empty($Osmotrakt->idReason->reason_text) ? '' : '. ') . $Osmotrakt->osmotrakt_comment);
        $objPHPExcel->getActiveSheet()->setCellValue('C14', $Osmotrakt->idMaster->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValue('D14', $Osmotrakt->idMaster->idperson->auth_user_fullname);
    }

}