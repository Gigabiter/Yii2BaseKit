<?php

namespace kosuhin\Yii2BaseKit\Widgets\ConditionFormFieldsWidget;

use app\assets\JqueryInitializePluginAsset;
use yii\web\AssetBundle;

/**
 * This widget can be usefull if you want to control
 * what block on page will be see only by some value
 * in some form on the same page.
 *
 * @package kosuhin\Yii2BaseKit\Widgets\ConditionFormFieldsWidget
 */
class ConditionFormFieldsWidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__.'/resources';

    /**
     * @var array
     */
    public $js = [
        'ConditionFormFieldsWidget.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryInitializePluginAsset::class,
    ];
}