<?php

namespace kosuhin\Yii2BaseKit\Services;

class ConfigurationCheckService
{
    /**
     * Check set of options in configuration array
     *
     * @param $optionDataIterator
     * @param $arrayOfNeededKeys
     */
    public static function checkOptions($optionDataIterator, $arrayOfNeededKeys)
    {
        foreach ($optionDataIterator as $optionData) {
            $keys = array_keys($optionData);
            foreach ($arrayOfNeededKeys as $neededKey) {
                if (!in_array($neededKey, $keys)) {
                    throw new \LogicException('You must specify key '.$neededKey);
                }
            }
        }
    }

    /**
     * Check one option in configurartoin array
     *
     * @param $optionDataIterator
     * @param $neededKey
     */
    public static function checkValue($optionDataIterator, $neededKey)
    {
        $keys = array_keys($optionDataIterator);
        if (!in_array($neededKey, $keys)) {
            throw new \LogicException('You must specify key '.$neededKey);
        }
    }
}