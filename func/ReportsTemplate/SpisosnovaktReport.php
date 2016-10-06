<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Spisosnovakt;
use app\models\Fregat\Spisosnovmaterials;
use Yii;
use app\func\BaseReportPortal;

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 9:17
 * // Вывод акта осмотра материалов
 */
class SpisosnovaktReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('spisosnovakt');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Заявка на списание основных средств №' . $ID);

        $Spisosnovakt = Spisosnovakt::find($ID)->joinWith(['idMol', 'idEmployee', 'idSchetuchet'])->one();
        $Spisosnovmaterials = Spisosnovmaterials::findAll(['id_spisosnovakt' => $ID]);

        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'основных средств № ' . $Spisosnovakt->spisosnovakt_id . ' от ' . Yii::$app->formatter->asDate($Spisosnovakt->spisosnovakt_date));

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, $Spisosnovakt->idMol->idperson->auth_user_fullname . ', ' . $Spisosnovakt->idMol->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, $Spisosnovakt->idMol->idpodraz->podraz_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 6, $Spisosnovakt->idSchetuchet->schetuchet_kod . ', ' . $Spisosnovakt->idSchetuchet->schetuchet_name);

        $num = 11;
        foreach ($Spisosnovmaterials as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 10);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(1, $num, 2, $num);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, empty($ar->idMattraffic->idMaterial->material_serial) ? '-' : $ar->idMattraffic->idMaterial->material_serial);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, empty($ar->idMattraffic->idMaterial->material_release) ? '-' : Yii::$app->formatter->asDate($ar->idMattraffic->idMaterial->material_release));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->spisosnovmaterials_number);
            if (!empty($ar->idMattraffic->idMaterial->material_release)) {
                $DateRelease = new \DateTime($ar->idMattraffic->idMaterial->material_release);
                $DateNow = new \DateTime();
                $Years = $DateNow->diff($DateRelease)->y;
                $Months = $DateNow->diff($DateRelease)->m;
                $CountYears = ($Years === 0 ? '' : ($Years . ' л ')) . ($Months === 0 ? '' : ($Months . ' м'));
            } else
                $CountYears = '-';

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $CountYears);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idMattraffic->idMaterial->material_price);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':I' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($Spisosnovmaterials);
        $num = 12;

        $objPHPExcel->getActiveSheet()->unmergeCellsByColumnAndRow(1, $num + $crows - 1, 2, $num + $crows - 1);
        $objPHPExcel->getActiveSheet()->getStyle('C' . ($num + $crows - 1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num + $crows, $Spisosnovakt->idMol->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num + $crows, $Spisosnovakt->idMol->idperson->auth_user_fullname);

        if (isset($Spisosnovakt->idEmployee)) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num + $crows + 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + $crows + 1, 'Иное ответственное лицо');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num + $crows + 1, 1, $num + $crows + 1);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num + $crows + 1, 4, $num + $crows + 1);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(5, $num + $crows + 1, 8, $num + $crows + 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num + $crows + 1, $Spisosnovakt->idEmployee->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num + $crows + 1, $Spisosnovakt->idEmployee->idperson->auth_user_fullname);
        } else
            $objPHPExcel->getActiveSheet()->removeRow($num + $crows + 1);
    }

}