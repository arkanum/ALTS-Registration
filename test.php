<?php
    ini_set('display_errors', 'On');
    
$db = new SQLite3('mysqlitedb.db');

//$db->exec('CREATE TABLE foo (bar STRING)');
//$db->exec("INSERT INTO foo (bar) VALUES ('rogan')");

$query = $db->query('SELECT * FROM foo WHERE bar="rogan"');
$result = $query->fetchArray();
var_dump($result);
//$db->close();
?>