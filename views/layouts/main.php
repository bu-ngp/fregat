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
    <title>Портал БУ "НГП"</title>
    <?php $this->head() ?>
</head>
<body>
<style>
    img.brandlogo {
        position: absolute;
        margin-left: -90px;
        margin-top: -10px;
        -webkit-filter: drop-shadow(2px 2px 0 #59010b) drop-shadow(-2px 2px 0 #59010b);
        filter: drop-shadow(2px 2px 0 #59010b) drop-shadow(-2px 2px 0 #59010b);
        height: 80px;
    }
</style>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    use kartik\icons\Icon;

    Icon::map($this);

    NavBar::begin([
        'brandLabel' => '<img class="brandlogo" src="' . Yii::$app->urlManager->baseUrl . '/images/logo.png">' . 'БУ "Нижневартовская городская поликлиника"',
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
    <!--   <div style="position: fixed; z-index: 10000; top: 5px; left: 120px;">
           <image src="/images/logo.png"
                  style="filter: drop-shadow(4px 3px 0 #59010b) drop-shadow(-4px 3px 0 #59010b); height: 80px;"></image>
       </div>-->
    <div class="container body-container">
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
            'homeLink' => [
                'url' => Yii::$app->homeUrl,
                'label' => '<span class="bc_lighter"></span><i class="glyphicon glyphicon-home"></i>',
                'encode' => false,
            ],
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
    <div class="buttonside" id="scrollupbutton" style="display: none;">
        <span>ВВЕРХ</span>
    </div>

    <a class="buttonside" id="backbutton"
       href="<?= Proc::GetPreviousURLBreadcrumbsFromSession() === Yii::$app->homeUrl ? '#' : Proc::GetPreviousURLBreadcrumbsFromSession() ?>"
       style="display: none;">
        <span>НАЗАД</span>
    </a>

    <div style="position: fixed; top: 100px; left: 10px; font-size: 35px; width: 150px; color: red;">
        <?= Yii::$app->db->dsn === "mysql:host=127.0.0.1;dbname=baseportal;charset=UTF8" ? "Тестовая база!" : "" ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Карпов Владимир, БУ "Нижневартовская городская поликлиника" <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
