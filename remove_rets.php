<?php
/**
 * Created by PhpStorm.
 * User: big_k
 * Date: 1/26/2018
 * Time: 12:57 PM
 */
ini_set('max_execution_time', 0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

// Create connection
$conn = new mysqli("localhost", "root", "autosharkSite1", "flipster");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once("vendor/autoload.php");

$config = new \PHRETS\Configuration;
$config->setLoginUrl('http://rets.las.mlsmatrix.com/rets/login.ashx')
    ->setUsername('redwealth')
    ->setPassword('glvrets')
    ->setRetsVersion('1.5');

$rets = new \PHRETS\Session($config);
$connect = $rets->Login();

$count = 0;
$count_removed = 0;
$current_matrix_ids = array();

$process = true;
$next_value = 0;
while($process) {

    $results = $rets->Search('Property', 'Listing', '(Matrix_Unique_ID='.$next_value.'+),(propertytype=RES),(propertysubtype=SFR),(listingagreementtype=ER,EA),(status=A,C,P,S)', ["Select" => "Matrix_Unique_ID"]);

    if($results->getReturnedResultsCount()<=1){
        $process=false;
    }

    if($results->getReturnedResultsCount()==0){
        $process=false;
        print_r($current_matrix_ids);
        print($count."<br/>");
        die("DONE");
    }

    foreach ($results as $result) {
        $count++;
        $current = $result->toArray();
        $current_matrix_ids[] = $current["Matrix_Unique_ID"];
    }

    $next_value = $current["Matrix_Unique_ID"];

}

$sql = "SELECT id,Matrix_Unique_ID FROM property WHERE dateDeleted IS NULL";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        if(!in_array($row["Matrix_Unique_ID"],array_unique($current_matrix_ids))){
            $count_removed++;
            $sql2 = "UPDATE property SET dateDeleted = NOW() WHERE id=".$row["id"];

            if ($conn->query($sql2) === TRUE) {
                echo $count_removed." - Record ".$row["id"]." should be removed<br/>";
            } else {
                echo "Error updating record: " . $conn->error;
                die();
            }
        }
    }
}

// Check connection
print($count_removed."<br/>");
die("DONE");
