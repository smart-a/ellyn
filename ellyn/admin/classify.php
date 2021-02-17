<?php
    require("../../connection/connect.php");
    $GLOBALS['$con'] = $con;
    extract($_POST);

    if(empty($_SESSION['post']))
        $_SESSION['post'] = [];

    if(isset($_POST['action'])){
        switch ($action) {
            case 'category_fetch':
                echo get_category($cat_id);
                break;
            case 'add_category':
                $session = sizeof($_SESSION['post']);
                $post = sizeof($_POST);
                $arr = sizeof(array_diff_assoc($_POST,$_SESSION['post']));
                if($arr > 0){
                    $item_category = mysqli_real_escape_string($GLOBALS['$con'],$item_category);
                    $sql= "INSERT INTO category(item_category) VALUE('$item_category')";
                    $query = mysqli_query($GLOBALS['$con'],$sql) or die("error insert category");
                    $_SESSION['post'] = $_POST;
                }
                break;
            case 'update_category':
                $item_category = mysqli_real_escape_string($GLOBALS['$con'],$item_category);
                $sql= "UPDATE category SET item_category='$item_category' WHERE cat_id='$cat_id'";
                $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
                break;
            case 'delete_category':
                echo delete_category($cat_id);
                break;

            case 'subcat_fetch':
                echo get_subcat($subcat_id);
                break;
            case 'add_subcat':
                $session = sizeof($_SESSION['post']);
                $post = sizeof($_POST);
                $arr = sizeof(array_diff_assoc($_POST,$_SESSION['post']));
                if($arr > 0){
                    $item_subcat = mysqli_real_escape_string($GLOBALS['$con'],$item_subcat);
                    $sql= "INSERT INTO subcat(cat_id,item_subcat) VALUE('$item_category','$item_subcat')";
                    $query = mysqli_query($GLOBALS['$con'],$sql) or die("error insert subcat");
                    $_SESSION['post'] = $_POST;
                }
                break;
            case 'update_subcat':
                $item_subcat = mysqli_real_escape_string($GLOBALS['$con'],$item_subcat);
                $sql= "UPDATE subcat SET cat_id='$item_category', item_subcat='$item_subcat' WHERE subcat_id='$subcat_id'";
                $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
                break;
            case 'delete_subcat':
                echo delete_subcat($subcat_id);
                break;
        
            case 'brand_fetch':
                echo get_brand($brand_id);
                break;
            case 'add_brand':
                $session = sizeof($_SESSION['post']);
                $post = sizeof($_POST);
                $arr = sizeof(array_diff_assoc($_POST,$_SESSION['post']));
                if($arr > 0){
                    $item_brand = mysqli_real_escape_string($GLOBALS['$con'],$item_brand);
                    $sql= "INSERT INTO brand(brand_id,item_brand) VALUE('$item_brand','$item_brand')";
                    $query = mysqli_query($GLOBALS['$con'],$sql) or die("error insert brand");
                    $_SESSION['post'] = $_POST;
                }
                break;
            case 'update_brand':
                $item_brand = mysqli_real_escape_string($GLOBALS['$con'],$item_brand);
                $sql= "UPDATE brand SET cat_id='$item_brand', item_brand='$item_brand' WHERE brand_id='$brand_id'";
                $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
                break;
            case 'delete_brand':
                echo delete_brand($brand_id);
                break;
            
            case 'pickup_fetch':
                echo get_pickup($pickup_id);
                break;
            case 'add_pickup':
                $session = sizeof($_SESSION['post']);
                $post = sizeof($_POST);
                $arr = sizeof(array_diff_assoc($_POST,$_SESSION['post']));
                if($arr > 0){
                    $address = mysqli_real_escape_string($GLOBALS['$con'],$address);
                    $sql= "INSERT INTO pickup_station(`state`,city,`address`,fee) VALUE('$state','$city','$address','$fee')";
                    $query = mysqli_query($GLOBALS['$con'],$sql) or die("error insert pickup");
                    $_SESSION['post'] = $_POST;
                }
                break;
            case 'update_pickup':
                $address = mysqli_real_escape_string($GLOBALS['$con'],$address);
                $sql= "UPDATE pickup_station SET `state`='$state',city='$city',`address`='$address',fee='$fee' WHERE pickup_id='$pickup_id'";
                $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
                break;
            case 'delete_pickup':
                echo delete_pickup($pickup_id);
                break;
            
            default:
                # code...
                break;
        }
    }


    function fetch_city($state){
        $data = "<option selected disabled value=''>Select option</option>";
        $sql = "SELECT DISTINCT(city) FROM pickup_station WHERE `state`='$state'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query))
            $data.="<option>".$row['city']."</option>";
        return $data;
    }

    function get_pickup($pickup_id){
        $sql = "SELECT * FROM pickup_station WHERE pickup_id='$pickup_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        $row = mysqli_fetch_assoc($query);
        $data = ['pickup_id' => $row['pickup_id'],
                'state' => $row['state'],
                'city' => $row['city'],
                'address' => $row['address'],
                'fee' => $row['fee']] ;
        $jsonDATA = json_encode($data);
        return $jsonDATA;
    }

    function fetch_pickup(){
        $sql = "SELECT * FROM pickup_station";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        return $query;
    }

    function delete_pickup($pickup_id){
        $sql= "DELETE FROM pickup_station WHERE pickup_id='$pickup_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        if(mysqli_affected_rows($GLOBALS['$con']) > 0)
            return 1;
        else
            return 0;
    }

   function delete_category($id){
        $sql= "DELETE FROM category WHERE cat_id='$id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        if(mysqli_affected_rows($GLOBALS['$con']) > 0)
            return 1;
        else
            return 0;
   }

    function fetch_category(){
        $sql = "SELECT * FROM category";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        return $query;
    }

    function get_category($cat_id){
        $sql = "SELECT * FROM category WHERE cat_id='$cat_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        $row = mysqli_fetch_assoc($query);
        $data = ['item_category' => $row['item_category']] ;

        $jsonDATA = json_encode($data);
        return $jsonDATA;
    }

    function delete_subcat($id){
        $sql= "DELETE FROM subcat WHERE subcat_id='$id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        if(mysqli_affected_rows($GLOBALS['$con']) > 0)
            return 1;
        else
            return 0;
    }

    function fetch_subcat(){
        $sql = "SELECT * FROM subcat s LEFT JOIN category c ON s.cat_id=c.cat_id";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        return $query;
    }

    function get_subcat($subcat_id){
        $sql = "SELECT * FROM subcat s LEFT JOIN category c ON s.cat_id=c.cat_id WHERE s.subcat_id='$subcat_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        $row = mysqli_fetch_assoc($query);
        $data = ['cat_id' => $row['cat_id'],
                'item_subcat' => $row['item_subcat']] ;
        $jsonDATA = json_encode($data);
        return $jsonDATA;
    }

    function delete_brand($id){
        $sql= "DELETE FROM brand WHERE brand_id='$id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        if(mysqli_affected_rows($GLOBALS['$con']) > 0)
            return 1;
        else
            return 0;
   }

    function fetch_brand(){
        $sql = "SELECT * FROM brand";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        return $query;
    }

    function get_brand($brand_id){
        $sql = "SELECT * FROM brand WHERE brand_id='$brand_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        $row = mysqli_fetch_assoc($query);
        $data = ['item_brand' => $row['item_brand']] ;

        $jsonDATA = json_encode($data);
        return $jsonDATA;
    }

?>