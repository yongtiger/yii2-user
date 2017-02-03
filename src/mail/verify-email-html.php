<?php ///[Yii2 uesr:token verify email]
use yii\helpers\Html;
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['user/token/handle-token', 'type' => TokenHandler::SCENARIO_VERIFICATION, 'token' => $user->token]);
?>
<div class="verify-email">
    <p><?= Module::t('user', 'Hello ') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Module::t('user', 'Follow the link below to verify your email:') ?></p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
