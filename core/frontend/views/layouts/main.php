<?php

/* @var $this \yii\web\View */
/* @var $content string */


use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\assets\BootboxAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
BootboxAsset::overrideSystemConfirm();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
        ['label' => 'О нас', 'url' => ['/site/about']],
//        ['label' => 'Контакты', 'url' => ['/site/contact']],
        ['label' => 'Блог', 'url' => ['/blog']],
    ];


    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/user/registration/register']];
        $menuItems[] = ['label' => 'Вход', 'url' => ['/user/security/login']];
    } else {

        if (Yii::$app->user->can('admin')) {
            $menuItems[] = [
                'label' => 'Приложения',
                'items' => [
                    ['label' => 'Каталог', 'url' => '/apps/list'],
                    ['label' => 'Создать новое', 'url' => '/apps/create']
                ]
            ];
            $menuItems[] = [
                'label' => 'Панель управления',
                'url' => ['/admin'],
                'linkOptions' => ['target' => '_blank'],
            ];
        } else {
            $menuItems[] = [
                'label' => 'Приложения',
                'url' => '/apps/list',
            ];
        }

        $menuItems[] = '<li>'
            . Html::beginForm(['/user/security/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
