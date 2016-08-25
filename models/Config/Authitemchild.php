<?php

namespace app\models\Config;

use Yii;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class Authitemchild extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent', 'child'], 'required'],
            ['parent', 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent', 'child'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getparent() {
        return $this->hasOne(Authitem::className(), ['name' => 'parent'])->from(['parent' => Authitem::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getchildren() {
        return $this->hasOne(Authitem::className(), ['name' => 'child'])->from(['children' => Authitem::tableName()]);
    }

    public function delete() {
        $auth = Yii::$app->authManager;

        $parent = $auth->getRole($this->parent);
        $child = $auth->getRole($this->child);
        if (!isset($child))
            $child = $auth->getPermission($this->child);

        if (empty($parent) or empty($child))
            return false;

        return $auth->removeChild($parent, $child);
    }

}
