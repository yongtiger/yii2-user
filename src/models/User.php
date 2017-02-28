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
 * @property string $password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = -1;  ///[yii2-uesr:activation via email:INACTIVE]

    ///[yii2-user:role]
    const ROLE_ADMIN = 'role_admin';
    const ROLE_SUPER_MODERATOR = 'role_super_moderator';
    const ROLE_MODERATOR = 'role_moderator';

    ///[yii2-user:password]password must be required to verify at `create`, and has not to be verified at `update`
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_CREATE = 'create';

    /**
     * @var string password
     */
    public $password;

    /**
     * Memory cache of user roles
     * @var array
     */
    private $_roles;
    
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
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_CREATE] = ['username', 'password', 'email', 'status']; ///[yii2-user:password]password must be required to verify at `create`, and has not to be verified at `update`
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ///[Yii2 uesr:username]User name verification
            ['username', 'required'],
            ['username', 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            //The unicode range of Chinese characters is: 0x4E00~0x9FA5. This range also includes Chinese, Japanese and Korean characters
            //  i   Indicates to match both uppercase and lowercase
            //  u   Indicates to match by unicode (utf-8), mainly for multi-byte characters such as Chinese characters
            //  w   Indicates to match alphabetic, numeric, or underscore characters
            //  \x  Ignore whitespace
            //[(\x{4E00}-\x{9FA5})a-zA-Z]+  The character starts with a Chinese character or letter and appears 1 to n times
            //[(\x{4E00}-\x{9FA5})\w]*      Chinese characters underlined alphabet, there 0-n times
            ['username', 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-z]+[(\x{4E00}-\x{9FA5})\w]*$/iu', 'message' => Module::t('user', 'The username only contains letters ...')],
            ['username', 'unique'],

            ///[yii2-uesr:password]
            ['password', 'required', 'on' => 'create'],   ///[yii2-user:password]password must be required to verify at `create`, and has not to be verified at `update`
            ['password', 'string', 'min' => 6],
            ['password', 'match', 'pattern' => '/^[a-zA-Z0-9_\-\~\!\@\#\$\%\^\&\*\+\=\?\|\{\}\[\]\(\)]{6,20}$/', 'message' => Module::t('user', 'The password only contains letters ...')],  ///skipOnEmpty缺省为true，所以当没有输入密码时，忽略该项验证规则

            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique'],

            ///[yii2-uesr:activation via email:INACTIVE]
            ['status', 'default', 'value' => static::STATUS_ACTIVE],
            ['status', 'in', 'range' => [static::STATUS_ACTIVE, static::STATUS_DELETED, static::STATUS_INACTIVE]],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Module::t('user', 'Username'),
            'password' => Module::t('user', 'Password'),
            'email' => Module::t('user', 'Email'),
            'status' => Module::t('user', 'Status'),
            'created_at' => Module::t('user', 'Created At'),
            'updated_at' => Module::t('user', 'Updated At'),
            'created_date_range' => Module::t('user', 'Created Date Range'),
            'updated_date_range' => Module::t('user', 'Updated Date Range'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            ///[yii2-user:password]password must be required to verify at `create`, and has not to be verified at `update`
            if($this->password){
                $this->setPassword($this->password);
                $this->generateAuthKey();
            }
            ///[http://www.brainbook.cc]

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert) {

            ///[yii2-uesr:verify]
            $this->link('verify', new Verify(['password_verified_at' => time()]));

            ///[yii2-uesr v0.13.1 (user link profile)]
            $this->link('profile', new Profile());

        } else {
            // ...
        }

    }

    ///[yii2-user v0.12.0 (add role methods to user model)]
    /**
     * Gets user roles.
     *
     * @return array
     */
    public function getRoles()
    {
        if ($this->_roles === null) {
            $manager = Yii::$app->getAuthManager();
            $this->_roles = array_keys($manager->getAssignments($this->id));
        }
        return $this->_roles;
    }

    /**
     * Set user roles.
     *
     * @param array $roles
     * @return Assignments|false the role assignment information.
     */
    public function setRoles($roles)
    {
        $manager = Yii::$app->getAuthManager();
        if ($manager->revokeAll($this->id)) {   ///??????array_diff
            $ret = [];
            foreach ($roles as $role) {
                $ret[] = $manager->assignRole($role);
            }
            return $ret;
        }
        return false;
    }

    /**
     * Assigns a role to a this model.
     *
     * @param Role $role
     * @return Assignment the role assignment information.
     * @throws \Exception if the role has already been assigned to the user
     */
    public function assignRole($role)
    {
        $manager = Yii::$app->getAuthManager();
        try {
            $ret = $manager->assign($role, $this->id);
        } catch (\Exception $exc) {
            // Yii::error($exc->getMessage(), __METHOD__);  ///ignore exceptions
        }
        return $ret;
    }

    /**
     * Revokes a role from this model.
     * @param Role $role
     * @return bool whether the revoking is successful
     */
    public function revokeRole($role)
    {
        $manager = Yii::$app->getAuthManager();
        return $manager->revoke($role, $this->id);
    }
    ///[http://www.brainbook.cc]

    ///[yii2-user:IdentityInterface]
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
    ///[http://www.brainbook.cc]

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

    ///[yii2-uesr:oauth]
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOauths()
    {
        return $this->hasMany(Oauth::className(), ['user_id' => 'id']);
    }

    ///[yii2-uesr:verify]
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerify()
    {
        return $this->hasOne(Verify::className(), ['user_id' => 'id']);
    }

    ///[yii2-uesr v0.13.1 (user link profile)]
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    ///[yii2-uesr:oauth]
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
