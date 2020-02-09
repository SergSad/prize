<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

use yii\helpers\Url; ?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p><a class="btn btn-lg btn-success" href="<?= Url::toRoute('/site/generate')?>">Получить приз</a></p>
    </div>
</div>
