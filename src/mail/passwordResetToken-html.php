<?php
use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/recovery/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?= Module::t('user', 'Hello ') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Module::t('user', 'Follow the link below to reset your password:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
