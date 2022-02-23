<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__."/request.php";
require __DIR__."/response.php";
require __DIR__."/db.php";
require __DIR__."/function.php";
if (!isset($_SESSION)) {
    session_name('KREA_ALUMNI');
    session_start();
}
date_default_timezone_set("Asia/Kolkata");
$CURRENT_DATE = date("Y-m-d h:i:s");

$DB = new CRUD;