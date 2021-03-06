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
 * Change Email Form Model
 *
 * @package yongtiger\user\models
 * @property string $email
 */
class ChangeEmailForm extends ChangeForm
{
    /**
     * @var string email
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  parent::rules();

        $rules = array_merge($rules, [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'skipOnError' => true, 'targetClass' => User::className(), 'message' => Module::t('message', 'This email address has already been taken.')],
        ]);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        
        $attributeLabels['email'] = Module::t('message', 'Email');

        return $attributeLabels;
    }

    /**
     * Changes email.
     *
     * @return bool whether the changing is successfully
     */
    public function ChangeEmail($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }
        
        if ($this->beforeChange()) {

            // ...custom code here...
            $this->getUser()->email = $this->email;
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
        $this->getUser()->verify->email_verified_at = null; ///Note: MUST has current user's record in table verify! or get a exception 'Indirect modification of overloaded property yongtiger\user\models\User::$verify has no effect'
        if (!$this->getUser()->verify->save(false)) {
            throw new IntegrityException();
        }

        parent::afterChange();
    }
}