<?php

namespace app\models\Config;

use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Podraz;
use Yii;
use yii\base\Model;

class AuthuserFilter extends Model
{
    public $id_dolzh;
    public $id_dolzh_not;
    public $id_podraz;
    public $id_podraz_not;
    public $id_build;
    public $id_build_not;

    public function rules()
    {
        return [
            [[
                'id_dolzh',
                'id_dolzh_not',
                'id_podraz',
                'id_podraz_not',
                'id_build',
                'id_build_not',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_dolzh' => 'Должность',
            'id_podraz' => 'Подразделение',
            'id_build' => 'Здание',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'id_dolzh' => [$value => Dolzh::getDolzhByID($value)],
            'id_podraz' => [$value => Podraz::getPodrazByID($value)],
            'id_build' => [$value => Build::getBuildByID($value)],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
