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
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;
use yongtiger\user\Module;
use yongtiger\user\models\Oauth;

/**
 * This is the model class for table "{{%user}}".
 *
 * @package yongtiger\user\models
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = -1;  ///[Yii2 uesr:activation via email:INACTIVE]

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => static::STATUS_ACTIVE],
            ['status', 'in', 'range' => [static::STATUS_ACTIVE, static::STATUS_DELETED, static::STATUS_INACTIVE]],    ///[Yii2 uesr:activation via email:INACTIVE]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(Module::t('user', '"findIdentityByAccessToken" is not implemented.'));
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    ///[Yii2 uesr:oauth]
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauths()
    {
        return $this->hasMany(Oauth::className(), ['user_id' => 'id']);
    }

    ///[Yii2 uesr:verify]
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerify()
    {
        return $this->hasOne(Verify::className(), ['user_id' => 'id']);
    }

    ///[Yii2 uesr:oauth]
    /**
     * Finds user by Oauth provider and openid.
     *
     * @param string $provider
     * @param string $openid
     * @return static|null
     */
    public static function findByOauth($provider, $openid)
    {
        return static::find()
            ->joinwith('oauths')
            ->andWhere(['{{oauth}}.provider' => $provider])
            ->andWhere(['{{oauth}}.openid' => $openid])
            ->one();
    }
}
