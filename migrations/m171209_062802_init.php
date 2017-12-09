<?php

use yii\db\Migration;

class m171209_062802_init extends Migration
{
    public function up()
    {
        $this->execute(file_get_contents(__DIR__ . '/dump/init.sql'));
    }

    public function down()
    {
        Yii::$app->db->createCommand("SET foreign_key_checks = 0")->execute();
        $tables = Yii::$app->db->schema->getTableNames();
        foreach ($tables as $table) {
            if ($table !== 'migration') {
                Yii::$app->db->createCommand("DROP TABLE IF EXISTS `$table`;")->execute();
            }
        }
        Yii::$app->db->createCommand("SET foreign_key_checks = 1")->execute();
    }
}