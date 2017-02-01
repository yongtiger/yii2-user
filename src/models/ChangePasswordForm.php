<?php ///[Yii2 uesr:account]

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
use yii\base\ModelEvent;
use yii\db\IntegrityException;
use yongtiger\user\Module;

/**
 * ChangePassword Form Model
 *
 * @package yongtiger\user\models
 * @property string $newpassword
 * @property string $repassword
 */
class ChangePasswordForm extends ChangeForm
{
    /**
     * @var string newpassword
     */
    public $newpassword;

    /**
     * @var string repassword
     */
    public $repassword; ///[Yii2 uesr:repassword]

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  parent::rules();

        $rules = array_merge($rules, [
            [['newpassword'], 'required'],
            [['newpassword'], 'string', 'min' => 6],
        ]);

        ///[Yii2 uesr:repassword]
        if (Yii::$app->getModule('user')->enableAccountChangePasswordWithRepassword) {
            $rules = array_merge($rules, [
                [['repassword'], 'required'],
                [['repassword'], 'string', 'min' => 6],
                ['repassword', 'compare', 'compareAttribute' => 'newpassword', 'message' => Module::t('user', 'The two passwords do not match.')],
            ]);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();

        $attributeLabels['newpassword'] = Module::t('user', 'New Password');
        if (Yii::$app->getModule('user')->enableAccountChangePasswordWithRepassword) {
            $attributeLabels['repassword'] = Module::t('user', 'Repeat Password');   ///[Yii2 uesr:repassword]
        }

        return $attributeLabels;
    }

    /**
     * Changes password.
     *
     * @return bool whether the changing is successfully
     */
    public function ChangePassword($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if ($this->beforeChange()) {

            // ...custom code here...
            $this->getUser()->setPassword($this->newpassword);
            if ($this->getUser()->save(false)) {
                $this->afterChange();
                return true;
            }

        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function afterChange()
    {
        // ...custom code here...
        ///[Yii2 uesr:verify]
        $this->getUser()->verify->password_verified_at = time();
        if (!$this->getUser()->verify->save(false)) {
            throw new IntegrityException();
        }

        parent::afterChange();
    }
}