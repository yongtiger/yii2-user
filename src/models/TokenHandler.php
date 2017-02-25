<?php ///[Yii2 uesr:token]

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
use yii\db\IntegrityException;
use yongtiger\user\Module;

/**
 * Token Handler Model
 *
 * @package yongtiger\user\models
 * @property string $token
 * @property \yongtiger\user\models\User $user read-only user
 */
class TokenHandler extends Model
{
    const SCENARIO_ACTIVATION = 'activation';
    const SCENARIO_RECOVERY = 'recovery';
    const SCENARIO_VERIFICATION = 'verification';

    /**
     * @var string
     */
    public $token;

    /**
     * @var \yongtiger\user\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_ACTIVATION] = $scenarios[static::SCENARIO_RECOVERY] = $scenarios[static::SCENARIO_VERIFICATION] = $scenarios[static::SCENARIO_DEFAULT];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['token', 'required'],
            ['token', 'trim'],
            ['token', 'string', 'max' => 128],
            [
                'token',
                'exist',    ///@see http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#exist
                'targetClass' => User::className(),
                'filter' => ['status' => $this->scenario === static::SCENARIO_ACTIVATION ? User::STATUS_INACTIVE : User::STATUS_ACTIVE],
            ],
            ['token', 'validateKey'],
        ];
    }

    /**
     * Validates the token.
     *
     * This method serves as the inline validation for token.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateKey($attribute, $params)
    {
        if (!static::isValidKey($this->$attribute)) {
            $this->addError($attribute, Module::t('user', 'The token has been expired!'));
            ///remove the expired token
            $this->getUser()->token = null;
            $this->getUser()->save(false);
        }
    }

    /**
     * Finds user.
     *
     * @return User|null User object or null
     */
    public function getUser()
    {
        if ($this->_user === null) {

            $this->_user = User::findOne([
                'token' => $this->token,
                'status' => $this->scenario === static::SCENARIO_ACTIVATION ? User::STATUS_INACTIVE : User::STATUS_ACTIVE,
            ]);

        }
        return $this->_user;
    }

    ///[Yii2 uesr:activation via email:activation]
    /**
     * Activates user account.
     *
     * @return User|false the activated user model or false if activation fails
     */
    public function handle($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {

            ///Because activation is not used ActiveForm, so output errors by `setFlash()`.
            ///Traversing the two-dimensional array of errors. @see http://www.yiiframework.com/doc-2.0/yii-base-model.html#$errors-detail
            foreach ($this->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }

            return false;
        }

        if ($user = $this->getUser()) {

            ///[Yii2 uesr:verify]
            $user->verify->email_verified_at = time();  ///Note: MUST has current user's record in table verify! or get a exception 'Indirect modification of overloaded property yongtiger\user\models\User::$verify has no effect'
            if (!$user->verify->save(false)) {
                throw new IntegrityException();
            }

            $user->token = null;

            switch ($this->scenario) {
                case static::SCENARIO_ACTIVATION:
                    $this->getUser()->status = User::STATUS_ACTIVE;
                    $this->getUser()->generateAuthKey();

                    if ($this->getUser()->save(false)) {
                        Yii::$app->session->addFlash('success', Module::t('user', 'Your account has been successfully activated ...'));
                        return true;
                    }

                    Yii::$app->session->addFlash('error', Module::t('user', 'Your account has not been activated! Please try again.'));
                    return false;

                case static::SCENARIO_RECOVERY:
                    if ($this->getUser()->save(false)) {
                        Yii::$app->session->addFlash('success', Module::t('user', 'Please reset your password.'));
                        return true;
                    }

                    Yii::$app->session->addFlash('error', Module::t('user', 'Failed to reset password! Please try again.'));
                    return false;

                case static::SCENARIO_VERIFICATION:
                    if ($this->getUser()->save(false)) {
                        Yii::$app->session->addFlash('success', Module::t('user', 'Your email has been successfully verified.'));
                        return true;
                    }

                    Yii::$app->session->addFlash('error', Module::t('user', 'Your email has not been verified! Please try again.'));
                    return false;

                default:
                    return null;
            }
        }

        Yii::$app->session->addFlash('error', Module::t('user', 'Failed to find a user!'));
        return false;
    }

    /**
     * Generate a random key with time suffix.
     *
     * For example:
     * `1483947060_1483947060_Np9DM28poDl9x-3J0D9dolagcLNb8WmM`, it means never expired.
     * `1483947060_1483947999_Np9DM28poDl9x-3J0D9dolagcLNb8WmM`, it will expired at timestamp 1483947999.
     *
     * @param $duration integer The expiry time in seconds. Defaults to `0`, it means never expired.
     * @return string Random key
     */
    public static function generateExpiringRandomKey($duration = 0)
    {
        $key = Yii::$app->getSecurity()->generateRandomString();
        return time() . '_' . (time() + $duration) . '_' . $key;
    }

    /**
     * Finds out if token is valid.
     *
     * @param string $token Token that must be validated
     * @return bool|null bool if token is or not expired, null if token is empty
     */
    public static function isValidKey($token)
    {
        if (empty($token)) {
            return null;
        }

        list($createdTime, $expiryTime) = static::getKeyTime($token);

        if ($createdTime < $expiryTime) {
            return $expiryTime > time();
        }else{
            return true;
        }
    }

    /**
     * Gets created time of a token.
     *
     * @param string $token Token that must be validated
     * @return array|null [createdTime, expiryTime], null if token is empty
     */
    public static function getKeyTime($token)
    {
        if (empty($token)) {
            return null;
        }
        $parts = explode('_', $token);
        return [(int)$parts[0], (int)$parts[1]];
    }

    /**
     * Gets valid duration time of a token.
     *
     * @param string $token Token that must be validated
     * @return intger|null The valid duration time in seconds, null if token is empty, invalid if token is less than `0`
     */
    public static function getValidDuration($token)
    {
        if (empty($token)) {
            return null;
        }
        list($createdTime, $expiryTime) = static::getKeyTime($token);

        return $expiryTime - time();
    }
}