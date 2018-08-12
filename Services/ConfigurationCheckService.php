<?php

namespace kosuhin\Yii2BaseKit\Services;

class ConfigurationCheckService
{
    public static function checkOption($optionDataIterator, $arrayOfNeededKeys)
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

    public static function checkValue($optionDataIterator, $neededKey)
    {
        $keys = array_keys($optionDataIterator);
        if (!in_array($neededKey, $keys)) {
            throw new \LogicException('You must specify key '.$neededKey);
        }
    }
}