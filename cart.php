<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ellyn | Cart</title>
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
            
        ?>
    </head>
    <body>

        <div class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
            <a class="navbar-brand h1" href="#">ELLYN | Cart</a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu" >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="javascript:go_back()" class="nav-link">
                            <i class="fa fa-arrow-left mr-1"></i>
                            Continue shopping
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto mr-2">
                    <li class="nav-item dropdown ">
                    <?php
                        $user = '';
                        $data_user = '';
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
                        }else{
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
                                unset($_SESSION['e_cart']);
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
                </ul>
            </div>
            
        </div>
        <div class="container-sm mt-3 mb-5">
            <?php
                 $cart_num = 0;
                 if(!empty($_SESSION['e_cart']))
                     $cart_num = sizeof($_SESSION['e_cart']);
                 else if($isLoggedIn)
                     $cart_num = $auth->getCart($_COOKIE["e_username"]);
 
            ?>
                <div class="alert alert-info shadow sticky-top cart-top">
                    <h5 class="alert-heading " style="text-align:center">
                        <i class="fa fa-shopping-cart"></i>
                        My Cart <br/>
                        <span class="cart-list" style="font-size:0.8rem;">
                            <?php echo ($cart_num>0)?($cart_num==1)?'('.$cart_num.' Item)':'('.$cart_num.' Items)':'' ?>
                        </span>
                    </h5>
                    
                    <input type="hidden" class="cart" value="<?php echo $cart_num ?>"/>
                </div>

            <!--FLEX-->
            <!--New arrival-->
            <?php
               
                if($cart_num>0){
            ?>
            
            <div class="d-flex flex-wrap list-group" id="cartList" style="max-width:350px;margin: 0 auto">

                <?php
                    if($isLoggedIn){
                        $sql = "SELECT c.cart_id,c.item_id,c.size,c.color,i.item_name,i.item_amount,i.item_qty,it.liked FROM cart c 
                                 JOIN items i ON c.item_id=i.item_id 
                                 LEFT JOIN item_like it ON c.item_id=it.item_id AND it.username='$data_user'
                                 WHERE c.username='".$data_user."'";
                        $query = mysqli_query($GLOBALS['con'],$sql);
                        while($row=mysqli_fetch_array($query)){
                            $color = '';
                            $size = '';
                            if(!empty($row['color']))
                                $color='<div class="d-block">
                                        <h6 class="d-inline-block mr-1" >Color:</h6>'.$row['color'].'
                                        <span class="d-inline-block item-color" style="background-color:'.$row['color'].'" data-color="'.$row['color'].'"></span>
                                    </div>';
                            if(!empty($row['size']))
                            $size='<div class="d-block">
                                        <h6 class="d-inline-block mr-1">Size:</h6>
                                        <span class="d-inline-block item-size" data-size="'.$row['size'].'">'.$row['size'].'</span>
                                    </div>';
                            $liked = '';
                            if($row['liked']==1)
                                $liked = "item-liked";

                            echo '<div class="list-group-item cart-item" id="'.$row['cart_id'].'" item-id="'.$row['item_id'].'">
                                <a class="close remove-cart" cart-id="'.$row['cart_id'].'" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-trash"  ></i>    
                                </a>
                                <div style="text-align: center">
                                    <img src="image/'.$row['item_id'].'_1.png" img-id="'.$row['item_id'].'" class="preview-img card-image-md"/>
                                </div>
                                <h5 class="item-name">'.$row['item_name'].'</h5>
                                '.$size.'
                                '.$color.'
                                <span class="item-amount" style="color:red">'.number_format($row['item_amount']).'</span>
                                <hr/>
                                <div class="row">
                                    <div class="div-col-sm-2">
                                        <a class="btn item-like '.$liked.'" data-like="'.$row['item_id'].'" data-user="'.$data_user.'" data-toggle="tooltip" title="Like">
                                            <i class="fa fa-heart" style="color:red;"></i>
                                        </a>
                                    </div>
                                    <div class="div-col-sm-4 ml-auto cart-item-opt">
                                        <input type="number" class="p-0 item-num" value="1" min="1" max="'.$row['item_qty'].'"/>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                    else{
                        $max = sizeof($_SESSION['e_cart']);
                        foreach($_SESSION['e_cart'] as $key => $cart){
                            $sql = "SELECT * FROM items WHERE item_id='".$cart['item_id']."'";
                            $query = mysqli_query($GLOBALS['con'],$sql);
                            while($row=mysqli_fetch_assoc($query)){
                                $color = '';
                                if(!empty($cart['color']))
                                    $color='<div class="d-block">
                                            <h6 class="d-inline-block mr-1" >Color:</h6>'.$cart['color'].'
                                            <span class="d-inline-block item-color" style="background-color:'.$cart['color'].'" data-color="'.$cart['color'].'"></span>
                                        </div>';
                                $size='';
                                if(!empty($cart['size']))
                                    $size='<div class="d-block size-can" data-size="'.$cart['size'].'">
                                            <h6 class="d-inline-block mr-1">Size:</h6>
                                            <span class="d-inline-block item-size" >'.$cart['size'].'</span>
                                        </div>';
                                echo '<div class="list-group-item cart-item" id="cart-'.$key.'" item-id="'.$row['item_id'].'">
                                    <a class="close" cart-id="cart-'.$key.'" data-toggle="tooltip" title="Remove">
                                        <i class="fa fa-trash"></i>    
                                    </a>
                                    <div style="text-align: center">
                                        <img src="image/'.$row['item_id'].'_1.png" img-id="'.$row['item_id'].'" class="preview-img card-image-md"/>
                                    </div>
                                    <h5 class="item-name">'.$row['item_name'].'</h5>
                                    '.$size.'
                                    '.$color.'
                                    <span class="item-amount" style="color:red">'.number_format($row['item_amount']).'</span>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm">
                                            <!--<a class="btn item-like" data-toggle="tooltip" title="Like">
                                                <i class="fa fa-heart" style="color:red;"></i>
                                            </a> -->
                                        </div>
                                        <div class="div-col-sm-4 ml-auto cart-item-opt">
                                            <input type="number" class="p-0 item-num" value="1" min="1" max="'.$row['item_qty'].'"/>
                                        </div>
                                    </div>
                                </div>';
                            }
                        }
                    }
                ?>
            </div>

            <div class="d-flex flex-wrap shadow list-group p-4" style="max-width:500px;margin: 0 auto" id="cart_sum">
                <div class="list-group">
                    <div class="p-3">
                        <span class="h5">Total:</span> 
                        <span class="item-amount h5 mr-4" id="total" style="float: right;color: red">0</span>
                        <span class="item-name d-block mt-1">Excluding Shipping fees</span>
                    </div>
                    <button type="button" class="btn btn-block btn-success" id="checkout">
                        <span style="color: white; font-size: 1.1rem; font-weight:600">CHECK OUT</span>
                    </button>
                </div>
            </div>
            <?php }else{ ?>
                <div class="alert alert-light" >
                    <h4 class="alert-heading" style="text-align:center">
                        <i class="fa fa-frown"></i>
                        Please add cart!
                    </h4>
                </div>
            <?php } ?>
            
          

        </div>
        <div class="modal fade" id="confirm" >
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6>
                            <i class="fa fa-exclamation-triangle" style="color:red"></i>
                            Delete
                        </h6>
                        <a href="#" class="close" data-dismiss="modal">
                            &times;    
                        </a>
                    </div>

                    <div class="modal-body">
                        <h6>Sure to remove item from cart?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" id="remove-cart">
                            <i class="fa fa-check"></i>
                            Yes
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                            No
                        </button>
                    </div>
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
                                <h6 class="item-name mt-1" style="text-align:center;" id="item_name"></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="js/my-js.js"></script>
        <script>
           //alert("JQuery");
            $("[data-toggle='tooltip']").tooltip();
            $("input[type='number']").inputSpinner();

           
            function go_back(){
                return (document.referrer=='')?window.location.href='./':history.back();
            }
            
           
        </script>    
    </body>
</html> 