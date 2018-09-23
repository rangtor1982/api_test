<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\modules\api\modules\v1\models\ApiClients;
use app\modules\api\modules\v1\models\ApiPhones;
use Yii;

class QueueController extends Controller
{
    public function actionApply()
    {
        $cache = Yii::$app->cache;
        $cache_queue = $cache->get('query_queue');
        $cache->delete('query_queue');
        $requests = explode(';', $cache_queue);
        foreach ($requests as $request) {
            try {
                $clients = \yii\helpers\Json::decode($request,false);
            } catch (\yii\base\InvalidArgumentException $ex) {
                return [
                    'status' => false,
                    'message' => $ex->getMessage(),
                ];
            }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if(is_array($clients)){
                    foreach ($clients as $client) {
                        $this->newClient($client);
                    }
                } elseif (is_object($clients)){
                    $this->newClient($clients);
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
        return ExitCode::OK;
    }
    protected function newClient($client) {
        $new_client = new ApiClients();
        $new_client->loadData($client);
        $new_client->save();
        if(!empty($client->phoneNumbers)){
            foreach ($client->phoneNumbers as $number){
                $new_phone =new ApiPhones();
                $new_phone->phone = $number;
                $new_client->addPhone($new_phone);
            }
        }
    }
}
