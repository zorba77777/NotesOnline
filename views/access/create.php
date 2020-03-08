<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Access */
/** @var $users array */

$this->title = 'Предоставить доступ';
$this->params['breadcrumbs'][] = ['label' => 'Заметки', 'url' => ['/note/my']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users
    ]) ?>

</div>
