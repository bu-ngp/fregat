<?php

namespace app\func;

use Yii;
use app\func\Proc;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Installakt;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Material;
use app\models\Fregat\Employee;
use app\models\Fregat\Removeakt;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\TrMatOsmotr;

class ReportTemplates
{

    private static $Dopparams; // Дополнительные переменные POST, отправленные Ajax запросом
    private static $style = [ // Стиль ячеек в Excel
        'title' => [
            'font' => [
                'bold' => true,
                'name' => 'Tahoma',
                'size' => 9,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
        ],
        'titleleft' => [
            'font' => [
                'bold' => true,
                'name' => 'Tahoma',
                'size' => 9,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'bottom' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'top' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
            ],
        ],
        'caption' => [
            'font' => [
                'bold' => true,
                'name' => 'Tahoma',
                'size' => 9,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'top' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
            ],
        ],
        'nums' => [
            'font' => [
                'bold' => false,
                'name' => 'Tahoma',
                'size' => 8,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'top' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
            ],
        ],
        'data' => [
            'font' => [
                'bold' => false,
                'name' => 'Tahoma',
                'size' => 8,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'bottom' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'top' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
            ],
        ],
        'sign' => [
            'font' => [
                'bold' => false,
                'name' => 'Tahoma',
                'size' => 7,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
        ],
        'signdata' => [
            'font' => [
                'bold' => false,
                'name' => 'Tahoma',
                'size' => 9,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'bottom' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'top' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
            ],
        ],
    ];

    private static function CellsWrapAndTop(&$objPHPExcel, $CellCoordinate)
    {
        $objPHPExcel->getActiveSheet()->getStyle($CellCoordinate)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle($CellCoordinate)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
    }

    // Читаем дополнительные параметры из URL

    public static function GetDopparams()
    {
        $dopparams = json_decode(Yii::$app->request->post()['dopparams']);
        if (!empty($dopparams))
            self::$Dopparams = $dopparams;
        else
            throw new \Exception('Ошибка в OsmotraktReport()');
    }

    // Вывод акта осмотра по id
    public static function Osmotrakt()
    {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('osmotrakt'); // Создаем объект PHPExcel

        $Osmotrakt = Osmotrakt::findOne(self::$Dopparams->id);

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

        Proc::DownloadExcelPHP($objPHPExcel, 'Акт осмотра №' . $Osmotrakt->osmotrakt_id); // Скачиваем сформированный отчет
    }

    // Вывод акта отправки материальной ценности от сторонней организации
    public static function Recoverysendakt()
    {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('recoverysendakt'); // Создаем объект PHPExcel

        $Recoverysendakt = Recoverysendakt::findOne(self::$Dopparams->id);
        $Recoveryrecieveakt = Recoveryrecieveakt::findAll(['id_recoverysendakt' => self::$Dopparams->id]);
        $Mols = Recoveryrecieveakt::getMolsByRecoverysendakt(self::$Dopparams->id);

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'сторонней организации № ' . $Recoverysendakt->recoverysendakt_id . ' от ' . Yii::$app->formatter->asDate($Recoverysendakt->recoverysendakt_date));
        $objPHPExcel->getActiveSheet()->setCellValue('C4', $Recoverysendakt->idOrgan->organ_name);

        $num = 9;
        foreach ($Recoveryrecieveakt as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 8);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idOsmotrakt->idReason->reason_text . (empty($ar->idOsmotrakt->idReason->reason_text) ? '' : '. ') . $ar->idOsmotrakt->osmotrakt_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idOsmotrakt->idTrosnov->tr_osnov_kab);
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

        Proc::DownloadExcelPHP($objPHPExcel, 'Акт передачи матер-ных цен-тей сторонней организации №' . $Recoverysendakt->recoverysendakt_id); // Скачиваем сформированный отчет
    }

    // Вывод акта получения материальной ценности от сторонней организации 
    public static function Recoveryrecieveakt()
    {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('recoveryrecieveakt'); // Создаем объект PHPExcel

        $Recoverysendakt = Recoverysendakt::findOne(self::$Dopparams->id);
        $Recoveryrecieveakt_ok = Recoveryrecieveakt::findAll(['id_recoverysendakt' => self::$Dopparams->id, 'recoveryrecieveakt_repaired' => 2]);
        $Recoveryrecieveakt_fail = Recoveryrecieveakt::find()
            ->andWhere(['and', ['id_recoverysendakt' => self::$Dopparams->id], ['or', ['recoveryrecieveakt_repaired' => 1], ['recoveryrecieveakt_repaired' => NULL]]])
            ->all();
        $Mols = Recoveryrecieveakt::getMolsByRecoverysendakt(self::$Dopparams->id);

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idOsmotrakt->idTrosnov->tr_osnov_kab);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, Yii::$app->formatter->asDate($ar->recoveryrecieveakt_date));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($Recoveryrecieveakt_ok);
        $num = 15;
        foreach ($Recoveryrecieveakt_fail as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num + $crows, $num + $crows - 16);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num + $crows, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num + $crows, $ar->recoveryrecieveakt_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num + $crows, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num + $crows, $ar->idOsmotrakt->idTrosnov->tr_osnov_kab);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num + $crows, Yii::$app->formatter->asDate($ar->recoveryrecieveakt_date));
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num + $crows . ':K' . $num + $crows)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
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

        Proc::DownloadExcelPHP($objPHPExcel, 'Акт получения матер-ных цен-тей от сторонней организации №' . $Recoverysendakt->recoverysendakt_id); // Скачиваем сформированный отчет
    }

    // Вывод акта перемещения материальной ценности по id
    public static function Installakt()
    {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('installakt'); // Создаем объект PHPExcel

        $Installakt = Installakt::findOne(self::$Dopparams->id);
        $Trosnov = TrOsnov::findAll(['id_installakt' => self::$Dopparams->id]);
        $Trmat = TrMat::find()->andWhere(['id_installakt' => self::$Dopparams->id])->GroupBy('id_parent')->all();

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'материальных ценностей № ' . $Installakt->installakt_id . ' от ' . Yii::$app->formatter->asDate($Installakt->installakt_date));

        $num = 5;
        $c_Trosnov = count($Trosnov);
        if ($c_Trosnov > 0) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Перемещение материальных ценностей');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['title']);
            $num++;

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, '№');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, 'Вид');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, 'Наименование');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, 'Инвентарный номер');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, 'Серийный номер');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 'Кол-во');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, 'Единица измерения');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, 'Лицо отправитель');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, 'Здание, кабинет, откуда перемещено');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, 'Лицо получатель');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, 'Здание, кабинет, куда перемещено');
            for ($i = 0; $i <= 10; $i++)
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['caption']);
            self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
            $num++;

            for ($i = 0; $i <= 10; $i++) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $num, $i + 1);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['nums']);
            }
            self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
            $num++;

            $startrow = $num;
            foreach ($Trosnov as $ar) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - $startrow + 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->idMatv->matvid_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idMattraffic->idMaterial->material_name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idMattraffic->idMaterial->material_serial);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);

                $mattraffic_previous = Mattraffic::GetPreviousMattrafficByInstallaktMaterial(self::$Dopparams->id, $ar->idMattraffic->id_material);

                if (!empty($mattraffic_previous)) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $mattraffic_previous->idMol->idperson->auth_user_fullname . ', ' . $mattraffic_previous->idMol->iddolzh->dolzh_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $mattraffic_previous->idMol->idbuild->build_name . (empty($mattraffic_previous->mattraffic_tip = 1 && $mattraffic_previous->trOsnovs[0]->tr_osnov_kab) ? ', Приход' : (', ' . $mattraffic_previous->trOsnovs[0]->tr_osnov_kab)));
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idMattraffic->idMol->iddolzh->dolzh_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $ar->idMattraffic->idMol->idbuild->build_name . ', ' . $ar->tr_osnov_kab);
                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['data']);
                $num++;
            }
            self::CellsWrapAndTop($objPHPExcel, 'A' . $startrow . ':K' . $num);
            $num++;
        }

        $c_Trmat = count($Trmat);
        if ($c_Trmat > 0) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Установка комплектующих');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['title']);
            $num++;
            foreach ($Trmat as $arm) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Материальная ценность');
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['titleleft']);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, '№');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, 'Вид');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, 'Наименование');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, 'Инвентарный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, 'Серийный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 'Год выпуска');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, 'Стоимость');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, 'Здание');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, 'Кабинет');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, 'Материально-ответственное лицо');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, 'Тип');
                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['caption']);
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                for ($i = 0; $i <= 10; $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $num, $i + 1);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['nums']);
                }
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                $Matparent = TrOsnov::find()
                    ->joinWith(['idMattraffic', 'idInstallakt'])
                    ->andWhere(['mattraffic.id_material' => $arm->id_parent])
                    ->orderBy(['installakt.installakt_date' => SORT_DESC])
                    ->one();

                $material_tip = Material::VariablesValues('material_tip');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $Matparent->idMattraffic->idMaterial->idMatv->matvid_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $Matparent->idMattraffic->idMaterial->material_name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $Matparent->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $Matparent->idMattraffic->idMaterial->material_serial);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, Yii::$app->formatter->asDate($Matparent->idMattraffic->idMaterial->material_release, 'YYYY'));
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(6, $num, $Matparent->idMattraffic->idMaterial->material_price, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $Matparent->idMattraffic->idMol->idbuild->build_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $Matparent->tr_osnov_kab);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $Matparent->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $Matparent->idMattraffic->idMol->iddolzh->dolzh_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$Matparent->idMattraffic->idMaterial->material_tip]);

                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['data']);
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Установленные комплектующие');
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['titleleft']);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, '№');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, 'Вид');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, 'Наименование');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, 'Инвентарный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, 'Серийный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 'Кол-во');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, 'Единица измерения');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, 'Год выпуска');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, 'Стоимость');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, 'Материально-ответственное лицо');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, 'Тип');
                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['caption']);
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                for ($i = 0; $i <= 10; $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $num, $i + 1);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['nums']);
                }
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                $MatbyParent = TrMat::find()
                    ->andWhere([
                        'id_parent' => $Matparent->idMattraffic->id_material,
                        'id_installakt' => $Installakt->installakt_id,
                    ])
                    ->all();

                $c_MatbyParent = count($MatbyParent);
                $startrow = $num;
                foreach ($MatbyParent as $ar) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - $startrow + 1);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->idMatv->matvid_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idMattraffic->idMaterial->material_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idMattraffic->idMaterial->material_serial);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->idMattraffic->mattraffic_number);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, Yii::$app->formatter->asDate($ar->idMattraffic->idMaterial->material_release, 'YYYY'));
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idMattraffic->idMaterial->material_price);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idMattraffic->idMol->iddolzh->dolzh_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$ar->idMattraffic->idMaterial->material_tip]);
                    for ($i = 0; $i <= 10; $i++)
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['data']);
                    $num++;
                }
                self::CellsWrapAndTop($objPHPExcel, 'A' . $startrow . ':K' . $num);
                $num++;
            }
        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, '(Подпись)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, '(Должность)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, '(Ф.И.О.)');
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['sign']);
        $num++;

        $Mols = Installakt::getMolsByInstallakt($Installakt->installakt_id);
        foreach ($Mols as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Материально ответственное лицо');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $num)->applyFromArray(self::$style['titleleft']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $num)->applyFromArray(self::$style['titleleft']);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);

            for ($i = 2; $i <= 10; $i++)
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['signdata']);

            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $ar->dolzh_name_tmp);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->auth_user_fullname_tmp);

            self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
            $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);

            $num++;
        }

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Мастер');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $num)->applyFromArray(self::$style['titleleft']);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $num)->applyFromArray(self::$style['titleleft']);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);
        for ($i = 2; $i <= 10; $i++)
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['signdata']);

        $Master = Employee::findOne($Installakt->id_installer);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $Master->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $Master->idperson->auth_user_fullname);

        self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
        $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);

        Proc::DownloadExcelPHP($objPHPExcel, 'Акт перемещения матер-ых цен-тей №' . $Installakt->installakt_id); // Скачиваем сформированный отчет
    }

    // Вывод акта снятия комплектующих с материальных ценностей по id
    public static function Removeakt()
    {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('removeakt'); // Создаем объект PHPExcel

        $Removeakt = Removeakt::findOne(self::$Dopparams->id);
        $Trmat = TrMat::find()
            ->innerJoinWith(['trRmMats'])
            ->andWhere(['tr_rm_mat.id_removeakt' => self::$Dopparams->id])
            ->GroupBy('id_parent')
            ->all();

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'комплектующих № ' . $Removeakt->removeakt_id . ' от ' . Yii::$app->formatter->asDate($Removeakt->removeakt_date));

        $num = 5;
        $c_Trmat = count($Trmat);
        if ($c_Trmat > 0) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Снятие комплектующих');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['title']);
            $num++;
            foreach ($Trmat as $arm) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Материальная ценность');
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['titleleft']);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, '№');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, 'Вид');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, 'Наименование');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, 'Инвентарный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, 'Серийный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 'Год выпуска');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, 'Стоимость');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, 'Здание');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, 'Кабинет');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, 'Материально-ответственное лицо');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, 'Тип');
                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['caption']);
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                for ($i = 0; $i <= 10; $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $num, $i + 1);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['nums']);
                }
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                $Matparent = TrOsnov::find()
                    ->joinWith(['idMattraffic', 'idInstallakt'])
                    ->andWhere(['mattraffic.id_material' => $arm->id_parent])
                    ->orderBy(['installakt.installakt_date' => SORT_DESC])
                    ->one();

                $material_tip = Material::VariablesValues('material_tip');

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $Matparent->idMattraffic->idMaterial->idMatv->matvid_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $Matparent->idMattraffic->idMaterial->material_name);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $Matparent->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $Matparent->idMattraffic->idMaterial->material_serial);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, Yii::$app->formatter->asDate($Matparent->idMattraffic->idMaterial->material_release, 'YYYY'));
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(6, $num, $Matparent->idMattraffic->idMaterial->material_price, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $Matparent->idMattraffic->idMol->idbuild->build_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $Matparent->tr_osnov_kab);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $Matparent->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $Matparent->idMattraffic->idMol->iddolzh->dolzh_name);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$Matparent->idMattraffic->idMaterial->material_tip]);

                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['data']);
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Снятые комплектующие');
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 10, $num);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['titleleft']);
                $num++;

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, '№');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, 'Вид');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, 'Наименование');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, 'Инвентарный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, 'Серийный номер');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, 'Кол-во');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, 'Единица измерения');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, 'Год выпуска');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, 'Стоимость');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, 'Материально-ответственное лицо');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, 'Тип');
                for ($i = 0; $i <= 10; $i++)
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['caption']);
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                for ($i = 0; $i <= 10; $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $num, $i + 1);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['nums']);
                }
                self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
                $num++;

                $MatbyParent = TrMat::find()
                        ->joinWith([
                            'trRmMats' => function ($query) {
                                $query->from(['trRmMats' => 'tr_rm_mat']);
                            },
                                ])
                                ->andWhere([
                                    'id_parent' => $Matparent->idMattraffic->id_material,
                                    'trRmMats.id_removeakt' => $Removeakt->removeakt_id,
                                ])
                                ->all();

                $c_MatbyParent = count($MatbyParent);
                $startrow = $num;
                foreach ($MatbyParent as $ar) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - $startrow + 1);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idMattraffic->idMaterial->idMatv->matvid_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idMattraffic->idMaterial->material_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, $ar->idMattraffic->idMaterial->material_serial);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->idMattraffic->mattraffic_number);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idMattraffic->idMaterial->idIzmer->izmer_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, Yii::$app->formatter->asDate($ar->idMattraffic->idMaterial->material_release, 'YYYY'));
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idMattraffic->idMaterial->material_price);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idMattraffic->idMol->iddolzh->dolzh_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $material_tip[$ar->idMattraffic->idMaterial->material_tip]);
                    for ($i = 0; $i <= 10; $i++)
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['data']);
                    $num++;
                }
                self::CellsWrapAndTop($objPHPExcel, 'A' . $startrow . ':K' . $num);
                $num++;
            }

            // Вывод акта осмотра материалов
            public static function Osmotraktmat() {
                self::GetDopparams(); // Читаем дополнительные параметры из URL

                $objPHPExcel = Proc::CreateExcelPHP('osmotraktmat'); // Создаем объект PHPExcel

                $Osmotraktmat = Osmotraktmat::findOne(self::$Dopparams->id);
                $TrMatOsmotr = TrMatOsmotr::findAll(['id_osmotraktmat' => self::$Dopparams->id]);
                $Mols = TrMatOsmotr::getMolsByTrMatOsmotr(self::$Dopparams->id);

                $objPHPExcel->getActiveSheet()->setCellValue('A3', 'материалов № ' . $Osmotraktmat->osmotraktmat_id . ' от ' . Yii::$app->formatter->asDate($Osmotraktmat->osmotraktmat_date));

                $num = 7;
                foreach ($TrMatOsmotr as $ar) {
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 6);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMat->idMattraffic->idMaterial->material_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, !empty($ar->idTrMat->idParent) ? ('Инв. номер: ' . $ar->idTrMat->idParent->material_inv . ', ' . $ar->idTrMat->idParent->material_name) : '');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->tr_mat_osmotr_number);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idReason->reason_text . (empty($ar->idReason->reason_text) ? '' : '. ') . $ar->tr_mat_osmotr_comment);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, '');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':J' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $num++;
                }
                $objPHPExcel->getActiveSheet()->removeRow($num);

                $crows = count($TrMatOsmotr);
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

                Proc::DownloadExcelPHP($objPHPExcel, 'Акт осмотра материалов №' . $Osmotraktmat->primaryKey); // Скачиваем сформированный отчет
            }

            // Вывод акта отправки материала от сторонней организации
            public static function Recoverysendaktmat() {
                self::GetDopparams(); // Читаем дополнительные параметры из URL
                $Recoverysendakt = Recoverysendakt::findOne(self::$Dopparams->id);

                $objPHPExcel = Proc::CreateExcelPHP('recoverysendaktmat'); // Создаем объект PHPExcel

                Proc::DownloadExcelPHP($objPHPExcel, 'Акт передачи материалов сторонней организации №' . $Recoverysendakt->recoverysendakt_id); // Скачиваем сформированный отчет
            }

            // Вывод акта получения материала у сторонней организации
            public static function Recoveryrecieveaktmat() {
                self::GetDopparams(); // Читаем дополнительные параметры из URL
                $Recoverysendakt = Recoverysendakt::findOne(self::$Dopparams->id);

                $objPHPExcel = Proc::CreateExcelPHP('recoveryrecieveaktmat'); // Создаем объект PHPExcel

                Proc::DownloadExcelPHP($objPHPExcel, 'Акт получения материалов у сторонней организации №' . $Recoverysendakt->recoverysendakt_id); // Скачиваем сформированный отчет
            }

        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, '(Подпись)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, '(Должность)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, '(Ф.И.О.)');
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':K' . $num)->applyFromArray(self::$style['sign']);
        $num++;

        $Mols = Removeakt::getMolsByRemoveakt($Removeakt->removeakt_id);
        foreach ($Mols as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Материально ответственное лицо');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $num)->applyFromArray(self::$style['titleleft']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $num)->applyFromArray(self::$style['titleleft']);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);

            for ($i = 2; $i <= 10; $i++)
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['signdata']);

            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $ar->dolzh_name_tmp);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->auth_user_fullname_tmp);

            self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
            $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);

            $num++;
        }

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, 'Демонтажник');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $num)->applyFromArray(self::$style['titleleft']);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $num)->applyFromArray(self::$style['titleleft']);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $num, 1, $num);
        for ($i = 2; $i <= 10; $i++)
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray(self::$style['signdata']);

        $Remover = Employee::findOne($Removeakt->id_remover);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $num, 6, $num);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(7, $num, 10, $num);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $Remover->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $Remover->idperson->auth_user_fullname);

        self::CellsWrapAndTop($objPHPExcel, 'A' . $num . ':K' . $num);
        $objPHPExcel->getActiveSheet()->getRowDimension($num)->setRowHeight(45.75);

        Proc::DownloadExcelPHP($objPHPExcel, 'Акт снятия комплектующих с матер-ых цен-тей №' . $Removeakt->removeakt_id); // Скачиваем сформированный отчет
    }

    // Вывод акта осмотра материалов
    public static function Osmotraktmat()
    {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('osmotraktmat'); // Создаем объект PHPExcel

        $Osmotraktmat = Osmotraktmat::findOne(self::$Dopparams->id);
        $TrMatOsmotr = TrMatOsmotr::findAll(['id_osmotraktmat' => self::$Dopparams->id]);
        $Mols = TrMatOsmotr::getMolsByTrMatOsmotr(self::$Dopparams->id);

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'материалов № ' . $Osmotraktmat->osmotraktmat_id . ' от ' . Yii::$app->formatter->asDate($Osmotraktmat->osmotraktmat_date));

        $num = 7;
        foreach ($TrMatOsmotr as $ar) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($num);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 6);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $ar->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMat->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3, $num, $ar->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num, !empty($ar->idTrMat->idParent) ? ('Инв. номер: ' . $ar->idTrMat->idParent->material_inv . ', ' . $ar->idTrMat->idParent->material_name) : '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num, $ar->tr_mat_osmotr_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, $ar->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idReason->reason_text . (empty($ar->idReason->reason_text) ? '' : '. ') . $ar->tr_mat_osmotr_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $ar->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, '');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $num . ':J' . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $num++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($num);

        $crows = count($TrMatOsmotr);
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

        Proc::DownloadExcelPHP($objPHPExcel, 'Акт осмотра материалов №' . $Osmotraktmat->primaryKey); // Скачиваем сформированный отчет
    }

 
    public static function Recoverysendaktmat() {

    }

}
        
