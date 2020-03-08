<?php
/**
 * Created by PhpStorm.
 * User: timur
 * Date: 24.12.17
 * Time: 2:32
 */


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

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'text:ntext',

            [
                'label' => 'Предоставлено пользователю',
                'attribute' => 'creator_id',
                'value' => function ($model) {
                    return $model->getAccesses()->one()->user->name;
                }

            ],

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],

            ['header' => 'Отозвать доступ',
             'class' => 'yii\grid\ActionColumn',
             'template' => '{unshare}',
             'buttons' => [
                 'unshare' => function ($url, $model){
                     return Html::a(yii\bootstrap\Html::icon('remove'),
                         ['/access/delete', 'id'=> $model->getAccesses()->one()->id],
                         ['data' => [
                             'confirm' => 'Вы уверены что хотите отозвать доступ для этого пользователя',
                             'method' => 'post',
                         ],]);
                 }
             ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>