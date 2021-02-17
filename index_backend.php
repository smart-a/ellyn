<?php
    require("connection/connect.php");
    $GLOBALS['$con'] = $con;
    $GLOBALS['group'] = 'subcat';
    $GLOBALS['limit'] = "LIMIT 0,10";

    extract($_POST);

    if(empty($_SESSION['post']))
        $_SESSION['post'] = [];

    if(isset($_POST['action']))
        switch($action){
            case 'add-cart':
                $session = sizeof($_SESSION['post']);
                $post = sizeof($_POST);
                $arr = sizeof(array_diff_assoc($_POST,$_SESSION['post']));
                if($arr > 0){
                    add_cart($user,$item_id,$size,$color);
                    $_SESSION['post'] = $_POST;
                }
                break;
            case 'remove-cart':
                remove_cart($user,$cart_id);
                break;
            case 'item-like':
                item_like($user,$item_id,$like);
                break;
            case 'fetch-item':
                filter_items($search, $cat, $amt, $brand);
                break;
            case 'fetch-item-cat':
                echo getItemCatById($cat_id);
                break;
            case 'liked-item':
                echo loadSavedItems($user_id);
                break;
        }





    //functions

    function checkout_items($user,$item_id,$size,$color,$qty,$amount){
        
        session_start();
        if(empty($_SESSION['e_checkout']))
            $_SESSION['e_checkout'] = array();
        $old = sizeof($_SESSION['e_checkout']);
        $cart = array('item_id'=>$item_id,'size'=>$size,'color'=>$color,'qty'=>$qty,'amount'=>$amount);
        array_push($_SESSION['e_checkout'], $cart);
        $rs = sizeof($_SESSION['e_checkout']);
        if($old != $rs)
            echo 1; 
    }

    function loadSavedItems($user_id){
        $data ="";
        $sql = "SELECT * FROM item_like l LEFT JOIN items i ON l.item_id=i.item_id LEFT JOIN category c ON i.item_category=c.cat_id 
                LEFT JOIN subcat s ON i.item_subcat=s.subcat_id WHERE l.username='$user_id' ORDER BY i.item_date DESC";

        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= '<div class="list-group-item item-info" id="'.$row['item_id'].'">
                            <img src="image/'.$row['item_id'].'_1.png" class=" card-image-md"/>
                            <h6 class="item-name overflow-hidden" style="white-space:nowrap">'.$row['item_name'].'</h6>
                            <span class="item-amount">'.number_format($row['item_amount'],2).'</span>
                        </div>';
        }
        
        return $data;
    }

    function getItemSubCat($cat_id){
        $data =[];
        $sql = "SELECT * FROM subcat WHERE cat_id='$cat_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - sub-category");
        while($row = mysqli_fetch_assoc($query))
            $data[] = ['subcat_id' => $row['subcat_id'],'item_subcat' => $row['item_subcat']];
        return $data;
    }

    function getItemCatById($cat_id){
        $data ='';
        $subcat = getItemSubCat($cat_id);
        
        
        for($j=0;$j<sizeof($subcat);$j++){
            $item_subcat = $subcat[$j];
            $subcat_id = $item_subcat['subcat_id'];

            if(getItemNum($subcat_id)>0){
                $data .='<div class="item-card shadow mb-3">
                            <div class="item-card-header shadow pool-header" >
                                <span class="h6">'.strtoupper($item_subcat['item_subcat']).'</span>  
                                <a href="pool.php?ty='.$subcat_id.'" class="card-link">SEE ALL</a>
                            </div>
                            <div class="item-card-body">
                                <div class="d-flex flex-wrap list-group list-group-horizontal cat-lister items pl-4" >';

                $sql = "SELECT * FROM items i LEFT JOIN category c ON i.item_category=c.cat_id 
                        LEFT JOIN subcat s ON i.item_subcat=s.subcat_id WHERE i.item_subcat='$subcat_id' ORDER BY i.item_date DESC";

                $query = mysqli_query($GLOBALS['$con'],$sql) or die('error');
                while($row = mysqli_fetch_assoc($query)){

                    $data .= '<div class="list-group-item item-info" id="'.$row['item_id'].'">
                                    <img src="image/'.$row['item_id'].'_1.png" class=" card-image-md"/>
                                    <h6 class="item-name overflow-hidden" style="white-space:nowrap">'.$row['item_name'].'</h6>
                                    <span class="item-amount">'.number_format($row['item_amount'],2).'</span>
                                </div>';
                    $hasRecord = true;
                }
                $data .= '</div></div></div>';
            }
        }
        
        return $data;
    }

    function getItemNum($subcat_id){
        $sql = "SELECT * FROM items WHERE item_subcat='$subcat_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - sub-category");
        return mysqli_num_rows($query);
    }
    
    function filter_items($search, $cat, $amt, $brand){
        $search = mysqli_real_escape_string($GLOBALS['$con'],$search);
        $data = "";
        $col ="";
        $arr = "";
        $s='';$c='';$a='';$b='';
        if(!empty($search) || !empty($cat) || !empty($amt) || !empty($brand)){
            $col =" WHERE ";
            if($search != ""){
                $s = "i.item_name LIKE '%$search%'  ";
            }
            if($cat != "")
                $c = "i.item_category='$cat'";
            if($amt != "")
                $a = "i.item_amount BETWEEN $amt";
            if($brand != "")
                $b = "i.item_brand='$brand'";
            
            
            $arr = ($s!='')?$s:'';
            $arr .= ($arr!='' && $c!='')?' AND '.$c:$c;
            $arr .= ($arr!='' && $a!='')?' AND '.$a:$a;
            $arr .= ($arr!='' && $b!='')?' AND '.$b:$b;
            
        }
        
        if(!empty($arr) || $arr!=''){
            $col .= $arr;
            $header = ($search!='')?" for '".$search."'":"";
        
        
        
            $data ='<div class="item-card shadow mb-3">
                        <div class="item-card-header shadow item_other" >
                            <span class="h6">Search result'.$header.'</span>
                        </div>
                    <div class="item-card-body ">
                        <div class="d-flex flex-wrap list-group list-group-horizontal items pl-4" >';

            $sql = "SELECT * FROM items i LEFT JOIN category c ON i.item_category=c.cat_id LEFT JOIN subcat s ON i.item_subcat=s.subcat_id 
            LEFT JOIN brand b ON i.item_brand=b.brand_id $col ORDER BY i.item_date DESC";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die('Error filter');
            while($row = mysqli_fetch_assoc($query)){
                $data .= '<div class="list-group-item item-info" id="'.$row['item_id'].'">
                                <img src="image/'.$row['item_id'].'_1.png" class="card-image-md"/>
                                <h6 class="item-name">'.$row['item_name'].'</h6>
                                <span class="item-amount">'.number_format($row['item_amount'],2).'</span>
                            </div>';
            }
            $data.= '</div></div></div>';
            if(mysqli_num_rows($query)>=1)
                echo $data;
            else if(mysqli_num_rows($query)==0)
                echo '<div class="item-card shadow mb-3">
                        <div class="item-card-header shadow item_other" >
                            <span class="h6">No result for your search "'.$search.'"</span>
                    </div></div>';
        }
        else{
            $data = "";
            if(load_new_items($GLOBALS['limit'])!=''){
                echo '<div class="item-card shadow mb-3">
                            <div class="item-card-header shadow bg-dark" style="color: white;">
                                <span class="h6">New Arrivals</span>  
                                <span class="badge badge-pill badge-danger">NEW!</span>
                                <a href="pool.php?ty=new" class="card-link" style="float: right;color: white;">SEE ALL</a>
                            </div>
                            
                            <div class="item-card-body">
                                <div class="d-flex flex-wrap list-group list-group-horizontal items pl-4">
                                    '.load_new_items($GLOBALS['limit']).'
                                </div>
                            </div>
                        </div>';
            }
            echo other_items($GLOBALS['group']);
        }
        
    }

    function item_like($user,$item_id,$like){
        if(!empty($user) && $like==1){
            $sql = "INSERT INTO item_like(username,item_id,liked) VALUES('$user','$item_id','$like')";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - cart insert");
            echo $query;
        }
        else if(!empty($user) && $like==0){
            $sql = "DELETE FROM item_like WHERE username='$user' AND item_id='$item_id'";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - cart insert");
            echo $query;
        }
    }

    function add_cart($user,$item_id,$size,$color){
        if(!empty($user)){
            $sql = "INSERT INTO cart(username,item_id,size,color) VALUES('$user','$item_id','$size','$color')";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - cart insert");
            echo $query;
        }
        else{
            session_start();
            if(empty($_SESSION['e_cart']))
                $_SESSION['e_cart'] = array();
            try {
                $old = sizeof($_SESSION['e_cart']);
                $cart = array('item_id'=>$item_id,'size'=>$size,'color'=>$color);
                array_push($_SESSION['e_cart'], $cart);
                $rs = sizeof($_SESSION['e_cart']);
                if($old != $rs)
                    echo 1;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    function check_cart($item_id){
        $sql = "SELECT * FROM cart WHERE item_id='$item_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - check cart");
        return mysqli_num_rows($query);
    }

    function remove_cart($user,$cart_id){
        if(!empty($user)){
            $sql = "DELETE FROM cart WHERE cart_id='$cart_id' AND username='$user'";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - cart delete");
            echo $query;
        }
        else{
            session_start();
            $cart_id = explode("-",$cart_id)[1];
            unset($_SESSION['e_cart'][$cart_id]);
            if(empty($_SESSION['e_cart'][$cart_id]))
                echo 1;   
        }
    }

    function load_new_items($limit){
        $data ="";
        $sql = "SELECT * FROM items WHERE DATEDIFF(CURDATE(),item_date) <= 30 ORDER BY item_date DESC ".$limit;
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= '<div class="list-group-item item-info" id="'.$row['item_id'].'">
                            <img src="image/'.$row['item_id'].'_1.png" class=" card-image-md"/>
                            <h6 class="item-name overflow-hidden">'.$row['item_name'].'</h6>
                            <span class="item-amount">'.number_format($row['item_amount'],2).'</span>
                        </div>';
        }
        
        return $data;
    }

    function load_items($group,$group_id){
        $data ="";
        $cat = 'item_'.$group;
        $sql = "SELECT * FROM items i LEFT JOIN category c ON i.item_category=c.cat_id 
                LEFT JOIN subcat s ON i.item_subcat=s.subcat_id WHERE i.$cat='$group_id' ORDER BY i.item_date DESC";

        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= '<div class="list-group-item item-info" id="'.$row['item_id'].'">
                            <img src="image/'.$row['item_id'].'_1.png" class=" card-image-md"/>
                            <h6 class="item-name overflow-hidden" style="white-space:nowrap">'.$row['item_name'].'</h6>
                            <span class="item-amount">'.number_format($row['item_amount'],2).'</span>
                        </div>';
        }
        
        return $data;
    }

    function load_header($group,$group_id){
        $data ="";
        $cat = 'item_'.$group;
        $id = ($group=='subcat')?$group.'_id':'cat_id';
        $sql = "SELECT $cat FROM $group WHERE $id='$group_id'";

        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query))
            $data = '<h5 class="header-title d-inline-block ml-2 mt-1">'.strtoupper($row[$cat]).'</h5>';
        
        return $data;
    }

    function getItemCat($cat){
        $data =[];
        $id = ($cat=='subcat')?$cat.'_id':'cat_id';
        $sql = "SELECT * FROM $cat";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error - category");
        while($row = mysqli_fetch_assoc($query))
            $data[] = [$id => $row[$id],'item_'.$cat => $row['item_'.$cat]];
        return $data;
    }

    function other_items($group){
        $cat = getItemCat($group);
        $id = ($group=='subcat')?$group.'_id':'cat_id';
        $col = 'item_'.$group;
        //echo json_encode($cat);
        for($j=0;$j<sizeof($cat);$j++){
            $item_cat = $cat[$j];
            $data ='<div class="item-card shadow mb-3">
                        <div class="item-card-header shadow item_other" >
                            <span class="">'.strtoupper($item_cat[$col]).'</span>  
                            <!--<span class="badge badge-pill badge-danger">NEW!</span> -->
                            <a href="pool.php?ty='.$item_cat[$id].'" class="card-link" style="">SEE ALL</a>
                        </div>
                        <div class="item-card-body ">
                            <div class="d-flex flex-wrap list-group list-group-horizontal items pl-2" >';

            $sql = "SELECT * FROM items WHERE $col='$item_cat[$id]' ORDER BY item_date DESC LIMIT 0,10";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die("error fetch items - key $key - SQL: ".$sql);
            while($row = mysqli_fetch_assoc($query)){
                $data .= '<div class="list-group-item item-info" id="'.$row['item_id'].'">
                                <img src="image/'.$row['item_id'].'_1.png" class="card-image-md"/>
                                <h6 class="item-name">'.$row['item_name'].'</h6>
                                <span class="item-amount">'.number_format($row['item_amount'],2).'</span>
                            </div>';
            }
            $data.= '</div></div></div>';
            //echo 'All '.$item_cat[$col].': '.mysqli_num_rows($query);
            if(mysqli_num_rows($query)>0)
                echo $data;
        }
    }

    function fetch_columns($column){
        $data = "<option selected value=''>All</option>";
        $table = explode('_',$column)[1];
        $id = ($table=='category')?'cat_id':$table.'_id';
        
        $sql = "SELECT * FROM $table";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= "<option value='".$row[$id]."'>".$row[$column]."</option>";
        }
        return $data;
    }

    
?>