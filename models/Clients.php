<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%clients}}".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $updateTime
 * @property string $insertTime
 *
 * @property Phones[] $phones
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clients}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstName'], 'required'],
            [['updateTime', 'insertTime'], 'safe'],
            [['firstName', 'lastName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'updateTime' => 'Update Time',
            'insertTime' => 'Insert Time',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones()
    {
        return $this->hasMany(Phones::className(), ['client_id' => 'id']);
    }
    
    public function setPhones($data = []) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($data['model']->phones as $phone) {
                if(is_object($phone)) $phone->delete();
            }
            $count = count($data['request']['Phones']);
            $phones = [new \app\models\Phones()];
            for($i = 1; $i < $count; $i++) {
                $phones[] = new \app\models\Phones();
            }
            \yii\base\Model::loadMultiple($phones, $data['request']);
            foreach ($phones as $key => $phone) {
                if(!empty($phone->phone)) $phone->link('client', $data['model']);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
    }
    
    public function loadData($data) {
        if(!$data) return false;
        foreach ($data as $key => $value) {
            if($this->hasAttribute($key)) $this->$key = filter_var($value, FILTER_SANITIZE_STRING);
        }
    }
}
