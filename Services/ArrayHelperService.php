<?php

namespace kosuhin\Yii2BaseKit\Services;

use yii\helpers\Html;

class ArrayHelperService
{
    /**
     * Sorts array by provided field
     *
     * @param $array
     * @param $field
     * @param $sort
     */
    public function arraySort(&$array, $field, $sort)
    {
        $sort = $sort == SORT_ASC ? 1 : -1;
        uasort($array, function ($a, $b) use ($field, $sort) {
            return $a[$field] > $b[$field] ? $sort : -1 * $sort;
        });
    }

    /**
     * Rotates array by 90 deg
     *
     * @param $mat
     * @return array
     */
    public function rotate90($mat)
    {
        $height = count($mat);
        $width = count($mat[0]);
        $mat90 = [];
        for ($i = 0; $i < $width; $i++) {
            for ($j = 0; $j < $height; $j++) {
                $mat90[$height - $i - 1][$j] = $mat[$height - $j - 1][$i];
            }
        }

        return $mat90;
    }

    /**
     * Encode array to json with needed options
     *
     * @param $data
     * @return string
     */
    public function toJson($data)
    {
        return json_encode($data ?: [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Search of array item in array
     * with needed value in specified column
     *
     * @param $array
     * @param $column
     * @param $value
     * @return null
     */
    public function arraySearch($array, $column, $value)
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
     * Just return value of key, and
     * set default value if there is no key.
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return null
     */
    public function getValue($array, $key, $default = null)
    {
        return isset($array[$key]) ? Html::encode($array[$key]) : $default;
    }
}