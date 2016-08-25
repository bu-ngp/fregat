<?php

namespace app\models\Fregat\Import;

use Yii;

/**
 * This is the model class for table "matlog".
 *
 * @property string $matlog_id
 * @property string $id_logreport
 * @property string $matlog_filename
 * @property string $matlog_filelastdate
 * @property string $matlog_rownum
 * @property integer $matlog_type
 * @property string $matlog_message
 * @property string $material_name1c
 * @property string $material_1c
 * @property string $material_inv
 * @property string $material_serial
 * @property string $material_release
 * @property string $material_number
 * @property string $material_price
 * @property string $material_tip
 * @property string $material_writeoff
 * @property string $izmer_name
 * @property string $matvid_name
 *
 * @property Logreport $idLogreport
 * @property Traflog[] $traflogs
 */
class Matlog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'matlog';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_logreport', 'matlog_filename', 'matlog_rownum', 'matlog_type', 'matlog_message', 'matlog_filelastdate'], 'required'],
            [['id_logreport', 'matlog_rownum', 'matlog_type'], 'integer'],
            [['matlog_filename', 'material_1c', 'material_inv', 'material_serial', 'material_release', 'material_number', 'material_price', 'material_tip', 'material_writeoff', 'izmer_name', 'matvid_name'], 'string', 'max' => 255],
            [['matlog_message'], 'string', 'max' => 1000],
            [['material_name1c'], 'string', 'max' => 400]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'matlog_id' => 'Matlog ID',
            'id_logreport' => 'Id Logreport',
            'matlog_filename' => 'Имя файла',
            'matlog_filelastdate' => 'Дата изменения файла',
            'matlog_rownum' => 'Номер строки',
            'matlog_type' => 'Тип сообщения',
            'matlog_message' => 'Сообщение',
            'material_name1c' => 'Наименование (Из 1С)',
            'material_1c' => 'Код 1С',
            'material_inv' => 'Инвентарный номер',
            'material_serial' => 'Серийный номер',
            'material_release' => 'Дата выпуска',
            'material_number' => 'Количество',
            'material_price' => 'Цена',
            'material_tip' => 'Тип',
            'material_writeoff' => 'Статус списания',
            'izmer_name' => 'Единица измерения',
            'matvid_name' => 'Вид',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdlogreport() {
        return $this->hasOne(Logreport::className(), ['logreport_id' => 'id_logreport'])->from(['idlogreport' => Logreport::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraflogs() {
        return $this->hasMany(Traflog::className(), ['id_matlog' => 'matlog_id'])->from(['traflogs' => Traflog::tableName()]);
    }

}
