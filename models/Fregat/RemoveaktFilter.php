<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class RemoveaktFilter extends Model
{
    public $mat_id_material;
    public $mol_id_person;
    public $id_parent;

    public function rules()
    {
        return [
            [[
                'mat_id_material',
                'mol_id_person',
                'id_parent',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mat_id_material' => 'Снятая материальная ценность',
            'mol_id_person' => 'Материально-ответственное лицо снятой материальной ценности',
            'id_parent' => 'Материальная ценность, с которой снято',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'mat_id_material' => [$value => Material::getMaterialByID($value)],
            'mol_id_person' => [$value => Authuser::getAuthuserByID($value)],
            'id_parent' => [$value => Material::getMaterialByID($value)],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
