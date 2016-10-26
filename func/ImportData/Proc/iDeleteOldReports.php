<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 14:27
 */

namespace app\func\ImportData\Proc;


use Exception;

/**
 * Interface iDeleteOldReports
 * @package app\func\ImportData\Proc
 */
interface iDeleteOldReports
{
    /**
     * @return int
     */
    public function getMaxReportsFiles();

    /**
     * @return int
     */
    public function getCountReportsFiles();

    /**
     * @return array
     */
    public function getNeedDeleteReports();

    /**
     * Метод создает экземпляр класса.
     * @return static
     */
    public static function Init();

    /**
     * Выполняем удаление отчетов, если превышен лимит количества хранящихся отчетов.
     * @throws Exception
     */
    public function Execute();
}