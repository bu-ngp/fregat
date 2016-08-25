<?php

namespace app\models\Glauk;

use Yii;
use app\models\Base\Preparat;

/**
 * This is the model class for table "glprep".
 *
 * @property integer $glprep_id
 * @property string $id_glaukuchet
 * @property integer $id_preparat
 *
 * @property Glaukuchet $idGlaukuchet
 * @property Preparat $idPreparat
 */
class Glprep extends \yii\db\ActiveRecord {

    public $glaukuchet_preparats; // Строка со списком препаратов назначенных пациенту

    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'glprep';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_glaukuchet', 'id_preparat'], 'required'],
            [['id_glaukuchet', 'id_preparat', 'glprep_rlocat'], 'integer'],
            [['id_glaukuchet'], 'exist', 'skipOnError' => true, 'targetClass' => Glaukuchet::className(), 'targetAttribute' => ['id_glaukuchet' => 'glaukuchet_id']],
            [['id_preparat'], 'exist', 'skipOnError' => true, 'targetClass' => Preparat::className(), 'targetAttribute' => ['id_preparat' => 'preparat_id']],
            [['glaukuchet_preparats'], 'safe'],
            ['id_preparat', 'UniqueGlaukPreparat'], // Проверяет на уникальность список препаратов, включая проверку на NULL
        ];
    }

    // Проверяет на уникальность список препаратов, включая проверку на NULL
    public function UniqueGlaukPreparat($attribute, $params) {
        if (is_string($attribute)) {
            $query = self::find()
                    ->andWhere(['id_glaukuchet' => $this->id_glaukuchet, 'id_preparat' => $this->id_preparat, 'glprep_rlocat' => empty($this->glprep_rlocat) ? NULL : $this->glprep_rlocat])
                    ->count();

            if ($query > 0)
                $this->addError($attribute, 'Этот препарат с этой категорией льготного лекарственного обеспечения уже есть у глаукомного пациента');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'glprep_id' => 'Glprep ID',
            'id_glaukuchet' => 'Карта глаукомного больного',
            'id_preparat' => 'Препарат',
            'glprep_rlocat' => 'Категория льготного лекарственного обеспечения',
            'glaukuchet_preparats' => 'Потребность в медикаментозной терапии',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGlaukuchet() {
        return $this->hasOne(Glaukuchet::className(), ['glaukuchet_id' => 'id_glaukuchet'])->from(['idGlaukuchet' => Glaukuchet::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPreparat() {
        return $this->hasOne(Preparat::className(), ['preparat_id' => 'id_preparat'])->from(['idPreparat' => Preparat::tableName()]);
    }

    public static function VariablesValues($attribute, $value = NULL) {
        $values = [
            'glprep_rlocat' => [1 => 'Федеральная', 2 => 'Региональная'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

    public function save($runValidation = true, $attributeNames = null) {
        $result = parent::save($runValidation, $attributeNames);

        if ($result) // При изменении препаратов, менять пользователя и дату изменения в карте глаукомного пациента
            Glaukuchet::findOne($this->id_glaukuchet)->UpdateChangeAttributes();

        return $result;
    }

    public function delete() {
        $result = parent::delete();

        if ($result)  // При изменении препаратов, менять пользователя и дату изменения в карте глаукомного пациента
            Glaukuchet::findOne($this->id_glaukuchet)->UpdateChangeAttributes();

        return $result;
    }

}
