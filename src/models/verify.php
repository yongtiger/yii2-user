<?php ///[Yii2 uesr:verify]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yongtiger\user\Module;

/**
 * This is the model class for table "{{%verify}}".
 *
 * @property integer $user_id
 * @property integer $password_verified_at
 * @property integer $email_verified_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property User $user
 */
class Verify extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%verify}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                // 'value' => new \yii\db\Expression('NOW()'), ///if you're using datetime instead of UNIX timestamp
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'password_verified_at', 'email_verified_at', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],    ///@see http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#exist
            ['password_verified_at', 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Module::t('message', 'User ID'),
            'password_verified_at' => Module::t('message', 'Password Verified At'),
            'email_verified_at' => Module::t('message', 'Email Verified At'),
            'created_at' => Module::t('message', 'Created At'),
            'updated_at' => Module::t('message', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
