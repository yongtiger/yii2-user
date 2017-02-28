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
use yongtiger\user\Module;

/**
 * Change Username Form Model
 *
 * @package yongtiger\user\models
 * @property string $username
 */
class ChangeUsernameForm extends ChangeForm
{
    /**
     * @var string username
     */
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules =  parent::rules();

        $rules = array_merge($rules, [
            ['username', 'required'],
            ['username', 'trim'],
            ['username', 'filter', 'filter' => function ($value) {  ///@see http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#filter
                return preg_replace('/[^(\x{4E00}-\x{9FA5})\w]/iu', '', $value);
            }],
            ['username', 'string', 'min' => 2, 'max' => 32],

            ///[Yii2 uesr:username]User name verification
            //The unicode range of Chinese characters is: 0x4E00~0x9FA5. This range also includes Chinese, Japanese and Korean characters
            //  u   Indicates to match by unicode (utf-8), mainly for multi-byte characters such as Chinese characters
            //  \x  Ignore whitespace
            //[(\x{4E00}-\x{9FA5})a-zA-Z]+  The character starts with a Chinese character or letter and appears 1 to n times
            //[(\x{4E00}-\x{9FA5})\w]*      Chinese characters underlined alphabet, there 0-n times
            ['username', 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})\w]*$/u', 'message' => Module::t('user', 'The username only contains letters ...')],

            ['username', 'unique', 'skipOnError' => true, 'targetClass' => User::className(), 'message' => Module::t('user', 'This username has already been taken.')],
        ]);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        
        $attributeLabels['username'] = Module::t('user', 'Username');

        return $attributeLabels;
    }

    /**
     * Changes username.
     *
     * @return bool whether the changing is successfully
     */
    public function ChangeUsername($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }
        
        if ($this->beforeChange()) {

            // ...custom code here...
            $this->getUser()->username = $this->username;
            if ($this->getUser()->save(false)) {
                $this->afterChange();
                return true;
            }
            
        }

        return false;
    }
}