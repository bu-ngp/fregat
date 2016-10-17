<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class RecoverysendaktFilter extends Model
{
    public $recoverysendakt_closed_mark;
    public $recoverysendakt_opened_mark;
    public $recoveryrecieveakt_repaired;
    public $mat_id_material;
    public $mol_id_person;
    public $mat_id_material_mat;
    public $mol_id_person_mat;

    public function rules()
    {
        return [
            [[
                'recoverysendakt_closed_mark',
                'recoverysendakt_opened_mark',
                'recoveryrecieveakt_repaired',
                'mat_id_material',
                'mol_id_person',
                'mat_id_material_mat',
                'mol_id_person_mat',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mat_id_material' => 'Материальная ценность',
            'recoverysendakt_closed_mark' => 'Закрытые акты',
            'recoverysendakt_opened_mark' => 'Открытые акты',
            'recoveryrecieveakt_repaired' => 'Подлежит восстановлению',
            'mol_id_person' => 'Материально-ответственное лицо',
            'mat_id_material_mat' => 'Материал',
            'mol_id_person_mat' => 'Материально-ответственное лицо',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'mat_id_material' => [$value => Material::getMaterialByID($value)],
            'mol_id_person' => [$value => Authuser::getAuthuserByID($value)],
            'mat_id_material_mat' => [$value => Material::getMaterialByID($value)],
            'mol_id_person_mat' => [$value => Authuser::getAuthuserByID($value)],
            'recoveryrecieveakt_repaired' => Recoveryrecieveakt::VariablesValues($attribute)
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
