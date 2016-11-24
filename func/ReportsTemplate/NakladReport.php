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
        $objPHPExcel->getActiveSheet()->getStyle("BB7")->getFont()->setSize(9);
        $objPHPExcel->getActiveSheet()->setCellValue('CL7', Yii::$app->formatter->asDate($Naklad->naklad_date));

        //    $objPHPExcel->getActiveSheet()->setCellValue('O8', '');
        $objPHPExcel->getActiveSheet()->getStyle("O8")->getFont()->setSize(8);

        $objPHPExcel->getActiveSheet()->setCellValue('O10', $Naklad->idMolRelease->idpodraz->podraz_name);
        $objPHPExcel->getActiveSheet()->getStyle("O10")->getFont()->setSize(8);
        $objPHPExcel->getActiveSheet()->setCellValue('O12', $Naklad->idMolGot->idpodraz->podraz_name);
        $objPHPExcel->getActiveSheet()->getStyle("O12")->getFont()->setSize(8);

        $objPHPExcel->getActiveSheet()->setCellValue('H15', $Naklad->idMolGot->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->getStyle("H15")->getFont()->setSize(6);
        $objPHPExcel->getActiveSheet()->setCellValue('W15', $Naklad->idMolGot->idperson->shortName);
        $objPHPExcel->getActiveSheet()->getStyle("W15")->getFont()->setSize(7);


        $num = 23;
        foreach ($Nakladmaterials as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $ar->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 16, $num);

            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(17, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(17, $num, 22, $num);

            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(23, $num, 28, $num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(29, $num, 34, $num);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(35, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_kod_okei);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(35, $num, 40, $num);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(41, $num, $ar->idMattraffic->idMaterial->material_price);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(41, $num, 49, $num);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(50, $num, $ar->nakladmaterials_number);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(50, $num, 55, $num);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(56, $num, $ar->nakladmaterials_number);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(56, $num, 61, $num);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(62, $num, $ar->nakladmaterials_sum);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(62, $num, 70, $num);

            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(71, $num, 81, $num);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(82, $num, 92, $num);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(93, $num, 98, $num);

            $num++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A23:CU' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('A23:CU' . $num)->getFont()->setSize(7);
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(0, $num, 16, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(17, $num, 22, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(23, $num, 28, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(29, $num, 34, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(35, $num, 40, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(93, $num, 98, $num);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + 6, $Naklad->idMolRelease->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->getStyle("A" . ($num + 6))->getFont()->setSize(8);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $num + 6, $Naklad->idMolRelease->idperson->shortName);
        $objPHPExcel->getActiveSheet()->getStyle("S" . ($num + 6))->getFont()->setSize(8);

        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($num + 9), date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($num + 9), Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($num + 9), date('y', strtotime('2016-11-05')));

        $objPHPExcel->getActiveSheet()->setCellValue('AG' . ($num + 9), date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('AJ' . ($num + 9), Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('AW' . ($num + 9), date('y', strtotime('2016-11-05')));

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num + 11, $Naklad->idMolGot->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->getStyle("G" . ($num + 11))->getFont()->setSize(8);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $num + 11, $Naklad->idMolGot->idperson->shortName);
        $objPHPExcel->getActiveSheet()->getStyle("AI" . ($num + 11))->getFont()->setSize(8);

        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($num + 13), date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($num + 13), Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($num + 13), date('y', strtotime('2016-11-05')));

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