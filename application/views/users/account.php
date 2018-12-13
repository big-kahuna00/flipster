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
    <div class="column">
    <div class="row main admin">
    <h2>User Account</h2>
    <h3>Welcome <?php echo $user['name']; ?>!</h3>
    <div class="account-info">
        <p><b>Name: </b><?php echo $user['name']; ?></p>
        <p><b>Email: </b><?php echo $user['email']; ?></p>
        <p><b>Phone: </b><?php echo $user['phone']; ?></p>
        <br/>
        <br/>
        <a href="/games/create"><button type="button" class="btn btn-primary">Create a Game</button></a>
    </div>

        </div>


</div>
</body>
</html>