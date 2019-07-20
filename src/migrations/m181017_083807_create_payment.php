<?php

use omny\yii2\payment\component\models\Payment;
use yii\db\Migration;

/**
 * Class m181017_083807_create_payment
 */
class m181017_083807_create_payment extends Migration
{
    const INDEX_TEMPLATE = 'idx__%s__%s';

    const DEFAULT_TIMESTAMP = '0000-00-00 00:00:00';
    const CURRENT_TIMESTAMP_EXPRESSION = 'current_timestamp()';
    const DEFAULT_ON_UPDATE_EXPRESSION = "current_timestamp() ON UPDATE CURRENT_TIMESTAMP";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            Payment::tableName(),
            [
                'id' => $this->bigPrimaryKey()->unsigned(),
                'amount' => $this->decimal(10,2)->notNull()->defaultValue(0),
                'tax' => $this->decimal(10,2)->notNull()->defaultValue(0),
                'status' => $this->integer()->defaultValue(Payment::STATUS_NEW),
                'user_id' => $this->bigInteger()->unsigned()->notNull(),
                'created_at' => $this->dateTime()->notNull()->defaultExpression(self::CURRENT_TIMESTAMP_EXPRESSION),
                'made_at' => $this->timestamp()->defaultExpression(self::DEFAULT_ON_UPDATE_EXPRESSION)
            ]
        );
        $this->createIndex(
            sprintf(self::INDEX_TEMPLATE, Payment::tableName(), 'status'),
            Payment::tableName(),
            'status'
        );
        $this->createIndex(
            sprintf(self::INDEX_TEMPLATE, Payment::tableName(), 'user_id'),
            Payment::tableName(),
            'user_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Payment::tableName());
    }
}
