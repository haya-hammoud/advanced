<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\CmsValues */

$this->title = $model->cms_values_id;
$this->params['breadcrumbs'][] = ['label' => 'Cms Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-values-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cms_values_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cms_values_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cms_values_id',
            'cms_item_id',
            'cms_category_field_id',
            'cms_value:ntext',
        ],
    ]) ?>

</div>
