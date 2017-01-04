<?php ///[Yii2 uesr]

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

use yii\base\Model;
use yongtiger\user\models\User;
use yongtiger\user\Module;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repassword; ///[Yii2 uesr:repassword]

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This email address has already been taken.')],

            ///[Yii2 uesr:repassword]
            [['password','repassword'],'required'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword','compare','compareAttribute'=>'password','message' => Module::t('user', 'The two passwords do not match.')],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('user', 'Username'),
            'email' => Module::t('user', 'Email'),
            'password' => Module::t('user', 'Password'),
            'repassword' => Module::t('user', 'Repeat Password'),    ///[Yii2 uesr:repassword]
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
