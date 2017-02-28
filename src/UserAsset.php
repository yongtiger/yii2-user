<?php ///[Yii2 user]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-cropper-avatar
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user;

use Yii;
use yii\web\AssetBundle;

/**
 * Class UserAsset
 *
 * @package yongtiger\user
 */
class UserAsset extends AssetBundle
{
    public $sourcePath = '@yongtiger/user/assets';

    public $js = [
        'js/delete-in.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}