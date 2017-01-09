<?php ///[Yii2 uesr:activation via email:signup]
use yii\helpers\Html;
use yongtiger\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/registration/activate', 'key' => $user->activation_key]);
?>
<div class="activation">
    <p><?= Module::t('user', 'Hello ') ?> <?= Html::encode($user->username) ?>,</p>

    <p><?= Module::t('user', 'Follow the link below to activate your account:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
