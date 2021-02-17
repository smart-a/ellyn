<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ellyn | Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/all.css">
        <link rel="stylesheet" href="css/my-style.css">

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/all.js"></script>

        <style>

        </style>

    </head>
    <body>
        <?php
            session_start();
            $message = '<span>Create account to extricate future checkout, track order history and other facilities.</span>';
            require('user-validation.php');
            //die('Logged: '.$isLoggedIn.' Session: '.$_SESSION['e_member'].' Cookies: '.$_COOKIE["e_username"]);
            if($isLoggedIn)
                $util->redirect('./');

            if(isset($_GET['reset-pass']) && isset($_SESSION['success'])){
                if($_SESSION['success']=='success')
                   $message = '<span>You have successfully reset you password, please login to continue</span>';
                else{
                    $_SESSION['success']='';
                    header('location:login.php');
                }   
            }
            /*
            else{
                $_SESSION['success']='';
                header('location:login.php');
            }
            */
        ?>

        <div class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
            <a class="navbar-brand h1" href="#">ELLYN</a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu" >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./">
                            <i class="fa fa-home"></i>
                            Home
                        </a>
                    </li>
                </ul>
            </div>
            
        </div>

        <div class="container-sm mt-5">
            
            <div class="alert alert-primary shadow mb-4 " role="alert" alert-dismissible style="width:60%;text-align:center;margin:0 auto">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $message; ?>
            </div>

            <div class="card shadow mb-3" style="width:300px;margin:0 auto; border-top: 2px solid #343a40">
                
                <div class="card-body" >
                    <h3 class="card-title mb-3" style="font-size:3rem;text-align: center">
                        <i class="fa fa-user-circle" ></i>
                    </h3>
                    <form id="login_form" method="POST" action="" style="font-size: 1rem;">
                        <input type="hidden" name="action" value="signin"/>
                        <div class="mb-1">
                            <label class="">Username</label>
                            <input type="text" class="form-control" placeholder="Username" id="username" name="username"/>
                        </div>
                        <div class="mb-2">
                            <label class="">Password </label>
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password"/>
                            <a href="forgetpassword.html" class="card-link">Forget Password?</a>
                        </div>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" checked class="custom-control-input" id="remember" name="remember"/>
                            <label for="remember" class="custom-control-label">Remeber Me?</label>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-block btn-dark" id="signin" name="signin" >
                                <span class="sp"></span>
                                    Sign In
                            </button>
                        </div>
                        
                    </form>
                    
                </div>
                <div class="card-footer">
                    <span style="font-size: 0.75rem;">Are you new here?
                        <a href="account.html" class="card-link">Create an Account</a>
                    </span>
                </div>
            </div>
        </div>

        <script>
           //alert("JQuery");
           $("#signin").on("click", function(){
                $(this).children(".sp").addClass("spinner-border spinner-border-sm mr-2");
                setTimeout(function(){
                    var user = $("#username").val();
                    var pass = $("#password").val();
                    if(user==''){
                        $("#username").focus();
                    }
                    else if(pass=='')
                        $("#password").focus();
                   else
                        $("#login_form").submit();
                   $('#signin').children(".sp").removeClass("spinner-border spinner-border-sm mr-2");
                },500)
            });
        </script>    
    </body>
</html> 