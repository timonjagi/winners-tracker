

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">

    <title>Radio Africa Winners Tracker</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="jquery/datepicker/css/datepicker.css" type="text/css" rel="stylesheet">
    <link type="text/css" href="jquery/datatables/media/css/jquery.dataTables.css">

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="jquery/tablesorter/jquery.tablesorter.min.js"></script>
    <script src="jquery/datepicker/js/datepicker.js"></script>
    <script src="jquery/datatables/media/js/jquery.dataTables.js"></script>

    <script src="jquery/datatables/media/js/dataTables.bootstrap.js"></script>
    <script src="js/bootbox.min.js"></script>
    

  <style>
.form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
    margin-bottom: 10px;
}
.form-signin .checkbox
{
    font-weight: normal;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="text"]
{
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.account-wall
{
    margin-top: 20px;
    padding: 40px 0px 20px 0px;
    background-color: #f7f7f7;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
.login-title
{
    color: #555;
    font-size: 18px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 5%;
}
.need-help
{
    margin-top: 10px;
}
.new-account
{
    display: block;
    margin-top: 10px;
}
  </style>
       
</head>

<body>

<!--<div style="width:100%; text-align:center">
    <img src="img/logo2.jpg"/>
</div>-->

<div class="container">

    <div class="row">

<?php 
    require("config.php"); 
    $submitted_username = ''; 
    if(!empty($_POST)){ 
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email 
            FROM users 
            WHERE 
                username = :username 
        "; 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try{ 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $login_ok = false; 
        $row = $stmt->fetch(); 
        if($row){ 
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++){
                $check_password = hash('sha256', $check_password . $row['salt']);
            } 
            if($check_password === $row['password']){
                $login_ok = true;
            } 
        } 

        if($login_ok){ 
            unset($row['salt']); 
            unset($row['password']); 
            $_SESSION['user'] = $row; 

            if (htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8') == "presenter"){
                header("Location: addwinner.php"); 
                die("Redirecting to: addwinner.php"); 
            }else if(htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8') == "reception"){
                header("Location: records.php"); 
                die("Redirecting to: records.php"); 
            }else if (htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8') == "finance"){
                header("Location: weeklyreports.php"); 
                die("Redirecting to: weeklyreports.php");
            }else{
                header("Location: index.php"); 
                die("Redirecting to: index.php"); 
            }
            
        } 
        else{ 
            print '<br><div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Login Unsuccessful!</strong> Invalid username or password.
                    </div>'; 
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        } 
    } 
?> 
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h2 class="text-center ">Winners Tracker</h2>
            <div class="account-wall">
                <img class="profile-img" src="img/logo.jpg"
                    alt="">
                <form class="form-signin" action="login.php" method="post">
                    <input type="text" class="form-control" name="username" value="<?php echo $submitted_username; ?>" placeholder="Username" required autofocus>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </form>
            </div>
            <!--<a href="register.php" class="text-center new-account">Create an account </a>-->
        </div>
    </div>
</div>
<script>
    window.setTimeout(function() {
     $(".alert").fadeTo(5000, 0).slideUp(500, function(){
          $(this).remove(); 
     });
        }, 1000);
    </script>
</body>

</html>

