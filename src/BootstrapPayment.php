<?php

namespace omny\yii2\payment\component;

use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package omny\yii2\payment\component
 */
class BootstrapPayment implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        if (($app instanceof \yii\console\Application)) {
            return;
        }

        $app->setComponents(['payment' => Payment::class]);
    }
}
