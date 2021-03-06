<?php

namespace kosuhin\Yii2BaseKit\Widgets\ColumnButtonWidget;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;

/**
 * @package kosuhin\Yii2BaseKit\Widgets\ColumnButtonWidget
 */
class ColumnButtonWidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__.'/resources';

    /**
     * @var array
     */
    public $js = [
        'ColumnButtonWidget.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        BootstrapAsset::class
    ];
}