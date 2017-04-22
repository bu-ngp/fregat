<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Fregatsettings;
use app\models\Fregat\Spismatmaterials;
use Yii;
use app\func\BaseReportPortal;
use app\models\Fregat\Spismat;

class SpismatReport extends BaseReportPortal
{
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName('spismat');
    }

    protected function Body()
    {
        $ID = $this->getDopparamID();
        $this->setReportName('Ведомость №' . $ID);

        $Spismat = Spismat::findOne($ID);
        $Fregatsettings = Fregatsettings::findOne(1);
        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('EH2', $Fregatsettings->getShortGlavvrachName());
        $objPHPExcel->getActiveSheet()->setCellValue('DL11', '№ ' . $Spismat->spismat_id);
        $objPHPExcel->getActiveSheet()->setCellValue('BC13', date('d', strtotime($Spismat->spismat_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('BJ13', Yii::$app->formatter->asDate(date('M', strtotime($Spismat->spismat_date)), 'php:F'));
        $objPHPExcel->getActiveSheet()->setCellValue('CH13', date('y', strtotime($Spismat->spismat_date)));
        $objPHPExcel->getActiveSheet()->setCellValue('L14', $Fregatsettings->fregatsettings_uchrezh_namesokr);
        $objPHPExcel->getActiveSheet()->setCellValue('Y15', $Spismat->idMol->idpodraz->podraz_name);
        $objPHPExcel->getActiveSheet()->setCellValue('AC16', $Spismat->idMol->idperson->auth_user_fullname);
        $objPHPExcel->getActiveSheet()->setCellValue('AA27', $Fregatsettings->getShortGlavbuhName());
        $objPHPExcel->getActiveSheet()->setCellValue('CP27', $Spismat->idMol->iddolzh->dolzh_name);
        $objPHPExcel->getActiveSheet()->setCellValue('EF27', $Spismat->idMol->idperson->getShortName());

        $objPHPExcel_tmp = $objPHPExcel->getSheet(1)->copy();
        $objPHPExcel_tmp->setTitle('Страница' . ($objPHPExcel->getSheetCount() + 1));
        $objPHPExcel->addSheet($objPHPExcel_tmp);

        file_put_contents('test.txt', print_r(Spismatmaterials::getVedomostArray($ID), true));

    }

}