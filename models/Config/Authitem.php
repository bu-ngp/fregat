<?php

namespace app\models\Config;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthUser[] $users
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 */
class Authitem extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'type', 'description'], 'required'],
            ['name', 'unique', 'message' => '{attribute} уже существует'],
            [['type'], 'integer', 'min' => 1, 'max' => 2],
            [['created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            ['name', 'match', 'pattern' => '/^\w+$/ui', 'message' => '{attribute} может состоять только из латинских букв']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'Идентификатор',
            'type' => 'Тип',
            'description' => 'Наименование',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getauthassignments() {
        return $this->hasMany(Authassignment::className(), ['item_name' => 'name'])->from(['authassignments' => Authassignment::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getusers() {
        return $this->hasMany(Authuser::className(), ['auth_user_id' => 'user_id'])->from(['users' => Authuser::tableName()])->viaTable('auth_assignment', ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName() {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name'])->from(['ruleName' => AuthRule::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getauthitemchildrenparent() {
        return $this->hasMany(Authitemchild::className(), ['parent' => 'name'])->from(['authitemchildrenparent' => Authitemchild::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthitemchildrenchild() {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name'])->from(['authitemchildrenchild' => Authitemchild::tableName()]);
    }

    public function save($runValidation = true, $attributeNames = null) {
        if ($runValidation && !$this->validate())
            return false;

        $auth = Yii::$app->authManager;
        if ($this->getIsNewRecord()) {
            $authitem = $this->type == 1 ? $auth->createRole($this->name) : $auth->createPermission($this->name);
            $authitem->description = $this->description;
            return $auth->add($authitem);
        } else {
            $authitem = $this->type == 1 ? $auth->getRole($this->name) : $auth->getPermission($this->name);
            $authitem->description = $this->description;
            return $auth->update($authitem->name, $authitem);
        }
    }

    public function delete() {
        $auth = Yii::$app->authManager;
        $authitem = $this->type == 1 ? $auth->getRole($this->name) : $auth->getPermission($this->name);
        return $auth->remove($authitem);
    }

    public static function VariablesValues($attribute) {
        $values = [
            'type' => [1 => 'Роль', 2 => 'Операция']
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
