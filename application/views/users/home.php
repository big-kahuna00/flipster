<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Flipster.io</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
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
        #map{
            height:600px;
        }

        <?php
                if ($users_result->num_rows > 0):
                    while ($user_color = $users_result->fetch_assoc()): ?>
        .color-<?php echo $user_color["id"]?>{
            color:<?php echo $user_color["color"];?>
        }
        <?php endwhile;
    endif;
?>

    </style>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">


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
                <?php if($user["user_type"]==1):?>
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
            <div class="row main-info">

                <div class="col-lg-2">
                    Price min: <input type="input" id="price_min"><br/>
                    Price max: <input type="input" id="price_max">
                </div>

                <div class="col-lg-2">
                    DOM min: <input type="input" id="dom_min"><br/>
                    DOM max: <input type="input" id="dom_max">
                </div>

                <div class="col-lg-2">
                    Offer Price/List Price % min: <input type="input" id="percentage_min"><br/>
                    Offer Price/List Price % max: <input type="input" id="percentage_max">
                </div>

                <div class="col-lg-2">
                    Zip Code: <input type="input" id="zip_code">
                </div>
                <div class="col-lg-3">
                    <button type="button" id="clear_filters" class="btn btn-primary">Clear Filters</button>

                    <button type="button" id="clear_map" class="btn btn-primary">Clear Map</button>
                </div>
            </div>
            <div class="row"></div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list-content" role="tab" aria-controls="list" aria-selected="true">List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="map-tab" data-toggle="tab" href="#map" role="tab" aria-controls="map" aria-selected="false">Map</a>
                </li>
            </ul>


            <div class="tab-content">
                <div class="tab-pane active" id="list-content" role="tabpanel" aria-labelledby="list-tab">
                    <table id="list" class="display">
                        <thead>
                        <th>MLS #</th>
                        <th>Address</th>
                        <th>Zip Code</th>
                        <th>Offer Price to List Price Ratio</th>
                        <th>Offer Price</th>
                        <th>List Price</th>
                        <th>Bldg Desc</th>
                        <th>Sqft</th>
                        <th>Beds</th>
                        <th>Baths</th>
                        <th>Garage</th>
                        <th>Pool</th>
                        <th>Spa</th>
                        <th>YrBuilt</th>
                        <?php /*<th>Comp Distance</th>
                        <th>Sqft Offset</th>
                        <th>YearBuilt Offset</th>
                        <th>Included Bldg Desc</th> */?>
                        <th>DOM</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Lat</th>
                        <th>Lng</th>

                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="map" role="tabpanel" aria-labelledby="MAP-tab">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/assets/js/jquery.cookie.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi-2wYGvfYD07j6vGlXWgP3rcUsC50Fo4&callback=initMap&libraries=drawing">
</script>
<script src="/assets/js/list.js"></script>

</body>
</html >