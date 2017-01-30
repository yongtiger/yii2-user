<?php ///[Yii2 uesr:account verify email]
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/account/verify-email', 'key' => $user->activation_key]);
?>
<?= Module::t('user', 'Hello ') ?> <?= $user->username ?>,

<?= Module::t('user', 'Follow the link below to verify your email:') ?>

<?= $verifyLink ?>
