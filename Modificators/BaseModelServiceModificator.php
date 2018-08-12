<?php

namespace kosuhin\Yii2BaseKit\Modificators;

use kosuhin\Yii2BaseKit\Services\Base\BaseModelService;
use yii\base\Behavior;

/**
 * TODO to create more event handlers and events
 *
 * @see BaseModelService
 */
class BaseModelServiceModificator extends Behavior
{
    public function events()
    {
        return [
            BaseModelService::EVENT_BEFORE_MODEL_SAVE => 'handleBeforeModelSave',
        ];
    }

    public function handleBeforeModelSave()
    {

    }
}