<?php

namespace app\models\Config;

use Yii;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 * @property AuthUser $user
 */
class Authassignment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['item_name', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            ['user_id', 'unique', 'targetAttribute' => ['item_name', 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getitemname() {
        return $this->hasOne(Authitem::className(), ['name' => 'item_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getuser() {
        return $this->hasOne(Authuser::className(), ['auth_user_id' => 'user_id']);
    }

    public function save($runValidation = true, $attributeNames = null) {
        if ($runValidation && !$this->validate())
            return false;

        $auth = Yii::$app->authManager;
        if ($this->getIsNewRecord()) {
            $item = $auth->getRole($this->item_name);
            if (!isset($item))
                $item = $auth->getPermission($this->item_name);

            if (empty($item))
                return false;

            return $auth->assign($item, $this->user_id);
        } else {
            return false;
        }
    }

    public function delete() {
        $auth = Yii::$app->authManager;

        $item = $auth->getRole($this->item_name);
        if (!isset($item))
            $item = $auth->getPermission($this->item_name);

        if (empty($item))
            return false;

        return $auth->revoke($item, $this->user_id);
    }

}
