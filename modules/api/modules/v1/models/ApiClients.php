<?php

namespace app\modules\api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "{{%clients}}".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $phoneNumbers
 * @property string $updateTime
 * @property string $insertTime
 */
class ApiClients extends \app\models\Clients
{
    public function __construct($config = []) {
        parent::__construct($config);
    }
}
