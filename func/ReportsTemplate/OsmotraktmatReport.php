<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\TrMatOsmotr;
use Yii;
use app\func\BaseReportPortal;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 * // Вывод акта осмотра материалов
 */
class OsmotraktmatReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('osmotraktmat');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Акт осмотра материалов №' . $ID);

        $Osmotraktmat = Osmotraktmat::findOne($ID);
        $TrMatOsmotr = TrMatOsmotr::findAll(['id_osmotraktmat' => $ID]);
        $Mols = TrMatOsmotr::getMolsByTrMatOsmotr($ID);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'материалов № ' . $Osmotraktmat->osmotraktmat_id . ' от ' . Yii::$app->formatter->asDate($Osmotraktmat->osmotraktmat_date));

        $num = 7;
        foreach ($TrMatOsmotr as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 6);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMat->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, !empty($ar->idTrMat->idParent) ? ('Инв. номер: ' . $ar->idTrMat->idParent->idMaterial->material_inv . ', ' . $ar->idTrMat->idParent->idMaterial->material_name) : '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->tr_mat_osmotr_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idReason->reason_text . (empty($ar->idReason->reason_text) ? '' : '. ') . $ar->tr_mat_osmotr_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, TrMatOsmotr::getBuildandKabByTrMatOsmotr($ar->primaryKey));
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':J' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($TrMatOsmotr);
        $num = 8;
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

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num + $crows + 1, $Osmotraktmat->idMaster->iddolzh->dolzh_name );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows + 1, $Osmotraktmat->idMaster->idperson->auth_user_fullname);
        $objPHPExcel->getActiveSheet()->removeRow($num + $crows);
    }

}