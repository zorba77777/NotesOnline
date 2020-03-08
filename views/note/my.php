<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заметки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать заметку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'text:ntext',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],

            ['header' => 'Действия',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{share} {update} {delete}',
                'buttons' => [
                    'share' => function ($url, $model){
                        return Html::a(yii\bootstrap\Html::icon('share'), ['/access/create', 'id'=> $model->id], ['data-toggle'=>'tooltip','title' => 'Предоставить доступ']);
                    }

                ]

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>
