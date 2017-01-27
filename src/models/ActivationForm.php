<?php ///[Yii2 uesr:activation via email:activation]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\models;

use Yii;
use yii\base\Model;
use yongtiger\user\Module;
use yongtiger\user\helpers\SecurityHelper;

/**
 * Activation Form Model
 *
 * @package yongtiger\user\models
 * @property string $activation_key Activation key
 */
class ActivationForm extends Model
{
    /**
     * @var string $activation_key Activation key
     */
    public $activation_key;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['activation_key', 'required'],
            ['activation_key', 'trim'],
            ['activation_key', 'string', 'max' => 128],
            [
                'activation_key',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                    $query->andWhere(['status' => User::STATUS_INACTIVE]);
                }
            ],
            ['activation_key', 'validateKey'],  ///[Yii2 uesr:activation via email:activation]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activation_key' => Module::t('user', 'Activation Key')
        ];
    }

    ///[Yii2 uesr:activation via email:activation]
    /**
     * Validates the activation key.
     *
     * This method serves as the inline validation for activation key.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateKey($attribute, $params)
    {
        if (!SecurityHelper::isValidKey($this->$attribute)) {
            $this->addError($attribute, Module::t('user', 'The activation link is expired!'));
        }
    }

    ///[Yii2 uesr:activation via email:activation]
    /**
     * Activates user account.
     *
     * @return boolean true if account was successfully activated
     * @return User|false the activated user model or false if activation fails
     */
    public function activate($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {

            ///Because activation is not used ActiveForm, so output errors by `setFlash()`.
            ///Traversing the two-dimensional array of errors. @see http://www.yiiframework.com/doc-2.0/yii-base-model.html#$errors-detail
            foreach ($this->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }

            return false;
        }

        $user = User::findOne(['activation_key' => $this->activation_key, 'status' => User::STATUS_INACTIVE]);
        if ($user !== null) {
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();
            $user->activation_key = null;

            if ($user->save(false)) {

                Yii::$app->session->addFlash('success', Module::t('user', 'Your Account has been successfully activated ...'));
                return $user;
            }

        }

        Yii::$app->session->addFlash('error', Module::t('user', 'User has not been activated. Please try again!'));
        return false;
    }
}