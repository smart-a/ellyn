<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ellyn Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
      
        <!--
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        -->

        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/all.css">
        <link rel="stylesheet" href="../../css/my-style.css">
        <link rel="stylesheet" href="../../css/bootstrap-multiselect.css">


        <script src="../../js/jquery-3.2.1.min.js"></script>
        <script src="../../js/bootstrap.bundle.js"></script>
        <script src="../../js/all.js"></script>
        <script src="../../js/bootstrap-input-spinner.js"></script>
        <script src="../../js/bootstrap-multiselect.js"></script>

        <style>
            

        </style>

    </head>
    <body>

    <?php
        session_start();
        $menu ="";
        if(isset($_GET['menu']))
            $menu = $_GET['menu'];
    ?>

    <div class="navbar navbar-expand-xl bg-dark navbar-dark sticky-top shadow">
        <a class="navbar-brand h1" href="#">ELLYN</a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu" >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav admin-menu">
                <li class="nav-item">
                    <a class="nav-link <?php if($menu==''||$menu=='home') echo 'active'; ?>" href="?menu=home">
                        <i class="fa fa-home"></i>
                        Home
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link <?php if($menu=='order') echo 'active'; ?>" href="?menu=order">
                        <i class="fa fa-dolly-flatbed"></i>
                        Check Order
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link <?php if($menu=='delivery') echo 'active'; ?>" href="?menu=delivery">
                        <i class="fa fa-box-open"></i>
                        Item Delivery
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if($menu=='category'||$menu=='subcat'||$menu=='brand') echo 'active'; ?>" data-toggle="dropdown">
                        <i class="fa fa-boxes"></i>
                        Item Classificaton
                    </a>
                    <div class="dropdown-menu shadow">
                        <a class="dropdown-item" href="?menu=category">
                            <i class="fa fa-list-alt"></i>
                            Category
                        </a>
                        <a class="dropdown-item" href="?menu=subcat">
                            <i class="fa fa-sitemap"></i>
                            Sub-Category
                        </a>
                        <a class="dropdown-item" href="?menu=brand">
                            <i class="fa fa-atom"></i>
                            Brand
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($menu=='item') echo 'active'; ?>" href="?menu=item">
                        <i class="fa fa-gift"></i>
                        Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($menu=='pickup') echo 'active'; ?>" href="?menu=pickup">
                        <i class="fa fa-laptop-house"></i>
                        Pickup Station
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto mr-4">
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user-circle"></i>
                        User
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

    <?php

        if($menu!='' && $menu!='home'){
            if(file_exists($menu.'.html'))
                require_once($menu.'.html');
            else
                echo '<div class="alert ml-5 mt-5">The page you are looking for does not exist! <a href=./>Go Home</a></div>';
        }
            

        /*
        switch ($menu) {
            case '':
                # code...
                break;
            case 'items':
                include('item.html');
                break;
            case 'category':
                include('category.html');
                break;
            case 'subcat':
                include('subcat.html');
                break;
            
            default:
                # code...
                break;
        }
        */

    ?>

        
      
       

        <script src="../../js/admin-js.js"></script>  
        <script>
            $('#item_size').multiselect({
                enableFiltering: true,
                filterPlaceholder: 'Search ...',
                buttonWidth: '80%'
            });
            $('#item_color').multiselect({
                enableFiltering: true,
                filterPlaceholder: 'Search ...',
                buttonWidth: '100%'
            });
            
            /*
            $('#item_size').multiselect('select', ss);
            $("#color").on("change",function(){
                alert($(this).val());
            });
            */
        </script>  
        
    </body>
</html> 