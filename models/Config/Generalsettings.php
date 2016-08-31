<?php

namespace app\models\Config;

use Yii;

/**
 * This is the model class for table "generalsettings".
 *
 * @property integer $generalsettings_id
 * @property string $ofoms_host
 * @property integer $ofoms_port
 * @property string $ofoms_login
 * @property string $ofoms_password
 * @property string $ofoms_remotehost
 * @property string $version_db
 * @property string $version_base
 * @property string $version_fregat
 * @property string $version_portalofoms
 * @property string $mailer_host
 * @property integer $mailer_smtpport
 * @property string $mailer_login
 * @property string $mailer_password
 */
class Generalsettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'generalsettings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ofoms_port', 'mailer_smtpport'], 'integer'],
            [['ofoms_host', 'ofoms_login', 'ofoms_password', 'ofoms_remotehost', 'mailer_host', 'mailer_login', 'mailer_password'], 'string', 'max' => 255],
            [['version_db', 'version_base', 'version_fregat', 'version_portalofoms'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'generalsettings_id' => 'Generalsettings ID',
            'ofoms_host' => 'Ofoms Host',
            'ofoms_port' => 'Ofoms Port',
            'ofoms_login' => 'Ofoms Login',
            'ofoms_password' => 'Ofoms Password',
            'ofoms_remotehost' => 'Ofoms Remotehost',
            'version_db' => 'Version Db',
            'version_base' => 'Version Base',
            'version_fregat' => 'Version Fregat',
            'version_portalofoms' => 'Version Portalofoms',
            'mailer_host' => 'IP почтового сервера',
            'mailer_smtpport' => 'Порт SMTP',
            'mailer_login' => 'Логин',
            'mailer_password' => 'Пароль',
        ];
    }
}
