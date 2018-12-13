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
            <?php
            if(!empty($success_msg)){
                echo '<p class="statusMsg">'.$success_msg.'</p>';
            }elseif(!empty($error_msg)){
                echo '<p class="statusMsg">'.$error_msg.'</p>';
            }
            ?>
            <form class="form-horizontal" method="post" action="#">

                <div class="form-group">
                    <label for="name" class="cols-sm-2 control-label">Email</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="email" class="form-control" name="email" placeholder="Email" required="" value="">
                            <?php echo form_error('email','<span class="help-block">','</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="cols-sm-2 control-label">Password</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required="">
                            <?php echo form_error('password','<span class="help-block">','</span>'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <input type="submit" name="loginSubmit" class="btn-primary" value="Submit"/>
                </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>