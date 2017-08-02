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
    public $authuser_active_mark;
    public $authuser_inactive_mark;
    public $employee_null_mark;

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
            [[
                'authuser_active_mark',
                'authuser_inactive_mark',
                'employee_null_mark',
            ],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_dolzh' => 'Должность',
            'id_podraz' => 'Подразделение',
            'id_build' => 'Здание',
            'authuser_active_mark' => 'Имеются активные специальности',
            'authuser_inactive_mark' => 'Отсутствуют активные специальности',
            'employee_null_mark' => 'У пользователя отсутствуют специальности',
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
