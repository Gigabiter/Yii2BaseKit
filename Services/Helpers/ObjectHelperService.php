<?php

namespace kosuhin\Yii2BaseKit\Services\Helpers;

use ReflectionClass;

class ObjectHelperService
{
    /**
     * Return class short name
     *
     * @param $object
     * @return string
     * @throws \ReflectionException
     */
    public function getShortClass($object)
    {
        return (new ReflectionClass($object))->getShortName();
    }

    /**
     * Sorts list of objects by provided property
     *
     * @param $array
     * @param $property
     * @param $sort
     */
    public function objectsSort(&$array, $property, $sort)
    {
        $sort = $sort == SORT_ASC ? 1 : -1;
        uasort($array, function ($a, $b) use ($property, $sort) {
            return $a->{$property} > $b->{$property} ? $sort : -1 * $sort;
        });
    }

    /**
     * It is like array_column for arrays but
     * it is for array of objects. Return
     * array of values specified in $object->$column
     *
     * @param $array
     * @param $column
     * @return array
     */
    public function arrayObjectColumn($array, $column)
    {
        $result = [];
        foreach ($array as $item) {
            $result[] = $item->$column;
        }

        return $result;
    }

    /**
     * Search of object with needed column value in array
     *
     * @param $array
     * @param $column
     * @param $value
     * @return null
     */
    public function arrayObjectSearch($array, $column, $value)
    {
        foreach ($array as $item) {
            if ($item->$column == $value) {
                return $item;
            }
        }

        return null;
    }
}