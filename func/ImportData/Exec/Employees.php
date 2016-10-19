<?php

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 16:28
 */

namespace app\func\ImportData\Exec;

use app\func\ImportData\Proc\ImportFromTextFile;

class Employees extends ImportFromTextFile
{
    const Pattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';

    protected function getItem()
    {
        // TODO: Implement getItem() method.
    }

    protected function afterIterateItem()
    {
        // TODO: Implement afterIterateItem() method.
    }

    protected function afterIterateAll()
    {
        // TODO: Implement afterIterateAll() method.
    }

    protected function ProcessItem($String)
    {
        preg_match(self::Pattern, $String, $Matches);

        if ($Matches[0] !== NULL) {
            $Pattern = '/(^Поликлиника №)\s?([1,2,3])\s?$/ui';
            $Matches[2] = preg_replace($Pattern, 'Взрослая $1$2', mb_strtolower($Matches[2], 'UTF-8'));

            if ($Matches[3] === 'Поликлиника профилактических осмотров')
                $Matches[2] = $Matches[3];

            $Pattern = '/^(.+) БУ "Нижневартовская городская поликлиника"$/ui';
            $Matches[3] = preg_replace($Pattern, '$1', $Matches[3]);

            $employee_fio = $Matches[1];
            // $location = self::AssignLocationForEmployeeImport(trim($Matches[3]), trim($Matches[2]));

            // $id_dolzh = self::AssignDolzh(trim($Matches[4]));

        }
    }
}