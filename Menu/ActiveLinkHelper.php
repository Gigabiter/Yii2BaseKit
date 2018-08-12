<?php

namespace kosuhin\Yii2BaseKit\Menu;

class ActiveLinkHelper
{
    public static function isLinkActive($link)
    {
        return strpos(\Yii::$app->request->getUrl(), $link) !== false;
    }
}