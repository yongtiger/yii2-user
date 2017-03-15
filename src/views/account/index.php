<?php ///[Yii2 uesr:account]

/**
 * @var $this yii\base\View
 * @var $oauths array
 */

use yii\helpers\Html;
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;
use yongtiger\authclient\widgets\AuthChoice;

$this->title = Module::t('message', 'Account Security');
$this->params['breadcrumbs'][] = ['label' => Module::t('message', 'My Account'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

///[Yii2 uesr:account oauth]
if (\Yii::$app->getModule('user')->enableOauth && \Yii::$app->get("authClientCollection", false)) {
    $this->registerCss(<<<CSS
.gray { 
    -webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%);
    filter: grayscale(100%);
    filter: gray;
}
CSS
    );
}

?>
<div class="account-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('message', 'Manage your personal account security information.') ?></p>

    <div class="row">
        <div class="col-lg-2">
            <label class="control-label"><?= Module::t('message', 'Username') ?></label>
        </div>
        <div class="col-lg-5">
            <p class="help-block">
                <?php if (empty(\Yii::$app->user->identity->username)): ?>
                    <?= '(' . Module::t('message', 'Username is not set') . ')' ?>
                <?php else: ?>
                    <?= \Yii::$app->user->identity->username ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-5">
            <?= Html::a(Module::t('message', empty(\Yii::$app->user->identity->username) ? 'Set': 'Change'), ['account/change', 'item' => 'username']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <label class="control-label"><?= Module::t('message', 'Email') ?></label>
        </div>
        <div class="col-lg-5">
            <p class="help-block">
                <?php if (empty(\Yii::$app->user->identity->email)): ?>
                    <?= '(' . Module::t('message', 'Email is not set') . ')' ?>
                <?php else: ?>
                    <?= \Yii::$app->user->identity->email ?>
                    <!--///[Yii2 uesr:verify]-->
                    <?php if (isset(\Yii::$app->user->identity->verify->email_verified_at)): ?>
                        <?= '<br />(' . Module::t('message', 'Last verified at:') . ' ' . date('Y-m-d H:i:s', \Yii::$app->user->identity->verify->email_verified_at) . ')' ?>
                    <?php else: ?>
                        <?= '<br />(' . Html::a(Module::t('message', 'Verify email'), ['token/send-token', 'type' => TokenHandler::SCENARIO_VERIFICATION]) . ')' ?>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-5">
            <?= Html::a(Module::t('message', empty(\Yii::$app->user->identity->email) ? 'Set': 'Change'), ['account/change', 'item' => 'email']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <label class="control-label"><?= Module::t('message', 'Password') ?></label>
        </div>
        <div class="col-lg-5">
            <p class="help-block">
                <!--///[Yii2 uesr:verify]-->
                <?php if (isset(\Yii::$app->user->identity->verify->password_verified_at)): ?>
                    *********
                    <?= '<br />(' . Module::t('message', 'Last updated at:') . ' ' . date('Y-m-d H:i:s', \Yii::$app->user->identity->verify->password_verified_at) . ')' ?>
                <?php else: ?>
                    <?= '(' . Module::t('message', 'Danger!') . ' ' . Module::t('message', 'Password is not set') . ')' ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-5">
            <?= Html::a(Module::t('message', isset(\Yii::$app->user->identity->verify->password_verified_at) ? 'Change': 'Set'), ['account/change', 'item' => 'password']) ?>
        </div>
    </div>

    <!--///[Yii2 uesr:account oauth]-->
    <?php if (\Yii::$app->getModule('user')->enableOauth && \Yii::$app->get("authClientCollection", false)): ?>
        <div class="row">
            <div class="col-lg-2">
                <label class="control-label"><?= Module::t('message', 'Oauth') ?></label>
            </div>
            <div class="col-lg-5">

                <?php $authAuthChoice = AuthChoice::begin(array_merge(\Yii::$app->getModule('user')->authChoiceWidgetConfig, [
                    'autoRender' => false,
                ])); ?>

                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <div class="row">
                        <div class="col-lg-2">
                            <?= Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName() . (in_array($client->id, $oauths) ? '': ' gray')]) ?>
                        </div>
                        <div class="col-lg-2">
                            <?php if (in_array($client->id, $oauths)): ?>
                                <?= Html::a(Module::t('message', 'Disconnect'), ['security/disconnect', 'provider' => $client->id]) ?>
                            <?php else: ?>
                                <?= Html::a(Module::t('message', 'Connect'), $authAuthChoice->createClientUrl($client)) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php AuthChoice::end(); ?>

            </div>

        </div>
    <?php endif; ?>

</div>
