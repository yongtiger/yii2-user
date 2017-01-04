<?php
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/recovery/reset-password', 'token' => $user->password_reset_token]);
?>
<?= Module::t('user', 'Hello ') ?> <?= $user->username ?>,

<?= Module::t('user', 'Follow the link below to reset your password:') ?>

<?= $resetLink ?>
