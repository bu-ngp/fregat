<?php

namespace app\models\Fregat;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the model class for table "material".
 *
 * @property string $material_id
 * @property string $material_name
 * @property string $material_name1c
 * @property string $material_1c
 * @property string $material_inv
 * @property string $material_serial
 * @property string $material_release
 * @property string $material_number
 * @property string $material_price
 * @property integer $material_tip
 * @property integer $material_writeoff
 * @property integer $id_matvid
 * @property integer $id_izmer
 * @property integer $id_schetuchet
 * @property string $material_comment
 *
 * @property Izmer $idIzmer
 * @property Matvid $idMatv
 * @property Schetuchet $idSchetuchet
 * @property MaterialDocfiles[] $materialDocfiles
 * @property Mattraffic[] $mattraffics
 *
 */
class Material extends \yii\db\ActiveRecord
{
    const OSNOV = 1;
    const MATERIAL = 2;
    const GROUP_UCHET = 3;
    const OSNOV_R = 4;
    const MATERIAL_R = 5;
    const V_KOMPLEKTE = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['material_username'], 'filter', 'filter' => function ($value) {
                return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
            }],
            [['material_username'], 'filter', 'filter' => function ($value) {
                return 'IMPORT';
            }, 'on' => 'import1c'],
            [['material_inv'], 'filter', 'filter' => function ($value) {
                return $value ?: (string)Material::getInvVKomplekte();
            }, 'when' => function ($model) {
                return $model->material_tip == Material::V_KOMPLEKTE;
            }],
            [['material_number'], 'default', 'value' => 1],
            [['material_name', 'material_number', 'material_price', 'material_name1c', 'material_tip', 'id_matvid', 'id_izmer', 'material_username'], 'required'],
            [['id_izmer'], 'exist', 'skipOnError' => true, 'targetClass' => Izmer::className(), 'targetAttribute' => ['id_izmer' => 'izmer_id']],
            [['id_matvid'], 'exist', 'skipOnError' => true, 'targetClass' => Matvid::className(), 'targetAttribute' => ['id_matvid' => 'matvid_id']],
            [['id_schetuchet'], 'exist', 'skipOnError' => true, 'targetClass' => Schetuchet::className(), 'targetAttribute' => ['id_schetuchet' => 'schetuchet_id']],
            [['material_inv'], 'required', 'except' => 'import1c'],
            [['material_release'], 'safe'],
            [['material_number', 'material_price'], 'number'],
            [['material_writeoff', 'id_matvid', 'id_izmer', 'id_izmer'], 'integer'],
            [['material_name', 'material_name1c'], 'string', 'max' => 500],
            [['material_1c'], 'string', 'max' => 20],
            [['material_inv'], 'string', 'max' => 50],
            [['material_serial'], 'string', 'max' => 255],
            [['material_tip'], 'integer', 'min' => 1, 'max' => 6], // 1 - Основное средство, 2 - Материалы, 3 - Групповой учет основных средств, 4 - Основное средство (Ручной ввод), 5 - Материал (Ручной ввод), 6 - В комплекте
            [['material_name', 'material_name1c', 'material_1c', 'material_inv', 'material_release'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
            ['material_inv', 'unique', 'targetAttribute' => ['material_inv', 'material_1c', 'material_tip'], 'message' => '"{value}" - такой инвентарный номер уже есть у данного типа материальнной ценности'],
            [['material_1c'], 'required', 'on' => 'import1c'],
            [['material_serial'], 'match', 'pattern' => '/^null$|^б\/н$|^б\н$|^б\/н\.$|^б\н\.$|^-$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "null", "б/н", "б\н", "б/н.", "б\н.", "-"'],
            ['material_price', 'double', 'min' => 0, 'max' => 1000000000],
            ['material_number', 'double', 'min' => 0, 'max' => 10000000000],
            ['material_release', 'date', 'format' => 'yyyy-MM-dd'],
            [['material_username'], 'string', 'max' => 128],
            [['material_lastchange'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['material_importdo'], 'integer', 'min' => 0, 'max' => 1], // 0 - Материальная ценность при импорте не изменяется, 1 - Материальная ценность может быть изменена при импорте
            [['material_number'], 'FoldDevision'],
            [['material_comment'], 'string', 'max' => 512],
            [['material_install_kab'], 'safe'],
        ];
    }

    public function FoldDevision($attribute)
    {
        if (!(ctype_digit(strval(round($this->$attribute, 3))) && in_array($this->material_tip, [Material::OSNOV, Material::OSNOV_R, Material::GROUP_UCHET, Material::V_KOMPLEKTE]) || in_array($this->material_tip, [Material::MATERIAL, Material::MATERIAL_R])))
            $this->addError($attribute, 'Количество должно быть целым числом');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'material_id' => 'Material ID',
            'material_name' => 'Наименование',
            'material_name1c' => 'Наименование (Из 1С)',
            'material_1c' => 'Код 1С',
            'material_inv' => 'Инвентарный номер',
            'material_serial' => 'Серийный номер',
            'material_release' => 'Дата выпуска',
            'material_number' => 'Количество',
            'material_price' => 'Стоимость',
            'material_tip' => 'Тип',
            'material_writeoff' => 'Списан',
            'id_matvid' => 'Вид',
            'id_izmer' => 'Единица измерения',
            'material_username' => 'Пользователь изменивший запись',
            'material_lastchange' => 'Дата изменения записи',
            'material_importdo' => 'Запись изменяема при импортировании из 1С',
            'id_schetuchet' => 'Счет учета',
            'material_comment' => 'Заметка',
            'material_install_kab' => 'В данный момент установлено в кабинете',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdIzmer()
    {
        return $this->hasOne(Izmer::className(), ['izmer_id' => 'id_izmer'])->from(['idIzmer' => Izmer::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMatv()
    {
        return $this->hasOne(Matvid::className(), ['matvid_id' => 'id_matvid'])->from(['idMatv' => Matvid::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSchetuchet()
    {
        return $this->hasOne(Schetuchet::className(), ['schetuchet_id' => 'id_schetuchet'])->from(['idSchetuchet' => Schetuchet::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMattraffics()
    {
        return $this->hasMany(Mattraffic::className(), ['id_material' => 'material_id'])->from(['mattraffics' => Mattraffic::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialDocfiles()
    {
        return $this->hasMany(MaterialDocfiles::className(), ['id_material' => 'material_id'])->from(['materialDocfiles' => MaterialDocfiles::tableName()]);
    }

    public function getCurrentMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['id_material' => 'material_id'])
            ->from(['currentmattraffic' => Mattraffic::tableName()])
            ->leftJoin('mattraffic mt1', 'currentmattraffic.id_material = mt1.id_material and `currentmattraffic`.`mattraffic_tip` IN (1, 2) and mt1.mattraffic_tip IN (1, 2) and (currentmattraffic.mattraffic_date < mt1.mattraffic_date or currentmattraffic.mattraffic_date = mt1.mattraffic_date  and currentmattraffic.mattraffic_id < mt1.mattraffic_id)')
            ->andWhere(['in', 'currentmattraffic.mattraffic_tip', [1, 2]])
            ->andWhere(['mt1.mattraffic_date' => NULL]);
    }

    public function getLastMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['id_material' => 'material_id'])
            ->from(['lastmattraffic' => Mattraffic::tableName()])
            ->leftJoin('mattraffic mt2', 'lastmattraffic.id_material = mt2.id_material and (lastmattraffic.mattraffic_date < mt2.mattraffic_date or lastmattraffic.mattraffic_id < mt2.mattraffic_id)')
            ->andWhere(['mt2.mattraffic_date' => NULL]);
    }

    public function getMaterial_install_kab()
    {
        $material = self::find()
            ->select(['idbuild.build_name', 'trOsnovs.tr_osnov_kab'])
            ->joinWith(['mattraffics.trOsnovs', 'mattraffics.idMol.idbuild'])
            ->andWhere(['mattraffics.id_material' => $this->primaryKey])
            ->andWhere(['mattraffics.mattraffic_tip' => 3])
            ->orderBy(['mattraffics.mattraffic_date' => SORT_DESC, 'mattraffics.mattraffic_id' => SORT_DESC])
            ->limit(1)
            ->asArray()
            ->one();

        return $material ? $material['build_name'] . ', каб. ' . $material['tr_osnov_kab'] : 'Не установлено';
    }

    public function selectinputfortrmat_parent($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['material_id AS id'], ['CONCAT_WS(", ", material_inv, material_name) AS text']))
            ->where(['like', isset($params['init']) ? 'material_id' : 'material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere(['material_number' => 1])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinput($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $where = isset($params['init']) ? ['material_id' => $params['q']] : ['like', 'material_inv', $params['q']];
        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['material_id AS id'], ['CONCAT_WS(", ", material_inv, material_name) AS text']))
            ->andWhere($where)
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public static function getMaterialByID($ID)
    {
        return 'инв. ' . self::findOne($ID)->material_inv . ', ' . self::findOne($ID)->material_name;
    }

    private static function getInvVKomplekte()
    {
        $maxInv = Material::find()
            ->andWhere(['material_tip' => Material::V_KOMPLEKTE])
            ->max('material_inv');

        return $maxInv ? intval($maxInv) + 1 : 99000001;
    }

    public static function VariablesValues($attribute)
    {
        $values = [
            'material_tip' => [
                Material::OSNOV => 'Основное средство',
                Material::MATERIAL => 'Материал',
                Material::GROUP_UCHET => 'Групповой учет',
                Material::OSNOV_R => 'Основное средство (Р)',
                Material::MATERIAL_R => 'Материал (Р)',
                Material::V_KOMPLEKTE => 'В комплекте',
            ],
            'material_writeoff' => [0 => 'Нет', 1 => 'Да'],
            'material_importdo' => [0 => 'Нет', 1 => 'Да'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
