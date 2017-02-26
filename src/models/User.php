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

    ///[yii2-admin-boot_v0.5.1_f0.5.0_user_add_role_field]
    const ROLE_ADMIN = 'role_admin';
    const ROLE_SUPER_MODERATOR = 'role_super_moderator';
    const ROLE_MODERATOR = 'role_moderator';
    const ROLE_USER = 'role_user';
    const ROLES_BACKEND = [self::ROLE_ADMIN, self::ROLE_SUPER_MODERATOR, self::ROLE_MODERATOR];   ///[yii2-admin-boot_v0.5.5_f0.5.4_user_BUG#2_user_error_403]允许登陆后台的所有role
    ///[http://www.brainbook.cc]

    public $password;
    
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

    ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]以便在更新时，不验证密码password，只在创建时必须要求password
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username', 'password', 'email', 'role', 'status']; ///create的scenarios虽然与缺省的scenarios相同值，但规则中password在create时才是必须的！而update时不是必须。
        return $scenarios;
    }
    ///[http://www.brainbook.cc]

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => static::STATUS_ACTIVE],
            ['status', 'in', 'range' => [static::STATUS_ACTIVE, static::STATUS_DELETED, static::STATUS_INACTIVE]],    ///[Yii2 uesr:activation via email:INACTIVE]

            ///[yii2-admin-boot_v0.5.1_f0.5.0_user_add_role_field]
            ['role', 'default', 'value' => self::ROLE_USER],    ///[yii2-admin-boot_v0.5.2_f0.5.1_user_create_default_role]
            ['role', 'string', 'max' => 32],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_SUPER_MODERATOR, self::ROLE_MODERATOR, self::ROLE_USER]],
            ///[http://www.brainbook.cc]

            ///['auth_key', 'default', 'value' => strval(rand())],  ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]

            [
                [
                    'username',
                    ///'auth_key', ///因为_form表单中没有该字段，不做required验证！所以注释不注释掉都一样
                    ///'password_hash', ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]
                    'email',
                    ///'status', ///因为_form表单中没有status，如果提交表单时不填写则通不过验证，另外，上面的default并不真正设置值，所以必须注释掉！
                    ///'created_at', 'updated_at', ///因为_form表单中没有该字段，不做required验证！所以注释不注释掉都一样
                ], 'required',
            ],

            ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]以便在更新时，不验证密码password，只在创建时必须要求password
            [
                [
                    'password',
                ], 'required', 'on' => 'create'
            ],
            ///[http://www.brainbook.cc]

            [
                [
                    'status',
                    ///'created_at', 'updated_at'  ///因为_form表单中没有该字段，不做required验证！所以注释不注释掉都一样
                ], 'integer'
            ],

            ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]
            [
                [
                    'username',
                ], 'string', 'max' => 20
            ],
                    ///'password_hash', ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]
                    ///'password_reset_token', ///因为_form表单中没有该字段，不做required验证！所以注释不注释掉都一样

            [
                [
                    'email'
                ], 'string', 'max' => 255
            ],
            // [
            //     [
            //         'password',///////////////////
            //     ], 'string', 'min' => 6, 'max' => 20
            // ],
            [['password'],'match','pattern'=>'/^[a-zA-Z0-9_\-\~\!\@\#\$\%\^\&\*\+\=\?\|\{\}\[\]\(\)]{6,20}$/','message'=>'只能含6-20位数字、大小写字母和“-”、“_”等特殊字符'],  ///skipOnEmpty缺省为true，所以当没有输入密码时，忽略该项验证规则
            ///[http://www.brainbook.cc]

            ///[['auth_key'], 'string', 'max' => 32],  ///因为_form表单中没有该字段，不做required验证！所以注释不注释掉都一样
            [['username'], 'unique'],
            [['email'], 'unique'],
            ///[['password_reset_token'], 'unique'],   ///因为_form表单中没有该字段，不做required验证！所以注释不注释掉都一样
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            ///[yii2-admin-boot_v0.5.0_f0.4.6_user_fix_rbac]还原代码！无需注释掉'created_at', 'updated_at'
            'id' => 'ID',
            'username' => 'Username',
            //////[yii2-admin-boot_v0.5.17_f0.5.16_user_password]
            ///'auth_key' => 'Auth Key',
            ///'password_hash' => 'Password Hash',
            'password' => 'Password',   ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]
            ///'password_reset_token' => 'Password Reset Token',
            //////[http://www.brainbook.cc]
            'email' => 'Email',
            'role' => 'Role',   ///[yii2-admin-boot_v0.5.1_f0.5.0_user_add_role_field]
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            ///[http://www.brainbook.cc]
        ];
    }

    ///[yii2-admin-boot_v0.4.3_f0.4.2_user_datetime]
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $time = time();
            if($this->isNewRecord)
            {
                $this->created_at = $time;
            }
            $this->updated_at = $time;

            ///[yii2-admin-boot_v0.5.17_f0.5.16_user_password]
            if($this->password){
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            ///[http://www.brainbook.cc]

            return true;
        }
        return false;
    }
    ///[http://www.brainbook.cc]

    ///[yii2-admin-boot_v0.5.2_f0.5.1_user_create_default_role]
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert) {
            //这里是新增数据
            $manager = Yii::$app->getAuthManager();
            $item = $manager->getRole(self::ROLE_USER);
            $manager->assign($item, $this->id);

        } else {
            //这里是更新数据
        }

    }
    ///[http://www.brainbook.cc]

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
