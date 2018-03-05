<?php

namespace app\func;

use Yii;
use app\func\Proc;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Recoveryrecieveaktmat;
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

    private static function SetTitlebyArray(&$objPHPExcel, $TitleArrayNames, $RowNum)
    {
        foreach ($TitleArrayNames as $ColumnNum => $Title)
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($ColumnNum, $RowNum, $Title);
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

    public static function Recoverysendakt_ExportExcel()
    {
        $objPHPExcel = new \PHPExcel;
        $reportName = 'Выгрузка';

        /* Границы таблицы */
        $ramka = array(
            'borders' => array(
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ],
            ),
        );

        /* Жирный шрифт для шапки таблицы */
        $font = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $reportName);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
        ]);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Дата: ' . date('d.m.Y'));
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 2)->applyFromArray([
            'font' => [
                'italic' => true
            ]
        ]);

        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        //   $fields = Proc::GetArrayValuesByKeyName($modelName, $inputdata);
        //   $selectvalues = (array) $selectvalues;

        $filter = 'Фильтр:';

        /*  foreach ($fields[$modelName] as $attr => $value) {
          $val_result = $value;
          if (!empty($value)) {
          if (isset($selectvalues[$modelName . '[' . $attr . ']']))
          $val_result = $selectvalues[$modelName . '[' . $attr . ']'][$fields[$modelName][$attr]];

          $filter .= ' ' . $labels[$attr] . ': "' . $val_result . '";';
          }
          } */

        /*   if ($ModelFilter instanceof Model) {
          $dopfilter = self::ConstructFilterOutput($ModelFilter);
          if (!empty($dopfilter))
          $filter .= ' ' . $dopfilter;
          } */

        $num = 5;

        //  $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, 10, 1);

        self::SetTitlebyArray($objPHPExcel, [
            '№',
            'Тип материальной ценности',
            'Вид материальной ценности',
            'Наименование',
            'Инвентарный номер',
            'Серийный номер',
            'Дата выпуска',
            'Стоимость',
            'Списание',
            'Количество',
            'Единица измерения',
            'Материально-ответственное лицо',
            'Здание',
            'Кабинет',
            'Укомплектовано в',
            'Инвентарный номер мат-ой цен-ти в которую укомплектовано',
            'Номер акта осмотра',
            'Дата акта осмотра',
            'Вид акта осмотра',
            'Мастер',
            'Причина неисправности',
            'Пользователь',
            'Организация',
            'Дата отправки',
            'Дата получения',
            'Результат',
            'Подлежит восстановлению',
        ], $num);

        for ($i = 0; $i <= 26; $i++)
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $num)->applyFromArray($font);

        $num++;

        $Recoveryrecieveakt = Recoveryrecieveakt::find()
            ->joinWith([
                'idRecoverysendakt.idOrgan',
                'idOsmotrakt.idReason',
                'idOsmotrakt.idUser idUser',
                'idOsmotrakt.idMaster idMaster',
                'idOsmotrakt.idUser.idperson idpersonuser',
                'idOsmotrakt.idUser.iddolzh iddolzhuser',
                'idOsmotrakt.idMaster.idperson idpersonmaster',
                'idOsmotrakt.idMaster.iddolzh iddolzhmaster',
                'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.idMatv',
                'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.idIzmer',
                'idOsmotrakt.idTrosnov.idMattraffic.idMol.idperson',
                'idOsmotrakt.idTrosnov.idMattraffic.idMol.iddolzh',
                'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild',
            ])
            ->orderBy(['idOsmotrakt.osmotrakt_date' => SORT_ASC])
            ->all();

        $material_tip = Material::VariablesValues('material_tip');
        $material_writeoff = Material::VariablesValues('material_writeoff');
        $recoveryrecieveakt_repaired = Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired');

        foreach ($Recoveryrecieveakt as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 5);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $material_tip[$ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_tip]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(5, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, Yii::$app->formatter->asDate($ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_release));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_price);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $material_writeoff[$ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->material_writeoff]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $num, $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $num, empty($ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name) ? '' : $ar->idOsmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $num, $ar->idOsmotrakt->idTrosnov->idCabinet->cabinet_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $num, '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $num, '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $num, $ar->idOsmotrakt->osmotrakt_id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $num, Yii::$app->formatter->asDate($ar->idOsmotrakt->osmotrakt_date));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $num, 'Материальная ценность');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $num, $ar->idOsmotrakt->idMaster->idperson->auth_user_fullname . ', ' . $ar->idOsmotrakt->idMaster->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $num, (empty($ar->idOsmotrakt->idReason->reason_text) ? '' : ($ar->idOsmotrakt->idReason->reason_text . ', ')) . $ar->idOsmotrakt->osmotrakt_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $num, $ar->idOsmotrakt->idUser->idperson->auth_user_fullname . ', ' . $ar->idOsmotrakt->idUser->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $num, $ar->idRecoverysendakt->idOrgan->organ_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $num, Yii::$app->formatter->asDate($ar->idRecoverysendakt->recoverysendakt_date));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $num, Yii::$app->formatter->asDate($ar->recoveryrecieveakt_date));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $num, $ar->recoveryrecieveakt_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $num, empty($ar->recoveryrecieveakt_repaired) ? '' : $recoveryrecieveakt_repaired[$ar->recoveryrecieveakt_repaired]);

            $num++;
        }

        $Recoveryrecieveaktmat = Recoveryrecieveaktmat::find()
            ->joinWith([
                'idRecoverysendakt.idOrgan',
                'idTrMatOsmotr.idReason',
                'idTrMatOsmotr.idOsmotraktmat.idMaster idMaster',
                'idTrMatOsmotr.idOsmotraktmat.idMaster.idperson idpersonmaster',
                'idTrMatOsmotr.idOsmotraktmat.idMaster.iddolzh iddolzhmaster',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.idMatv',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.idIzmer',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild',
                'idTrMatOsmotr.idTrMat.idParent idParent',
            ])
            ->orderBy(['idOsmotraktmat.osmotraktmat_date' => SORT_ASC])
            ->all();

        $recoveryrecieveaktmat_repaired = Recoveryrecieveaktmat::VariablesValues('recoveryrecieveaktmat_repaired');

        foreach ($Recoveryrecieveaktmat as $ar) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num, $num - 5);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num, $material_tip[$ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_tip]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idMatv->matvid_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(5, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_serial, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num, Yii::$app->formatter->asDate($ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_release));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_price);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num, $material_writeoff[$ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->material_writeoff]);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $num, $ar->idTrMatOsmotr->tr_mat_osmotr_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMaterial->idIzmer->izmer_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $num, $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $num, empty($ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->idbuild->build_name) ? '' : $ar->idTrMatOsmotr->idTrMat->idMattraffic->idMol->idbuild->build_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $num, TrMatOsmotr::getBuildandCabinetByTrMatOsmotr($ar->id_tr_mat_osmotr));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $num, $ar->idTrMatOsmotr->idTrMat->idParent->material_name);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(15, $num, $ar->idTrMatOsmotr->idTrMat->idParent->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $num, $ar->idTrMatOsmotr->idOsmotraktmat->osmotraktmat_id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $num, Yii::$app->formatter->asDate($ar->idTrMatOsmotr->idOsmotraktmat->osmotraktmat_date));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $num, 'Материал');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $num, $ar->idTrMatOsmotr->idOsmotraktmat->idMaster->idperson->auth_user_fullname . ', ' . $ar->idTrMatOsmotr->idOsmotraktmat->idMaster->iddolzh->dolzh_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $num, (empty($ar->idTrMatOsmotr->idReason->reason_text) ? '' : ($ar->idTrMatOsmotr->idReason->reason_text . ', ')) . $ar->idTrMatOsmotr->tr_mat_osmotr_comment);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $num, '');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $num, $ar->idRecoverysendakt->idOrgan->organ_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $num, Yii::$app->formatter->asDate($ar->idRecoverysendakt->recoverysendakt_date));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $num, Yii::$app->formatter->asDate($ar->recoveryrecieveaktmat_date));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $num, $ar->recoveryrecieveaktmat_result);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $num, empty($ar->recoveryrecieveaktmat_repaired) ? '' : $recoveryrecieveaktmat_repaired[$ar->recoveryrecieveaktmat_repaired]);

            $num++;
        }

        /* Авторазмер колонок Excel */
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(6);

        $objPHPExcel->getActiveSheet()->getStyle('A5:AA' . ($num - 1))->applyFromArray($ramka);


        for ($i = 1; $i <= 26; $i++)
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);

        /*   if ($filter !== 'Фильтр:') {
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, $filter);
          $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 3, $i, 3);
          $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray([
          'font' => [
          'italic' => true
          ]
          ]);
          } */

        // присваиваем имя файла от имени модели
        $FileName = $reportName;

        // Устанавливаем имя листа
        $objPHPExcel->getActiveSheet()->setTitle($FileName);

        // Выбираем первый лист
        $objPHPExcel->setActiveSheetIndex(0);
        // Формируем файл Excel
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $FileName = OSHelper::setFileNameByOS($FileName);
        // Proc::SaveFileIfExists() - Функция выводит подходящее имя файла, которое еще не существует. mb_convert_encoding() - Изменяем кодировку на кодировку Windows
        $fileroot = Proc::SaveFileIfExists('files/' . $FileName . '.xlsx');
        // Сохраняем файл в папку "files"
        $objWriter->save('files/' . $fileroot);
        // Возвращаем имя файла Excel
        echo OSHelper::getFileNameByOS($fileroot);
    }

}
        