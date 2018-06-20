<?php
    require("config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: login.php");
        die("Redirecting to login.php"); 
    }
?>


<script>
    window.onload=function() {
        if(<?php htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8')?> == "Admin")
        {
            document.getElementById("noAuth").setAttribute("id", "dummy"); 
        }
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">

    <title>Winners Tracker: Reports</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="jquery/datepicker/css/datepicker.css" type="text/css" rel="stylesheet">
    <link type="text/css" href="jquery/datatables/media/css/jquery.dataTables.css">
    <link type="text/css" href="jquery/tabletools/media/css/tabletools.css">
    <link type="text/css" href="jquery/validator/css/bootstrapValidator.css"/>


    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="jquery/tablesorter/jquery.tablesorter.min.js"></script>
    <script src="jquery/datepicker/js/datepicker.js"></script>
    <script src="jquery/datatables/media/js/jquery.dataTables.js"></script>
    <script src="jquery/tabletools/media/js/tabletools.min.js"></script>
    <script src="jquery/tabletools/media/zeroclipboard/zeroclipboard.js"></script>
    <script src="js/bootbox.js"></script>

    <script src="jquery/datatables/media/js/dataTables.bootstrap.js"></script>


    <script>
        function send() 
        {
            $("#dateselected").append("\n" + $("#system-search").val());
        }
    </script>



   
       
</head>

<body>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Winners Tracker</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php"><span class=" glyphicon glyphicon-pencil"></span>Add Winner</a></li>
                <li><a href="adminrecords.php"><span class="glyphicon glyphicon-list-alt"></span>Records</a></li>
                <li class="active" ><a href="#"><span class="glyphicon glyphicon-list"></span>Reports</a></li>
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

            <div class="tab-content">
          
                <!--weeklyreports--> 
                <div class="col-md-12" id="weeklyreports" \>
                    <br>

                     <div class="well" style="overflow: auto">
                        <div class ="row">
                            <form class="form-inline" name="fetchreport" id="fetchreport" method="post" >
                                <fieldset>
                                    <div class="col-md-3"> 
                                        <label class="control-label">Start Date</label> 
                                        <div class="input-group date" id="datepicker" data-date="" data-date-format="dd-mm-yyyy"> 
                                            <input class="form-control" type="text" readonly="" value="<?php if(isset($_POST['report'])){ 
                                                                                                        echo date("d-m-Y ",strtotime($_POST['startdate']));
                                                                                                        }?>" name="startdate"> 
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> 
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">End Date</label> 
                                        <div class="input-group date" id="datepicker2" data-date="" data-date-format="dd-mm-yyyy"> 
                                            <input class="form-control" type="text" readonly="" value="<?php if(isset($_POST['report'])){ 
                                                                                                        echo date("d-m-Y",strtotime($_POST['enddate']));
                                                                                                        }?>" name="enddate"> 
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <label class="control-label "></label> 
                                        <div class="input-group date"> 
                                            <button class="btn btn-inverse btn-lg" type="submit" name="report">
                                                Generate Report</button> 
                                        </div>
                                    </div> 
                                     
                                </fieldset>
                                
                            </form>
                        </div>
                    </div>

                        <?php

                            if(isset($_POST['report']))
                            {
                            //set up mysql connection
                            include("connect.php");

                               $fromdate=date("Y-m-d", strtotime($_POST['startdate']));
                               $todate=date("Y-m-d", strtotime($_POST['enddate']));



                                $sql = $conn->prepare("SELECT promodate, fname, lname, promotion, showname, presenter, prize, telephone, idno, datecollected FROM winnerslist 
                                                        WHERE (DATE(promodate) BETWEEN '$fromdate'AND '$todate' || DATE(promodate) BETWEEN '$todate'AND '$fromdate') UNION 
                                                        SELECT promodate, fname, lname, promotion, showname, presenter, prize, telephone, idno, datecollected FROM collected 
                                                        WHERE (DATE(promodate) BETWEEN '$fromdate'AND '$todate' || DATE(promodate) BETWEEN '$todate'AND '$fromdate') ORDER BY promodate ASC");

                                $sql->execute();
                                $counter = 1;

                                 echo '<div class="alert alert-info">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                    Showing records from <strong>'.date("F d, Y",strtotime($fromdate)).'</strong> — <strong>'.date("F d, Y",strtotime($todate)).'</strong> 
                                </div>';
                                
                        ?>

                                <table class="table table-list-search table-striped table-bordered " id="table_reports">
                                    <thead>
                                        <tr class="row-of-data">
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Telephone</th>
                                            <th>Promotion</th>
                                            <th>Prize</th>
                                            <th>Show</th>
                                            <th>Presenter</th>
                                            <th>Verification</th>
                                            <th>Claimed</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                <?php                                      
                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                    //Print out the contents of the database
                                ?>
                                    <tr>
                                        <td> <?php echo $counter; ?> </td>
                                        <td> <?php echo date("d-m-Y, H:i:s",strtotime($row['promodate'])); ?>  </td>
                                        <td> <?php echo $row['fname'] .' '. $row['lname'] ; ?> </td>
                                        <td> <?php echo $row['telephone']; ?> </td>
                                        <td> <?php echo $row['promotion'] ; ?> </td>
                                        <td> <?php echo $row['prize']; ?> </td>
                                        <td> <?php echo $row['showname']; ?> </td>
                                        <td> <?php echo $row['presenter']; ?> </td>
                                        <td> <?php echo $row['idno'];
                                            ?>
                                        </td>
                                        <td> <?php if ($row['idno'] != null){
                                                    echo "<strong>".date("d-m-Y, H:i:s",strtotime($row['promodate']))."</strong>";
                                                    }else{
                                                    echo "Not Claimed";
                                                    }
                                              ?> 
                                        </td>
                                    </tr>
                                <?php
                                    $counter++;
                                    }
                                ?>  
                                    </tbody></table>
                                <?php

                                }
                                ?>    
                </div><!--/#weeklyreports-->

            </div><!--tab-content-->
        </div><!--/.container-->
    </div><!--wrap-->

<script type="text/javascript">
    $(document).ready( function () {
        $('#table_reports').dataTable({
        "sDom": 'lT<"clear">frtip',
        "oTableTools": {
            "sSwfPath": "jquery/tabletools/media/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {
                    "sExtends": "print",
                    "sInfo": "Please use your browser's print function to print this table. Press escape when finished.",
                    "sButtonText": '<span class="glyphicon glyphicon-print"></span>  Print',
                    "sMessage": "Promotion Winners: <?php echo '<strong>' . date('d-m-Y',strtotime($fromdate)).'</strong> to <strong>'.date('d-m-Y',strtotime($todate)).'</strong>';?>"
                },
                {
                    "sExtends":"pdf",
                    "sButtonText": '<span class="glyphicon glyphicon-save"></span>  PDF',
                    "sPdfMessage": "Promotion Winners: <?php echo '<strong>' . date('d-m-Y',strtotime($fromdate)).'</strong> to <strong>'.date('d-m-Y',strtotime($todate)).'</strong>';?>"
                },
                
            ]  
        },
        "bPaginate":false,
        "bLengthChange":false,
        "bFilter": false,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "asStripClasses": null,
        "oLanguage": {
            "sLengthMenu": "_MENU_ Records per page"
        }
        });
    });
</script>


<script>
    $('#datepicker').datepicker({
        
        autoclose: true
    });
    $('#datepicker2').datepicker({
       
        autoclose: true
    });
</script>

</body>

</html>

   