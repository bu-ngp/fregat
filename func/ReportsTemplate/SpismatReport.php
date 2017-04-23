<?php

namespace app\func\ReportsTemplate;

use app\models\Fregat\Fregatsettings;
use app\models\Fregat\Spismatmaterials;
use PHPExcel;
use Yii;
use app\func\BaseReportPortal;
use app\models\Fregat\Spismat;

class SpismatReport extends BaseReportPortal
{
    const MATERIAL_PER_SHEET = 10;
    const INSTALLER_PER_SHEET = 1;

    private $mat_sheet_address = [
        'AH',
        'AS',
        'BD',
        'BO',
        'BZ',
        'CK',
        'CV',
        'DG',
        'DR',
        'EC',
    ];

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
        $objPHPExcel->getSheet(0)->setCellValue('EH2', $Fregatsettings->getShortGlavvrachName());
        $objPHPExcel->getSheet(0)->setCellValue('DL11', '№ ' . $Spismat->spismat_id);
        $objPHPExcel->getSheet(0)->setCellValue('BC13', date('d', strtotime($Spismat->spismat_date)));
        $objPHPExcel->getSheet(0)->setCellValue('BJ13', Yii::$app->formatter->asDate(date('M', strtotime($Spismat->spismat_date)), 'php:F'));
        $objPHPExcel->getSheet(0)->setCellValue('CH13', date('y', strtotime($Spismat->spismat_date)));
        $objPHPExcel->getSheet(0)->setCellValue('L14', $Fregatsettings->fregatsettings_uchrezh_namesokr);
        $objPHPExcel->getSheet(0)->setCellValue('Y15', $Spismat->idMol->idpodraz->podraz_name);
        $objPHPExcel->getSheet(0)->setCellValue('AC16', $Spismat->idMol->idperson->auth_user_fullname);
        $objPHPExcel->getSheet(0)->setCellValue('AA27', $Fregatsettings->getShortGlavbuhName());
        $objPHPExcel->getSheet(0)->setCellValue('CP27', $Spismat->idMol->iddolzh->dolzh_name);
        $objPHPExcel->getSheet(0)->setCellValue('EF27', $Spismat->idMol->idperson->getShortName());

        $this->createSheet($objPHPExcel);

        $VedomostArr = Spismatmaterials::getVedomostArray($ID);

        

        file_put_contents('test.txt', print_r($VedomostArr, true));

        $i = 0;

        $sheetIndex = 1;
    /*    foreach ($VedomostArr['materials'] as $mattraffic_id => $material) {

            $objPHPExcel->getSheet($sheetIndex)->setCellValue($this->mat_sheet_address[$i] . '3', $material['material_name']);
            $objPHPExcel->getSheet($sheetIndex)->setCellValueExplicit($this->mat_sheet_address[$i] . '4', $material['material_inv'], \PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($sheetIndex)->setCellValue($this->mat_sheet_address[$i] . '5', $material['izmer_name']);
            $objPHPExcel->getSheet($sheetIndex)->setCellValue($this->mat_sheet_address[$i] . '25', $material['material_price']);

            $e = 9;
            foreach ($material['installers'] as $installer_id => $installer) {

//                if ($e === (self::INSTALLER_PER_SHEET + 9)) {
//                    $sheetIndex = $this->createSheet($objPHPExcel) - 1;
//                    $i = 0;
//                    $e = 9;
//                }

                if ($i === 0)
                    $objPHPExcel->getSheet($sheetIndex)->setCellValue('A' . $e, $this->getShortName($installer['auth_user_fullname']));


                if (!empty($installer['vsum']))
                    $objPHPExcel->getSheet($sheetIndex)->setCellValue($this->mat_sheet_address[$i] . $e, $installer['vsum']);
                $e++;
            }

            $i++;
        }*/
    }


    private function getShortName($value)
    {
        return preg_replace('/^(\w+)\s(\w)(\w+)?(\s(\w)(\w+)?)?/iu', '$1 $2. $5.', $value);
    }

    private function createSheet(PHPExcel $objPHPExcel)
    {
        $objPHPExcel_tmp = $objPHPExcel->getSheet($objPHPExcel->getSheetCount() - 1)->copy();
        $objPHPExcel_tmp->setTitle('Страница' . ($objPHPExcel->getSheetCount() + 1));
        $objPHPExcel->addSheet($objPHPExcel_tmp);
        return $objPHPExcel->getSheetCount() - 1;
    }

}