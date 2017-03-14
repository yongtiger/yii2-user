<?php ///[Yii2 uesr:status]

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
use yii\behaviors\TimestampBehavior;
use yongtiger\user\Module;

/**
 * This is the model class for table "{{%user_status}}".
 *
 * @property integer $user_id
 * @property string $registration_ip
 * @property string $last_login_ip
 * @property integer $last_login_at
 * @property integer $banned_at
 * @property string $banned_reason
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_status}}';
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
            [['user_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'last_login_at', 'banned_at', 'created_at', 'updated_at'], 'integer'],
            [['registration_ip', 'last_login_ip', 'banned_reason'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Module::t('message', 'User ID'),
            'registration_ip' => Module::t('message', 'Registration IP'),
            'last_login_ip' => Module::t('message', 'Last Login IP'),
            'last_login_at' => Module::t('message', 'Last Login At'),
            'banned_at' => Module::t('message', 'Banned At'),
            'banned_reason' => Module::t('message', 'Banned Reason'),
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
