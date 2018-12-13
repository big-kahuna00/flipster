<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Flipster.io</title>

    <!-- Bootstrap core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>
body {
    padding-top: 54px;
        }
        @media (min-width: 992px) {
    body {
        padding-top: 56px;
            }
        }
        #map {
            height: 500px!important;
            width:1024px!important;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/vendor/bootstrap-slider/css/bootstrap-slider.css">

</head>

<body>
<div id="loader-wrapper">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Flipster.io</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>

        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/users">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <?php if($user->user_type==1):?>
                    <li class="nav-item">
                        <a class="nav-link" href="/users/admin">Admin
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                <?php endif;?>
                <li class="nav-item">
                    <a class="nav-link" href="/users/logout">Logout
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <a href="/users"><- Back to List</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="row" style="margin-bottom:20px">
                <div class="col-sm-9">
                    <span style="font-size:35px;">
                                                          <?php $current_address = $current["StreetNumber"];
                                                          $zillow = "https://www.zillow.com/homes/".$current["StreetNumber"];
                                                          if($current["StreetDirPrefix"]){
                                                              $current_address .= " ".$current["StreetDirPrefix"];
                                                              $zillow .= "-".$current["StreetDirPrefix"];
                                                          }
                                                          if($current["StreetName"]){
                                                              $current_address .= " ".$current["StreetName"];
                                                              $zillow .= "-".$current["StreetName"];
                                                          }
                                                          if($current["StreetSuffix"]){
                                                              $current_address .= " ".$current["StreetSuffix"];
                                                              $zillow .= "-".$current["StreetSuffix"];
                                                          }
                                                          if($current["StreetDirSuffix"]){
                                                              $current_address .= " ".$current["StreetDirSuffix"];
                                                              $zillow .= "-".$current["StreetDirSuffix"];
                                                          }
                                                          $zillow .= "-".urlencode($current["City"])."-NV-".$current["PostalCode"]."_rb";
                                                          echo $current_address; ?>
                    </span>
                </div>
        </div>

<div class="row main-info">
<div class="col-sm-12">
<div class="row">
<div class="col-sm-4" style="border:1px solid #ccc; padding:10px;">
    <div class="row">
        <div class="col-6">
            Owner:
        </div>
        <div class="col-6">
            <select id="owner" style="width:100%">
                <?php
                if ($users_result->num_rows > 0):
                    while ($owner = $users_result->fetch_assoc()):?>
                        <option value="<?php echo $owner["id"];?>" <?php echo $property_owner == $owner["id"]?"selected":""?>><?php echo $owner["name"];?></option>
                    <?php endwhile;
                endif;?>
            </select>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Flipster Status:
        </div>
        <div class="col-6">
            <select id="status" style="width:100%">
                <?php if(!$internal_status):?><option id="select_status_option" value="">Select Status</option>><?php endif;?>
                <option value="Offer Submitted" <?php echo $internal_status == "Offer Submitted"?"selected":""?>>Offer Submitted</option>
                <option value="Offer Countered" <?php echo $internal_status == "Offer Countered"?"selected":""?>>Offer Countered</option>
                <option value="Offer Rejected" <?php echo $internal_status == "Offer Rejected"?"selected":""?>>Offer Rejected</option>
                <option value="In Escrow" <?php echo $internal_status == "In Escrow"?"selected":""?>>In Escrow</option>
                <option value="Dead Deal" <?php echo $internal_status == "Dead Deal"?"selected":""?>>Dead Deal</option>
                <option value="Follow Up Needed" <?php echo $internal_status == "Follow Up Needed"?"selected":""?>>Follow Up Needed</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            List Price:
        </div>
        <div class="col-6">
            <?php echo "$".number_format($current["CurrentPrice"]);?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Offer Price:
        </div>
        <div class="col-6">
            <span style="color:red" id="offer-price" class="offer_price"><?php echo "$".number_format($totals["offer_price"])?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Purchase Closing Costs:
        </div>
        <div class="col-6">
            <span id="purchase-closing-costs"><?php echo "$".number_format((int)(.01* $totals["offer_price"]));?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>

    <div class="row">
        <div class="col-6">
            Commission Credit:
        </div>
        <div class="col-6">
            <span id="commission-credit"><?php echo "$".number_format((int)(.015* $totals["offer_price"]));?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>

    <div class="row">
        <div class="col-6">
            Rehab Budget
        </div>
        <div class="col-6">
            <span class="rehab-budget">$<?php echo number_format($current["ApproxTotalLivArea"] * $internal_values["rehab"])?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Holding Costs:
        </div>
        <div class="col-6">

            <span id="holding-costs"><?php echo "$".number_format((int)(((.07 * ( $totals["offer_price"] + ($current["ApproxTotalLivArea"] * $internal_values["rehab"])))/12)*4));?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Selling Closing Costs:
        </div>
        <div class="col-6">
            <span id="selling-closing-costs"><?php echo "$".number_format((int)(.045* $totals["alt_max_arv_totals_av"] * $current["ApproxTotalLivArea"]));?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>

    <div class="row">
        <div class="col-6">
            Total Cost:
        </div>
        <div class="col-6">
            <span id="total-cost"><?php echo "$".number_format($totals["total_cost"])?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            ARV:
        </div>
        <div class="col-6">
            <input id="original-arv" type="hidden" value="<?php echo $totals["alt_max_arv_totals_av"] *  $current["ApproxTotalLivArea"]?>">
            <span style="color:red" id="arv-price-2" class="top5arv">$<?php echo number_format($totals["alt_max_arv_totals_av"] * $current["ApproxTotalLivArea"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            <b>NET PROFIT:</b>
        </div>
        <div class="col-6">
            <span id="net-profit">$<?php echo number_format($totals["net_profit"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            <b>ROI:</b>
        </div>
        <div class="col-6">
            <span id="roi"><?php echo number_format($totals["net_profit"]/$totals["total_cost"]*100,1)?>%</span>
        </div>
    </div>
</div>

<div class="col-sm-4"  style="border:1px solid #ccc; padding:10px;">
    <div class="row">
        <div class="col-6">
            MLS Number:
        </div>
        <div class="col-6">
            <?php echo $current["MLSNumber"];?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            List Price:
        </div>
        <div class="col-6">
            <?php echo "$".number_format($current["CurrentPrice"]);?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            DOM:
        </div>
        <div class="col-6">
            <?php echo number_format($interval->format('%a'))?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Approx Living Area:
        </div>
        <div class="col-6">
            <?php echo number_format($current["ApproxTotalLivArea"])?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Bed:
        </div>
        <div class="col-6">
            <?php echo $current["BedsTotal"]?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Bath:
        </div>
        <div class="col-6">
            <?php echo (int)$current["BathsTotal"]?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Gar:
        </div>
        <div class="col-6">
            <?php echo $current["Garage"]?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Pool:
        </div>
        <div class="col-6">
            <?php echo $current["PvPool"]==0?"No":"Yes";?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Spa:
        </div>
        <div class="col-6">
            <?php echo $current["Spa"];?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Year Built:
        </div>
        <div class="col-6">
            <?php echo $current["YearBuilt"];?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Lot Size:
        </div>
        <div class="col-6">
            <?php echo number_format($current["LotSqft"]);?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>

    <div class="row">
        <div class="col-6">
            Building Description:
        </div>
        <div class="col-6">
            <?php echo $current["BuildingDescription"];?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Listing Per Foot:
        </div>
        <div class="col-6">
            $<?php echo round($current["ListPrice"]/$current["ApproxTotalLivArea"],2);?>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Zillow Link:
        </div>
        <div class="col-6">
            <a href="<?php echo $zillow?>" target="_blank">Go to Zillow</a>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
</div>
<div class="col-sm-4" style="border:1px solid #ccc; padding:10px;">
    <div class="row">
        <div class="col-6">
            Top 5 ARV:
        </div>
        <div class="col-6">
            <span class="top5arv">$<?php echo number_format($totals["alt_max_arv_totals_av"] * $current["ApproxTotalLivArea"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            MAX ARV:
        </div>
        <div class="col-6">
            <span id="maxarv">$<?php echo number_format($totals["max_comp_sqft"] * $current["ApproxTotalLivArea"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            AV Comps/SQFT:
        </div>
        <div class="col-6">
            <span id="avcompsqft">$<?php echo $totals["total_comp_sqft_av"]?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Comps Max/SQFT:
        </div>
        <div class="col-6">
            <span id="compsmaxsqft">$<?php echo $totals["max_comp_sqft"];?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Comps Min/SQFT:
        </div>
        <div class="col-6">
            <span id="compsminsqft">$<?php echo $totals["min_comp_sqft"]?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            MAX Selling Price:
        </div>
        <div class="col-6">
            <span id="maxsellingprice">$<?php echo number_format((int)$totals["max_close_price"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Min Selling Price:
        </div>
        <div class="col-6">
            <span id="minsellingprice">$<?php echo number_format((int)$totals["min_close_price"])?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            AV Selling Price:
        </div>
        <div class="col-6">
            <span id="avsellingprice">$<?php echo number_format($totals["total_close_price_av"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            AV Sold Living Area:
        </div>
        <div class="col-6">
            <span id="avsoldlivingarea"><?php echo number_format($totals["total_living_area_av"]);?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Average DOM:
        </div>
        <div class="col-6">
            <span id="avdom"><?php echo $totals["total_dom_av"];?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            ARV Override:
        </div>
        <div class="col-6">
            <input type="text" style="width:100%" id="arv-override" value="--">
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            85% ARV Less Rehab:
        </div>
        <div class="col-6">
            <span id="arv-equation" class="offer_price">$<?php echo number_format($totals["offer_price"])?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            System Rehab:
        </div>
        <div class="col-6">
            <span class="rehab-budget">$<?php echo number_format($current["ApproxTotalLivArea"] * $internal_values["rehab"])?></span>
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
    <div class="row">
        <div class="col-6">
            Rehab Override PSF:
        </div>
        <div class="col-6">
            <input type="text" id="rehab-override" style="color:red; width:100%" value="--">
        </div>
    </div>
    <div style="border-bottom:1px solid #ccc"></div>
</div>
</div>

<div class="row" style="margin-top:20px;">
    <p><b>My Property Notes:</b></p>
    <textarea id="notes" style="width:100%; height:100px"><?php echo $notes;?></textarea>
</div>
<div class="row" style="margin-top:20px;">

    <div class="col-3">
        <p><b>Transaction Status:</b></p>
        <form id="message_form" method="post">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user["id"]?>">
            <input type="hidden" name="property_id" id="property_id" value="<?php echo $_GET["id"]?>">
            <label>User</label>
            <select id="to_user_id" name="to_user_id">
                <?php
                if ($users_result->num_rows > 0):
                    $users_result->data_seek(0);
                    while ($owner = $users_result->fetch_assoc()):
                        if($user["id"] != $owner["id"]):?>
                        <option value="<?php echo $owner["id"];?>" ><?php echo $owner["name"];?></option>
                        <?php endif;?>
                    <?php endwhile;
                endif;?>
            </select><br/>
            <textarea style="width:100%; height:100px;" id="message" name="message"></textarea>
            <br/>
            <input type="submit" value="Submit">

        </form>
    </div>

    <div class="col-9" id="messages-content" style="height:180px; overflow-y:scroll;">
        <?php if ($messages->num_rows > 0):
            while($row = $messages->fetch_assoc()):?>
                <br/>
                <div class="row">
                    <p style="border-top:1px solid black;"><?php echo $row["time"]?><br/>
                        From User: <?php echo $row["from_user_name"]?><br/>
                        To User: <?php echo $row["to_user_name"]?><br/>
                        Message: <?php echo $row["message"]?>
                    </p>
                </div>
            <?php endwhile;
        else:?>
            <div class="row">
                No Messages
            </div>
        <?php endif;?>
    </div>
</div>
</div>
</div>
</div>
<div class="col-sm-4" style="font-size:12px;">
    <div class="row">
        <div class="col-12">
            <div id="main-carousel" class="carousel">
                <ol class="carousel-indicators">
                        <?php echo $indicators;?>
                </ol>
                <div class="carousel-inner">
                        <?php echo $photo?>
                </div>
                <a class="carousel-control-prev" href="#main-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#main-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <h5>Listing Agent Information:</h5>
                </div>

            </div>
            <div class="row">
                <div class="col-6">
                    <?php echo $current["ListAgentFullName"]?>
                </div>
                <div class="col-6">
                    <?php echo $current["ListAgentDirectWorkPhone"]?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    Email:
                </div>
                <div class="col-6">
                    <input type="text" style="max-width:100%">
                </div>
            </div>
            <div class="row" style="border-bottom:1px solid #ccc;"></div>
            <div class="row">
                <div class="col-6">
                    Listing Office Name:
                </div>
                <div class="col-6">
                    <?php echo $current["ListOfficeName"];?>
                </div>
            </div>
            <div class="row" style="border-bottom:1px solid #ccc;"></div>
            <div class="row">
                <div class="col-6">
                    Listing Office Number:
                </div>
                <div class="col-6">
                    <?php echo $current["ListOfficePhone"]?>
                </div>
            </div>
            <div class="row" style="border-bottom:1px solid #ccc;"></div>

        </div>
    </div>
    <div class="row" style="margin-top:20px;">
        <div class="col-12">
            <p><b>Summary:</b></p>
            <?php echo $current["PublicRemarks"]?><br/><br/>
        </div>
    </div>
</div>

</div>


<div class="row" style="margin-top:20px;">
    <div class="col-12" style="border:1px solid black;font-size:12px;">
        <table id="comps" class="display">
            <thead>
            <tr>
                <?php /* <th style="width:30px">Dist</th> */?>
                <th>Distance</th>
                <th style="width:80px">MLS#</th>
                <th style="width:100px">Address</th>
                <th style="width:80px">List Price</th>
                <th style="width:80px">Close Price</th>
                <th style="width:50px">Close Price /SQFT</th>
                <th style="width:80px">Bldg Desc</th>
                <th style="width:30px">SQFT</th>
                <th style="width:30px">Beds</th>
                <th style="width:30px">Baths</th>
                <th style="width:30px">Garage</th>
                <th style="width:30px">Pool</th>
                <th style="width:30px">Spa</th>
                <th style="width:30px">YrBlt</th>
                <th style="width:80px">Close Date</th>
                <th style="width:30px">DOM</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<button id="clear_all">Clear All</button>
<div id="map"></div>
</div>

<div class="modal" id="modal-gallery" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
                <div id="modal-carousel" class="carousel">

                    <div class="carousel-inner">
                        <?php echo $large_photo;?>
                    </div>

                    <a class="carousel-control-prev" href="#modal-carousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#modal-carousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap core JavaScript -->
<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/vendor/bootstrap-slider/js/bootstrap-slider.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi-2wYGvfYD07j6vGlXWgP3rcUsC50Fo4&libraries=drawing">
</script>

    <script type="text/javascript">

        $.fn.digits = function(){
            return this.each(function(){
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
            })
        }

    var rehab = 20;

    var arv = <?php echo (int)$totals["alt_max_arv_totals_av"] * $current["ApproxTotalLivArea"];?>;
    var sqft = <?php echo (int)$current["ApproxTotalLivArea"];?>;
    var offer_price = <?php echo $totals["offer_price"]? $totals["offer_price"]:0?>;
    var comps_table;
    var total_cost = parseInt($("#total-cost"));
    var holding_cost = parseInt($("#holding-cost"));
    $(document).ready(function(){

        var $loading = $('#loader-wrapper').hide();

        $(document)
            .ajaxStart(function () {
                $loading.show();
            })
            .ajaxStop(function () {
                $loading.hide();
            });



        $('.carousel').carousel('pause');

        $("#main-carousel .carousel-item img").on("click", function(){
            $("#modal-gallery").modal("show");
            var photo_number = $(this).attr("data-number");
            $("#modal-gallery .active").removeClass("active");
            $("#large-"+photo_number).addClass("active");
        });
        $("#message_form").submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/ajax/update_message",
                dataType:"html",
                data: {user_id: <?php echo $user["id"]?>, to_user_id: $("#to_user_id").val(),property_id: <?php echo $_GET["id"]?>,message:$("#message").val()},
                success: function(data){
                    $("#messages-content").html(data);
                    $("#message").val('');
                }
            });
        });

        $("#arv-override").on("change",function(){
            arv = $(this).val();
            offer_price = parseInt((.85 * parseInt(arv))- (rehab*sqft))
            holding_cost = parseInt((.07 * ( offer_price + (sqft * rehab)))/12*4);
            total_cost = offer_price + parseInt(.01 * offer_price) + parseInt(.045 * arv ) + parseInt(sqft * rehab)+holding_cost - parseInt(.015 * offer_price);
            net_profit = parseInt(arv-total_cost);

            $("#arv-price").html("$"+parseInt(arv));
            $("#arv-price-2").html("$"+parseInt(arv));
            $("#arv-equation").html("$"+offer_price);
            $("#offer-price").html("$"+offer_price);
            $("#purchase-closing-costs").html("$"+ parseInt(.01*offer_price));
            $("#selling-closing-costs").html("$"+ parseInt(arv * .045));
            $("#commission-credit").text(parseInt(.015 * offer_price));
            $("#total-cost").text("$"+total_cost);
            $("#holding-costs").html("$"+ holding_cost);
            $("#net-profit").text("$"+net_profit);
            $("#roi").text(((net_profit/total_cost)*100).toFixed(1)+"%");


        });

        $("#rehab-override").on("change",function(){
            rehab = $(this).val();
            $(".rehab-budget").html("$"+(rehab * sqft));
            offer_price = parseInt((.85 * parseInt(arv))- (rehab*sqft))
            holding_cost = parseInt((.07 * ( offer_price + (sqft * rehab)))/12*4);
            total_cost = offer_price + parseInt(.01 * offer_price) + parseInt(.045 * arv ) + parseInt(sqft * rehab)+holding_cost - parseInt(.015 * offer_price);
            net_profit = parseInt(arv-total_cost);

            $("#arv-equation").html("$"+offer_price);
            $("#offer-price").html("$"+offer_price);
            $("#purchase-closing-costs").html("$"+ parseInt(.01*offer_price));
            $("#selling-closing-costs").html("$"+ parseInt(arv * .045));
            $("#commission-credit").text(parseInt(.015 * offer_price));
            $("#total-cost").text("$"+total_cost);
            $("#holding-costs").html("$"+ holding_cost);
            $("#net-profit").text("$"+net_profit);
            $("#roi").text(((net_profit/total_cost)*100).toFixed(1)+"%");


        });

        $("#clear_all").on("click",function(e){
            e.preventDefault();
            clear_overlays();
            show_all_comps();
        });

        comps_table = $('#comps').DataTable({
            fixedHeader: true,
            scrollY: "400px"
        });

  /*      $('#ex1').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });
        $('#ex2').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });

        $('#ex3').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });

        $("#ex1").on("slide", function(slideEvt) {
            $("#ex1SliderVal").text(slideEvt.value);
        });

        $("#ex2").on("slide", function(slideEvt) {
            $("#ex2SliderVal").text(slideEvt.value);
        });

        $("#ex3").on("slide", function(slideEvt) {
            $("#ex3SliderVal").text(slideEvt.value);
        }); */

        $("#owner").on("change", function(){
            data = $(this).val()
            $.ajax({
                type: "POST",
                url: "/ajax/update_user",
                data: {user_id: data, property_id: <?php echo $_GET["id"]?>},
                success: function(data){
                    $("#messages-content").html(data);
                }
            });
        });

        $("#status").on("change", function(e){
            if($(this).val()!="") {
                data = $(this).val()
                $.ajax({
                    type: "POST",
                    url: "/ajax/update_status",
                    data: {user_id: $("#user_id").val(), status: data, property_id: $("#property_id").val()},
                    success: function (data) {
                        $("#messages-content").html(data);
                        $("#select_status_option").remove();
                    }
                });
            } else {
                e.preventDefault();
                alert("Status must be selected");
            }
        });

        $("#notes").on("change", function(){
            data = $(this).val()
            $.ajax({
                type: "POST",
                url: "/ajax/update_notes",
                data: {notes: $(this).val(), property_id: <?php echo $_GET["id"]?>}
            });
        });

        initMap();

    });
</script>
<script>
    // This example requires the Drawing library. Include the libraries=drawing
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">

    var myLatLng;
    var map;
    var markers = [];
    var all_overlays = [];

    function initMap() {

        myLatLng = {lat: <?php echo $lat?>, lng: <?php echo $long?>};

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: myLatLng
        });

        show_all_comps();

        var home = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5
            }
        });


        var drawingManager = new google.maps.drawing.DrawingManager({

            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [ 'polyline']
            },
            markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
            circleOptions: {
                fillColor: '#ffff00',
                fillOpacity: 1,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1
            }
        });
        //      addHomeMarker(myLatLng,"");
        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            var polygonBounds = event.overlay.getPath();
            var testArray = [];

            for (var a = 0; a < polygonBounds.length; a++)
            {
                testArray.push([polygonBounds.getAt(a).lat(), polygonBounds.getAt(a).lng()]);
            }

            $.post("/ajax/get_polygon_single",{id:$("#property_id").val(),polygon:testArray},function(data){
                comps_table.clear().draw();

                clear_markers();


                //if no arv override was set
                if(arv == parseInt($("#original-arv").val())){
                    arv = data.totals.alt_max_arv_totals_av * sqft;
                    offer_price = data.totals.offer_price;
                    holding_cost = parseInt((.07 * ( offer_price + (sqft * rehab)))/12*4);
                    total_cost = data.totals.total_cost;

                //if arv override was set
                } else {
                    offer_price = parseInt((.85 * parseInt(arv))- (rehab*sqft));
                    holding_cost = parseInt((.07 * ( offer_price + (sqft * rehab)))/12*4);
                    total_cost = offer_price + parseInt(.01 * offer_price) + parseInt(.045 * arv ) + parseInt(sqft * rehab)+holding_cost - parseInt(.015 * offer_price);
                }

                $.each(data.comps,function(index,value){

                    $(".top5arv").text("$"+(arv));
                    $("#maxarv").text("$"+(data.totals.max_comp_sqft * sqft));
                    $("#avcompsqft").text("$"+data.totals.total_comp_sqft_av);
                    $("#compsmaxsqft").text("$"+data.totals.max_comp_sqft);
                    $("#compsminsqft").text("$"+data.totals.min_comp_sqft);
                    $("#maxsellingprice").text("$"+parseInt(data.totals.max_close_price));
                    $("#minsellingprice").text("$"+parseInt(data.totals.min_close_price));
                    $("#avsellingprice").text("$"+parseInt(data.totals.total_close_price_av));
                    $("#avsoldlivingarea").text(data.totals.total_living_area_av);
                    $("#avdom").text(data.totals.total_dom_av);
                    $(".offer_price").text("$"+offer_price);
                    $("#total-cost").text("$"+total_cost);
                    $("#purchase-closing-costs").text("$"+ parseInt(.01*offer_price));
                    $("#selling-closing-costs").text("$"+ parseInt(arv * .045));
                    $("#holding-costs").text("$"+parseInt((.07 * ( offer_price + (sqft * rehab)))/12)*4);
                    net_profit = parseInt(arv-total_cost);
                    $("#net-profit").text("$"+net_profit);
                    $("#roi").text(((net_profit/total_cost)*100).toFixed(1)+"%");

/*                    $(".top5arv").digits();
                    $("#maxarv").digits();
                    $("#minsellingprice").digits();
                    $("#maxsellingprice").digits();
                    $("#avsellingprice").digits();
                    $("#avsoldlivingarea").digits();
                    $(".offer_price").digits();
                    $("#purchase-closing-costs").digits();
                    $("#selling-closing-costs").digits();
                    $("#holding-costs").digits();
                    $("#total-cost").digits();
                    $("#net-profit").digits();
*/
                    addMarker({lat:parseFloat(value.lat),lng:parseFloat(value.lng)});
                    comps_table.row.add( [
                        value.distance,
                        value.MLSNumber,
                        value.address,
                        value.ListPrice,
                        value.ClosePrice,
                        value.ClosePriceSqFt,
                        value.BuildingDescription,
                        value.ApproxTotalLivArea,
                        value.BedsTotal,
                        value.BathsTotal,
                        value.Garage,
                        value.PvPool,
                        value.Spa,
                        value.YearBuilt,
                        value.CloseDate,
                        value.DOM
                    ])
                })
                comps_table.draw();

            },"json");

            all_overlays.push(event);
        });
    }

    function clear_overlays(){
        for (var i=0; i < all_overlays.length; i++)
        {
            all_overlays[i].overlay.setMap(null);
        }
        all_overlays = [];
    }

    function clear_markers(){
        for (var i=0; i < markers.length; i++)
        {
            markers[i].setMap(null);
        }
        markers = [];
    }

    function show_all_comps(){
        $.post("/ajax/get_single",{id:$("#property_id").val()},function(data){
            comps_table.clear().draw();

            clear_markers();

            $.each(data.comps,function(index,value){

                addMarker({lat:parseFloat(value.lat),lng:parseFloat(value.lng)});
                comps_table.row.add( [
                    value.distance,
                    value.MLSNumber,
                    value.address,
                    value.ListPrice,
                    value.ClosePrice,
                    value.ClosePriceSqFt,
                    value.BuildingDescription,
                    value.ApproxTotalLivArea,
                    value.BedsTotal,
                    value.BathsTotal,
                    value.Garage,
                    value.PvPool,
                    value.Spa,
                    value.YearBuilt,
                    value.CloseDate,
                    value.DOM
                ])
            })
            comps_table.draw();

        },"json");
    }

    function showMarkers(){
        var home = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5
            }
        });


        /*$("tr").each(function(index){
                addMarker({lat:parseFloat($(this).attr("data-lat")),lng:parseFloat($(this).attr("data-lng"))},$(this).attr("data-address"));
        }); */

    }

    /*    function addHomeMarker(location,address){
     var marker = new google.maps.Marker({
     position: location,
     map: map,
     title:address
     icon: {
     path: google.maps.SymbolPath.CIRCLE,
     scale: 10
     }
     });


     }
     */
    function addMarker(location,address) {
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title:address
        });
        markers.push(marker);
    }

</script>
</body>
</html>