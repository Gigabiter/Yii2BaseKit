<?php

namespace kosuhin\Yii2BaseKit\Helpers;

use yii\helpers\Html;

class ArrayHelper
{
    /**
     * @deprecated Moved to objectHelperService
     */
    public static function objectsSort(&$array, $property, $sort)
    {
        $sort = $sort == SORT_ASC ? 1 : -1;
        uasort($array, function ($a, $b) use ($property, $sort) {
            return $a->{$property} > $b->{$property} ? $sort : -1 * $sort;
        });
    }

    /**
     * @deprecated Moved to arrayHelperService
     */
    public static function arraySort(&$array, $field, $sort)
    {
        $sort = $sort == SORT_ASC ? 1 : -1;
        uasort($array, function ($a, $b) use ($field, $sort) {
            return $a[$field] > $b[$field] ? $sort : -1 * $sort;
        });
    }

    /**
     * @deprecated Moved to arrayHelperService
     */
    public static function rotate90($mat)
    {
        $height = count($mat);
        $width = count($mat[0]);
        $mat90 = array();

        for ($i = 0; $i < $width; $i++) {
            for ($j = 0; $j < $height; $j++) {
                $mat90[$height - $i - 1][$j] = $mat[$height - $j - 1][$i];
            }
        }

        return $mat90;
    }

    /**
     * @deprecated Moved to arrayHelperService
     */
    public static function toJson($data)
    {
        return json_encode($data ?: [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @deprecated Moved to objectHelperService
     */
    public static function arrayObjectColumn($array, $column)
    {
        $result = [];
        foreach ($array as $item) {
            $result[] = $item->$column;
        }

        return $result;
    }

    /**
     * @deprecated Moved to objectHelperService
     */
    public static function arrayObjectSearch($array, $column, $value)
    {
        foreach ($array as $item) {
            if ($item->$column == $value) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @deprecated Moved to arrayHelperService
     */
    public static function arraySearch($array, $column, $value)
    {
        foreach ($array as $key => $item) {
            if ($item[$column] == $value) {
                $item['array_search_key'] = $key;
                return $item;
            }
        }

        return null;
    }

    /**
     * @deprecated Moved to arrayHelperService
     */
    public static function getValue($array, $key, $default = null)
    {
        return isset($array[$key]) ? Html::encode($array[$key]) : $default;
    }
}