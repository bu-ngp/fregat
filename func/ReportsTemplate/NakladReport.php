<?php

namespace app\func\ReportsTemplate;

use app\func\FregatImport;
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

        $Naklad = Naklad::findOne($ID);
        $Nakladmaterials = Nakladmaterials::findAll(['id_naklad' => $ID]);
        $Fregatsettings = Fregatsettings::findOne(1);

        $objPHPExcel = $this->getObjPHPExcel();

        $objPHPExcel->getActiveSheet()->setCellValue('BA4', $Naklad->primaryKey);

        $objPHPExcel->getActiveSheet()->setCellValue('AK7', date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('AO7', Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('BB7', date('y', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('CL7', Yii::$app->formatter->asDate($Naklad->naklad_date));

        $objPHPExcel->getActiveSheet()->setCellValue('O8', $Fregatsettings->fregatsettings_uchrezh_namesokr);

        $objPHPExcel->getActiveSheet()->setCellValue('O10', $Naklad->idMolRelease->idpodraz->podraz_name);
        $objPHPExcel->getActiveSheet()->setCellValue('O12', $Naklad->idMolGot->idpodraz->podraz_name);

        $objPHPExcel->getActiveSheet()->setCellValue('H15', $Naklad->idMolGot->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValue('W15', $Naklad->idMolGot->idperson->shortName);

        $objPHPExcel->getActiveSheet()->setCellValue('CC15', $Fregatsettings->ShortGlavvrachName);

        $num = 23;
        $rows_height = 0;
        foreach ($Nakladmaterials as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $ar->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 16, $num);

            $length = mb_strlen($ar->idMattraffic->idMaterial->material_name, 'UTF-8');

            $str_rows = ceil($length / 36);
            $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(12.75 * $str_rows);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num)->getAlignment()->setWrapText(true);
            $rows_height += 12.75 * $str_rows;

            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(17, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(17, $num, 22, $num);

            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(23, $num, '---');
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

            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(93, $num, '---');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(93, $num, 98, $num);

            $num++;
        }
        $objPHPExcel->getActiveSheet()->getStyle('A23:CO' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('X23:AC' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A23:CO' . $num)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->getStyle('A23:CU' . $num)->getFont()->setSize(7);
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $objPHPExcel->getActiveSheet()->setCellValue('BK' . $num, '=SUM(BK23:BK' . ($num - 1) . ')');

        if ($rows_height > 78)
            $objPHPExcel->getActiveSheet()->setBreak('A' . ($num + 1), \PHPExcel_Worksheet::BREAK_ROW);

        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(0, $num, 16, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(17, $num, 22, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(23, $num, 28, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(29, $num, 34, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(35, $num, 40, $num);
        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(93, $num, 98, $num);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + 6, $Naklad->idMolRelease->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $num + 6, $Naklad->idMolRelease->idperson->shortName);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $num + 6, 'ГЛАВНЫЙ ВРАЧ');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(49, $num + 6, $Fregatsettings->ShortGlavvrachName);

        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($num + 9), date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($num + 9), Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($num + 9), date('Y', strtotime('2016-11-05')));

        $objPHPExcel->getActiveSheet()->setCellValue('AG' . ($num + 9), date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('AJ' . ($num + 9), Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('AV' . ($num + 9), date('Y', strtotime('2016-11-05')));

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num + 11, $Naklad->idMolGot->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $num + 11, $Naklad->idMolGot->idperson->shortName);

        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($num + 13), date('d', strtotime($Naklad->naklad_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($num + 13), Yii::$app->formatter->asDate(date('M', strtotime($Naklad->naklad_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($num + 13), date('Y', strtotime('2016-11-05')));
    }

}