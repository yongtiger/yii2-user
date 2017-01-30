<?php ///[Yii2 uesr:activation via email:signup]
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$activateLink = Yii::$app->urlManager->createAbsoluteUrl(['user/registration/activate', 'key' => $user->activation_key]);
?>
<?= Module::t('user', 'Hello ') ?> <?= $user->username ?>,

<?= Module::t('user', 'Follow the link below to activate your account:') ?>

<?= $activateLink ?>
