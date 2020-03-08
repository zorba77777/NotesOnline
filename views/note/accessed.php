<?php
/**
 * Created by PhpStorm.
 * User: timur
 * Date: 24.12.17
 * Time: 5:02
 */


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $users array */
/* @var $searchModel app\models\search\NoteSearch */

$this->title = 'Заметки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'text:ntext',

            [
                'filter' => $users,
                'attribute' => 'creator_id',
                'value' => function($model) {
                    return $model->creator->name;
                }

            ],

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],


        ],
    ]); ?>
<?php Pjax::end(); ?></div>