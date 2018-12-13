<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Management class created by CodexWorld
 */
class Update extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        require_once("vendor/autoload.php");
        $this->load->library('form_validation');
        $this->load->model('user');
        $this->load->helper('rets');
        $this->conn = new mysqli("localhost", "root", "autosharkSite1", "flipster");
// Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function update_rets()
    {
        $internal_values = array("rehab"=>"20");

        $config = new \PHRETS\Configuration;
        $config->setLoginUrl('http://rets.las.mlsmatrix.com/rets/login.ashx')
            ->setUsername('redwealth')
            ->setPassword('glvrets')
            ->setRetsVersion('1.5');

        $rets = new \PHRETS\Session($config);
        $connect = $rets->Login();

        $count = 0;

        $sql = "SELECT MatrixModifiedDT FROM property ORDER BY MatrixModifiedDT DESC LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $next_value = $row["MatrixModifiedDT"];
            }
        }
//TODO MATT remove this when going live
        $next_value = 0;
        $process=true;
        while($process) {

            //$rets_results = $rets->Search('Property', 'Listing', '(MatrixModifiedDT=' . $next_value . '+),(propertytype=RES),(propertysubtype=SFR),(listingagreementtype=ER),(status=A,C,P,S)', ['Limit' => 1000]);
            $rets_results = $rets->Search('Property', 'Listing', '(Matrix_Unique_ID=' . $next_value . '+),(propertytype=RES),(propertysubtype=SFR),(listingagreementtype=ER,EA),(status=A,C,P,S)', ['Limit' => 1000]);

            if($rets_results->getReturnedResultsCount()<1000){
                $process=false;
            }

            foreach ($rets_results as $rets_result) {
                $count++;
                $current = $rets_result->toArray();
                print($count." " . $current["Matrix_Unique_ID"]." ");

                if(empty($current["ApproxTotalLivArea"])){
                    $current["ApproxTotalLivArea"] = $current["SqFtTotal"];
                }

                $sql = "SELECT * FROM property WHERE matrix_unique_id = '".$current["Matrix_Unique_ID"]."' LIMIT 1";
                $result = $this->conn->query($sql);

                if ($result->num_rows > 0) {

                    while($row = $result->fetch_assoc()) {

                        if ($row["MatrixModifiedDT"] != $current["MatrixModifiedDT"]) {

                            $comp_data = get_comps($row["lat"],$row["lng"],$current,$this->conn);

                            $totals = calculate_comps($comp_data,$current["ApproxTotalLivArea"],$internal_values);

                            //$sql = "UPDATE property SET PvPool='".$current["PvPool"]."' AND Spa='".$row["Spa"]."' AND ClosePrice='" . $current["ClosePrice"] . "' WHERE id='" . $row["id"] . "'";
                            $sql = "UPDATE property SET
                    AnnualPropertyTaxes=?,
                    ApproxTotalLivArea=?,
                    Area=?,
                    BathsTotal=?,
                    BedsTotal=?,
                    BuildingDescription=?,
                    CurrentPrice=?,
                    DOM=?,
                    DomModifier_DateTime=?,
                    Garage=?,
                    LastChangeTimestamp=?,
                    LastChangeType=?,
                    LastListPrice=?,
                    LastStatus=?,
                    ListAgent_MUI=?,
                    ListAgentDirectWorkPhone=?,
                    ListAgentFullName=?,
                    ListAgentMLSID=?,
                    ListingAgreementType=?,
                    ListOffice_MUI=?,
                    ListOfficeMLSID=?,
                    ListOfficeName=?,
                    ListOfficePhone=?,
                    ListPrice=?,
                    LotSqft=?,
                    Matrix_Unique_ID=?,
                    MatrixModifiedDT=?,
                    MLSNumber=?,
                    OriginalEntryTimestamp=?,
                    OriginalListPrice=?,
                    OwnersName=?,
                    ParcelNumber=?,
                    PostalCode=?,
                    PriceChgDate=?,
                    PropertySubType=?,
                    PropertyType=?,
                    PublicAddress=?,
                    PublicRemarks=?,
                    RoomCount=?,
                    SaleType=?,
                    SellingAgent_MUI=?,
                    SellingAgentDirectWorkPhone=?,
                    SellingAgentFullName=?,
                    SellingAgentMLSID=?,
                    SellingOffice_MUI=?,
                    SellingOfficeMLSID=?,
                    SellingOfficeName=?,
                    SellingOfficePhone=?,
                    SqFtTotal=?,
                    StateOrProvince=?,
                    Status=?,
                    StatusChangeTimestamp=?,
                    StreetName=?,
                    StreetNumber=?,
                    StreetSuffix=?,
                    SubdivisionName=?,
                    SubdivisionNumber=?,
                    YearBuilt=?,
                    CloseDate=?,
                    StreetDirSuffix=?,
                    StreetDirPrefix=?,
                    City=?,
                    PvPool=?,
                    Spa=?,
                    ClosePrice=?,
                    ListingContractDate=?,
                    offerprice=?,
                    netprofit=?,
                    comp_distance=?,
                    sqft_offset=?,
                    yearbuilt_offset=?,
                    include_bldg_desc=? WHERE id=?";

                            if ($current["Status"] == "Sold") {
                                $time_array = explode("T", $current["CloseDate"]);
                                $final_time = $time_array[0] ? $time_array[0] : "";
                            } else {
                                $final_time = date("Y-m-d");
                            }

                            $totalLivingArea = (int)$current["ApproxTotalLivArea"];

                            if ($stmt = $this->conn->prepare($sql)) { // assuming $mysqli is the connection
                                $stmt->bind_param('sisssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss',
                                    $current["AnnualPropertyTaxes"],
                                    $totalLivingArea,
                                    $current["Area"],
                                    $current["BathsTotal"],
                                    $current["BedsTotal"],
                                    $current["BuildingDescription"],
                                    $current["CurrentPrice"],
                                    $current["DOM"],
                                    $current["DomModifier_DateTime"],
                                    $current["Garage"],
                                    $current["LastChangeTimestamp"],
                                    $current["LastChangeType"],
                                    $current["LastListPrice"],
                                    $current["LastStatus"],
                                    $current["ListAgent_MUI"],
                                    $current["ListAgentDirectWorkPhone"],
                                    $current["ListAgentFullName"],
                                    $current["ListAgentMLSID"],
                                    $current["ListingAgreementType"],
                                    $current["ListOffice_MUI"],
                                    $current["ListOfficeMLSID"],
                                    $current["ListOfficeName"],
                                    $current["ListOfficePhone"],
                                    $current["ListPrice"],
                                    $current["LotSqft"],
                                    $current["Matrix_Unique_ID"],
                                    $current["MatrixModifiedDT"],
                                    $current["MLSNumber"],
                                    $current["OriginalEntryTimestamp"],
                                    $current["OriginalListPrice"],
                                    $current["OwnersName"],
                                    $current["ParcelNumber"],
                                    $current["PostalCode"],
                                    $current["PriceChgDate"],
                                    $current["PropertySubType"],
                                    $current["PropertyType"],
                                    $current["PublicAddress"],
                                    $current["PublicRemarks"],
                                    $current["RoomCount"],
                                    $current["SaleType"],
                                    $current["SellingAgent_MUI"],
                                    $current["SellingAgentDirectWorkPhone"],
                                    $current["SellingAgentFullName"],
                                    $current["SellingAgentMLSID"],
                                    $current["SellingOffice_MUI"],
                                    $current["SellingOfficeMLSID"],
                                    $current["SellingOfficeName"],
                                    $current["SellingOfficePhone"],
                                    $current["SqFtTotal"],
                                    $current["StateOrProvince"],
                                    $current["Status"],
                                    $current["StatusChangeTimestamp"],
                                    $current["StreetName"],
                                    $current["StreetNumber"],
                                    $current["StreetSuffix"],
                                    $current["SubdivisionName"],
                                    $current["SubdivisionNumber"],
                                    $current["YearBuilt"],
                                    $final_time,
                                    $current["StreetDirPrefix"],
                                    $current["StreetDirSuffix"],
                                    $current["City"],
                                    $current["PvPool"],
                                    $current["Spa"],
                                    $current["ClosePrice"],
                                    $current["ListingContractDate"],
                                    $totals["offer_price"], $totals["net_profit"],
                                    $comp_data["distance"],
                                    $comp_data["sqft_offset"],
                                    $comp_data["yrblt_offset"],
                                    $comp_data["include_bldg_desc"],
                                    $row["id"]);
                                if ($stmt->execute()) {
                                    //query with out errors:

                                    printf("%d - rows updated: %d<br/>", $current["Matrix_Unique_ID"], $stmt->affected_rows);


                                } else {
                                    //some error:
                                    printf("Error: %s.\n", $stmt->error);
                                    die();
                                }
                            }
                        } else {
                            print("skipping since no change<br/>");
                        }
                    }
                } else {


                    $address = $current["StreetNumber"];
                    if($current["StreetDirPrefix"]){
                        $address .= " ".$current["StreetDirPrefix"];
                    }
                    if($current["StreetName"]){
                        $address .= " ".$current["StreetName"];
                    }
                    if($current["StreetSuffix"]){
                        $address .= " ".$current["StreetSuffix"];
                    }
                    if($current["StreetDirSuffix"]){
                        $address .= " ".$current["StreetDirSuffix"];
                    }

                    if(empty($current["ApproxTotalLivArea"])){
                        $current["ApproxTotalLivArea"] = $current["SqFtTotal"];
                    }


                    $address = urlencode($address." ".$current["City"]." NV ".$current["PostalCode"]);

                    $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyCi-2wYGvfYD07j6vGlXWgP3rcUsC50Fo4";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $details_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $response = json_decode(curl_exec($ch), true);

                    if ($response['status'] != 'OK') {
                        $lat = 0;
                        $lng = 0;
                    } else {

                        $lat=$response["results"][0]["geometry"]["location"]["lat"];
                        $lng=$response["results"][0]["geometry"]["location"]["lng"];

                    }


                    $comp_data = get_comps($lat,$lng,$current,$this->conn);

                    $totals = calculate_comps($comp_data,$current["ApproxTotalLivArea"],$internal_values);

                    $totalLivingArea = (int)$current["ApproxTotalLivArea"];
                    $sql = "INSERT INTO property(
    AnnualPropertyTaxes,
    ApproxTotalLivArea,
    Area,
    BathsTotal,
    BedsTotal,
    BuildingDescription,
    CurrentPrice,
    DOM,
    DomModifier_DateTime,
    Garage,
    LastChangeTimestamp,
    LastChangeType,
    LastListPrice,
    LastStatus,
    ListAgent_MUI,
    ListAgentDirectWorkPhone,
    ListAgentFullName,
    ListAgentMLSID,
    ListingAgreementType,
    ListOffice_MUI,
    ListOfficeMLSID,
    ListOfficeName,
    ListOfficePhone,
    ListPrice,
    LotSqft,
    Matrix_Unique_ID,
    MatrixModifiedDT,
    MLSNumber,
    OriginalEntryTimestamp,
    OriginalListPrice,
    OwnersName,
    ParcelNumber,
    PostalCode,
    PriceChgDate,
    PropertySubType,
    PropertyType,
    PublicAddress,
    PublicRemarks,
    RoomCount,
    SaleType,
    SellingAgent_MUI,
    SellingAgentDirectWorkPhone,
    SellingAgentFullName,
    SellingAgentMLSID,
    SellingOffice_MUI,
    SellingOfficeMLSID,
    SellingOfficeName,
    SellingOfficePhone,
    SqFtTotal,
    StateOrProvince,
    Status,
    StatusChangeTimestamp,
    StreetName,
    StreetNumber,
    StreetSuffix,
    SubdivisionName,
    SubdivisionNumber,
    YearBuilt,
    CloseDate,
    StreetDirSuffix,
    StreetDirPrefix,
    City,
    PvPool,
    Spa,
    ClosePrice,
    ListingContractDate,
    offerprice,
    netprofit,
    lat,
    lng,
    comp_distance,
    sqft_offset,
    yearbuilt_offset,
    include_bldg_desc
    )
    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                    if($stmt = $this->conn->prepare($sql)) { // assuming $mysqli is the connection
                        $stmt->bind_param('sissssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss',
                            $current["AnnualPropertyTaxes"],
                            $totalLivingArea,
                            $current["Area"],
                            $current["BathsTotal"],
                            $current["BedsTotal"],
                            $current["BuildingDescription"],
                            $current["CurrentPrice"],
                            $current["DOM"],
                            $current["DomModifier_DateTime"],
                            $current["Garage"],
                            $current["LastChangeTimestamp"],
                            $current["LastChangeType"],
                            $current["LastListPrice"],
                            $current["LastStatus"],
                            $current["ListAgent_MUI"],
                            $current["ListAgentDirectWorkPhone"],
                            $current["ListAgentFullName"],
                            $current["ListAgentMLSID"],
                            $current["ListingAgreementType"],
                            $current["ListOffice_MUI"],
                            $current["ListOfficeMLSID"],
                            $current["ListOfficeName"],
                            $current["ListOfficePhone"],
                            $current["ListPrice"],
                            $current["LotSqft"],
                            $current["Matrix_Unique_ID"],
                            $current["MatrixModifiedDT"],
                            $current["MLSNumber"],
                            $current["OriginalEntryTimestamp"],
                            $current["OriginalListPrice"],
                            $current["OwnersName"],
                            $current["ParcelNumber"],
                            $current["PostalCode"],
                            $current["PriceChgDate"],
                            $current["PropertySubType"],
                            $current["PropertyType"],
                            $current["PublicAddress"],
                            $current["PublicRemarks"],
                            $current["RoomCount"],
                            $current["SaleType"],
                            $current["SellingAgent_MUI"],
                            $current["SellingAgentDirectWorkPhone"],
                            $current["SellingAgentFullName"],
                            $current["SellingAgentMLSID"],
                            $current["SellingOffice_MUI"],
                            $current["SellingOfficeMLSID"],
                            $current["SellingOfficeName"],
                            $current["SellingOfficePhone"],
                            $current["SqFtTotal"],
                            $current["StateOrProvince"],
                            $current["Status"],
                            $current["StatusChangeTimestamp"],
                            $current["StreetName"],
                            $current["StreetNumber"],
                            $current["StreetSuffix"],
                            $current["SubdivisionName"],
                            $current["SubdivisionNumber"],
                            $current["YearBuilt"],
                            $final_time,
                            $current["StreetDirPrefix"],
                            $current["StreetDirSuffix"],
                            $current["City"],
                            $current["PvPool"],
                            $current["Spa"],
                            $current["ClosePrice"],
                            $current["ListingContractDate"],
                            $totals["offer_price"],
                            $totals["net_profit"],
                            $lat,
                            $lng,
                            $comp_data["distance"],
                            $comp_data["sqft_offset"],
                            $comp_data["yrblt_offset"],
                            $comp_data["include_bldg_desc"]);
                        if ($stmt->execute()) {
                            //query with out errors:
                            printf("%d - rows inserted: %d<br/>", $current["Matrix_Unique_ID"],$stmt->affected_rows);

                        } else {
                            //some error:


                            printf("Error: %s.\n", $stmt->error);
                            die();
                        }
                        // any additional code you need would go here.
                    } else {

                        $error = $mysqli->errno . ' ' . $mysqli->error;
                        echo $error."<br/>"; // 1054 Unknown column 'foo' in 'field list'
                    }

                    $stmt->close();
//            $next_value = $current["Matrix_Unique_ID"];
//            print($current["Matrix_Unique_ID"]." completed<br/>");

                }

            }

            $next_value = $current["Matrix_Unique_ID"];
        }
// Check connection
        print($count."<br/>");
        die("DONE");
    }
}