<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%phones}}".
 *
 * @property int $id
 * @property int $client_id
 * @property string $phone
 * @property string $updateTime
 * @property string $insertTime
 *
 * @property Clients $client
 */
class Phones extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%phones}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id'], 'required'],
            [['client_id'], 'integer'],
            [['updateTime', 'insertTime'], 'safe'],
            [['phone'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'phone' => 'Phone',
            'updateTime' => 'Update Time',
            'insertTime' => 'Insert Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['id' => 'client_id']);
    }
}
