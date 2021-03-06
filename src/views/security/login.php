<?php ///[Yii2 uesr]

/**
 * @var $this yii\base\View
 * @var $form yii\widgets\ActiveForm
 * @var $model yongtiger\user\models\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

$this->title = Module::t('message', 'Login');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(".site-login { width: 360px; margin: 7% auto; }");

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('message', 'Please fill out the following fields:') ?></p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form',

                ///[Yii2 uesr:Ajax validation]
                'enableClientValidation' => \Yii::$app->getModule('user')->enableLoginClientValidation,
                'enableAjaxValidation' => \Yii::$app->getModule('user')->enableLoginAjaxValidation,
                'validateOnBlur' => \Yii::$app->getModule('user')->enableLoginValidateOnBlur,
                ///disable validate on submit while using captcha & ajax!!!
                'validateOnSubmit' => !(
                    \Yii::$app->getModule('user')->enableLoginAjaxValidation &&
                    \Yii::$app->getModule('user')->enableLoginWithCaptcha
                ) && \Yii::$app->getModule('user')->enableLoginValidateOnSubmit,

            ]); ?>

                <!--///[Yii2 uesr:login with username or email]-->
                <?php if (\Yii::$app->getModule('user')->enableLoginWithUsername && \Yii::$app->getModule('user')->enableLoginWithEmail || !empty(\Yii::$app->request->getBodyParam('LoginForm')['usernameOrEmail'])): ?>
                    <?= $form->field($model, 'usernameOrEmail')->textInput(['autofocus' => true]) ?>
                <?php else: ?>

                    <?php if (\Yii::$app->getModule('user')->enableLoginWithUsername): ?>
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                    <?php endif; ?>

                    <?php if (\Yii::$app->getModule('user')->enableLoginWithEmail): ?>
                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if (\Yii::$app->getModule('user')->enableLoginWithUsername || \Yii::$app->getModule('user')->enableLoginWithEmail): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <!--///[v0.9.7 (backend:enableRecoveryPassword)]-->
                    <?php if (\Yii::$app->getModule('user')->enableRecoveryPassword): ?>
                        <div style="color:#999;margin:1em 0">
                            <?= Module::t('message', 'If you forgot your password you can [{reset it}].', ['reset it' => Html::a(Module::t('message', 'reset it'), ['token/send-token', 'type' => TokenHandler::SCENARIO_RECOVERY])]) ?>
                        </div>
                    <?php endif; ?>

                    <!--///[Yii2 uesr:verifycode]-->
                    <?php if (\Yii::$app->getModule('user')->enableLoginWithCaptcha): ?>
                        <?= $form->field($model, 'verifyCode', [

                            'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                            'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html

                            ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!

                        ])->widget(\Yii::$app->getModule('user')->captchaActiveFieldWidget['class'], array_merge(\Yii::$app->getModule('user')->captchaActiveFieldWidget, [

                            ///captcha in module, e.g. `/user/security/captcha`
                            'captchaAction' => '/' . \Yii::$app->controller->module->id . '/security/captcha',  ///default is 'site/captcha'

                        ])) ?>

                    <?php endif; ?>
                <?php endif; ?>

                <?php if (\Yii::$app->user->enableAutoLogin): ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <?php endif; ?>

                <?php if (\Yii::$app->getModule('user')->enableLoginWithUsername || \Yii::$app->getModule('user')->enableLoginWithEmail): ?>
                    <div class="form-group">
                        <?= Html::submitButton(Module::t('message', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                <?php endif; ?>

            <?php ActiveForm::end(); ?>

            <?php if (\Yii::$app->getModule('user')->enableOauth && \Yii::$app->get("authClientCollection", false)): ?>

                <?= yongtiger\authclient\widgets\AuthChoice::widget(\Yii::$app->getModule('user')->authChoiceWidgetConfig) ?>

            <?php endif; ?>

        </div>
    </div>
</div>
