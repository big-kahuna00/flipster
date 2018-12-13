<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Users</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('/assets/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('/assets/css/style.css')?>" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; }
        #sortable li { margin: 10px 0 ; padding: 0; background:#efefef; border: 1px solid black;}
        #sortable td { padding:10px; border: solid 1px #9d9d9d;}
        label {font-weight:bold;}
         input[type="text"] {text-align:right;}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Flipster.io</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>

        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/users">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <?php if($user["user_type"]==1):?>
                    <li class="nav-item active">
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

<div class="container" style="margin-top:70px;">
    <div class="column">
        <div class="row admin">
            <h3>Manage Users</h3>
        </div>
        <div class="row">

        <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display:block;">
            <thead>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>User Phone</th>
                <th>User Color</th>
                <th>User Type</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user){?>
                <tr>
                    <td><?php echo $user->id;?></td>
                    <td><?php echo $user->name;?></td>
                    <td><?php echo $user->email;?></td>
                    <td><?php echo $user->phone;?></td>
                    <td>
                        <span style="display:block;height:20px;width:20px; border:2px black solid; background:<?php echo $user->color;?>">

                        </span>
                    </td>
                    <td><?php echo $user->user_type==1?"Admin":"User"?></td>
                    <td>
                        <!--<button class="btn btn-warning" onclick="edit_user(<?php echo $user->id?>)">Edit</button>-->
                        <button class="btn btn-danger" onclick="delete_user(<?php echo $user->id?>)">X</button>


                    </td>
                </tr>
            <?php }?>



            </tbody>

        </table>
        </div>
        <div class="row">            <p style="clear:both;"><a href="/users/registration">Create User</a></p>
        </div>
</div>
<div class="row">
    <h2>Site Settings</h2>
    </div>
    <div class="row">
                <div class="col-sm-4">
                    <label>Rehab Price Per Square Foot: </label>
                </div>
                <div class="col-sm-8">
                    $<input type="text" value="20">
                </div>
        <div class="col-sm-4">
                <label>Offer Price Percentage: </label>
        </div>
        <div class="col-sm-8">
                <input type="text" value="85">%
        </div>
        <div class="col-sm-4">
                <label>Commission Cost Percentage: </label>
        </div>
        <div class="col-sm-8">
            <input type="text" value="1.5">%
        </div>
        <div class="col-sm-4">
            <label>Selling Closing Cost Percentage: </label>
        </div>
        <div class="col-sm-8">
            <input type="text" value="4.5">%
        </div>
        <div class="col-sm-4">
            <label>Purchase Closing Cost Percentage: </label>
        </div>
        <div class="col-sm-8">
            <input type="text" value="1">%
        </div>
        <div class="col-sm-4">
            <label>Holding Cost Percentage: </label>
        </div>
        <div class="col-sm-8">
            <input type="text" value="7">%
        </div>
        <div class="col-sm-4">
                <label>Holding Cost Time: </label>
        </div>
        <div class="col-sm-8">
            <input type="text" value="4"> Months
        </div>

        <div class="col-sm-4">
            <label>Excluded Regions </label>
        </div>
            <div class="col-sm-8">
                <select multiple>
                    <option>101 - North</option>
                    <option>102 - North</option>
                    <option>103 - North</option>
                    <option>201 - East</option>
                    <option>202 - East</option>
                    <option>203 - East</option>
                    <option>204 - East</option>
                    <option>301 - South</option>
                    <option>302 - South</option>
                    <option>303 - South</option>
                    <option>401 - North West</option>
                    <option>402 - North West</option>
                    <option>403 - North West</option>
                    <option>404 - North West</option>
                    <option>405 - North West</option>
                    <option>501 - South West</option>
                    <option>502 - South West</option>
                    <option>503 - South West</option>
                    <option>504 - South West</option>
                    <option>505 - South West</option>
                    <option>601 - Henderson</option>
                    <option>602 - Henderson</option>
                    <option>603 - Henderson</option>
                    <option>604 - Henderson</option>
                    <option>605 - Henderson</option>
                    <option>606 - Henderson</option>
                    <option>701 - Boulder City</option>
                    <option>702 - Boulder City</option>
                    <option selected>800 - Mesquite</option>
                    <option selected>801 - Muddy River(Moapa, Glendale, Logandaleand Overton)</option>
                    <option selected>802 - Mt. Charleston/Lee Canyon</option>
                    <option selected>803 - Indian Springs/Cold Creek</option>
                    <option selected>804 - Mountain Springs</option>
                    <option selected>805 - Blue Diamond</option>
                    <option selected>806 - State Line/Jean/Goodsprings</option>
                    <option selected>807 - Sandy Valley</option>
                    <option selected>808 - Laughlin</option>
                    <option selected>809 - Other Clark County</option>
                    <option selected>810 - Pahrump</option>
                    <option selected>811 - Nye County</option>
                    <option selected>812 - Lincoln County</option>
                    <option selected>813 - Other Nevada</option>
                    <option selected>814 - Amargosa Valley</option>
                    <option selected>815 - Beatty</option>
                    <option selected>816 - White Pine County</option>
                    <option selected>817 - Searchlight</option>
                    <option selected>900 - Outside Nevada</option>
                    <option selected>950 - Outside the U. S.</option>
                </select>
            </div>
            <div class="col-sm-4"><label>Building Description Group #1</label></div>
            <div class="col-sm-8">
                <div class="border:1px solid black;">
                    <ul>
                        <?php foreach($building_descriptions as $description):?>
                            <?php if( strpos( $description["group_id"], "1" ) !== false):?>
                        <li><?php echo $description["description"]?></li>
                        <?php endif;?>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <div class="col-sm-4"><label>Building Description Group #2</label></div>
        <div class="col-sm-8">
            <div class="border:1px solid black;">
                <ul>
                    <?php foreach($building_descriptions as $description):?>
                        <?php if( strpos( $description["group_id"], "2" ) !== false):?>
                            <li><?php echo $description["description"]?></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="col-sm-4"><label>Building Description Group #3</label></div>
        <div class="col-sm-8">
            <div class="border:1px solid black;">
                <ul>
                    <?php foreach($building_descriptions as $description):?>
                        <?php if( strpos( $description["group_id"], "3" ) !== false):?>
                            <li><?php echo $description["description"]?></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        </div>

        <div class="form-group">
                <button>Save Settings</button>
        </div>





<div class="row">

    <h2>Comp Passes</h2>
    </div>
<div class="row">
    <ul id="sortable">
        <li class="ui-state-default"><table>
                <tr>
                    <td>1<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
                    <td>
                        <b>Square Footage Within</b>: 20%<br/>
                        <b>Distance Within</b>: .5 miles<br/>
                        <b>Year Built Within</b>: 10 Years<br/>
                        <b>Sold Date Within</b>: 6 months<br/>
                        <b>Like properties according to syatem building description settings</b>
                    </td>
                    <td><a href="#" style="color:red">Delete Pass</a></td>
                </tr>
            </table>
        </li>
    <li class="ui-state-default"><table>
            <tr>
                <td>2<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
                <td>
                    <b>Square Footage Within</b>: 25%<br/>
                    <b>Distance Within</b>: .5 miles<br/>
                    <b>Year Built Within</b>: 10 Years<br/>
                    <b>Sold Date Within</b>: 6 months<br/>
                    <b>Like properties according to syatem building description settings</b>
                </td>
                <td><a href="#" style="color:red">Delete Pass</a></td>
           </tr>
        </table>
    </li>
    <li class="ui-state-default"><table>
            <tr>
                <td>3<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
                <td>
                    <b>Square Footage Within</b>: 30%<br/>
                    <b>Distance Within</b>: .5 miles<br/>
                    <b>Sold Date Within</b>: 6 months<br/>
                    <b>Year Built Within</b>: 10 Years<br/>
                    <b>Like properties according to syatem building description settings</b>
                </td>
                <td><a href="#" style="color:red">Delete Pass</a></td>
            </tr>
        </table>
    </li>
    <li class="ui-state-default"><table>
            <tr>
                <td>4<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
                <td>
                    <b>Square Footage Within</b>: 20%<br/>
                    <b>Distance Within</b>: 1 miles<br/>
                    <b>Year Built Within</b>: 10 Years<br/>
                    <b>Sold Date Within</b>: 6 months<br/>
                    <b>Like properties according to syatem building description settings</b>
                </td>
                <td><a href="#" style="color:red">Delete Pass</a></td>
            </tr>
        </table>
    </li>
        <li class="ui-state-default"><table>
                <tr>
                    <td>5<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
                    <td>
                        <b>Square Footage Within</b>: 25%<br/>
                        <b>Distance Within</b>: 1 miles<br/>
                        <b>Year Built Within</b>: 10 Years<br/>
                        <b>Sold Date Within</b>: 6 months<br/>
                        <b>Like properties according to syatem building description settings</b>
                    </td>
                    <td><a href="#" style="color:red">Delete Pass</a></td>
                </tr>
            </table>
        </li>
        <li class="ui-state-default"><table>
                <tr>
                    <td>6<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
                    <td>
                        <b>Square Footage Within</b>: 30%<br/>
                        <b>Distance Within</b>: 1 miles<br/>
                        <b>Year Built Within</b>: 10 Years<br/>
                        <b>Sold Date Within</b>: 6 months<br/>
                        <b>Like properties according to syatem building description settings</b>
                    </td>
                    <td><a href="#" style="color:red">Delete Pass</a></td>
                </tr>
            </table>
        </li>
    </ul>
    </div>
<div class="row">
    <a href="/users/new_pass">Add New Pass</a>
</div>



</div>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="currency_id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">User Name</label>
                            <div class="col-md-9">
                                <input name="currency_name" placeholder="Currency Name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Email</label>
                            <div class="col-md-9">
                                <input name="currency_nickname" placeholder="User Email" class="form-control" type="text">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User Phone</label>
                            <div class="col-md-9">
                                <input name="currency_category" placeholder="User Phone" class="form-control" type="text">

                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script type="text/javascript" src="/assets/js/bootstrap.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#table_id').DataTable();
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
} );
var save_method; //for save method string
var table;

function edit_user(id)
{
save_method = 'update';
$('#form')[0].reset(); // reset form on modals

//Ajax Load data from ajax
$.ajax({
url : "<?php echo site_url('/users/ajax_edit')?>/" + id,
type: "GET",
dataType: "JSON",
success: function(data)
{

$('[name="id"]').val(data.user_id);
$('[name="name"]').val(data.user_id);
$('[name="email"]').val(data.user_email);
$('[name="phone"]').val(data.user_phone);


$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
$('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

},
error: function (jqXHR, textStatus, errorThrown)
{
alert('Error get data from ajax');
}
});
}



function save()
{
var url;

url = "<?php echo site_url('Users/user_update')?>";


// ajax adding data to database
$.ajax({
url : url,
type: "POST",
data: $('#form').serialize(),
dataType: "JSON",
success: function(data)
{
//if success close modal and reload ajax table
$('#modal_form').modal('hide');
location.reload();// for reload a page
},
error: function (jqXHR, textStatus, errorThrown)
{
alert('Error adding / update data');
}
});
}

function delete_user(id)
{
if(confirm('Are you sure delete this data?'))
{
// ajax delete data from database
$.ajax({
url : "<?php echo site_url('Users/delete')?>/"+id,
type: "POST",
dataType: "JSON",
success: function(data)
{

location.reload();
},
error: function (jqXHR, textStatus, errorThrown)
{
alert('Error deleting data');
}
});

}
}

</script>

</body>
</html>