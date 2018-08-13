<?php

namespace kosuhin\Yii2BaseKit\Widgets\DataTableWidget;

use kosuhin\Yii2BaseKit\Assets\DataTableAsset\DataTableAsset;
use yii\web\AssetBundle;

class DataTableWidgetAsset extends AssetBundle
{
    /**
     * @var array
     */
    public $depends = [
        DataTableAsset::class,
    ];
}