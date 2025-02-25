<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\ReportForm $model */
/** @var array $tables */
/** @var array $columns */

$this->title = 'SQL Query Builder';
?>

    <div class="sql-query-form">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php Pjax::begin(['id' => 'prl-pjax']);?>

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'options' => [
                //'class' => 'form-control',
                'data' => ['pjax' => true]
            ],
        ]); ?>

        <?= $form->field($model, 'table')->dropDownList($tables, [
            'prompt' => 'Выберите таблицу',
            'id' => 'table-selector',
            /*[
                'attributes' => 'table',
                'value' => function ($data) {
                    return $data['table'] ? $data['table'] : 'Выберите таблицу';
                }

            ],*/
            /*'options' => [
                $model->table => ['Selected' => true] // Автоматический выбор текущего значения
            ]*/
        ]) ?>

        <?= $form->field($model, 'column')->dropDownList($columns, [
            'prompt' => 'Выберите колонку',
            'id' => 'multiple-select-field',
            'multiple' => true,
            'class' => 'form-select',
            'options' => [
                /*'class' => 'form-select',
                'tag' => null,*/
               // 'data-pjax' => 0,

            ]
        ]) ?>

        <?php /*= $form->field($model, 'column')->widget(\kartik\select2\Select2::class, [
            'data' => $columns,
            'language' => 'ru',

            'options' => [
                    'placeholder' => 'Выберите колонку',
                    'id' => 'column-selector',
                    'data-pjax' => 0,
                    'multiple' => true
                ],
            'pluginOptions' => [
                'allowClear' => true
            ],

        ]);*/   ?>

        <?= $form->field($model, 'condition')->textInput(['placeholder' => 'WHERE условия (например, id > 10)']) ?>
<br>
        <div class="form-group">
            <?= Html::submitButton('Выполнить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Экспорт в Excel', ['export', 'table' => $model->table, 'column' => $model->column, 'condition' => $model->condition], ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php  Pjax::end();?>


<?php
$this->registerJs("
    $('#table-selector').change(function () {
    event.preventDefault();
        //let table = $(this).select(option).text();
        let table = $('#table-selector option:selected').text();
        $.get('/report/get-columns', {table: table}, function (data) {
            $('#multiple-select-field').html(data);
        });
    });   
");
?>