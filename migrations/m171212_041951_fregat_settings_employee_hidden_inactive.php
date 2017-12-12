<?php

use yii\db\Migration;

class m171212_041951_fregat_settings_employee_hidden_inactive extends Migration
{
    public function up()
    {
        $this->addColumn('{{%fregatsettings}}', 'fregatsettings_employee_inactive_hidden', $this->boolean());
        $this->insert('{{%fregatsettings}}', ['fregatsettings_employee_inactive_hidden' => 1]);
    }

    public function down()
    {
        $this->dropColumn('{{%fregatsettings}}', 'fregatsettings_employee_inactive_hidden');
    }
}