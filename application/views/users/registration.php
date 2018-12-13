<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">

    <!-- Website CSS style -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">

    <!-- Website Font style -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

    <title>Admin</title>
</head>
<body>
<div class="container">
    <div class="row main">
        <div class="main-login main-center">
            <form class="form-horizontal" method="post" action="#">

                <div class="form-group">
                    <label for="name" class="cols-sm-2 control-label">Your Name</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="name" placeholder="Name" required="" value="<?php echo !empty($user['name'])?$user['name']:''; ?>">
                            <?php echo form_error('name','<span class="help-block">','</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="cols-sm-2 control-label">Your Email</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                            <input type="email" class="form-control" name="email" placeholder="Email" required="" value="<?php echo !empty($user['email'])?$user['email']:''; ?>">
                            <?php echo form_error('email','<span class="help-block">','</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="cols-sm-2 control-label">Phone</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo !empty($user['phone'])?$user['phone']:''; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="cols-sm-2 control-label">Color</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-paint-brush fa" aria-hidden="true"></i></span>
                            <input class="form-control" name="color" type='text' id="custom" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label">Password</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required="">
                            <?php echo form_error('password','<span class="help-block">','</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="conf_password" placeholder="Confirm password" required="">
                            <?php echo form_error('conf_password','<span class="help-block">','</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <input type="submit" name="regisSubmit" class="btn-primary" value="Submit"/>
                </div>

            </form>
        </div>
    </div>
</div>
<script src="/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
<script>
    $("#custom").spectrum({
        color: "#f00",
        preferredFormat: "hex"
    });
</script>
</body>
</html>