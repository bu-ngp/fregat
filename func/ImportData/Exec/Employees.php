<?php

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 16:28
 */

namespace app\func\ImportData\Exec;

use app\func\ImportData\Proc\DataFilter;
use app\func\ImportData\Proc\ImportFromTextFile;
use app\models\Fregat\Employee;
use Exception;

class Employees extends ImportFromTextFile
{
    const Pattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';

    private $_employeeFio;
    private $_filterDolzh;
    private $_filterPodraz;
    private $_filterBuild;
    private $_EmployeeAR;

    /**
     * @return mixed
     */
    public function getFilterPodraz()
    {
        return $this->_filterPodraz;
    }

    /**
     * @param mixed $filterPodraz
     */
    public function setFilterPodraz(DataFilter $filterPodraz)
    {
        $this->_filterPodraz = $filterPodraz;
    }

    /**
     * @return mixed
     */
    public function getFilterDolzh()
    {
        return $this->_filterDolzh;
    }

    /**
     * @param mixed $filterDolzh
     */
    public function setFilterDolzh(DataFilter $filterDolzh)
    {
        $this->_filterDolzh = $filterDolzh;
    }

    /**
     * @return mixed
     */
    public function getFilterBuild()
    {
        return $this->_filterBuild;
    }

    /**
     * @param mixed $filterBuild
     */
    public function setFilterBuild(DataFilter $filterBuild)
    {
        $this->_filterBuild = $filterBuild;
    }

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

    private function getEmployee()
    {
        $Result = Employee::find()
            ->joinWith('idperson')
            ->where(array_merge([
                'id_dolzh' => $this->getFilterDolzh()->getID(),
                'id_podraz' => $this->getFilterPodraz()->getID(),
            ], empty($this->getFilterBuild()->getID()) ? [] : ['id_build' => $this->getFilterBuild()->getID()]))
            ->andFilterWhere(['like', 'auth_user_fullname', $this->_employeeFio, false])
            ->one();

        if (empty($Result))
            return false;
        else
            $this->_EmployeeAR = $Result;

        return true;
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

            $this->_employeeFio = $Matches[1];

            if (empty($this->_filterDolzh))
                throw new Exception('Не установлен фильтр filterDolzh');
            if (empty($this->_filterPodraz))
                throw new Exception('Не установлен фильтр filterPodraz');
            if (empty($this->_filterBuild))
                throw new Exception('Не установлен фильтр filterBuild');

            $this->_filterDolzh->installValue(trim($Matches[4]));
            $this->_filterPodraz->installValue(trim($Matches[3]));
            $this->_filterBuild->installValue(trim($Matches[2]));

            if ($this->getEmployee()) {

            } else {

            }

        }
    }
}