<?php ///[Yii2 uesr:account verify email]
use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/account/verify-email', 'key' => $user->activation_key]);
?>
<div class="activation">
    <p><?= Module::t('user', 'Hello ') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Module::t('user', 'Follow the link below to verify your email:') ?></p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
