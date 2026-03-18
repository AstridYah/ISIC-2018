<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

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
        'brandLabel' => 'International Symposium on Intelligent Computing Systems 2018',
        'brandUrl' => 'https://www.isics-symposium.org/registration',
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
			[
				'label' => 'Registrations',
				'url' => ['/registration/index'],
				'visible' => !Yii::$app->user->isGuest,
			],
			[
				'label' => 'Registration Type',
				'url' => ['/registration-type/index'],
				'visible' => !Yii::$app->user->isGuest,
			],
			[
				'label' => 'Registration Code',
				'url' => ['/registration-code/index'],
				'visible' => !Yii::$app->user->isGuest,
			],
			[
				'label' => 'Users',
				'url' => ['/user/index'],
				'visible' => !Yii::$app->user->isGuest,
			],
			[
				'label' => Yii::$app->user->isGuest ? 'Logout' : 'Logout (' . Yii::$app->user->identity->username . ')',
				'url' => ['/site/logout'],
				'linkOptions' => ['data-method' => 'post'],
				'visible' => !Yii::$app->user->isGuest,
			],
            
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ISICS <?= date('Y') ?></p>

        <!--<p class="pull-right"> 
		
		</p>!-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
