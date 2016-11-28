<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class OsmotraktmatFilter extends Model
{
    public $mat_id_material;
    public $mol_id_person;
    public $id_parent;
    public $installakt_id_znak;
    public $installakt_id;
    public $reason_text;
    public $reason_text_not;
    public $tr_mat_osmotr_comment;

    public function rules()
    {
        return [
            [[
                'mat_id_material',
                'mol_id_person',
                'id_parent',
            ], 'integer'],
            [[
                'installakt_id',
                'installakt_id_znak',
                'reason_text',
                'reason_text_not',
                'tr_mat_osmotr_comment',
            ], 'safe']

        ];
    }

    public function attributeLabels()
    {
        return [
            'mat_id_material' => 'Материальная ценность',
            'mol_id_person' => 'Материально-ответственное лицо',
            'id_parent' => 'Укомплектовано в материальную ценность',
            'installakt_id' => '№ акта установки',
            'reason_text' => 'Причина поломки',
            'tr_mat_osmotr_comment' => 'Описание причины неисправности',
        ];
    }

    public function __construct($config = array())
    {
        $config['installakt_id_znak'] = '=';
        parent::__construct($config);
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'mat_id_material' => [$value => Material::getMaterialByID($value)],
            'mol_id_person' => [$value => Authuser::getAuthuserByID($value)],
            'id_parent' => [$value => Material::getMaterialByID($value)],
            'reason_text' => [$value => Reason::getReasonByID($value)],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
