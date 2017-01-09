<?php ///[Yii2 uesr:activation via email]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\helpers;

use Yii;

/**
 * Helper Class SecurityHelper
 *
 * @package yongtiger\user\helpers
 */
class SecurityHelper
{
    /**
     * Generate a random key with time suffix.
     *
     * For example:
     * `1483947060_1483947060_Np9DM28poDl9x-3J0D9dolagcLNb8WmM`, it means never expired.
     * `1483947060_1483947999_Np9DM28poDl9x-3J0D9dolagcLNb8WmM`, it will expired at timestamp 1483947999.
     *
     * @param $duration integer The expiry time in seconds. Defaults to `0`, it means never expired.
     * @return string Random key
     */
    public static function generateExpiringRandomKey($duration = 0)
    {
        $key = Yii::$app->getSecurity()->generateRandomString();
        return time() . '_' . (time() + $duration) . '_' . $key;
    }

    /**
     * Check if key is not expired.
     *
     * @param string $key Token that must be validated
     * @return boolean true if key is not expired
     */
    public static function isValidKey($key)
    {
        if (empty($key)) {
            return false;
        }

        list($createdTime, $expiryTime) = static::getKeyTime($key);

        if ($createdTime < $expiryTime) {
            return $expiryTime > time();
        }else{
            return true;    ///while $duration <= 0
        }
    }

    /**
     * Get created time of a key.
     *
     * @param string $key Token that must be validated
     * @return array|null [createdTime, expiryTime], null if key is empty
     */
    public static function getKeyTime($key)
    {
        if (empty($key)) {
            return null;
        }
        $parts = explode('_', $key);
        return [(int)$parts[0], (int)$parts[1]];
    }
}