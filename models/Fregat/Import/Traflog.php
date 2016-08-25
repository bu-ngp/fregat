<?php

namespace app\models\Fregat\Import;

use Yii;

/**
 * This is the model class for table "traflog".
 *
 * @property string $traflog_id
 * @property string $id_logreport
 * @property string $traflog_filename
 * @property integer $traflog_rownum
 * @property integer $traflog_type
 * @property string $traflog_message
 * @property string $mattraffic_number
 * @property string $id_matlog
 * @property string $id_employeelog
 *
 * @property Employeelog $idEmployeelog
 * @property Logreport $idLogreport
 * @property Matlog $idMatlog
 */
class Traflog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'traflog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_logreport', 'traflog_filename', 'traflog_rownum', 'traflog_type', 'traflog_message', 'id_matlog', 'id_employeelog'], 'required'],
            [['id_logreport', 'traflog_rownum', 'traflog_type', 'id_matlog', 'id_employeelog'], 'integer'],
            [['mattraffic_number'], 'number'],
            [['traflog_filename'], 'string', 'max' => 255],
            [['traflog_message'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'traflog_id' => 'Traflog ID',
            'id_logreport' => 'Id Logreport',
            'traflog_filename' => 'Имя файла',
            'traflog_rownum' => 'Номер строки',
            'traflog_type' => 'Тип сообщения',
            'traflog_message' => 'Сообщение',
            'mattraffic_number' => 'Количество (Задействованное в операции)',
            'id_matlog' => 'Id Matlog',
            'id_employeelog' => 'Id Employeelog',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdemployeelog()
    {
        return $this->hasOne(Employeelog::className(), ['employeelog_id' => 'id_employeelog'])->from(['idemployeelog' => Employeelog::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdlogreport()
    {
        return $this->hasOne(Logreport::className(), ['logreport_id' => 'id_logreport'])->from(['idlogreport' => Logreport::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdmatlog()
    {
        return $this->hasOne(Matlog::className(), ['matlog_id' => 'id_matlog'])->from(['idmatlog' => Matlog::tableName()]);
    }
}
