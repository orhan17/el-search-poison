<?php

use ClickHouseDB\Client as ClickHouseClient;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/clickhouse-test', function (ClickHouseClient $clickhouse) {
    $result = $clickhouse->select('SELECT version() AS ver');
    return $result->rows();
});


//Route::get('/ch-create-table', function (ClickHouseClient $clickhouse) {
//    $createSQL = "
//        CREATE TABLE IF NOT EXISTS test_table (
//            id UInt64,
//            name String,
//            created_at DateTime
//        ) ENGINE = MergeTree
//        ORDER BY (id)
//    ";
//    $clickhouse->write($createSQL);
//    return "Table created!";
//});
//
//
//Route::get('/ch-insert', function (ClickHouseClient $clickhouse) {
//    // Вставляем строки
//    $data = [
//        [1, 'Alice', date('Y-m-d H:i:s')],
//        [2, 'Bob', date('Y-m-d H:i:s')],
//    ];
//    // Имя таблицы test_table, колонки по порядку
//    $clickhouse->insert('test_table', $data, ['id', 'name', 'created_at']);
//    return "Inserted!";
//});
//
//
//Route::get('/ch-select', function (ClickHouseClient $clickhouse) {
//    $sql = "SELECT * FROM test_table ORDER BY id";
//    $result = $clickhouse->select($sql);
//    // $result->count() - число строк
//    // $result->rows() - массив (по строкам)
//    return $result->rows();
//});
