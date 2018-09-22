<?php

namespace app\modules\api\modules\v1\controllers;

use yii\rest\Controller;
use Yii;
use app\modules\api\modules\v1\models\ApiClients;
use app\modules\api\modules\v1\models\ApiPhones;


/**
 * Clients controller for the `api v1` module
 */
class ApiClientController extends Controller
{
    /**
     * Renders the index view for the module
     * @return array
     */
    public function actionIndex($p = 0)
    {
        $cache = Yii::$app->cache;
        $limit = 5;
        $offset = $p*$limit;
        $clients = $cache->getOrSet("api_client_$p", function () use ($offset, $limit) {
            return ApiClients::find()
                ->with('phones')
                ->asArray()
                ->offset($offset)
                ->limit($limit)
                ->all();
        });        
        return [
            'status' => true,
            'clients' => $clients,
        ];
    }
    public function actionAdd() {
        $request = Yii::$app->request->getRawBody();
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
        return [
            'status' => true,
            'clients' => $clients,
        ];
    }
    
    protected function newClient($client) {
        $new_client = new ApiClients();
        $new_client->loadData($client);
        $new_client->save();
        if(!empty($client->phoneNumbers)){
            foreach ($client->phoneNumbers as $number){
                $new_phone =new ApiPhones();
                $new_phone->phone = $number;
                $new_phone->link('client', $new_client);
            }
        }
    }
}
