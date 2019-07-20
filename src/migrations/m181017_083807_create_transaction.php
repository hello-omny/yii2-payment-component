<?php

use omny\yii2\payment\component\models\Balance;
use omny\yii2\payment\component\models\Transaction;
use yii\db\Migration;

/**
 * Class m181017_083807_create_transaction
 */
class m181017_083807_create_transaction extends Migration
{
    const INDEX_TEMPLATE = 'idx__%s__%s';

    const DEFAULT_TIMESTAMP = '0000-00-00 00:00:00';
    const CURRENT_TIMESTAMP_EXPRESSION = 'current_timestamp()';
    const DEFAULT_ON_UPDATE_EXPRESSION = "'0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            Transaction::tableName(),
            [
                'id' => $this->bigPrimaryKey()->unsigned(),
                'value' => $this->string(2048)->defaultValue(''),
                'type' => $this->smallInteger()->notNull()->defaultValue(Transaction::TYPE_INCOME),
                'created_at' => $this->timestamp()->defaultExpression(self::CURRENT_TIMESTAMP_EXPRESSION),
            ]
        );
        $this->createIndex(
            sprintf(self::INDEX_TEMPLATE, Transaction::tableName(), 'type'),
            Transaction::tableName(),
            'type'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Transaction::tableName());
    }
}
