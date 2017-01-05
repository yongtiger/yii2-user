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

use Yii;
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
    public $verifyCode; ///[Yii2 uesr:verifycode]

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

            ///[Yii2 uesr:username]注册页面：用户名验证
            //汉字的unicode范围是：0x4E00~0x9FA5，其实这个范围还包括了中，日，韩的字符
            //  u 表示按unicode(utf-8)匹配（主要针对多字节比如汉字）
            //  \x忽略空白
            //[(\x{4E00}-\x{9FA5})a-zA-Z]+表示以汉字或者字母开头，出现1-n次
            //[(\x{4E00}-\x{9FA5})\w]*表示以汉字字母数字下划线组成，出现0-n次
            ['username', 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})\w]*$/u', 'message' => Module::t('user', 'The username only contains letters ...')],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\yongtiger\user\models\User', 'message' => Module::t('user', 'This email address has already been taken.')],

            ///[Yii2 uesr:repassword]
            [['password','repassword'],'required'],
            [['password','repassword'], 'string', 'min' => 6],
            ['repassword','compare','compareAttribute'=>'password','message' => Module::t('user', 'The two passwords do not match.')],

            ///[Yii2 uesr:verifycode]
            ///default is 'site/captcha'. @see http://stackoverflow.com/questions/28497432/yii2-invalid-captcha-action-id-in-module
            ///Note: CaptchaValidator should be used together with yii\captcha\CaptchaAction.
            ///@see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
            ['verifyCode', 'captcha', 'captchaAction' => Yii::$app->controller->module->id . '/registration/captcha'],  
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
            'repassword' => Module::t('user', 'Repeat Password'),   ///[Yii2 uesr:repassword]
            'verifyCode' => Module::t('user', 'Verification Code'),  ///[Yii2 uesr:verifycode]
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
