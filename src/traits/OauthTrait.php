<?php ///[Yii2 uesr]

/**
 * Yii2 user
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\traits;

use Yii;
use yii\db\IntegrityException;
use yii\helpers\Url;
use yongtiger\user\models\Oauth;

/**
 * Trait OauthTrait
 *
 * Used of `RegistrationController` and `SecurityController` classes.
 *
 * @package yongtiger\user\traits
 */
trait OauthTrait
{
    /**
     * Inserts a new record to the oauth ActiveRecord.
     *
     * @throws IntegrityException if fails to save.
     */
    private function insertOauth($userId, $clientInfos)
    {
        $auth = new Oauth(['user_id' => $userId]);
        $auth->attributes = $clientInfos;   ///massive assignment @see http://www.yiiframework.com/doc-2.0/guide-structure-models.html#massive-assignment
        if (!$auth->save(false)) {
            throw new IntegrityException();
        }
    }

    /**
     * Sets oauth session and passes to the signup page.
     *
     * @see http://p2code.com/post/yii2-facebook-login-step-by-step-4
     * @see http://www.hafidmukhlasin.com/2014/10/29/yii2-super-easy-to-create-login-social-account-with-authclient-facebook-google-twitter-etc/
     *
     * @param \yongtiger\user\models\SignupForm $model
     * @param \yongtiger\authclient\clients\IAuth $client
     */
    private function setOauthSession($model, $clientInfos)
    {
        // Yii::$app->session['signup-form'] = $model; ///Note: Uncaught exception 'Exception' with message 'Serialization of 'Closure' is not allowed'
        Yii::$app->session['signup-form'] = ['username' => $model->username, 'email' => $model->email, 'errors' => $model->errors];///???????
        // Yii::$app->session['auth-client'] = $client; ///Note: Uncaught exception 'Exception' with message 'Serialization of 'Closure' is not allowed'
        Yii::$app->session['auth-client'] = $clientInfos;
        $this->action->successUrl = Url::to(['registration/signup']);
    }

    /**
     * Gets oauth session.
     *
     * @see http://p2code.com/post/yii2-facebook-login-step-by-step-4
     * @see http://www.hafidmukhlasin.com/2014/10/29/yii2-super-easy-to-create-login-social-account-with-authclient-facebook-google-twitter-etc/
     *
     * @return session
     */
    private function getOauthSession()
    {
        $oauthSession = ['signup-form' => Yii::$app->session['signup-form'], 'auth-client' => Yii::$app->session['auth-client']];
        unset(Yii::$app->session['signup-form'], Yii::$app->session['auth-client']);

        return $oauthSession;
    }
}