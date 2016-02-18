<?php

namespace app\models\Config;

use Yii;

/**
 * This is the model class for table "auth_user".
 *
 * @property integer $auth_user_id
 * @property string $auth_user_fullname
 * @property string $auth_user_login
 * @property string $auth_user_password
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 */
class Authuser extends \yii\db\ActiveRecord
{
    public $auth_user_password2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_user_fullname', 'auth_user_login'], 'required'],
            [['auth_user_password', 'auth_user_password2'], 'required', 'on' => 'Newuser'],
            [['auth_user_fullname', 'auth_user_login'], 'string', 'max' => 128],
            [['auth_user_password'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auth_user_id' => 'Код',
            'auth_user_fullname' => 'Фамилия Имя Отчество',
            'auth_user_login' => 'Логин',
            'auth_user_password' => 'Пароль',
            'auth_user_password2' => 'Подтвердите пароль',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'auth_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'auth_user_id']);
    }
}
