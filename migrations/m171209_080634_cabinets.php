<?php

use yii\db\Migration;

class m171209_080634_cabinets extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%cabinet}}', [
            'cabinet_id' => $this->primaryKey(),
            'id_build' => $this->boolean()->unsigned(),
            'cabinet_name' => $this->string(),
        ]);

        $this->addForeignKey('cabinet_id_build', '{{%cabinet}}', 'id_build', '{{%build}}', 'build_id', 'CASCADE');
        $this->createIndex('idx_cabinet', '{{%cabinet}}', ['id_build', 'cabinet_name'], true);

        $this->addColumn('{{%tr_osnov}}', 'id_cabinet', $this->integer()->notNull());

        $this->execute(<<<EOT
            INSERT INTO cabinet (id_build, cabinet_name)
            (
                SELECT id_build, UPPER(tr_osnov_kab)
                FROM tr_osnov
                LEFT JOIN mattraffic ON mattraffic.mattraffic_id = tr_osnov.id_mattraffic
                LEFT JOIN employee ON employee.employee_id = mattraffic.id_mol
                GROUP BY id_build,  tr_osnov_kab
            );

            UPDATE tr_osnov a1
            LEFT JOIN mattraffic ON mattraffic.mattraffic_id = a1.id_mattraffic
            LEFT JOIN employee ON employee.employee_id = mattraffic.id_mol
            LEFT JOIN cabinet ON cabinet.id_build = employee.id_build AND cabinet.cabinet_name = a1.tr_osnov_kab
            SET a1.id_cabinet = cabinet.cabinet_id
            WHERE
            cabinet.id_build = employee.id_build
            AND cabinet.cabinet_name = a1.tr_osnov_kab;
EOT
        );

        $this->addForeignKey('tr_osnov_id_cabinet', '{{%tr_osnov}}', 'id_cabinet', '{{%cabinet}}', 'cabinet_id');
        $this->dropColumn('{{%tr_osnov}}', 'tr_osnov_kab');

        /* Удаление дублей */

        /**
         * 'ПРИСТРОЙКА 3 ЭТАЖ, НАПРОТИВ ЛИФТА' +
         * 'ПРИСТРОЙКА 3 ЭТ НАПРОТИВ ЛИФТА' -
         */
        $this->execute(<<<EOT
            UPDATE tr_osnov a1
            INNER JOIN cabinet ON cabinet.cabinet_id = a1.id_cabinet
            LEFT JOIN (
                SELECT cabinet_id
                FROM cabinet
                WHERE cabinet_name = 'ПРИСТРОЙКА 3 ЭТАЖ, НАПРОТИВ ЛИФТА'
                ) c2 ON 1 = 1
            SET a1.id_cabinet = c2.cabinet_id
            WHERE cabinet.cabinet_name = 'ПРИСТРОЙКА 3 ЭТ НАПРОТИВ ЛИФТА';
EOT
        );
        $this->delete('{{%cabinet}}', ['cabinet_name' => 'ПРИСТРОЙКА 3 ЭТ НАПРОТИВ ЛИФТА']);

        /**
         * 'СЕРВЕРНАЯ' +
         * 'СЕРЕРНАЯ' -
         */
        $this->execute(<<<EOT
            UPDATE tr_osnov a1
            INNER JOIN cabinet ON cabinet.cabinet_id = a1.id_cabinet
            LEFT JOIN (
                SELECT cabinet_id
                FROM cabinet
                WHERE cabinet_name = 'СЕРВЕРНАЯ'
                ) c2 ON 1 = 1
            SET a1.id_cabinet = c2.cabinet_id
            WHERE cabinet.cabinet_name = 'СЕРЕРНАЯ';
EOT
        );
        $this->delete('{{%cabinet}}', ['cabinet_name' => 'СЕРЕРНАЯ']);

        /**
         * 'РАСЧЕТНЫЙ ОТДЕЛ' +
         * 'РАСЧЕТНЫЙ' -
         */
        $this->execute(<<<EOT
            UPDATE tr_osnov a1
            INNER JOIN cabinet ON cabinet.cabinet_id = a1.id_cabinet
            LEFT JOIN (
                SELECT cabinet_id
                FROM cabinet
                WHERE cabinet_name = 'РАСЧЕТНЫЙ ОТДЕЛ'
                ) c2 ON 1 = 1
            SET a1.id_cabinet = c2.cabinet_id
            WHERE cabinet.cabinet_name = 'РАСЧЕТНЫЙ';
EOT
        );
        $this->delete('{{%cabinet}}', ['cabinet_name' => 'РАСЧЕТНЫЙ']);

        /**
         * 'СЕРВЕРНАЯ' +
         * 'СЕРВЕРНЫЙ КАБИНЕТ' -
         */
        $this->execute(<<<EOT
            UPDATE tr_osnov a1
            INNER JOIN cabinet ON cabinet.cabinet_id = a1.id_cabinet
            LEFT JOIN (
                SELECT cabinet_id
                FROM cabinet
                WHERE cabinet_name = 'СЕРВЕРНАЯ'
                ) c2 ON 1 = 1
            SET a1.id_cabinet = c2.cabinet_id
            WHERE cabinet.cabinet_name = 'СЕРВЕРНЫЙ КАБИНЕТ';
EOT
        );
        $this->delete('{{%cabinet}}', ['cabinet_name' => 'СЕРВЕРНЫЙ КАБИНЕТ']);

        /**
         * 'СКЛАД' +
         * 'СКЛАД С/Х' -
         */
        $this->execute(<<<EOT
            UPDATE tr_osnov a1
            INNER JOIN cabinet ON cabinet.cabinet_id = a1.id_cabinet
            LEFT JOIN (
                SELECT cabinet_id
                FROM cabinet
                WHERE cabinet_name = 'СКЛАД'
                ) c2 ON 1 = 1
            SET a1.id_cabinet = c2.cabinet_id
            WHERE cabinet.cabinet_name = 'СКЛАД С/Х';
EOT
        );
        $this->delete('{{%cabinet}}', ['cabinet_name' => 'СКЛАД С/Х']);

        /**
         * 'ДИСПЕЧЕРСКАЯ' +
         * 'ДИСПЕЧЕР' -
         */
        $this->execute(<<<EOT
            UPDATE tr_osnov a1
            INNER JOIN cabinet ON cabinet.cabinet_id = a1.id_cabinet
            LEFT JOIN (
                SELECT cabinet_id
                FROM cabinet
                WHERE cabinet_name = 'ДИСПЕЧЕРСКАЯ'
                ) c2 ON 1 = 1
            SET a1.id_cabinet = c2.cabinet_id
            WHERE cabinet.cabinet_name = 'ДИСПЕЧЕР';
EOT
        );
        $this->delete('{{%cabinet}}', ['cabinet_name' => 'ДИСПЕЧЕР']);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%tr_osnov}}', 'id_cabinet');
        $this->dropTable('{{%cabinet}}');
        $this->addColumn('{{%tr_osnov}}', 'tr_osnov_kab', $this->string()->notNull());
    }
}
