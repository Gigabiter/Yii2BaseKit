<?php

namespace kosuhin\Yii2BaseKit\Widgets\OutFormSubmitButtons;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class OutFormSubmitButtonsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__.'/resources';
    /**
     * @var array
     */
    public $js = [
        'OutFormSubmitButtons.js',
    ];

    public $depends = [
        BootstrapAsset::class,
        YiiAsset::class,
    ];
}