<?php ///[Yii2 uesr:account]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

/**
 * @var $this yii\base\View
 * @var $oauths array
 */

use Yii;
use yii\helpers\Html;
use yongtiger\authclient\widgets\AuthChoice;
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

$this->title = Module::t('user', 'Account');
$this->params['breadcrumbs'][] = $this->title;

///[Yii2 uesr:account oauth]
if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)) {
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
<div class="registration-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Module::t('user', 'Manage your personal account information.') ?></p>

    <div class="row">
        <div class="col-lg-2">
            <label class="control-label"><?= Module::t('user', 'Username') ?></label>
        </div>
        <div class="col-lg-5">
            <p class="help-block">
                <?php if (empty(Yii::$app->user->identity->username)): ?>
                    <?= '(' . Module::t('user', 'Username is not set') . ')' ?>
                <?php else: ?>
                    <?= Yii::$app->user->identity->username ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-5">
            <?= Html::a(Module::t('user', empty(Yii::$app->user->identity->username) ? 'Set': 'Change'), ['account/change', 'item' => 'username']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <label class="control-label"><?= Module::t('user', 'Email') ?></label>
        </div>
        <div class="col-lg-5">
            <p class="help-block">
                <?php if (empty(Yii::$app->user->identity->email)): ?>
                    <?= '(' . Module::t('user', 'Email is not set') . ')' ?>
                <?php else: ?>
                    <?= Yii::$app->user->identity->email ?>
                    <!--///[Yii2 uesr:verify]-->
                    <?php if (isset(Yii::$app->user->identity->verify->email_verified_at)): ?>
                        <?= '<br />(' . Module::t('user', 'Last verified at:') . ' ' . date('Y-m-d H:i:s', Yii::$app->user->identity->verify->email_verified_at) . ')' ?>
                    <?php else: ?>
                        <?= '<br />(' . Html::a(Module::t('user', 'Verify email'), ['token/send-token', 'type' => TokenHandler::SCENARIO_VERIFICATION]) . ')' ?>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-5">
            <?= Html::a(Module::t('user', empty(Yii::$app->user->identity->email) ? 'Set': 'Change'), ['account/change', 'item' => 'email']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <label class="control-label"><?= Module::t('user', 'Password') ?></label>
        </div>
        <div class="col-lg-5">
            <p class="help-block">
                <!--///[Yii2 uesr:verify]-->
                <?php if (isset(Yii::$app->user->identity->verify->password_verified_at)): ?>
                    *********
                    <?= '<br />(' . Module::t('user', 'Last updated at:') . ' ' . date('Y-m-d H:i:s', Yii::$app->user->identity->verify->password_verified_at) . ')' ?>
                <?php else: ?>
                    <?= '(' . Module::t('user', 'Danger!') . ' ' . Module::t('user', 'Password is not set') . ')' ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-5">
            <?= Html::a(Module::t('user', isset(Yii::$app->user->identity->verify->password_verified_at) ? 'Change': 'Set'), ['account/change', 'item' => 'password']) ?>
        </div>
    </div>

    <!--///[Yii2 uesr:account oauth]-->
    <?php if (Yii::$app->getModule('user')->enableOauth && Yii::$app->get("authClientCollection", false)): ?>
        <div class="row">
            <div class="col-lg-2">
                <label class="control-label"><?= Module::t('user', 'Oauth') ?></label>
            </div>
            <div class="col-lg-5">

                <?php $authAuthChoice = AuthChoice::begin(array_merge(Yii::$app->getModule('user')->authChoiceWidgetConfig, [
                    'autoRender' => false,
                ])); ?>

                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <div class="row">
                        <div class="col-lg-2">
                            <?= Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName() . (in_array($client->id, $oauths) ? '': ' gray')]) ?>
                        </div>
                        <div class="col-lg-2">
                            <?php if (in_array($client->id, $oauths)): ?>
                                <?= Html::a(Module::t('user', 'Disconnect'), ['security/disconnect', 'provider' => $client->id]) ?>
                            <?php else: ?>
                                <?= Html::a(Module::t('user', 'Connect'), $authAuthChoice->createClientUrl($client)) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php AuthChoice::end(); ?>

            </div>

        </div>
    <?php endif; ?>

</div>
