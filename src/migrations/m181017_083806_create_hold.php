<?php

use omny\yii2\payment\component\models\Hold;
use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m181017_083806_create_hold extends Migration
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
            Hold::tableName(),
            [
                'id' => $this->bigPrimaryKey()->unsigned(),
                'value' => $this->decimal(10,2)->notNull()->defaultValue(0),
                'user_id' => $this->bigInteger()->unsigned()->notNull(),
                'updated_at' => $this->timestamp()->defaultExpression(self::CURRENT_TIMESTAMP_EXPRESSION),
            ]
        );
        $this->createIndex(
            sprintf(self::INDEX_TEMPLATE, Hold::tableName(), 'user_id'),
            Hold::tableName(),
            'user_id',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Hold::tableName());
    }
}
