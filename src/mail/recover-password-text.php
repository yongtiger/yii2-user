<?php ///[Yii2 uesr:token recover password]
use yongtiger\user\Module;
use yongtiger\user\models\TokenHandler;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['user/token/handle-token', 'type' => TokenHandler::SCENARIO_RECOVERY, 'token' => $user->token]);
?>
<?= Module::t('message', 'Hello ') ?> <?= $user->username ?>,

<?= Module::t('message', 'Follow the link below to reset your password:') ?>

<?= $link ?>
