<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Welcome to Ellyn</title>
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
        <link rel="stylesheet" href="css/jquery-ui.min.css">

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/all.js"></script>
        <script src="js/bootstrap-input-spinner.js"></script>
        <script src="js/jquery-ui.min.js"></script>

        <style>
            

        </style>
        <?php
            session_start();
            require('user-validation.php');
            require("index_backend.php");
            $group = 'subcat';  //'category'
            $limit = "LIMIT 0,10";
        ?>
    </head>
    <body>
        <!--Server URL-->
        <input type="hidden" id="server_root" value="<?php echo $_SERVER['REQUEST_URI'] //$_SERVER['DOCUMENT_ROOT'] ?>"/>
        <!---->
        <div class="navbar bg-dark navbar-dark float-menu shadow">
            <button type="button" class="btn navbar-light" id="btnFilter" style="color:white">
                <i class="fa fa-filter" style="font-size:1.1rem"></i> Filter
            </button>
        </div>
        <div class="container-fluid h1"><span class="figure-caption">E</span>LLYN</div>
        <nav class="navbar bg-dark navbar-dark navbar-expand-md  sticky-top shadow">
            <a class="navbar-brand h1" href="#">ELLYN</a>
            
            <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navmenu" >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="" class="nav-link active">
                            <i class="fa fa-home"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="fa fa-phone-square-alt"></i>
                            Contact
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="helpNavbar" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Help
                        </a>
                        <div class="dropdown-menu shadow" aria-labelledby="helpNavbar">
                            <a class="dropdown-item" href="#">Content</a>
                            <a class="dropdown-item" href="#">General</a>
                        </div>
                    </li>
                    
                </ul>

                <ul class="navbar-nav ml-auto mr-2">
                    <li class="nav-item dropdown ">
                    <?php
                        $item_like = '';
                        $data_user = '';
                        $cart_num = 0;
                        if(!$isLoggedIn){
                    ?>
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" id="userNavbar" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user-circle"></i>
                            Login
                        </a>
                        <div class="dropdown-menu shadow" aria-labelledby="userNavbar">
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

        <div class="carousel slide" id="myslide" data-ride="carousel">
                <!--Carousel Indicators-->
            <ul class="carousel-indicators">
                <li data-target="#myslide" class="active" data-slide-to="0"></li>
                <li data-target="#myslide" data-slide-to="1" ></li>
                <li data-target="#myslide" data-slide-to="2"></li>
            </ul>

            <!--Carousel Images/ Items-->
            <div class="carousel-inner mb-3">
                <div class="carousel-item item active">
                    <div class="carousel-caption">
                        <!--
                            <h1>DP1</h1>
                        <p>My DP1 images in png</p>
                        -->
                    </div>
                    <img src="banner/banner1.jpg"/>
                </div>
                <div class="carousel-item item">
                    <img src="banner/banner2.jpg">
                    <div class="carousel-caption"></div>
                </div>
                <div class="carousel-item item">
                    <img src="banner/banner3.jpg">
                    <div class="carousel-caption"></div>
                </div>
            </div>

                <!--Control-->
            <a href="#myslide" class="carousel-control-prev" style="width:5%;" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a href="#myslide" class="carousel-control-next" style="width:5%;" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
        
        <div class="container-fluid pl-0">
            <div class="row">
                <div class="col-sm-2-5">
                    <!-- Sidebar -->
                    <div id="filter-sidebar">
                        
                        <div class="d-block" >
                            <div class="d-inline-block position-absolute" style="top:0 ;right:3px;">
                                <a class="btn close-filter" style="display:none">
                                    <i class="fa fa-times" style="font-size:0.8rem"></i>
                                </a>
                            </div>
                            <div class="h4 sidebar-heading d-inline-block pb-1">Search</div>
                            <div class="list-group-item border-top-0">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend" onclick="javascript:$('#search').focus()">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                    <input type="search" class="form-control" id="search"/>
                                    <div class="input-group-append cancel-search" onclick="javascript:$('#search').val('');$('#search').focus()">
                                        <span class="input-group-text"><i class="fa fa-times"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="h4 pb-0 mb-0 sidebar-heading d-inline-block">Filter</div>
                        <div class="list-group list-group-flush sidebar-list">
                            
                            <div class="list-group-item">
                                <label>Category:</label>
                                <select class="custom-select custom-select-sm" id="item_category" name="item_category">
                                    <?php echo fetch_columns('item_category'); ?>
                                </select>
                            </div>
                            <div class="list-group-item">
                                <label>Amount:</label>
                                <!--
                                <div class="input-group input-group-sm" style="font-size:0.7rem">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text min-range">0</span>
                                    </div>
                                    <div class="m-auto "style="min-width:40%">
                                        <div class="" id="amt_range"  ></div>
                                    </div>
                                    <div class="input-group-append ">
                                        <span class="input-group-text max-range">0</span>
                                    </div>
                                </div>
                            -->
                                <div class="m-auto "style="min-width:40%">
                                    <div class="" id="amt_range"  ></div>
                                </div>
                                <div class="input-group input-group-sm mt-2" style="font-size:0.7rem">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text min-range">0</span>
                                    </div>
                                    <div class="m-auto "style="min-width:40%">
                                        <span class="align-text-center" ></span>
                                    </div>
                                    <div class="input-group-append ">
                                        <span class="input-group-text max-range">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <label>Brand:</label>
                                <select class="custom-select custom-select-sm" id="item_brand" name="item_brand">
                                    <?php echo fetch_columns('item_brand'); ?>
                                </select>
                            </div>
                            <div class="list-group-item m-auto ">
                                <button type="button" class="btn btn-sm btn-success mt-2" id="btn-filter">
                                    <i class="fa fa-filter"></i> 
                                     Save Filter
                                </button>
                                <button type="button" class="btn btn-sm btn-danger mt-2 ml-2" id="btn-c-filter">
                                    <i class="fa fa-times"></i> 
                                     Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /#sidebar-wrapper -->
                </div>

                <div class="col-sm item_body pb-4">
                    <?php 
                        //Fetch New Items 
                        if(load_new_items($limit)!=''){
                    ?>
                    <div class="item-card shadow mb-3">
                        <div class="item-card-header shadow bg-dark" style="font-size: 0.9rem;color: white;">
                            <span>NEW ARRIVALS</span>  
                            <span class="badge badge-pill badge-danger">NEW!</span>
                            <a href="pool.php?ty=new" class="card-link" style="float: right;color: white;">SEE ALL</a>
                        </div>
                        
                        <div class="item-card-body">
                            <div class="d-flex flex-wrap list-group list-group-horizontal items pl-2">
                                <?php
                                    echo load_new_items($limit);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                        //Fetch Other Items
                        other_items($group);
                    ?>
                </div>
            </div> 
            
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
        <script>
           //alert("JQuery");
           $(window).on('resize',function(){
                //alert($(this).width());
                var size = $(this).width();
                if(size >= 987)
                    $('#filter-sidebar').css('display','block');
           });
            
            $('#btnFilter').on('click',()=>{
                var display = $('#filter-sidebar').css('display');
                if(display == 'none')
                    $('#filter-sidebar').css('display','block');
                else
                    $('#filter-sidebar').css('display','none');
            });

            $('.close-filter').on('click',()=>{
                $('#filter-sidebar').css('display','none');
            });

            $("[data-toggle='tooltip']").tooltip();
            $("input[type='number']").inputSpinner();

            $('#amt_range').slider({
                range: true,
                min: 0,
                max: 999999,
                step: 100,
                values: [0,999999],
                slide: (evt, ui)=>{
                    $('.min-range').text(ui.values[0].toLocaleString());
                    $('.max-range').text(ui.values[1].toLocaleString());
                }
            });

            var range = $('#amt_range').slider('values');
            $('.min-range').text(range[0].toLocaleString());
            $('.max-range').text(range[1].toLocaleString());
           
        </script>    
    </body>
</html> 