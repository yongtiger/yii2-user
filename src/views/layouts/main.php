<?php ///[v0.18.4 (frontend user menus)]

/**
 * @var $this yii\base\View
 * @var $content string
 */

use yii\helpers\Html;

$menus = $this->context->module->menus;

foreach ($menus as $i => $menu) {
    $menus[$i]['active'] = strpos($this->context->route, trim($menu['url'][0], '/')) === 0;
}

?>

<?php $this->beginContent(\Yii::$app->getModule('user')->frontendLayout) ?>
<div class="row">
    <div class="col-sm-3">
        <div id="manager-menu" class="list-group">
            <?php
            foreach ($menus as $menu) {
                $label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
                    Html::tag('span', Html::encode($menu['label']), []);
                $active = $menu['active'] ? ' active' : '';
                echo Html::a($label, $menu['url'], [
                    'class' => 'list-group-item' . $active,
                ]);
            }
            ?>
        </div>
    </div>
    <div class="col-sm-9">
        <?= call_user_func([isset($this->params['alertClassName']) ? $this->params['alertClassName'] : 'common\\widgets\\Alert', 'widget']); ?>
        <?= $content ?>
    </div>
</div>
<?php $this->endContent(); ?>