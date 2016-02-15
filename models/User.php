<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Security;

/**
 * This is the model class for table "mjc_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * @property MjcRegistryitem[] $mjcRegistryitems
 * @property MjcRegistrylist[] $mjcRegistrylists
 */
class User extends ActiveRecord implements IdentityInterface {

    public $auth_key;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['auth_user_fullname', 'auth_user_login', 'auth_user_password'], 'required'],
            [['auth_user_fullname', 'auth_user_login'], 'string', 'max' => 128],
            [['auth_user_password'], 'string', 'max' => 255],
            [['auth_user_login'], 'unique'],
            [['auth_user_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'auth_user_id' => 'ID',
            'auth_user_fullname' => 'Имя пользователя',
            'auth_user_login' => 'Логин',
            'auth_user_password' => 'Пароль',
        ];
    }

    // Interface methods

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS* */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['auth_user_login' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->auth_user_password === $password; //sha1($password);
//        return Security::validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = $password; //Security::generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

}
