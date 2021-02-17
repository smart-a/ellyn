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
    

   <div class="toggled" id="wrapper">
      
     
         <nav class="navbar navbar-expand-xl bg-dark navbar-dark fixed-top shadow">
               
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
               <ul class="navbar-nav ml-auto mr-2">
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
        <!-- Page Content -->
        
         <div class="d-flex" >
            <div class="cat-list-wrapper ">
               <div class="cat-list shadow">
                  <div class="list-group list-group-flush">
                     <?php
                        $cat = getItemCat('category');
                        for($j=0;$j<sizeof($cat);$j++){
                           $item_cat = $cat[$j];
                           echo '<a class="list-group-item list-group-item-action cat-item" id="'.$item_cat['cat_id'].'">
                                 '.$item_cat['item_category'].'
                              </a>';
                              
                        }
                     ?>
                  </div>
               </div>
            </div>

            <div class="container" id="cat-body" >

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

   </div>

 
      


        <script src="js/my-js.js"></script>
        <script>
           /*/alert("JQuery");
            setTimeout(() => {
                $("#wrapper").toggleClass("toggled");
                $('.menu-toggle').focus();
            }, 3000);
            */
            $('a.cat-item:first-child').addClass('selected');

            var cat_id = $('a.cat-item:first-child').attr('id');
            //alert("ID:" + cat_id);
            var form_data = new FormData();
            form_data.append("cat_id",cat_id);
            form_data.append("action","fetch-item-cat");
            $.ajax({
               type: "POST",
               url: "index_backend.php",
               data: form_data,
               contentType: false,
               processData: false,
               success: function(e){
                     //alert(e);
                     if(e!='')
                        $('#cat-body').html(e);
               }
            }); 
        </script>
</body>

</html>