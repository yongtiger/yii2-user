<?php ///[Yii2 user]

/**
 * Yii2 user
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user;

use Yii;

/**
 * @package yongtiger\user
 */
class Module extends \yii\base\Module
{
    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     *
     * @see http://www.yiiframework.com/doc-2.0/yii-base-module.html#$defaultRoute-detail
     */
    public $defaultRoute = 'security';

    /**
     * @var string The controller namespace to use
     */
    public $controllerNamespace = 'yongtiger\user\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Registers the translation files
     */
    protected function registerTranslations()
    {
        ///[i18n]
        ///if no setup the component i18n, use setup in this module.
        if (!isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-user/*']) && !isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-user'])) {
            Yii::$app->i18n->translations['extensions/yongtiger/yii2-user/*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@vendor/yongtiger/yii2-user/src/messages',    ///default base path is '@vendor/yongtiger/yii2-user/src/messages'
                'fileMap' => [
                    'extensions/yongtiger/yii2-user/user' => 'user.php',  ///category in Module::t() is user
                ],
            ];
        }
    }

    /**
     * Translates a message. This is just a wrapper of Yii::t()
     *
     * @see Yii::t()
     *
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('extensions/yongtiger/yii2-user/' . $category, $message, $params, $language);
    }
}
