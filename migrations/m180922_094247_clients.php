<?php

use yii\db\Migration;

/**
 * Class m180922_094247_clients
 */
class m180922_094247_clients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('clients', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string()->notNull(),
            'lastName' => $this->string(),
            'updateTime' => 'datetime DEFAULT CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP()',
            'insertTime' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP()')
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('clients');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180922_094247_clients cannot be reverted.\n";

        return false;
    }
    */
}
