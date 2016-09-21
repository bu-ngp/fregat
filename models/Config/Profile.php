<?php

namespace app\models\Config;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $profile_id
 * @property string $profile_inn
 * @property string $profile_dr
 * @property integer $profile_pol
 * @property string $profile_address
 * @property string $profile_snils
 *
 * @property AuthUser $authUser
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id'], 'required'],
            [['profile_pol'], 'filter', 'filter' => function ($value) {
                return $value === 'М' ? 1 : ($value === 'Ж' ? 2 : NULL);
            }],
            [['profile_dr'], 'filter', 'filter' => function ($value) {
                return substr($value, 6, 4) . '-' . substr($value, 3, 2) . '-' . substr($value, 0, 2);
            }],
            [['profile_inn'], 'filter', 'filter' => function ($value) {
                return substr($value, 0, 1) === 'I' ? substr($value, 1) : $value;
            }],
            [['profile_snils'], 'filter', 'filter' => function ($value) {
                return preg_replace('/[\s-]/u', '', $value);
            }],
            [['profile_id', 'profile_pol'], 'integer'],
            [['profile_dr'], 'date', 'format' => 'yyyy-MM-dd'],
            [['profile_inn'], 'string', 'max' => 12],
            [['profile_address'], 'string', 'max' => 400],
            [['profile_snils'], 'string', 'max' => 11],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'profile_inn' => 'ИНН',
            'profile_dr' => 'Дата рождения',
            'profile_pol' => 'Пол',
            'profile_address' => 'Адрес',
            'profile_snils' => 'СНИЛС',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthUser()
    {
        return $this->hasOne(AuthUser::className(), ['auth_user_id' => 'profile_id']);
    }
}
