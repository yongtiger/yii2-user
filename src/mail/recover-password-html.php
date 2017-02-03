<?php ///[Yii2 uesr:token  recover password]
use yii\helpers\Html;
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['user/token/handle-token', 'type' => TokenHandler::SCENARIO_RECOVERY, 'token' => $user->token]);
?>
<div class="recover-password">
    <p><?= Module::t('user', 'Hello ') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Module::t('user', 'Follow the link below to reset your password:') ?></p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
