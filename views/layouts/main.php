<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\Session;
use app\func\Proc;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => 'БУ "Нижневартовская городская поликлиника"',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => array_merge([
                    ['label' => 'Главная', 'url' => ['/site/index']],
                        ], Proc::GetMenuButtons($this),
                        // Yii::$app->user->can('Administrator') ? [['label' => 'Фрегат', 'url' => ['Fregat/fregat/index']]] : [],
                        [Yii::$app->user->isGuest ?
                            ['label' => 'Вход', 'url' => ['/site/login']] :
                            [
                        'label' => 'Выход (' . Yii::$app->user->identity->auth_user_fullname . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ]]),
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?php
                /*   $controller = Yii::$app->controller;
                  $default_controller = Yii::$app->defaultRoute;
                  $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;

                  if (!$isHome) {
                  NavBar::begin([
                  'options' => [
                  'class' => 'navbar-default container-fluid',
                  ],
                  ]);
                  echo Nav::widget([
                  'options' => ['class' => 'navbar-nav'],
                  'items' => array_merge(
                  Yii::$app->user->can('Administrator') ? [['label' => 'Материальные ценности', 'url' => ['Fregat/fregat/index']]] : [],
                  Yii::$app->user->can('Administrator') ? [['label' => 'Настройки', 'url' => ['Fregat/fregat/config']]] : []
                  ),
                  ]);
                  NavBar::end();
                  } */
                ?>

                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>

                <?php
                if (!isset($this->params['breadcrumbs'])) {
                    $session = new Session;
                    $session->open();
                    $session->remove('breadcrumbs');
                    $session->close();
                }
                ?>
                <?= $content ?>
            </div>
            <div id="scrollupbutton" style="display: none;">
                <span>ВВЕРХ</span>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Карпов Владимир, БУ "Нижневартовская городская поликлиника"  <?= date('Y') ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
