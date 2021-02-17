<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap 4 Example</title>
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
        <?php
            session_start();
            require('user-validation.php');
            if(!$isLoggedIn)
                header('location:./');

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

        <div class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
            <a class="navbar-brand h1" href="#">ELLYN</a>
            
            <!--
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu" >
                <span class="navbar-toggler-icon"></span>
            </button>
            -->

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ml-auto mr-4">
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user-check"></i>
                            <?php 
                                $data_user = $user['username'];
                                echo '<span class="user-id">'.$data_user.'</span>';
                                unset($_SESSION['e_cart'])
                            ?>
                        </a>
                        <div class="dropdown-menu shadow">
                            <a class="dropdown-item" href="logout.html">
                                <i class="fa fa-user-times"></i>
                                Logout
                            </a>
                        </div> 
                    </li>
                </ul>
            </div>
            

        </div>
        <div class="container-sm mt-3 mb-5">
            
                <div class="alert alert-light" >
                    <h4 class="alert-heading" style="text-align:center">
                        <i class="fa fa-thumbs-up"></i>
                        Almost Done!
                    </h4>
                </div>

            <!--FLEX-->

        <?php if(!$isLoggedIn) { ?>
            <div class="d-flex flex-wrap shadow list-group p-4 opt-list" style="margin: 0 auto">
                <div class="list-group ">
                    <div class="row">
                        <div class="col-sm p-1">
                            <a class="btn btn-info" data-toggle="collapse" data-target="#login">
                                <i class="fa fa-user-circle"></i> Login
                            </a>
                            <div class="collapse p-2" id="login">
                                <div class="card shadow-sm mb-2" style="width:300px;margin:0 auto; border-top: 2px solid #343a40">
                                    <div class="card-body" >
                                        <h3 class="card-title text-center" style="font-size:2.5rem;">
                                            <i class="fa fa-user-circle" ></i>
                                        </h3>
                                        <form style="font-size: 1rem;">
                                            <div class="mb-1">
                                                <label class="">Username</label>
                                                <input type="text" class="form-control" placeholder="Username" id="username"/>
                                            </div>
                                            <div class="mb-2">
                                                <label class="">Password </label>
                                                <input type="password" class="form-control" placeholder="Password" id="password"/>
                                                <a href="#" class="card-link">Forget Password?</a>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" checked class="custom-control-input" id="remember"/>
                                                <label for="remember" class="custom-control-label">Remeber Me?</label>
                                            </div>
                                            <div class="">
                                                <botton type="button" class="btn btn-block btn-dark" id="signin" >
                                                    <span class="fa fa-sign-in-alt"></span>
                                                        Login & Continue
                                                </botton>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer">
                                        <span style="font-size: 0.75rem;">Want to join?
                                            <a href="account.php?continue=checkout" class="card-link">Create an Account</a>
                                        </span>
                                    </div>
                                </div>
                                <botton type="button" id="skip" class="btn btn-dark mt-3 mb-2" data-toggle="collapse" data-target="#delivery">
                                    Skip
                                    <span class="fa fa-caret-square-right"></span>  
                                </botton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
            <div class="d-flex flex-wrap shadow list-group p-4 opt-list" style="margin: 0 auto">
                <div class="list-group ">
                    <div class="row">
                        <div class="col-sm p-1">
                            <a class="btn btn-info" data-toggle="collapse" data-target="#delivery">
                                <i class="fa fa-plane-departure"></i>
                                Item Delivery
                            </a>
                            <div class="collapse p-2" id="delivery">
                                <h5 class="mt-3">Billing Details</h5>
                            <?php if(!$isLoggedIn) { ?>
                                <div class="input-details d-container">
                                    <form>
                                        <div class="row delivery-row">
                                            <div class="col-sm">
                                                <label>Fullname:</label>
                                                <input type="text" class="form-control" id="fullname"/>
                                            </div>
                                        </div>
                                        <div class="row delivery-row">
                                            <div class="col-sm">
                                                <label>Country/ Region:</label>
                                                <select class="custom-select" id="country">
                                                    <option>Nigeria</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row delivery-row">
                                            <div class="col-sm">
                                                <label>Street address:</label>
                                                <input type="text" class="form-control" id="address"/>
                                            </div>
                                        </div>
                                        <div class="row delivery-row">
                                            <div class="col-sm">
                                                <label>State:</label>
                                                <select class="custom-select" id="state">
                                                    <option></option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label>City/ Town:</label>
                                                <select class="custom-select" id="city">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row delivery-row">
                                            <div class="col-sm">
                                                <label>Phone:</label>
                                                <input type="tel" class="form-control" id="phone"/>
                                            </div>
                                        </div>
                                        <div class="row delivery-row">
                                            <div class="col-sm">
                                                <label>Email:</label>
                                                <input type="email" class="form-control" id="email"/>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php } else { ?>
                                <div class="details d-container">
                                    <div class="row delivery-row">
                                        <div class="col-sm-2">Fullname:</div>
                                        <div class="col-sm">
                                            <span class="item-name fullname"><?php echo $user['fullname'] ?></span>
                                        </div>
                                    </div>
                                    <div class="row delivery-row">
                                        <div class="col-sm-2">Phone:</div>
                                        <div class="col-sm">
                                            <span class="item-name phone"><?php echo $user['phone'] ?></span>
                                        </div>
                                    </div>
                                    <div class="row delivery-row">
                                        <div class="col-sm-2">Email:</div>
                                        <div class="col-sm">
                                            <span class="item-name email"><?php echo $user['email'] ?></span>
                                        </div>
                                    </div>
                                    <div class="row delivery-row">
                                        <div class="col-sm-2">Address:</div>
                                        <div class="col-sm">
                                            <span class="item-name address"><?php echo $user['address'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                                <div class="d-container-centered mt-4">
                                    <h5 class="mb-1">Delivery Method</h5>
                                    <div class="d-method shadow p-4 mb-3 ">
                                        <div class="custom-control custom-radio d-method-opt" data-toggle="modal" data-target="#p_station">
                                            <input type="radio" class="custom-control-input" id="station"/> 
                                            <div class="custom-control-label d-station">
                                                <span style="color:red">
                                                    <i class="fa fa-laptop-house" ></i>
                                                    Pickup Station
                                                </span>
                                                <p>
                                                    Ready for pickup between
                                                    <span class="delivery-date item-name">Tuesday 25 May and Friday 28 May</span>
                                                </p>
                                                <div id="ps_address"></div>
                                                <span class="item-amount collapse" id="ship_fee" style="color:red">0</span>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div style="text-align:center">
                                            <a class="btn btn-noline p-station" data-toggle="modal" data-target="#p_station">
                                                SELECT A PICKUP STATION
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-method shadow p-4 mb-3 ">
                                        <div class="custom-control custom-radio d-method-opt door" >
                                            <input type="radio" class="custom-control-input" id="door"/> 
                                            <div class="custom-control-label d-door">
                                                <span style="color:red">
                                                    <i class="fa fa-truck-moving"></i>
                                                    Door Delivery
                                                </span>
                                                <p>
                                                    Delivered between
                                                    <span class="delivery-date item-name">Tuesday 25 May and Friday 28 May</span>
                                                </p>
                                                <span class="item-amount" style="color:red">4,200</span>
                                            </div>
                                        </div>
                                    </div>

                                <?php if($isLoggedIn) { ?>
                                    <div class="row delivery-row other-details collapse">
                                        <h6 class="mt-3 ">Shipping Details</h6>
                                        <div class="row delivery-row">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="ch1"/> 
                                                <label class="custom-control-label" for="ch1" data-toggle="collapse" data-target="#diff">
                                                    Use different address?
                                                </label>
                                            </div>
                                            <div class="collapse mt-3" id="diff">
                                                <div class="row delivery-row">
                                                    <div class="col-sm">
                                                        <label>Fullname:</label>
                                                        <input type="text" class="form-control fullname"/>
                                                    </div>
                                                </div>
                                                <div class="row delivery-row">
                                                    <div class="col-sm">
                                                        <label>Country/ Region:</label>
                                                        <select class="custom-select country">
                                                            <option>Nigeria</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row delivery-row">
                                                    <div class="col-sm">
                                                        <label>Street address:</label>
                                                        <input type="text" class="form-control address"/>
                                                    </div>
                                                </div>
                                                <div class="row delivery-row">
                                                    <div class="col-sm">
                                                        <label>State:</label>
                                                        <select class="custom-select state">
                                                            <option></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm">
                                                        <label>City/ Town:</label>
                                                        <select class="custom-select city">
                                                            <option></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row delivery-row">
                                                    <div class="col-sm">
                                                        <label>Phone:</label>
                                                        <input type="tel" class="form-control phone"/>
                                                    </div>
                                                </div>
                                                <div class="row delivery-row">
                                                    <div class="col-sm">
                                                        <label>Email:</label>
                                                        <input type="email" class="form-control email"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                </div>

                                <div class="shadow mb-3 delivery-row">
                                    <div class="p-4 summary">
                                        <div>
                                            <span class="h6 d-inline-block">Item Total:</span> 
                                            <span class="item-amount h6 d-inline-block mr-3 item-total" style="float:right">20,000</span>
                                        </div>
                                        <div>
                                            <span class="h6 d-inline-block">Shipping Fee:</span> 
                                            <span class="item-amount h6 d-inline-block mr-3 shipping-fee" style="float:right">0</span>
                                        </div>
                                        <hr/>
                                        <div class="mt-2">
                                            <span class="h5 d-inline-block">Total:</span> 
                                            <span class="item-amount d-inline-block h5 mr-3 total-amount mr-3" style="float: right;color:red">0</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary mr-4" style="float:right">
                                    <i class="fa fa-arrow-right"></i>
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!-- -->
            <div class="d-flex flex-wrap shadow list-group p-4 opt-list" style="margin: 0 auto">
                <div class="list-group ">
                    <div class="row">
                        <div class="col-sm p-1">
                            <a class="btn btn-info" data-toggle="collapse" data-target="#order">
                                <i class="fa fa-handshake"></i>
                                Order & Payment
                            </a>
                            <div class="collapse" id="order">
                                <h5 class="mt-3 ml-5">Payment Method</h5>
                                <div class="d-container-centered mt-4">
                                    <div class="p-method shadow p-4 mb-3 ">
                                        <div class="custom-control custom-radio p-method-opt on-delivery" >
                                            <input type="radio" class="custom-control-input" id="pay-on-deliver"/> 
                                            <div class="custom-control-label pay-on-deliver">
                                                <span style="color:red">
                                                    <i class="fa fa-money-bill-wave"></i>
                                                    Pay on Delivery
                                                </span>
                                                <p>
                                                    Mode:
                                                    <span class="delivery-date item-name">Pay via Bank Transfer or Cash</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-method shadow p-4 mb-3 ">
                                        <div class="custom-control custom-radio p-method-opt">
                                            <input type="radio" class="custom-control-input" id="pay-with-card"/> 
                                            <div class="custom-control-label pay-with-card">
                                                <span style="color:red">
                                                    <i class="fa fa-credit-card" ></i>
                                                    Pay online
                                                </span>
                                                <p>
                                                    Mode:
                                                    <span class="delivery-date item-name">Pay now with card</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mr-4" style="float:right">
                                    <i class="fa fa-arrow-right"></i>
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="p_station" >
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>
                                <i class="fa fa-laptop-house"></i>
                                Pickup Station
                            </h5>
                            <a href="#" class="close" data-dismiss="modal">
                                &times;    
                            </a>
                        </div>

                        <div class="modal-body" >
                            <div class="shadow p-3 mb-2">
                                <div class="mb-3">
                                    <label>Region</label>
                                    <select class="custom-select" id="ps_region">
                                        <option>Kaduna</option>
                                    </select>
                                </div>
                                <div class="">
                                    <label>City</label>
                                    <select class="custom-select" id="ps_city">
                                        <option>Kabala Custain</option>
                                    </select>
                                </div>
                            </div>
                            <h5 class="p_title">Kaduna Pickup Station</h5>
                            <div class="ml-2  ">
                                <div class="ps_address mb-2 shadow p-3" id="kabala_1">
                                    <div class="p_address">No 2 Abjo street off, LEA Primary school Kabala</div>
                                    <span >Shipping Fee: </span>
                                    <span class="item-amount ml-2 p_fee" style="color:red">3,000</span>
                                    <hr/>
                                    <span class="text-center">
                                        <button type="button" class="btn btn-noline">SELECT</button>
                                    </span>
                                </div>
                                <div class="ps_address mb-2 shadow p-3" id="malali_1">
                                    <div class="p_address">No 42 Gboike street off, GSS Primary school Malali</div>
                                    <span >Shipping Fee: </span>
                                    <span class="item-amount ml-2 p_fee" style="color:red">1,500</span>
                                    <hr/>
                                    <span class="text-center">
                                        <button type="button" class="btn btn-noline">SELECT</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                

        </div>

        <script src="js/my-js.js"></script>
        <script>
           //alert("JQuery");
            $("#color").on("change",function(){
                alert($(this).val());
            });

            
            
           
        </script>    
    </body>
</html> 