<?php
use Medoo\Medoo;

require "vendor/autoload.php";

$nama_database = "naive-bayes";
$username_database = "root";
$password_database = "";
$tipe_database = "mysql";
$hostname_database = "localhost";

$db = new Medoo([
    'database_type' => $tipe_database,
    'database_name' => $nama_database,
    'server' => $hostname_database,
    'username' => $username_database,
    'password' => $password_database
]);