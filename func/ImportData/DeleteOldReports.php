<?php

namespace app\func\ImportData;

use app\models\Fregat\Import\Employeelog;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;
use app\models\Fregat\Import\Matlog;
use app\models\Fregat\Import\Traflog;
use Exception;
use Yii;

/**
 * Created by PhpStorm.
 * User: VOVANCHO
 * Date: 01.10.2016
 * Time: 17:15
 */
class DeleteOldReports
{
    /**
     * @var integer Максимальное количество отчетов импорта из 1С, настройка из базы данных.
     */
    private $_maxReportsFiles;

    /**
     * @var integer Максимальное количество отчетов импорта из 1С, количество хранящихся файлов отчетов на данный момент.
     */
    private $_countReportsFiles;

    /**
     * @var array Список ID отчетов, подлежащих удалению из базы данных и файловой системы.
     */
    private $_needDeleteReports = [];

    /**
     * DeleteOldReports constructor.
     */
    public function __construct()
    {
        if (!$this->setMaxReportsFiles())
            throw new Exception('Не удалось взять значение из БД');

        if (!$this->setCountReportsFiles())
            throw new Exception('Не удалось взять значение из файловой системы, папки importreports/');

        $this->setNeedDeleteReports();
    }

    /**
     * Сеттер для максимального количества отчетов импорта из 1С, настройка из базы данных.
     * @return bool True, если присвоение успешно.
     */
    private function setMaxReportsFiles()
    {
        $config = Importconfig::findOne(1);
        if (empty($config))
            return false;

        $this->_maxReportsFiles = $config->logreport_reportcount;
        return true;
    }

    /**
     * Сеттер для максимального количества отчетов импорта из 1С, количество хранящихся файлов отчетов на данный момент.
     * @return bool True, если присвоение успешно.
     */
    private function setCountReportsFiles()
    {
        $FilesSet = glob('importreports/*.xlsx');

        if ($FilesSet === false)
            return false;

        $this->_countReportsFiles = count($FilesSet);
        return true;
    }

    /**
     * Сеттер для списока ID отчетов, подлежащих удалению из базы данных и файловой системы.
     */
    private function setNeedDeleteReports()
    {
        if ($this->_countReportsFiles > $this->_maxReportsFiles)
            $this->_needDeleteReports = Logreport::find()
                ->select(['logreport_id'])
                ->orderBy(['logreport_id' => SORT_ASC])
                ->limit($this->_countReportsFiles - $this->_maxReportsFiles)
                ->asArray()
                ->all();
    }

    /**
     * Метод создает экземпляр класса.
     * @return DeleteOldReports
     */
    public static function Init()
    {
        return new DeleteOldReports();
    }

    /**
     * Выполняем удаление отчетов, если превышен лимит количества хранящихся отчетов.
     * @throws Exception
     */
    public function Execute()
    {
        foreach ($this->_needDeleteReports as $Row) {
            $Transaction = Yii::$app->db->beginTransaction();
            try {
                Traflog::deleteAll(['id_logreport' => $Row['logreport_id']]);
                Matlog::deleteAll(['id_logreport' => $Row['logreport_id']]);
                Employeelog::deleteAll(['id_logreport' => $Row['logreport_id']]);
                Logreport::findOne($Row['logreport_id'])->delete();

                $FileRoot = 'importreports/Отчет импорта в систему Фрегат N' . $Row['logreport_id'] . '.xlsx';

                if (DIRECTORY_SEPARATOR !== '/')
                    $FileRoot = mb_convert_encoding($FileRoot, 'Windows-1251', 'UTF-8');

                unlink($FileRoot);

                $Transaction->commit();
            } catch (Exception $e) {
                $Transaction->rollBack();
                throw new Exception($e->getMessage() . ' logreport_id = ' . $Row['logreport_id']);
            }
        }
    }

}