<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 21.10.2016
 * Time: 8:34
 */

namespace app\func\ImportData\Exec;

use app\func\ImportData\Proc\DataFilter;

/**
 * Class DolzhFilter
 * @package app\func\ImportData\Exec
 */
class DolzhFilter extends DataFilter
{
    /**
     * @param string $Value
     * @return string
     */
    public function dolzhConvert($Value)
    {
        switch ($Value) {
            case 'мед.сестра':
                return 'Медицинская сестра';
            case 'Медицинская сестра *регистратуры*':
                return 'Медицинская сестра [регистратуры]';
            case 'Ст. медсестра поликлиники':
                return 'Старшая медицинская сестра поликлиники';
            case 'Ст.мед.сестра':
                return 'Старшая медицинская сестра';
            case 'старшая мед. сестра':
                return 'Старшая медицинская сестра';
            case 'Старшая медицинская сестра *регистратуры*':
                return 'Старшая медицинская сестра [регистратуры]';
            case 'Уборщик производственных и служебных  помещений':
                return 'Уборщик производственных и служебных помещений';
            case 'Заведующий фельдшерским здравпунктом-фельдшер':
                return 'Фельдшер';
            case 'Медицинская сестра кабинета (инфекционных заболеваний)':
                return 'Медицинская сестра (кабинета инфекционных заболеваний)';
            default:
                return $Value;
        }
    }

    /**
     * @param string $Value
     * @return string
     */
    public function beforeProcess($Value)
    {
        return $this->dolzhConvert($Value);
    }
}