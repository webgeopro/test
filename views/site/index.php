<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;

$this->title = 'Тестовое задание';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Тестовое задание</h1>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12"><?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                ]);

            ?></div>
        </div>

    </div>
</div>
