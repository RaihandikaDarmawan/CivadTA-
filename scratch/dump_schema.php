<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE', 'civad');
$keyName = "Tables_in_" . $dbName;

echo "CREATE DATABASE IF NOT EXISTS `$dbName`;\n";
echo "USE `$dbName`;\n\n";

foreach ($tables as $tableObj) {
    $tableName = $tableObj->$keyName;
    
    // Skip migrations table to keep it clean
    if ($tableName === 'migrations') {
        continue;
    }
    
    $createResult = DB::select("SHOW CREATE TABLE `$tableName`")[0];
    $createTableField = 'Create Table';
    echo $createResult->$createTableField . ";\n\n";
}
