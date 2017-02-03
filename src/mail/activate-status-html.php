<?php ///[Yii2 uesr:token activate status]
use yii\helpers\Html;
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['user/token/handle-token', 'type' => TokenHandler::SCENARIO_ACTIVATION, 'token' => $user->token]);
?>
<div class="activate-status">
    <p><?= Module::t('user', 'Hello ') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Module::t('user', 'Follow the link below to activate your account:') ?></p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
