<?php 
    require("config.php");
    if(!empty($_POST)) 
    { 
        // Ensure that the user fills out fields 
        if(empty($_POST['username'])) 
        { die("Please enter a username."); } 
        if(empty($_POST['password'])) 
        { die("Please enter a password."); } 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { die("Invalid E-Mail Address"); } 
         
        // Check if the username is already taken
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                username = :username 
        "; 
        $query_params = array( ':username' => $_POST['username'] ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $row = $stmt->fetch(); 
        if($row){ die("This username is already in use"); } 
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                email = :email 
        "; 
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage());} 
        $row = $stmt->fetch(); 
        if($row){ die("This email address is already registered"); } 
         
        // Add row to database 
        $query = " 
            INSERT INTO users ( 
                username, 
                password, 
                salt, 
                email 
            ) VALUES ( 
                :username, 
                :password, 
                :salt, 
                :email 
            ) 
        "; 
         
        // Security measures
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
        $password = hash('sha256', $_POST['password'] . $salt); 
        for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); } 
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'] 
        ); 
        try {  
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        header("Refresh: 3, url=login.php"); 
        die("Redirecting to Login...");
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

    <title>Winners Tracker Registation</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="jquery/validator/css/bootstrapValidator.css"/>


    <!-- Custom CSS -->

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="jquery/validator/js/bootstrapValidator.js"></script>

    <style tytype="text/css">
        .navbar .glyphicon{
        margin-right: 5px;

        }  
         .caret{
          margin-left: 5px;
         }
    </style>

       
</head>

<body>
    <nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Winners Tracker</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-arrow-left"></span>Back to Login</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    </nav>

    <div class="container">
       <div class="row">
            <!-- form: -->
            <section>
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="page-header">
                        <h2 class ="text-center">Register Account</h2>
                    </div>

                    <form id="defaultForm" class="form-horizontal" action="" method="post">
                        <div class="alert alert-success" style="display: none;"></div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Username</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="username" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Email address</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="email" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Password</label>
                            <div class="col-lg-5">
                                <input type="password" class="form-control" name="password" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Confirm Password</label>
                            <div class="col-lg-5">
                                <input type="password" class="form-control" name="confirmPassword" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" id="captchaOperation"></label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" name="captcha" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <!-- :form -->
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    // Generate a simple captcha
    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    };
    $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

    $('#defaultForm').bootstrapValidator({

        message: 'This value is not valid',
            submitHandler: function(validator, form) {
            // validator is the BootstrapValidator instance
            // form is the jQuery object present the current form
            form.find('.alert').html('Thank you. Now you can log in as <strong>' + validator.getFieldElement('username').val() + '</strong>. Redirecting to Login... ').show();
            form.submit();
            },
            
        fields: {
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'The username is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'The username must be more than 6 and less than 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: 'The username can only consist of alphabetical, number, dot and underscore'
                    },
                    different: {
                        field: 'password',
                        message: 'The username and password can\'t be the same as each other'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and can\'t be empty'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required and can\'t be empty'
                    },
                    identical: {
                        field: 'confirmPassword',
                        message: 'The password and its confirm are not the same'
                    },
                    different: {
                        field: 'username',
                        message: 'The password can\'t be the same as username'
                    }
                }
            },
            confirmPassword: {
                validators: {
                    notEmpty: {
                        message: 'The confirm password is required and can\'t be empty'
                    },
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    },
                    different: {
                        field: 'username',
                        message: 'The password can\'t be the same as username'
                    }
                }
            },
            captcha: {
                validators: {
                    callback: {
                        message: 'Wrong answer',
                        callback: function(value, validator) {
                            var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
                            return value == sum;
                        }
                    }
                }
            }
        }
    });
});
</script>

</body>

</html>

