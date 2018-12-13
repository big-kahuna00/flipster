<?php
/**
 * Created by PhpStorm.
 * User: big_k
 * Date: 1/26/2018
 * Time: 12:57 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');


// Create connection
$conn = new mysqli("localhost", "root", "autosharkSite1", "flipster");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once("vendor/autoload.php");
require_once("functions.php");


