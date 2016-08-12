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

class ReportTemplates {

    private static $Dopparams; // Дополнительные переменные POST, отправленные Ajax запросом

    // Читаем дополнительные параметры из URL

    public static function GetDopparams() {
        $dopparams = json_decode(Yii::$app->request->post()['dopparams']);
        if (!empty($dopparams))
            self::$Dopparams = $dopparams;
        else
            throw new \Exception('Ошибка в OsmotraktReport()');
    }

    // Вывод акта осмотра по id
    public static function Osmotrakt() {
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
    public static function Recoverysendakt() {
        self::GetDopparams(); // Читаем дополнительные параметры из URL

        $objPHPExcel = Proc::CreateExcelPHP('recoverysendakt'); // Создаем объект PHPExcel

        $Recoverysendakt = Recoverysendakt::findOne(self::$Dopparams->id);
        $Recoveryrecieveakt = Recoveryrecieveakt::findAll(['id_recoverysendakt' => self::$Dopparams->id]);
        $Mols = Recoveryrecieveakt::find()
                ->select(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
                ->joinWith([
                    'idOsmotrakt' => function($query) {
                        $query->from(['idOsmotrakt' => 'osmotrakt']);
                        $query->joinWith([
                            'idTrosnov' => function($query) {
                                $query->from(['idTrosnov' => 'tr_osnov']);
                                $query->joinWith([
                                    'idMattraffic' => function($query) {
                                        $query->from(['idMattraffic' => 'mattraffic']);
                                        $query->joinWith([
                                            'idMol' => function($query) {
                                                $query->from(['idMol' => 'employee']);
                                                $query->joinWith([
                                                    'idperson' => function($query) {
                                                        $query->from(['idperson' => 'auth_user']);
                                                    },
                                                            'iddolzh' => function($query) {
                                                        $query->from(['iddolzh' => 'dolzh']);
                                                    },
                                                        ]);
                                                    }
                                                        ]);
                                                    }
                                                        ]);
                                                    }
                                                        ]);
                                                    }
                                                        ])
                                                        ->andWhere(['id_recoverysendakt' => self::$Dopparams->id])
                                                        ->groupBy(['idMol.id_person', 'idMol.id_dolzh'])
                                                        ->asArray()
                                                        ->all();

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
                                            public static function Recoveryrecieveakt() {
                                                self::GetDopparams(); // Читаем дополнительные параметры из URL

                                                $objPHPExcel = Proc::CreateExcelPHP('recoveryrecieveakt'); // Создаем объект PHPExcel

                                                $Recoverysendakt = Recoverysendakt::findOne(self::$Dopparams->id);
                                                $Recoveryrecieveakt_ok = Recoveryrecieveakt::findAll(['id_recoverysendakt' => self::$Dopparams->id, 'recoveryrecieveakt_repaired' => 2]);
                                                $Recoveryrecieveakt_fail = Recoveryrecieveakt::find()
                                                        ->andWhere(['and', ['id_recoverysendakt' => self::$Dopparams->id], ['or', ['recoveryrecieveakt_repaired' => 1], ['recoveryrecieveakt_repaired' => NULL]]])
                                                        ->all();
                                                $Mols = Recoveryrecieveakt::find()
                                                        ->select(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
                                                        ->joinWith([
                                                            'idOsmotrakt' => function($query) {
                                                                $query->from(['idOsmotrakt' => 'osmotrakt']);
                                                                $query->joinWith([
                                                                    'idTrosnov' => function($query) {
                                                                        $query->from(['idTrosnov' => 'tr_osnov']);
                                                                        $query->joinWith([
                                                                            'idMattraffic' => function($query) {
                                                                                $query->from(['idMattraffic' => 'mattraffic']);
                                                                                $query->joinWith([
                                                                                    'idMol' => function($query) {
                                                                                        $query->from(['idMol' => 'employee']);
                                                                                        $query->joinWith([
                                                                                            'idperson' => function($query) {
                                                                                                $query->from(['idperson' => 'auth_user']);
                                                                                            },
                                                                                                    'iddolzh' => function($query) {
                                                                                                $query->from(['iddolzh' => 'dolzh']);
                                                                                            },
                                                                                                ]);
                                                                                            }
                                                                                                ]);
                                                                                            }
                                                                                                ]);
                                                                                            }
                                                                                                ]);
                                                                                            }
                                                                                                ])
                                                                                                ->andWhere(['id_recoverysendakt' => self::$Dopparams->id])
                                                                                                ->groupBy(['idMol.id_person', 'idMol.id_dolzh'])
                                                                                                ->asArray()
                                                                                                ->all();



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

                                                                                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $num + $crows, count($Recoveryrecieveakt_fail));

                                                                                        Proc::DownloadExcelPHP($objPHPExcel, 'Акт получения матер-ных цен-тей от сторонней организации №' . $Osmotrakt->osmotrakt_id); // Скачиваем сформированный отчет
                                                                                    }

                                                                                    // Вывод акта перемещения материальной ценности по id
                                                                                    public static function Installakt() {
                                                                                        self::GetDopparams(); // Читаем дополнительные параметры из URL

                                                                                        $objPHPExcel = Proc::CreateExcelPHP('installakt'); // Создаем объект PHPExcel

                                                                                        $Installakt = Installakt::findOne(self::$Dopparams->id);

                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'материальных ценностей № ' . $Installakt->installakt_id . ' от ' . Yii::$app->formatter->asDate($Installakt->installakt_date));
                                                                                       /* $objPHPExcel->getActiveSheet()->setCellValue('C5', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->idMatv->matvid_name);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C6', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->material_name);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValueExplicit('C7', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->material_inv, \PHPExcel_Cell_DataType::TYPE_STRING);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C8', $Osmotrakt->idTrosnov->idMattraffic->idMaterial->material_serial);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C9', $Osmotrakt->idTrosnov->idMattraffic->idMol->idbuild->build_name . ', ' . $Osmotrakt->idTrosnov->tr_osnov_kab);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C10', $Osmotrakt->idUser->idperson->auth_user_fullname . ', ' . $Osmotrakt->idUser->iddolzh->dolzh_name);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C11', $Osmotrakt->idTrosnov->idMattraffic->idMol->idperson->auth_user_fullname . ', ' . $Osmotrakt->idTrosnov->idMattraffic->idMol->iddolzh->dolzh_name);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C12', $Osmotrakt->idReason->reason_text . (empty($Osmotrakt->idReason->reason_text) ? '' : '. ') . $Osmotrakt->osmotrakt_comment);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('C14', $Osmotrakt->idMaster->iddolzh->dolzh_name);
                                                                                        $objPHPExcel->getActiveSheet()->setCellValue('D14', $Osmotrakt->idMaster->idperson->auth_user_fullname);*/

                                                                                        Proc::DownloadExcelPHP($objPHPExcel, 'Акт перемещения матер-ых цен-тей №' . $Installakt->installakt_id); // Скачиваем сформированный отчет
                                                                                    }

                                                                                }
                                                                                