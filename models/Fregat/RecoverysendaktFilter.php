<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;

class RecoverysendaktFilter extends Model
{
    public $recoverysendakt_closed_mark;
    public $mat_id_material;

    public function rules()
    {
        return [
            [['recoverysendakt_closed_mark','mat_id_material'],'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mat_id_material' => 'Материальная ценность',
            'recoverysendakt_closed_mark' => 'Закрытые акты',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'mat_id_material' => [$value => Material::getMaterialByID($value)]
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
