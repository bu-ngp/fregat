<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 28.10.2016
 * Time: 8:25
 */

namespace app\func\ImportData\Exec;


use app\func\ImportData\Proc\ExcelParseFactory;

class OsnovParseFactory extends ExcelParseFactory
{

    public function create()
    {
        if (!$this->instanceAssign())
            throw new \Exception('Материальная ценность не инициализирована');

        $MaterialObj = new OsnovParseObject();

        $MaterialObj->mattraffic_date = $this->getImportconfig()->os_mattraffic_date;
        $MaterialObj->material_1c = $this->getImportconfig()->os_material_1c;
        $MaterialObj->material_inv = $this->getImportconfig()->os_material_inv;
        $MaterialObj->material_name1c = $this->getImportconfig()->os_material_name1c;
        $MaterialObj->material_price = $this->getImportconfig()->os_material_price;
        $MaterialObj->material_serial = $this->getImportconfig()->os_material_serial;
        $MaterialObj->material_release = $this->getImportconfig()->os_material_release;
        $MaterialObj->material_status = $this->getImportconfig()->os_material_status;
        $MaterialObj->schetuchet_kod = $this->getImportconfig()->os_material_schetuchet_kod;
        $MaterialObj->schetuchet_name = $this->getImportconfig()->os_material_schetuchet_name;

        return $MaterialObj;
    }
}