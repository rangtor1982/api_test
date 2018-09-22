<?php

use yii\db\Migration;

/**
 * Class m180922_120320_phones
 */
class m180922_120320_phones extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('phones', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'phone' => $this->string(),
            'updateTime' => 'datetime DEFAULT CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP()',
            'insertTime' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP()')
        ]);

        // creates index for column `client_id`
        $this->createIndex(
            'idx-phone-client_id',
            'phones',
            'client_id'
        );

        // add foreign key for table `clients`
        $this->addForeignKey(
            'fk-phone-client_id',
            'phones',
            'client_id',
            'clients',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-phone-client_id',
            'phones'
        );

        // drops index for column `client_id`
        $this->dropIndex(
            'idx-phone-client_id',
            'phones'
        );
        $this->dropTable('phones');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180922_120320_phones cannot be reverted.\n";

        return false;
    }
    */
}
