<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Management class created by CodexWorld
 */
class Ajax extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');

        $this->load->model('user');
        $this->load->helper('rets');
        $this->conn = new mysqli("localhost", "root", "autosharkSite1", "flipster");
// Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function get_polygon_single()
    {
        $internal_values = array("rehab" => "20");


        $sql = "SELECT * FROM property WHERE Matrix_Unique_ID =" . $_POST["id"];
        $property_result = $this->conn->query($sql);
        if ($property_result->num_rows > 0) {
            $property = $property_result->fetch_assoc();
        }

        $lat = $property["lat"];
        $long = $property["lng"];

        if (empty($property["ApproxTotalLivArea"])) {
            $property["ApproxTotalLivArea"] = $property["SqFtTotal"];
        }

        $comp_data = get_comps_from_polygon($lat, $long, $property, $this->conn, $_POST["polygon"]);

        $totals = calculate_comps($comp_data, $property["ApproxTotalLivArea"], $internal_values);


        $comps = array();

        $comp_data["comps"]->data_seek(0);

        while ($row = $comp_data["comps"]->fetch_assoc()) {

            $address = $row["StreetNumber"];

            if ($row["StreetDirPrefix"]) {
                $address .= " " . $row["StreetDirPrefix"];
            }
            if ($row["StreetName"]) {
                $address .= " " . $row["StreetName"];
            }
            if ($row["StreetSuffix"]) {
                $address .= " " . $row["StreetSuffix"];
            }
            if ($row["StreetDirSuffix"]) {
                $address .= " " . $row["StreetDirSuffix"];
            }

            $close_time_array = explode(" ", $row["CloseDate"]);
            $close_date = DateTime::createFromFormat('Y-m-d', $close_time_array[0]);

            $comps[] = array("id" => $row["id"],
                "lat" => $row["lat"],
                "lng" => $row["lng"],
                "distance" =>round($row["distance"],2),
                "MLSNumber" => $row["MLSNumber"],
                "address" => $address,
                "ListPrice" => "$" . number_format((int)$row["ListPrice"]),
                "ClosePrice" => "$" . number_format((int)$row["ClosePrice"]),
                "ClosePriceSqFt" => "$" . number_format((int)($row["ClosePrice"] / $row["ApproxTotalLivArea"])),
                "BuildingDescription" => $row["BuildingDescription"],
                "ApproxTotalLivArea" => number_format($row["ApproxTotalLivArea"]),
                "BedsTotal" => round($row["BedsTotal"]),
                "BathsTotal" => round($row["BathsTotal"]),
                "Garage" => $row["Garage"],
                "PvPool" => $row["PvPool"] == 0 ? "No" : "Yes",
                "Spa" => $row["Spa"],
                "YearBuilt" => $row["YearBuilt"],
                "CloseDate" => date_format($close_date, "m-d-Y"),
                "DOM" => number_format($row["DOM"])
            );
        }

        print(json_encode(array("totals" => $totals, "comps" => $comps)));
    }

    public function get_single(){
        $internal_values = array("rehab" => "17");


        $sql = "SELECT * FROM property WHERE Matrix_Unique_ID =" . $_POST["id"];
        $property_result = $this->conn->query($sql);
        if ($property_result->num_rows > 0) {
            $property = $property_result->fetch_assoc();
        }

        $lat = $property["lat"];
        $long = $property["lng"];

        if (empty($property["ApproxTotalLivArea"])) {
            $property["ApproxTotalLivArea"] = $property["SqFtTotal"];
        }

        $comp_data = get_comps($lat, $long, $property, $this->conn,.5);

        $totals = calculate_comps($comp_data, $property["ApproxTotalLivArea"], $internal_values);


        $comps = array();

        $comp_data["comps"]->data_seek(0);

        while ($row = $comp_data["comps"]->fetch_assoc()) {

            $address = $row["StreetNumber"];

            if ($row["StreetDirPrefix"]) {
                $address .= " " . $row["StreetDirPrefix"];
            }
            if ($row["StreetName"]) {
                $address .= " " . $row["StreetName"];
            }
            if ($row["StreetSuffix"]) {
                $address .= " " . $row["StreetSuffix"];
            }
            if ($row["StreetDirSuffix"]) {
                $address .= " " . $row["StreetDirSuffix"];
            }

            $close_time_array = explode(" ", $row["CloseDate"]);
            $close_date = DateTime::createFromFormat('Y-m-d', $close_time_array[0]);

            $comps[] = array("id" => $row["id"],
                "lat" => $row["lat"],
                "lng" => $row["lng"],
                "distance" =>round($row["distance"],2),
                "MLSNumber" => $row["MLSNumber"],
                "address" => $address,
                "ListPrice" => "$" . number_format((int)$row["ListPrice"]),
                "ClosePrice" => "$" . number_format((int)$row["ClosePrice"]),
                "ClosePriceSqFt" => "$" . number_format((int)($row["ClosePrice"] / $row["ApproxTotalLivArea"])),
                "BuildingDescription" => $row["BuildingDescription"],
                "ApproxTotalLivArea" => number_format($row["ApproxTotalLivArea"]),
                "BedsTotal" => round($row["BedsTotal"]),
                "BathsTotal" => round($row["BathsTotal"]),
                "Garage" => $row["Garage"],
                "PvPool" => $row["PvPool"] == 0 ? "No" : "Yes",
                "Spa" => $row["Spa"],
                "YearBuilt" => $row["YearBuilt"],
                "CloseDate" => date_format($close_date, "m-d-Y"),
                "DOM" => number_format($row["DOM"])
            );
        }

        print(json_encode(array("totals" => $totals, "comps" => $comps)));
    }

    public function get_all_listings()
    {
        $sql = 'SELECT MLSNumber,user_id,
Matrix_Unique_ID,
ListPrice,
ApproxTotalLivArea,
BuildingDescription,
BedsTotal,
BathsTotal,
Garage,
PvPool,
Spa,
YearBuilt,
DOM,
StreetNumber,
StreetDirPrefix,
StreetName,
StreetSuffix,
StreetDirSuffix,offerprice,ListingContractDate,PostalCode,lat,lng,internal_status,users.name as user_name FROM property LEFT JOIN users ON users.id = property.user_id WHERE property.dateDeleted IS NULL AND property.Status="Active" AND property.Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.") ORDER BY property.ListingContractDate DESC ';

        $result = $this->conn->query($sql);
        $comps = array();
        $max_comp_sqft = 0;
        if ($result->num_rows > 0) {
            // output data of each row

            while ($row = $result->fetch_assoc()) {
                $comps[] = $row;
            }
        }

        $final_array = array();
        foreach ($comps as $row) {

            $address = $row["StreetNumber"];

            if ($row["StreetDirPrefix"]) {
                $address .= " " . $row["StreetDirPrefix"];
            }
            if ($row["StreetName"]) {
                $address .= " " . $row["StreetName"];
            }
            if ($row["StreetSuffix"]) {
                $address .= " " . $row["StreetSuffix"];
            }
            if ($row["StreetDirSuffix"]) {
                $address .= " " . $row["StreetDirSuffix"];
            }

            $listing_date_array = explode("T", $row["ListingContractDate"]);

            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($listing_date_array[0]);
            $interval = $datetime1->diff($datetime2);

            $final_array[] = array(
                "address" => $address,
                "lat" => $row["lat"],
                "lng" => $row["lng"],
                "user-id" => $row["user_id"],
                "link" => '<a target="_blank" href="/users/details?id=' . $row["Matrix_Unique_ID"] . '">' . $row["MLSNumber"] . '</a>',
                "PostalCode" => $row["PostalCode"],
                "OfferPriceToListPrice" => round(($row["offerprice"] / $row["ListPrice"]) * 100, 1) . "%",
                "OfferPrice" => "$" . number_format((float)$row["offerprice"]),
                "ListPrice" => "$" . number_format((float)$row["ListPrice"]),
                "BuildingDescription" => $row["BuildingDescription"],
                "ApproxTotalLivArea" => number_format($row["ApproxTotalLivArea"] ? $row["ApproxTotalLivArea"] : $row["SqFtTotal"]),
                "BedsTotal" => round($row["BedsTotal"]),
                "BathsTotal" => (int)$row["BathsTotal"],
                "Garage" => $row["Garage"],
                "PvPool" => $row["PvPool"] == 0 ? "No" : "Yes",
                "Spa" => $row["Spa"],
                "YearBuilt" => $row["YearBuilt"],
                "DOM" => number_format($interval->format('%a')),
                "user" => $row["user_name"],
                "user_id" => $row["user_id"],
                "internal_status" => $row["internal_status"]
            );
        }
        print(json_encode(array("comps" => $final_array)));
    }

    public function get_polygon_all()
    {
        $polygon_string = "POLYGON((";
        foreach ($_POST["polygon"] as $point) {
            $polygon_string .= $point[0] . " " . $point[1] . ",";
        }
        $polygon_string = rtrim($polygon_string, ",") . "))";

        $sql = 'SELECT MLSNumber,user_id,
Matrix_Unique_ID,
ListPrice,
ApproxTotalLivArea,
BuildingDescription,
BedsTotal,
BathsTotal,
Garage,
PvPool,
Spa,
YearBuilt,
DOM,
StreetNumber,
StreetDirPrefix,
StreetName,
StreetSuffix,
StreetDirSuffix,offerprice,ListingContractDate,PostalCode,lat,lng,internal_status,users.name as user_name FROM property LEFT JOIN users ON users.id = property.user_id WHERE ST_CONTAINS(ST_GEOMFROMTEXT("' . $polygon_string . '"), point(property.lat, property.lng)) AND property.dateDeleted IS NULL AND property.Status="Active" AND property.Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.") ORDER BY property.ListingContractDate DESC ';

        $result = $this->conn->query($sql);
        $comps = array();
        $max_comp_sqft = 0;
        if ($result->num_rows > 0) {
            // output data of each row

            while ($row = $result->fetch_assoc()) {
                $comps[] = $row;
            }
        }

        $final_array = array();
        foreach ($comps as $row) {

            $address = $row["StreetNumber"];

            if ($row["StreetDirPrefix"]) {
                $address .= " " . $row["StreetDirPrefix"];
            }
            if ($row["StreetName"]) {
                $address .= " " . $row["StreetName"];
            }
            if ($row["StreetSuffix"]) {
                $address .= " " . $row["StreetSuffix"];
            }
            if ($row["StreetDirSuffix"]) {
                $address .= " " . $row["StreetDirSuffix"];
            }

            $listing_date_array = explode("T", $row["ListingContractDate"]);

            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($listing_date_array[0]);
            $interval = $datetime1->diff($datetime2);

            $final_array[] = array(
                "address" => $address,
                "lat" => $row["lat"],
                "lng" => $row["lng"],
                "user-id" => $row["user_id"],
                "link" => '<a target="_blank" href="/users/details?id=' . $row["Matrix_Unique_ID"] . '">' . $row["MLSNumber"] . '</a>',
                "PostalCode" => $row["PostalCode"],
                "OfferPriceToListPrice" => round(($row["offerprice"] / $row["ListPrice"]) * 100, 1) . "%",
                "OfferPrice" => "$" . number_format($row["offerprice"]),
                "ListPrice" => "$" . number_format($row["ListPrice"]),
                "BuildingDescription" => $row["BuildingDescription"],
                "ApproxTotalLivArea" => number_format($row["ApproxTotalLivArea"] ? $row["ApproxTotalLivArea"] : $row["SqFtTotal"]),
                "BedsTotal" => round($row["BedsTotal"]),
                "BathsTotal" => (int)$row["BathsTotal"],
                "Garage" => $row["Garage"],
                "PvPool" => $row["PvPool"] == 0 ? "No" : "Yes",
                "Spa" => $row["Spa"],
                "YearBuilt" => $row["YearBuilt"],
                "DOM" => number_format($interval->format('%a')),
                "user" => $row["user_name"],
                "user_id" => $row["user_id"],
                "internal_status" => $row["internal_status"]
            );
        }
        print(json_encode(array("comps" => $final_array)));
    }

    public function update_message(){

        $sql = "INSERT INTO messages  (user_id, property_id, to_user_id, message, time) VALUES ('".$_POST["user_id"]."','".$_POST["property_id"]."','".$_POST["to_user_id"]."','".$_POST["message"]."','".date("Y-m-d H:i:s")."')";

        if ($this->conn->query($sql) === TRUE) {
            $sql = 'SELECT messages.time as time, messages.message as message, to_user.name as to_user_name, from_user.name as from_user_name FROM messages LEFT JOIN users AS from_user on from_user.id = messages.user_id LEFT JOIN users AS to_user ON to_user.id = messages.to_user_id WHERE property_id = "'.$_POST["property_id"].'" ORDER BY time DESC';

            $result = $this->conn->query($sql);
            $html = "";
            while ($row = $result->fetch_assoc()) {
                $html .='<div class="row"><p style="border-top:1px solid black;">'.$row["time"].'<br/>From User: '.$row["from_user_name"].'<br/>To User: '.$row["to_user_name"].'<br/>Message: '.$row["message"].'</p></div><br/>';
            }
            echo $html;
        } else {
            echo "ERROR";
        }
    }

    public function update_user(){
        $sql = "UPDATE property SET user_id = '".$_POST["user_id"]."' WHERE Matrix_Unique_ID=" . $_POST["property_id"];


        if ($this->conn->query($sql) === TRUE) {
            $this->load->model('user');
            $user = $this->user->get_by_id($_POST["user_id"]);
            $sql = "INSERT INTO messages  (user_id, property_id, to_user_id, message, time) VALUES ('".$_POST["user_id"]."','".$_POST["property_id"]."','1','Owner updated to ".$user->name."','".date("Y-m-d H:i:s")."')";
            if($this->conn->query($sql)){
                $sql = 'SELECT messages.time as time, messages.message as message, to_user.name as to_user_name, from_user.name as from_user_name FROM messages LEFT JOIN users AS from_user on from_user.id = messages.user_id LEFT JOIN users AS to_user ON to_user.id = messages.to_user_id WHERE property_id = "'.$_POST["property_id"].'" ORDER BY time DESC';

                $result = $this->conn->query($sql);
                $html = "";
                while ($row = $result->fetch_assoc()) {
                    $html .='<div class="row"><p style="border-top:1px solid black;">'.$row["time"].'<br/>From User: '.$row["from_user_name"].'<br/>To User: '.$row["to_user_name"].'<br/>Message: '.$row["message"].'</p></div><br/>';
                }
                echo $html;
            } else {
                echo "ERROR";
            }
        } else {
            echo "ERROR";
        }
    }

    public function update_status(){
        $sql = "UPDATE property SET internal_status = '".$_POST["status"]."' WHERE Matrix_Unique_ID=" . $_POST["property_id"];
        if ($this->conn->query($sql) === TRUE) {
            $sql = "INSERT INTO messages  (user_id, property_id, to_user_id, message, time) VALUES ('".$_POST["user_id"]."','".$_POST["property_id"]."','1','Status updated to ".$_POST["status"]."','".date("Y-m-d H:i:s")."')";
            if($this->conn->query($sql)){
                $sql = 'SELECT messages.time as time, messages.message as message, to_user.name as to_user_name, from_user.name as from_user_name FROM messages LEFT JOIN users AS from_user on from_user.id = messages.user_id LEFT JOIN users AS to_user ON to_user.id = messages.to_user_id WHERE property_id = "'.$_POST["property_id"].'" ORDER BY time DESC';

                $result = $this->conn->query($sql);
                $html = "";
                while ($row = $result->fetch_assoc()) {
                    $html .='<div class="row"><p style="border-top:1px solid black;">'.$row["time"].'<br/>From User: '.$row["from_user_name"].'<br/>To User: '.$row["to_user_name"].'<br/>Message: '.$row["message"].'</p></div><br/>';
                }
                echo $html;
            } else {
                echo "ERROR";
            }
        } else {
            echo "ERROR";
        }
    }

    public function update_notes(){
        $sql = "UPDATE property SET notes = '".$_POST["notes"]."' WHERE Matrix_Unique_ID=" . $_POST["property_id"];


        if ($this->conn->query($sql) === TRUE) {
            echo "SUCCESS";
        } else {
            echo "ERROR";
        }
    }
}