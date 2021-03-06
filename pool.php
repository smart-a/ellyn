<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ellyn | Item Pool</title>
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
         <?php
            session_start();
            require('user-validation.php');
            require("index_backend.php");
            
            if(isset($_GET['token']))
                $group = $_GET['token'];
            else
                $group = 'subcat';  //'category'

            $header = '';
            $body = '';
            $page = '';
            if(!isset($_GET['ty']) || $_GET['ty']==''){
                header('location:./');
            }else{
                switch($_GET['ty']){
                    case 'new':
                        $header =   '<h5 class="header-title d-inline-block mt-1 ml-2">New Arrivals</h5>  
                                    <span class="badge badge-pill badge-danger">NEW</span>';

                        $body = load_new_items('');

                        $page = '<li class="page-item active">
                                    <a href="#" class="page-link">1</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">2</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">3</a>
                                </li>';
                        break;
                    default:
                        $header = load_header($group,$_GET['ty']);
                        $body = load_items($group,$_GET['ty']);
                        break;
                }
            }
        ?>

    </head>
    <body>


        <nav class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
           
            <ul class="navbar-nav">
                <li class="nav-item" >
                    <a href="javascript:history.back()" class="nav-link" >
                        <i class="fa fa-arrow-left mr-1"></i>
                        Back
                    </a>
                </li>
            </ul>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu" >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse " id="navmenu">
                

                <ul class="navbar-nav ml-auto mr-4">
                    <li class="nav-item dropdown ">
                    <?php
                        $item_like = '';
                        $data_user = '';
                        $cart_num = 0;
                        if(!$isLoggedIn){
                    ?>
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user-circle"></i>
                            Login
                        </a>
                        <div class="dropdown-menu shadow">
                            <a class="dropdown-item" href="login.php">
                                <i class="fa fa-user-lock"></i>
                                Login
                            </a>
                            <a class="dropdown-item" href="account.html">
                                <i class="fa fa-user-plus"></i>
                                Register
                            </a>
                        </div> 
                    <?php
                            if(!empty($_SESSION['e_cart']))
                                $cart_num = sizeof($_SESSION['e_cart']);
                        }else{
                            $cart_num = $auth->getCart($_COOKIE["e_username"]);
                    ?>
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user-circle"></i>
                            <?php 
                                $user = '';
                                if(!empty($_SESSION['e_member']))
                                    $user = $auth->getMemberByUsername($_SESSION['e_member']);
                                else if(!empty($_COOKIE["e_username"])){
                                    if($auth->getTokenByUsername($_COOKIE["e_username"])['password_hash']==$_COOKIE["e_password"])
                                        $user = $auth->getMemberByUsername($_COOKIE["e_username"]);
                                }
                                $data_user = $user['username'];
                                echo '<span class="user-id">'.$data_user.'</span>';
                                unset($_SESSION['e_cart'])
                            ?>
                        </a>
                        <div class="dropdown-menu shadow">
                            <a class="dropdown-item" href="myaccount.php">
                                <i class="fa fa-user-shield"></i>
                                My Profile
                            </a>
                            <a class="dropdown-item" href="logout.php">
                                <i class="fa fa-user-lock"></i>
                                Logout
                            </a>
                        </div> 
                    <?php  } ?>
                    </li>
                    <li class="nav-item">
                        <a href="cart.php" class="card-link">
                            <i class="fa fa-shopping-cart cart-icon mt-1" style="color:white; font-size: 1.8rem"></i>
                            <span class="badge badge-pill bg-danger cart" style="color:black">
                                    <?php echo $cart_num ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            
        </nav>

        <div class="container-fluid pl-5 pr-5 mb-5">
            
            <!--FLEX-->
            <div class="pool-header shadow p-2 mt-5 mb-3" id="header">
                <?php echo $header; ?>
            </div>

            <div class="d-flex flex-wrap list-group list-group-horizontal pl-5" id="body">
                <?php echo $body; ?>
            </div>
            <!--
            <ul class="pagination mt-5">
                <?php echo $page; ?>
            </ul> 
            -->
        </div>
        

          <!--Item Modal-->
        <section>
            <div class="modal fade" id="item-modal">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-item">
                                <div class="carousel slide" data-ride="carousel" id="itemSlide">

                                </div>
                                <div class="prize-badge item-amount" id="item_amount"></div>
                                <h6 class="item-name mt-1" style="text-align:center;" id="item_name"></h6>
                                
                            </div>
                            <div>
                                <div class="size-can mt-2">
                                    <span class="">Size: </span>
                                    <ul class="pagination pagination-sm item-size d-flex flex-wrap ">

                                    </ul> 
                                </div>
                                <div class="color-can">
                                    <span class="">Color: </span>
                                    <ul class="pagination pagination-sm item-color d-flex flex-wrap ">
                                        
                                    </ul>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <a class="btn item-like" data-user="<?php echo $data_user;?>" data-toggle="tooltip" title="Like">
                                        <i class="fa fa-heart" style="color:red;"></i>
                                    </a>
                                </div>
                                <div class="col-sm">
                                    <button type="button" class="btn btn-block btn-success add-cart">
                                        <i class="fa fa-cart-plus"></i> Add cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
       

        <script src="js/my-js.js"></script>
        <script>
           
        </script>    
    </body>
</html> 