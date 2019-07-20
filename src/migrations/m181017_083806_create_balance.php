<?php

use omny\yii2\payment\component\models\Balance;
use yii\db\Migration;

/**
 * Class m181017_083806_create_balance
 */
class m181017_083806_create_balance extends Migration
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
            Balance::tableName(),
            [
                'id' => $this->bigPrimaryKey()->unsigned(),
                'value' => $this->decimal(10,2)->notNull()->defaultValue(0),
                'user_id' => $this->bigInteger()->unsigned()->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression(self::DEFAULT_ON_UPDATE_EXPRESSION),
            ]
        );
        $this->createIndex(
            sprintf(self::INDEX_TEMPLATE, Balance::tableName(), 'user_id'),
            Balance::tableName(),
            'user_id',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Balance::tableName());
    }
}
