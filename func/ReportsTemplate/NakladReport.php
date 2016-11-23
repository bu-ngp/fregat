<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Employee;
use app\models\Fregat\Fregatsettings;
use app\models\Fregat\Material;
use app\models\Fregat\Naklad;
use app\models\Fregat\Nakladmaterials;
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
class NakladReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('naklad');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Требование-накладная №' . $ID);

        /*      $Osmotraktmat = Osmotraktmat::findOne($ID);
            $TrMatOsmotr = TrMatOsmotr::findAll(['id_osmotraktmat' => $ID]);
            $Mols = TrMatOsmotr::getMolsByTrMatOsmotr($ID);
    */

        $Naklad = Naklad::findOne($ID);
        $Nakladmaterials = Nakladmaterials::findAll(['id_naklad' => $ID]);

        $objPHPExcel = $this->getObjPHPExcel();

        $objPHPExcel->getActiveSheet()->setCellValue('BA4', $Naklad->primaryKey);

        $objPHPExcel->getActiveSheet()->setCellValue('AK7', date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('AO7', Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('BB7', date('y', strtotime('2016-11-05')));
        $objPHPExcel->getActiveSheet()->setCellValue('CL7', Yii::$app->formatter->asDate($Naklad->naklad_date));

        //    $objPHPExcel->getActiveSheet()->setCellValue('O8', '');

        $objPHPExcel->getActiveSheet()->setCellValue('O10', $Naklad->idMolRelease->idpodraz->podraz_name);
        $objPHPExcel->getActiveSheet()->setCellValue('O12', $Naklad->idMolGot->idpodraz->podraz_name);

        $objPHPExcel->getActiveSheet()->setCellValue('H15', $Naklad->idMolGot->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValue('W15', $Naklad->idMolGot->idperson->auth_user_fullname);


        $num = 23;
        foreach ($Nakladmaterials as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 22);
            /*     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMat->idMattraffic->idMaterial->material_name);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, !empty($ar->idTrMat->idParent) ? ('Инв. номер: ' . $ar->idTrMat->idParent->idMaterial->material_inv . ', ' . $ar->idTrMat->idParent->idMaterial->material_name) : '');
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->tr_mat_osmotr_number);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idReason->reason_text . (empty($ar->idReason->reason_text) ? '' : '. ') . $ar->tr_mat_osmotr_comment);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, TrMatOsmotr::getBuildandKabByTrMatOsmotr($ar->primaryKey));
                 $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':J' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
          */
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);
        /*
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

                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num + $crows + 1, $Osmotraktmat->idMaster->idperson->auth_user_fullname);
                     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows + 1, $Osmotraktmat->idMaster->iddolzh->dolzh_name);
                     $objPHPExcel->getActiveSheet()->removeRow($num + $crows);
             */
    }

}