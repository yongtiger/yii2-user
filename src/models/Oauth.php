<?php ///[Yii2 uesr:oauth]

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
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%oauth}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $openid
 * @property string $email
 * @property string $fullname
 * @property string $firstname
 * @property string $lastname
 * @property int $gender
 * @property string $language
 * @property string $avatar
 * @property string $link
 * @property integer $created_at
 * @property integer $updated_at
 * @property User $user
 */
class Oauth extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth}}';
    }

    /**
     * @inheritdoc
     * @return array mixed
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
            [['user_id', 'provider', 'openid'], 'required'],
            [['user_id', 'gender', 'created_at', 'updated_at'], 'integer'],
            [['provider', 'openid', 'email', 'fullname', 'firstname', 'lastname', 'language', 'avatar', 'link'], 'string', 'max' => 255],
            [['provider', 'openid'], 'unique', 'targetAttribute' => ['provider', 'openid'], 'message' => 'The combination of Provider and Openid has already been taken.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],    ///@see http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#exist
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
