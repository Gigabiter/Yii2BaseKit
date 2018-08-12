<?php

namespace kosuhin\Yii2BaseKit\Helpers;

use app\ARs\Log;
use app\services\SL;

class UUIDGenerator
{
    public static function v1($lenght = 12)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            SL::o()->logService->log(Log::TYPE_ERROR, 'Ошибка в генераторе UID',
                'no cryptographically secure random function available', SL::o()->userService->getCurrentUserId());
        }

        return substr(bin2hex($bytes), 0, $lenght);
    }
}