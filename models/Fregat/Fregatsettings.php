<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "fregatsettings".
 *
 * @property integer $fregatsettings_id
 * @property string $fregatsettings_recoverysend_emailtheme
 * @property string $fregatsettings_recoverysend_emailfrom
 */
class Fregatsettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fregatsettings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fregatsettings_recoverysend_emailtheme'], 'string', 'max' => 255],
            [['fregatsettings_recoverysend_emailfrom'],'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fregatsettings_id' => 'Fregatsettings ID',
            'fregatsettings_recoverysend_emailtheme' => 'Тема электронного письма',
            'fregatsettings_recoverysend_emailfrom' => 'Электронная почта, от которой отправляется письмо',
        ];
    }
}
