<?php
/**
 * Created by PhpStorm.
 * User: big_k
 * Date: 3/5/2018
 * Time: 12:16 PM
 */

function get_comps($lat,$long,$current,$conn,$distance = .5){

    $yrblt_offset = 10;
    $include_bldg_desc = 1;

    $building_descriptions = array();
    $sql = "SELECT * FROM building_descriptions";
    $building_descriptions_result = $conn->query($sql);
    if ($building_descriptions_result->num_rows > 0){
        while ($description = $building_descriptions_result->fetch_assoc()){
            $building_descriptions[] = $description;
        }
    }

    $building_description_group="";

    foreach($building_descriptions as $description){
        if($description["description"] == $current["BuildingDescription"]){
            $building_description_group = $description["group_id"];
            continue;
        }
    }

    $building_description_group_array = explode(",",$building_description_group);


    $building_description_query_string = "";
    foreach($building_description_group_array as $group_id){
        foreach($building_descriptions as $description){
            if( strpos( $description["group_id"], $group_id ) !== false){
                $building_description_query_string.= '"'.$description["description"].'",';
            }
        }
    }

    $pass = 1;
    $sqft_max = 1.2;
    $sqft_min = .8;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property HAVING distance < '.$distance.' AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result1 = $conn->query($sql);
    if ($result1->num_rows != 0){
        return array("pass"=>$pass,"distance"=>$distance,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result1);
    }

    $pass++;
    $sqft_max = 1.25;
    $sqft_min = .75;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property HAVING distance < '.$distance.' AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result2 = $conn->query($sql);
    if ($result2->num_rows != 0){
        return array("pass"=>$pass,"distance"=>$distance,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result2);
    }

    $pass++;
    $sqft_max = 1.3;
    $sqft_min = .7;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property HAVING distance < '.$distance.' AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result3 = $conn->query($sql);
    if ($result3->num_rows != 0){
        return array("pass"=>$pass,"distance"=>$distance,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result3);
    }

    $pass++;
    $sqft_max = 1.2;
    $sqft_min = .8;
    $distance = 1.0;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property HAVING distance < '.$distance.' AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result4 = $conn->query($sql);
    if ($result4->num_rows != 0){
        return array("pass"=>$pass,"distance"=>$distance,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result4);
    }

    $pass++;
    $sqft_max = 1.25;
    $sqft_min = .75;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property HAVING distance < '.$distance.' AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result5 = $conn->query($sql);
    if ($result5->num_rows != 0){
        return array("pass"=>$pass,"distance"=>$distance,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result5);
    }

    $pass++;
    $sqft_max = 1.3;
    $sqft_min = .7;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property HAVING distance < '.$distance.' AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result6 = $conn->query($sql);
    if ($result6->num_rows != 0){
        return array("pass"=>$pass,"distance"=>$distance,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result6);
    }

    return false;
}

function get_comps_from_polygon($lat,$long,$current,$conn,$polygon){

    $sqft_max = 1.2;
    $sqft_min = .8;
    $yrblt_offset = 10;
    $include_bldg_desc = 1;


    $building_descriptions = array();
    $sql = "SELECT * FROM building_descriptions";
    $building_descriptions_result = $conn->query($sql);
    if ($building_descriptions_result->num_rows > 0){
        while ($description = $building_descriptions_result->fetch_assoc()){
            $building_descriptions[] = $description;
        }
    }

    $building_description_group="";

    foreach($building_descriptions as $description){
        if($description["description"] == $current["BuildingDescription"]){
            $building_description_group = $description["group_id"];
            continue;
        }
    }

    $building_description_group_array = explode(",",$building_description_group);


    $building_description_query_string = "";
    foreach($building_description_group_array as $group_id){
        foreach($building_descriptions as $description){
            if( strpos( $description["group_id"], $group_id ) !== false){
                $building_description_query_string.= '"'.$description["description"].'",';
            }
        }
    }

    $polygon_string = "POLYGON((";
    foreach($polygon as $point){
        $polygon_string .= $point[0] ." ".$point[1].",";
    }
    $polygon_string =rtrim($polygon_string,",")."))";

    $pass = 1;
    $sqft_max = 1.2;
    $sqft_min = .8;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property WHERE ST_CONTAINS(ST_GEOMFROMTEXT("'.$polygon_string.'"), point(lat, lng)) AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result1 = $conn->query($sql);
    if ($result1->num_rows != 0){
        return array("pass"=>$pass,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result1);
    }

    $pass++;
    $sqft_max = 1.25;
    $sqft_min = .75;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property WHERE ST_CONTAINS(ST_GEOMFROMTEXT("'.$polygon_string.'"), point(lat, lng)) AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result2 = $conn->query($sql);
    if ($result2->num_rows != 0){
        return array("pass"=>$pass,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result2);
    }

    $pass++;
    $sqft_max = 1.3;
    $sqft_min = .25;

    $sql = 'SELECT property.*,(
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance FROM property WHERE ST_CONTAINS(ST_GEOMFROMTEXT("'.$polygon_string.'"), point(lat, lng)) AND Status="Sold" AND YearBuilt > ('.$current["YearBuilt"].'-10) AND YearBuilt < ('.$current["YearBuilt"].'+10) AND BuildingDescription IN ('.rtrim($building_description_query_string,",").') and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result3 = $conn->query($sql);
    if ($result3->num_rows != 0){
        return array("pass"=>$pass,"sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result3);
    }

    return false;


/*    $sql = 'SELECT property.*, (
            3959 * acos (
              cos ( radians(' . $lat . ') )
              * cos( radians( property.lat ) )
              * cos( radians( property.lng ) - radians(' . $long . ') )
              + sin ( radians(' . $lat . ') )
              * sin( radians( property.lat ) )
            )
          )  AS distance from property where ST_CONTAINS(ST_GEOMFROMTEXT("'.$polygon_string.'"), point(lat, lng)) and Status="Sold" and ApproxTotalLivArea>('.$sqft_min.'*' . $current["ApproxTotalLivArea"] . ') AND ApproxTotalLivArea<('.$sqft_max.'*' . $current["ApproxTotalLivArea"] . ') AND CloseDate >= date_sub(now(), interval 6 month) AND ClosePrice !=0 AND Area NOT IN("800 - Mesquite","801 - Muddy River(Moapa, Glendale, Logandaleand Overton)","802 - Mt. Charleston/Lee Canyon","803 - Indian Springs/Cold Creek","804 - Mountain Springs","805 - Blue Diamond","806 - State Line/Jean/Goodsprings","807 - Sandy Valley","808 - Laughlin","809 - Other Clark County","810 - Pahrump","811 - Nye County","812 - Lincoln County","813 - Other Nevada","814 - Amargosa Valley","815 - Beatty","816 - White Pine County","817 - Searchlight","900 - Outside Nevada","950 - Outside the U. S.")';

    $result = $conn->query($sql);

    return array("sqft_offset"=>$sqft_max-1,"yrblt_offset"=>$yrblt_offset,"include_bldg_desc"=>$include_bldg_desc,"comps"=>$result);
*/
}


function calculate_comps($comp_data,$approxTotalLivArea,$internal_values){
    $comps = array();
    $max_comp_sqft = 0;
    $min_comp_sqft = 0;
    $total_sqft_comps = 0;
    $max_close_price = 0;
    $min_close_price = 0;
    $total_close_price = 0;
    $total_living_area = 0;
    $total_dom = 0;


    $arv_comp_totals = array();

    $total_max_arv_count = 0;

    if (!empty($comp_data["comps"])) {
        // output data of each row

        while ($row = $comp_data["comps"]->fetch_assoc()){
            $comps[]=$row;
            $arv_comp_totals[]=(int)($row["ClosePrice"] / $row["ApproxTotalLivArea"]);
            $total_sqft_comps += (int)($row["ClosePrice"] / $row["ApproxTotalLivArea"]);
            $total_close_price += $row["ClosePrice"];
            $total_living_area += $row["ApproxTotalLivArea"];
            $total_dom += $row["DOM"];
            if($max_close_price < $row["ClosePrice"] ){
                $max_close_price = $row["ClosePrice"];
            }

            if($min_close_price == 0){
                $min_close_price = $row["ClosePrice"];
            } else if($min_close_price > $row["ClosePrice"] ){
                $min_close_price = $row["ClosePrice"];
            }

            if($max_comp_sqft<(int)($row["ClosePrice"] / $row["ApproxTotalLivArea"])){
                $max_comp_sqft = (int)($row["ClosePrice"] / $row["ApproxTotalLivArea"]);
            }
            if($min_comp_sqft == 0){
                $min_comp_sqft = (int)($row["ClosePrice"] / $row["ApproxTotalLivArea"]);
            } else if($min_comp_sqft > (int)($row["ClosePrice"] / $row["ApproxTotalLivArea"])){
                $min_comp_sqft = (int)($row["ClosePrice"] / $row["ApproxTotalLivArea"]);
            }

        }

        rsort($arv_comp_totals);

        $alt_max_arv_totals_av =0;
        for($i=0;$i<5;$i++){
            if(array_key_exists($i, $arv_comp_totals) && $arv_comp_totals[$i]) {
                $total_max_arv_count++;
                $alt_max_arv_totals_av += $arv_comp_totals[$i];
            }
        }

        $alt_max_arv_totals_av = round($alt_max_arv_totals_av/$total_max_arv_count);
        $total_comp_sqft_av = (int)round($total_sqft_comps/count($comps));
        $total_close_price_av = (int)round($total_close_price/count($comps));
        $total_living_area_av = (int)round($total_living_area/count($comps));
        $total_dom_av = (int)round($total_dom/count($comps));
        $offer_price = round((.85 * $alt_max_arv_totals_av * $approxTotalLivArea) - ($internal_values["rehab"]*$approxTotalLivArea));
        $total_cost = (int)($offer_price + (.01 * $offer_price) + (.045 * ($alt_max_arv_totals_av * $approxTotalLivArea) + ($approxTotalLivArea * $internal_values["rehab"]))+(((.07 * ( $offer_price + ($approxTotalLivArea * $internal_values["rehab"])))/12)*4)-(.015 * $offer_price));
        $net_profit = (int)(($alt_max_arv_totals_av * $approxTotalLivArea) - $total_cost );

        return array(
            "min_comp_sqft"=>$min_comp_sqft,
            "max_close_price"=>$max_close_price,
            "min_close_price"=>$min_close_price,
            "max_comp_sqft"=>$max_comp_sqft,
            "alt_max_arv_totals_av"=>$alt_max_arv_totals_av,
            "total_comp_sqft_av"=>$total_comp_sqft_av,
            "total_close_price_av"=>$total_close_price_av,
            "total_living_area_av"=>$total_living_area_av,
            "total_dom_av"=>$total_dom_av,
            "offer_price"=>$offer_price,
            "total_cost"=>$total_cost,
            "net_profit"=>$net_profit
        );

    } else {
        return array(
            "min_comp_sqft"=>0,
            "max_close_price"=>0,
            "min_close_price"=>0,
            "max_comp_sqft"=>0,
            "alt_max_arv_totals_av"=>0,
            "total_comp_sqft_av"=>0,
            "total_close_price_av"=>0,
            "total_living_area_av"=>0,
            "total_dom_av"=>0,
            "offer_price"=>0,
            "total_cost"=>0,
            "net_profit"=>0
        );
    }
}