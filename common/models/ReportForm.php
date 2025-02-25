<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

class ReportForm extends Model
{
    public $table;
    public $column;
    public $condition;

    public function rules()
    {
        return [
            [['table', 'column'], 'required'],
            [['table','condition'], 'string'],
            [['column'], 'safe'],
        ];
    }

    public function getTables()
    {
        return Yii::$app->db->schema->getTableNames();
    }

    public function getColumns($table)
    {
        return Yii::$app->db->schema->getTableSchema($table)->columnNames;
    }

    public function executeQuery()
    {
        if (!$this->validate()) {
            return [];
        }

        $query = (new Query())->select([$this->column])->from($this->table);

        if (!empty($this->condition) && strpos($this->condition, 'WHERE') !== false) {
            $query->where($this->condition);
        }

        return $query->all();
    }
}