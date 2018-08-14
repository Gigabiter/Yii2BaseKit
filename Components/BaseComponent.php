<?php

namespace kosuhin\Yii2BaseKit\Components;

use kosuhin\Yii2BaseKit\Services\ArrayHelperService;
use yii\base\Component;

class BaseComponent extends Component
{
    /**
     * @return ArrayHelperService
     * @throws \yii\base\InvalidConfigException
     */
    public function getArrayHelperService()
    {
        return \Yii::$app->get('arrayHelperService');
    }
}