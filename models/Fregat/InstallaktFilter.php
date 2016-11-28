<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class InstallaktFilter extends Model
{
    public $mat_id_material;
    public $mol_id_person;
    public $tr_osnov_mol_id_build;
    public $tr_osnov_mol_id_build_not;
    public $mat_id_material_trmat;
    public $mol_id_person_trmat;
    public $tr_osnov_kab;
    public $id_parent;

    public function rules()
    {
        return [
            [[
                'mat_id_material',
                'mol_id_person',
                'tr_osnov_mol_id_build',
                'tr_osnov_mol_id_build_not',
                'mat_id_material_trmat',
                'mol_id_person_trmat',
                'id_parent',
            ], 'integer'],
            ['tr_osnov_kab', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mat_id_material' => 'Материальная ценность',
            'mol_id_person' => 'Материально-ответственное лицо',
            'tr_osnov_mol_id_build' => 'Здание',
            'mat_id_material_trmat' => 'Материальная ценность',
            'mol_id_person_trmat' => 'Материально-ответственное лицо',
            'tr_osnov_kab' => 'Кабинет',
            'id_parent' => 'Комплектуемая материальная ценность',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'mat_id_material' => [$value => Material::getMaterialByID($value)],
            'mol_id_person' => [$value => Authuser::getAuthuserByID($value)],
            'tr_osnov_mol_id_build' => [$value => Build::getBuildByID($value)],
            'mat_id_material_trmat' => [$value => Material::getMaterialByID($value)],
            'mol_id_person_trmat' => [$value => Authuser::getAuthuserByID($value)],
            'id_parent' => [$value => Material::getMaterialByID($value)],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
