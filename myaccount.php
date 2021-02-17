<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ellyn | My Account</title>
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
    <link href="css/my-sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="css/my-style.css">

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/all.js"></script>
    <script src="js/bootstrap-input-spinner.js"></script>

    <style>


    </style>
    <?php
        session_start();
        require('user-validation.php');
        if(!$isLoggedIn)
            header('location:./');

        require('index_backend.php');
        $group = 'subcat';  //'category'

        $user = '';
        if(!empty($_SESSION['e_member']))
            $user = $auth->getMemberByUsername($_SESSION['e_member']);
        else if(!empty($_COOKIE["e_username"])){
            if($auth->getTokenByUsername($_COOKIE["e_username"])['password_hash']==$_COOKIE["e_password"])
                $user = $auth->getMemberByUsername($_COOKIE["e_username"]);
        }
    ?>
</head>

<body>
    
    <nav class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
        <a class="navbar-brand h1" href="#">ELLYN</a>
        
        <button class="navbar-toggler menu-hide" type="button" data-toggle="collapse" data-target="#navmenu" >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button type="button" class="nav-link btn menu-toggle acct-menu" data-toggle="collapse" data-target="">
                        <i class="fa fa-bars" style="font-size:1.1rem"></i>
                    </button>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto mr-2">
                <li class="nav-item">
                    <a href="./" class="nav-link">
                        <i class="fa fa-shopping-cart"></i>
                        Shopping
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-opt"  menu="" data-toggle="collapse" data-target="">
                        <i class="fa fa-dolly-flatbed"></i>
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-opt"  menu="wishlist" data-toggle="collapse" data-target="" >
                        <i class="fa fa-heart"></i>
                        Wishlist
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="helpNavbar" role="button" data-toggle="dropdown" aria-haspopup="true" >
                        <i class="fa fa-user-cog"></i>
                        Account Settings
                    </a>
                    <div class="dropdown-menu shadow" aria-labelledby="helpNavbar">
                        <a class="dropdown-item menu-opt" menu="acct-details" data-toggle="collapse" data-target="">Details</a>
                        <a class="dropdown-item menu-opt" menu="change-password" data-toggle="collapse" data-target="">Change Password</a>
                    </div>
                </li>
                <li class="nav-item dropdown ">
                    <?php
                        $item_like = '';
                        $data_user = '';
                        $cart_num = 0;
                        
                        if($isLoggedIn){
                            $cart_num = $auth->getCart($_COOKIE["e_username"]);
                    ?>
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" id="userNavbar" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user-circle"></i>
                        <?php 
                            $data_user = $user['username'];
                            echo '<span class="user-id">'.$data_user.'</span>';
                            unset($_SESSION['e_cart'])
                        ?>
                    </a>
                    <div class="dropdown-menu shadow" aria-labelledby="userNavbar">
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
    <div class=" toggled" id="wrapper">
      
        
        <!-- Page Content -->
        <div class="menu-container mb-3" id="page-content-wrapper">
            <?php
                require('item-liked.html');
                require('account-details.html');
                require('change-password.html');
                
            ?>
        </div>

        <!-- Sidebar -->
        <div class="bg-light shadow" id="sidebar-wrapper">
            <div class="d-block p-3" >
                <div class="h4 d-inline-block">ELLYN</div>
                <div class="d-inline-block position-absolute" style="top:0 ;right:3px">
                    <a class="btn menu-toggle">
                        <i class="fa fa-times" style="font-size:1.1rem"></i>
                    </a>
                </div>
            </div>
            <div class="p-3 bg-dark" style="color:white;">
                <?php echo 'Welcome, '.strtoupper(explode(' ',$user['fullname'])[0]).
                            '<br/>'.$user['email'];
                ?>
            </div>
            <div class="sidebar-heading">MY ACCOUNT</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action bg-light menu-toggle menu-opt"  menu="">
                    <i class="fa fa-dolly-flatbed"></i>
                    Orders
                </a>
                <a class="list-group-item list-group-item-action bg-light menu-toggle menu-opt"  menu="wishlist">
                    <i class="fa fa-heart"></i>
                    Wishlist
                </a>
            </div>
            <div class="sidebar-heading mt-2">
                <div class="d-inline-block">OUR CATEGORIES</div>
                <a class="d-inline-block float-right bg-light pl-2 pr-2" style="cursor:pointer" href="allcategory.php" data-toggle='tooltip' title='All Category' >
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
            <div class="list-group cat-group list-group-flush">
                <?php
                    $cat = getItemCat('category');
                    for($j=0;$j<sizeof($cat);$j++){
                        $item_cat = $cat[$j];
                        echo '<a class="list-group-item list-group-item-action bg-light" href="pool.php?token=category&ty='.$item_cat['cat_id'].'">
                                '.$item_cat['item_category'].'
                            </a>';
                        if($j==5)
                            break;
                    }
                ?>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
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
                            <div class="size-can mt-1">
                            </div>
                            <div class="color-can">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <?php if($isLoggedIn){ ?>
                            <div class="div-col-sm-2">
                                <a class="btn item-like" data-user="<?php echo $data_user;?>" data-toggle="tooltip" title="Like">
                                    <i class="fa fa-heart" style="color:red;"></i>
                                </a>
                            </div>
                            <?php } ?>
                            <div class="div-col-sm">
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
        <script src="js/account-js.js"></script>
        <script>

            $("[data-toggle='tooltip']").tooltip();
           $(".menu-toggle").on('click',(e)=> {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
            $('.menu-hide').on('click',()=>{
                if($("#wrapper").hasClass("toggled"))
                    $("#wrapper").toggleClass("toggled")
            });
        </script>
</body>

</html>