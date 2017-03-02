<?php ///[Yii2 uesr:account]

/**
 * @var $this yii\base\View
 * @var $form yii\widgets\ActiveForm
 * @var $item string
 * @var $model yongtiger\user\models\ChangeUsernameForm, ChangeEmailForm or ChangePasswordForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yongtiger\user\Module;

$this->title = Module::t('message', 'Change') . Module::t('message', ucfirst($item));
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'Account'), 'url' => Url::to(['account/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-change">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('message', 'Please fill out the following fields:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'change-form',

                ///[Yii2 uesr:Ajax validation]
                'enableClientValidation' => \Yii::$app->getModule('user')->enableAccountChangeClientValidation,
                'enableAjaxValidation' => \Yii::$app->getModule('user')->enableAccountChangeAjaxValidation,
                'validateOnBlur' => \Yii::$app->getModule('user')->enableAccountChangeValidateOnBlur,
                ///disable validate on submit while using captcha & ajax!!!
                'validateOnSubmit' => !(
                    \Yii::$app->getModule('user')->enableAccountChangeAjaxValidation &&
                    \Yii::$app->getModule('user')->enableAccountChangeWithCaptcha
                ) && \Yii::$app->getModule('user')->enableAccountChangeValidateOnSubmit,

            ]); ?>
                
                <?php if (isset(\Yii::$app->user->identity->verify->password_verified_at) && \Yii::$app->getModule('user')->enableAccountChangeWithPassword): ?><!--///[Yii2 uesr:verify]-->
                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(Module::t('message', 'You must provide your account password when changing')) ?>
                <?php endif; ?>

                <?php if ($item === 'username'): ?>
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <?php elseif ($item === 'email'): ?>
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                <?php elseif ($item === 'password'): ?>
                    <?= $form->field($model,'newpassword')->passwordInput(['autofocus' => true]) ?>
                    <!--///[Yii2 uesr:repassword]-->
                    <?php if (\Yii::$app->getModule('user')->enableAccountChangePasswordWithRepassword): ?>
                        <?= $form->field($model,'repassword')->passwordInput() ?>
                    <?php endif; ?>
                <?php endif; ?>

                <!--///[Yii2 uesr:verifycode]-->
                <?php if (\Yii::$app->getModule('user')->enableAccountChangeWithCaptcha): ?>
                     <?= $form->field($model, 'verifyCode', [

                        'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                        'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html

                        ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!

                    ])->widget(\Yii::$app->getModule('user')->captchaActiveFieldWidget['class'], array_merge(\Yii::$app->getModule('user')->captchaActiveFieldWidget, [

                        ///captcha in module, e.g. `/user/account/captcha`
                        'captchaAction' => '/' . \Yii::$app->controller->module->id . '/account/captcha',  ///default is 'site/captcha'

                    ])) ?>
                <?php endif; ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('message', 'Save'), ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
