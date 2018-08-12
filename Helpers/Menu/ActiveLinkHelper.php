<?php

namespace kosuhin\Yii2BaseKit\Helpers\Menu;

class ActiveLinkHelper
{
    public static function isLinkActive($link)
    {
        return strpos(\Yii::$app->request->getUrl(), $link) !== false;
    }
}