<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Users</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('/assets/css/style.css')?>" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>


<div class="container">
    <div class="column">
        <div class="row admin">
            <h3>Create Pass</h3>
        </div>
        <div class="row">

        <div class="form-body">

        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Sq Ft</label>
            <div class="">
                <label>Within</label> <input type="text" value=""> <label>%</label>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Distance</label>
            <div class="">
                <label>Within</label> <input type="text" value=""> <label>Miles</label><br/>
            </div>
        </div>

        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Sold Date</label>
            <div class="">
                    <label>Within</label> <input type="text" value=""> <label>Months</label>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Bedroom</label>
            <div class="">
                    <label>Within</label> <input type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Bathroom</label>
            <div class="">
                    <label>Within</label> <input type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Garage</label>
            <div class="">
                    <label>Within</label> <input type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Year Built</label>
            <div class="">
                    <label>Within</label> <input type="text" value=""> <label>Years</label>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Spa</label>
            <div class="">
                <select>
                    <option>Same</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox"><label class="control-label ">Pool</label>
            <div class="">
                <select>
                    <option>Same</option>
                </select>
            </div>
        </div>
            <div class="form-group">
                <div class="">
                    <button>Save Pass</button>
                </div>
            </div>

    </div>
</div>

</body>
</html>