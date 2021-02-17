
<?php
	session_start();
    /****/
    if(isset($_SESSION['success'])){
        unset($_SESSION['success']);
    }
    else
        header('location:login.php');
	
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ellyn | Account Success</title>
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
        <script src="js/bootstrap-input-spinner.js"></script>

        <style>
            

        </style>

    </head>
    <body>

        <div class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
            <a class="navbar-brand h1" href="#">ELLYN</a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu" >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="./" class="nav-link active">
                            <i class="fa fa-home"></i>
                            Home
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
		<div class="container mt-5 mb-5">
			<center>	
				<div class="" style="width:40%;">
                    <div class="">
                        <img src="site_image/mark.png" width="150" height="150"/>
                    </div>
                    <div>
                        <h1 style="font-size:3rem">Congratulations!</h1>
                        <h4 style="">You have successfully create an account</h4>
                    </div>
                    <div class="mt-5">	
                        <button type='button' onclick="window.location.href='login.php'" class="btn btn-outline-success btn-block p-2">
                            <i class="fa fa-check-circle"></i> Let's get started
                        </button>
                    </div>
				</div>
			</center>
        </div>
        <script>
            /*
            setTimeout(() => {
                window.location.href = "login.php";
            }, 3000);
            */
        </script>
	</body>
</html>
			