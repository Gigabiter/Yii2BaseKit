<?php

namespace kosuhin\Yii2BaseKit\Components;

use kosuhin\Yii2BaseKit\Services\Helpers\ArrayHelperService;
use yii\base\Component;

class BaseComponent extends Component
{
    /**
     * @return ArrayHelperService
     */
    public function getArrayHelperService()
    {
        return \Yii::$app->get('arrayHelperService');
    }
}