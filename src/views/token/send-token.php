<?php ///[Yii2 uesr:token]

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model yongtiger\user\models\PasswordResetRequestForm 
 * @var $type string
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yongtiger\user\Module;

switch ($type) {
    case 'activation':
        $this->title = Module::t('message', 'Send activation email');
        $this->params['breadcrumbs'][] = $this->title;
        $desc = Module::t('message', 'Please fill out your registration email. A link to activation will be sent there.');
        break;
    case 'recovery':
        $this->title = Module::t('message', 'Request password reset');
        $this->params['breadcrumbs'][] = $this->title;
        $desc = Module::t('message', 'Please fill out your registration email. A link to reset password will be sent there.');
        break;
    case 'verification':
        $this->title = Module::t('message', 'Verify email');
        $this->params['breadcrumbs'][] = ['label' => Module::t('message', 'Account'), 'url' => Url::to(['account/index'])];
        $this->params['breadcrumbs'][] = $this->title;
        $desc = Module::t('message', 'Please fill out your registration email. A link to verify email will be sent there.');
        break;
    default:
        return;
}

?>
<div class="<?= $type ?>">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= $desc ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => $type . '-form',

                ///[Yii2 uesr:Ajax validation]
                'enableClientValidation' => \Yii::$app->getModule('user')->enableSendTokenClientValidation,
                'enableAjaxValidation' => \Yii::$app->getModule('user')->enableSendTokenAjaxValidation,
                'validateOnBlur' => \Yii::$app->getModule('user')->enableSendTokenValidateOnBlur,
                ///disable validate on submit while using captcha & ajax!!!
                'validateOnSubmit' => !(
                    \Yii::$app->getModule('user')->enableSendTokenAjaxValidation && 
                    \Yii::$app->getModule('user')->enableSendTokenWithCaptcha
                ) && \Yii::$app->getModule('user')->enableSendTokenValidateOnSubmit,

            ]); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <!--///[Yii2 uesr:verifycode]-->
                <?php if (\Yii::$app->getModule('user')->enableSendTokenWithCaptcha): ?>
                    <?= $form->field($model, 'verifyCode', [

                        'enableClientValidation' => false,  ///always disable client validation in captcha! Otherwise 'testLimit' of captcha will be invalid, and thus lead to attack. Also 'validateOnBlur' will be set false.
                        'enableAjaxValidation'=>false,     ///always disable Ajax validation. Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically. @see http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html
                        
                        ///also need to disable validate on ActiveForm submit while using captcha & ajax!!!
                        
                    ])->widget(\Yii::$app->getModule('user')->captchaActiveFieldWidget['class'], array_merge(\Yii::$app->getModule('user')->captchaActiveFieldWidget, [

                        ///captcha in module, e.g. `/user/token/captcha`
                        'captchaAction' => '/' . \Yii::$app->controller->module->id . '/token/captcha',  ///default is 'site/captcha'

                    ])) ?>
                <?php endif; ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('message', 'Send'), ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
