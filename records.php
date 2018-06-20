<?php
    require("config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: login.php");
        die("Redirecting to login.php"); 
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">

    <title>Winners Tracker: Records</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="jquery/datepicker/css/datepicker.css" type="text/css" rel="stylesheet">
    <link type="text/css" href="jquery/datatables/media/css/jquery.dataTables.css">
    <link type="text/css" href="jquery/validator/css/bootstrapValidator.css"/>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/livevalidation.js"></script>
    <script src="jquery/datepicker/js/datepicker.js"></script>
    <script src="jquery/datatables/media/js/jquery.dataTables.js"></script>
    <script src="jquery/validator/js/bootstrapValidator.js"></script>
    <script src="jquery/datatables/media/js/dataTables.bootstrap.js"></script>


    <script>
        function send() 
        {
            $("#dateselected").append("\n" + $("#system-search").val());
        }
    </script>

    <script>
    window.setTimeout(function() {
     $(".alert").fadeTo(1000, 0).slideUp(500, function(){
          $(this).remove(); 
     });
        }, 2000);

    </script>

    <style>
        td.verify {
            width: 200px;
        }
    </style>

       
</head>

<body>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="records.php">Winners Tracker</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li ><a href="#"><span class="glyphicon glyphicon-list"></span>Reports</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                    class="glyphicon glyphicon-user"></span><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    </nav>


    <div id="wrap">
        <div class="container">
            <div class="span8 col-md-12"></div>
            <br>
                <!--nav-/-->
            <div id="nav" class="col-md-12">
                <ul class="nav nav-tabs ">
                    <li class="active"><a href="#notclaimed" data-toggle="tab"><span class="glyphicon glyphicon-remove"></span> Not Claimed</a></li>
                    <li ><a href="#claimed" data-toggle="tab"><span class="glyphicon glyphicon-ok"></span> Claimed</a></li>

            </div> <!--end nav-->

            <div class="tab-content">
                <!--not claimed-->    
                <div class="col-md-12 tab-pane active" id="notclaimed" \>
                    <br>   
                    <div class="well" style="overflow: auto">
                        <div class ="row">
                           <div class="col-md-7" id="custom-search-input">
                           <form action="#" method="get">
                                <div class="input-group" >
                                    <input type="text" id="system-search" class="search-query form-control" name="q" placeholder="Search" required/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button">
                                            <span class=" glyphicon glyphicon-search"></span>
                                        </button>
                                    </span>
                                </div>
                            </form>
                            </div>

                        </div>
                    </div>
                            <?php 
                                if(isset($_POST['collect']))
                                {

                                    include("connect.php");

                                    $winnerid = $_POST['winnerid'];
                                    $winfirst = $_POST['winner_fname'];
                                    $winlast = $_POST['winner_lname'];

                                    $insert = $conn->prepare("INSERT INTO collected (promodate, fname, lname, promotion, showname, presenter, prize, telephone, idno, datecollected) SELECT promodate, fname, lname, promotion, showname, presenter, prize, telephone, idno, datecollected FROM winnerslist WHERE fname = '$winfirst' AND lname = '$winlast'");
                                    
                                    $update = $conn->prepare("UPDATE collected SET datecollected = NOW(), idno = '$winnerid' WHERE fname = '$winfirst' AND lname = '$winlast'");
                                    
                                    $delete = $conn->prepare("DELETE FROM winnerslist WHERE fname = '$winfirst' AND lname = '$winlast'");

                                    $insert->execute();

                                    $update->execute();

                                    $delete->execute(); 

                                    if (!$insert || !$update || !$delete)
                                    {
                                      die('<div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Failure!</strong> Something went wrong. Please try again. 
                                           </div>' . mysql_error());

                                    }else{
                                      echo '<div class="alert alert-success alert-dismissable">
                                               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                               <strong>Success!</strong> The record has been marked as <strong>claimed</strong>.
                                            </div>';
                                    }

                                  unset($_POST);


                                  //mysql_close($conn);
                              }
                        ?>  
                        
                        <?php
                                //set up mysql connection
                                include("connect.php");
                                // Retrieve all the data from the "winnerslist" table
                                $sql = $conn->prepare("SELECT * FROM winnerslist ORDER BY promodate DESC ");
                                $sql->execute();
                                $counter = 1;
                            ?>

                         <table class="table table-list-search table-striped table-bordered " id="table_notclaimed">
                                        <thead>
                                            <tr class="row-of-data">
                                                <th>#</th>
                                                <th>Promo Date</th>
                                                <th>Name</th>
                                                <th>Telephone</th>
                                                <th>Promotion</th>
                                                <th>Prize</th>
                                                <th>Show</th>
                                                <th>Presenter</th>
                                                <th>Collect Prize</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                 <?php                                      
                                    
                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    //Print out the contents of the database
                                ?>
                                    <tr>
                                    <td> <?php echo $counter; ?> </td>
                                    <td> <?php echo date("d-m-Y, H:i:s",strtotime($row['promodate'])); ?></td>
                                    <td> <?php echo $row['fname'] .' '. $row['lname'] ; ?> </td>
                                    <td> <?php echo $row['telephone']; ?> </td>
                                    <td> <?php echo $row['promotion'] ; ?> </td>
                                    <td> <?php echo $row['prize']; ?> </td>
                                    <td> <?php echo $row['showname']; ?> </td>
                                    <td> <?php echo $row['presenter']; ?> </td>
                                    <td class="verify"> <?php echo '<form id="collectForm" action="" method="post">
                                                        <div class="">
                                                            <div class="input-group">
                                                            <input type="text" name="winner_fname" value="'.$row['fname'].'" hidden>
                                                            <input type="text" name="winner_lname" value="'.$row['lname'].'" hidden>
                                                              <input type="number" name="winnerid" placeholder="Verification" class="form-control " required>
                                                              <span class="input-group-btn">
                                                                <button class="btn btn-default" name="collect" id="doCollect" type="submit">&#10004</button>
                                                              </span>
                                                            </div>
                                                          </div>
                                                    </form>'; ?> 
                                    </td>
                                <?php
                                    $counter++;
                                    }
                                ?>  
                                    </tbody></table>
                         
                </div><!--/#notclaimed-->

                <!--claimed--> 
                <div class="col-md-12 tab-pane fade" id="claimed" \>
                    <br>

                    <div class="well" style="overflow: auto">
                        <div class ="row">
                           <div class="col-md-7" id="custom-search-input">
                           <form action="#" method="get">
                                <div class="input-group" >
                                    <input type="text" id="system-search2" class="search-query form-control" name="q" placeholder="Search" required/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button">
                                            <span class=" glyphicon glyphicon-search"></span>
                                        </button>
                                    </span>
                                </div>
                            </form>
                            </div>

                        </div>
                    </div>
                            <?php
                                //set up mysql connection
                                include("connect.php");
                                
                                $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
                                $conn->exec("set names utf8");
                                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

                                // Retrieve all the data from the "winnerslist" table
                               $sql = $conn->prepare("SELECT * FROM collected ORDER BY promodate DESC");
                               $sql->execute();

                                $counter = 1;
                            ?>

                            <table class="table table-list-search2 table-striped table-bordered" id="table_claimed">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Promo Date</th>
                                                <th>Name</th>
                                                <th>Telephone</th>
                                                <th>Promotion</th>
                                                <th>Prize</th>
                                                <th>Show</th>
                                                <th>Presenter</th>
                                                <th>Verification</th>
                                                <th>Date Collected</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                 <?php                                      
                                    
                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    //Print out the contents of the database
                                ?>
                                    <tr>
                                        <td> <?php echo $counter; ?> </td>
                                        <td> <?php echo date("d-m-Y, H:i:s",strtotime($row['promodate'])); ?> </td>
                                        <td> <?php echo $row['fname'] .' '. $row['lname'] ; ?> </td>
                                        <td> <?php echo $row['telephone']; ?> </td>
                                        <td> <?php echo $row['promotion'] ; ?> </td>
                                        <td> <?php echo $row['prize']; ?> </td>
                                        <td> <?php echo $row['showname']; ?> </td>
                                        <td> <?php echo $row['presenter']; ?> </td>
                                        <td> <?php echo $row['idno']; ?> </td>
                                        <td> <?php echo date("d-m-Y, H:i:s",strtotime($row['datecollected'])); ?> </td>
                                    </tr>
                                <?php
                                    $counter++;
                                    }
                                ?>  
                                    </tbody></table>
                        <?php
                        ?>    
                </div><!--/#claimed-->


            </div><!--tab-content-->
        </div><!--/.container-->
    </div><!--wrap-->

    <!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
        <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×</button>
                <span class=" glyphicon glyphicon-ban-circle"></span> <strong>No Access</strong>
                <hr class="message-inner-separator">
                <p class="text-center">
                    You do not have permission to view that page.</p>
            </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    $(document).ready( function () {
        $('#table_notclaimed').dataTable({
        "bSort": true,
        "bFilter": false,
        "bLengthChange": false,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "asStripClasses": null //To remove "odd"/"event" zebra classes
        });
    });

    $(document).ready( function () {
        $('#table_claimed').dataTable({
        "bSort": true,
        "bFilter": false,
        "bLengthChange": false,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "asStripClasses": null //To remove "odd"/"event" zebra classes
        });
    });

    $(document).ready(function() {
        $('#').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "datatables_processing.php"
        });
    });
</script>

<script>
    $('#datepicker').datepicker({
        autoclose:true
    });
</script>

<script>
    $('#datepicker2').datepicker({
        autoclose:true
    })
</script>



</body>

</html>

