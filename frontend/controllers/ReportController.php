<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\ReportForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{

    public function actionIndex()
    {
        $model = new ReportForm();
        $tables = $model->getTables();
        $columns = [];
        //print_r($columns);die();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            echo "<pre>";
            print_r($model);
            echo "</pre>";die();

            $data = $model->executeQuery();

            return $this->render('index', compact('model', 'tables', 'columns', 'data'));
        }

        return $this->render('index', compact('model', 'tables', 'columns'));
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionGetColumns($table)
    {
        if (Yii::$app->request->isAjax && isset($table)) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $model = new ReportForm();
            $columns = $model->getColumns($table);
             return Html::renderSelectOptions(null, $columns);
            //return Html::renderSelectOptions(null, ArrayHelper::map(array_combine($columns, $columns), '0', '0'));
        }else{
            throw new NotFoundHttpException('Наименования таблицы не найдено', 420);
        }

    }

    public function actionExport($table, $column, $condition = null)
    {
        $model = new ReportForm();
        $model->table = $table;
        $model->column = $column;
        $model->condition = $condition;

        $data = $model->executeQuery();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', $column);

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A{$row}", $item[$column]);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'export.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename={$filename}");
        $writer->save('php://output');
        exit;
    }

}