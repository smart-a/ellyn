<?php
    require("../../connection/connect.php");
    $GLOBALS['$con'] = $con;
    $user_id = "";
    $limit = '';
    extract($_POST);

    if(empty($_SESSION['post']))
        $_SESSION['post'] = [];

    if(isset($_POST['action']))
        switch ($action) {
            case 'item_id':
                echo item_id();
                break;
            case 'item_fetch':
                echo get_item($user_id,$item_id);
                break;
            case 'add_item':
                $session = sizeof($_SESSION['post']);
                $post = sizeof($_POST);
                $arr = sizeof(array_diff_assoc($_POST,$_SESSION['post']));
                if($arr > 0){
                    $item_name = mysqli_real_escape_string($GLOBALS['$con'],$item_name);
                    @$item_size = implode(",",$item_size);
                    @$item_color = implode(",",$item_color); 
                    @$item_brand = $item_brand;
                    $item_date =  date('Y-m-d');
                    $item_amount = str_replace(",","",$item_amount);
                    $sql= "INSERT INTO items(item_id,item_name,item_qty,item_amount,item_brand,item_category,item_subcat,item_size,item_color,item_date)
                            VALUE('$item_id','$item_name','$item_qty','$item_amount','$item_brand','$item_category','$item_subcat','$item_size','$item_color','$item_date')";
                    $query = mysqli_query($GLOBALS['$con'],$sql) or die('error occure - <a href="./">Go back</a>');
                    $_SESSION['post'] = $_POST;
                }
                
                break;
            case 'update_item':
                @$item_size = implode(",",$item_size);
                @$item_color = implode(",",$item_color); 
                $date_col = "";
                $item_date =  date('Y-m-d');
                
                if(isset($new_item))
                   $date_col = ",item_date='$item_date'";

                $item_amount = str_replace(",","",$item_amount);
                $item_name = mysqli_real_escape_string($GLOBALS['$con'],$item_name);
                $sql= "UPDATE items SET 
                        item_name='$item_name',
                        item_qty='$item_qty',
                        item_amount='$item_amount',
                        item_brand='$item_brand',
                        item_category='$item_category',
                        item_subcat='$item_subcat',
                        item_size='$item_size',
                        item_color='$item_color' $date_col
                        WHERE item_id='$item_id'";
                $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
                break;
            case 'delete_item':
                echo delete_item($item_id);
                break;
            case 'close_item':
                echo check_id($item_id);
                break;
            case 'search':
                echo search_items($search, $order, $limit);
                break;
            case 'items_num':
                echo get_items_num($search);
                break;
            case 'fetch_column':
                echo fetch_columns($column);
                break;
            case 'fetch_SubcatByCat':
                echo fetch_SubcatByCat($cat_id);
                break;
            
            default:
                # code...
                break;
        }
    
    function fetch_columns($column){
        $data = "<option selected disabled value=''>Please select option</option>";
        $table = explode('_',$column)[1];
        $id = ($table=='category')?'cat_id':$table.'_id';
        
        $sql = "SELECT * FROM $table";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= "<option value='".$row[$id]."'>".$row[$column]."</option>";
        }
        return $data;
    }

    function fetch_SubcatByCat($cat_id){
        $data = "<option selected disbaled value=''>Please select option</option>";
        $sql = "SELECT * FROM subcat s LEFT JOIN category c ON s.cat_id=c.cat_id WHERE c.cat_id='$cat_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= "<option value='".$row['subcat_id']."'>".$row['item_subcat']."</option>";
        }
        return $data;
    }
    
    function search_items($search, $order, $limit){
        $search = mysqli_real_escape_string($GLOBALS['$con'],$search);
        $data = "";  //array("item_name","item_qty","item_amount","item_category","item_subcat","item_size","item_color");
        $col ="";
        if($search != ""){
            $col = "i.item_name,i.item_qty,i.item_amount,b.item_brand,c.item_category,s.item_subcat,i.item_size,i.item_color";
            $col = " WHERE ".str_replace(","," LIKE '%$search%' OR ",$col)." LIKE '%$search%' ";
        }
            
        $sql = "SELECT * FROM items i LEFT JOIN category c ON i.item_category=c.cat_id LEFT JOIN subcat s ON i.item_subcat=s.subcat_id 
                LEFT JOIN brand b ON i.item_brand=b.brand_id $col ORDER BY i.item_date DESC $limit";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error search");
        $c=1;
        while($row = mysqli_fetch_assoc($query)){
            $data .= "<tr class='".$row['item_id']."'>".
                            "<td>".$c."</td>".
                            "<td>".$row['item_category']."</td>".
                            "<td>".$row['item_subcat']."</td>".
                            "<td>".$row['item_brand']."</td>".
                            "<td>".$row['item_name']."</td>".
                            "<td>".$row['item_qty']."</td>".
                            "<td><span class='item-amount'>".number_format($row['item_amount'],2)."</span></td>".
                            "<td>".$row['item_size']."</td>".
                            "<td>".$row['item_color']."</td>".
                            "<td>
                                <button type='button' class='btn btn-sm btn-primary mr-2 item-edit' item-id='".$row['item_id']."' data-toggle='tooltip' title='Edit'>
                                    <i class='fa fa-edit'></i>
                                </button>
                                <button type='button' class='btn btn-sm btn-danger item-delete' item-id='".$row['item_id']."' data-toggle='tooltip' title='Delete'>
                                    <i class='fa fa-trash'></i>
                                </button>
                            </td></tr>";
            $c++;
        }
        
        return $data;
    }

   function delete_item($id){
        $sql= "DELETE FROM items WHERE item_id='$id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        if(mysqli_affected_rows($GLOBALS['$con']) > 0)
            return 1;
        else
            return 0;
   }

    function check_id($id){
        $sql= "SELECT * FROM items WHERE item_id='".$id."'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        if(mysqli_num_rows($query) == 1)
            return 1;
        else
            return 0;
    }

    function item_id(){
        $cAlpha = range('A','Z');
        $numeric = range('0','9');
        $val='';
        while(true){
            $val='';
            for($i=1; $i<=6; $i++){
                $a = rand(0, 25);
                $val.=$cAlpha[$a];
            }
            for($i=1; $i<=4; $i++){
                $a = rand(0, 8);
                $val.=$numeric[$a];
            }
            if(check_id($val)==0)
                break;
        }
        return $val; 
    }

    function get_items_num($filter){
        $col ="";
        if($filter != ""){
            $col = "item_name,item_qty,item_amount,item_category,item_subcat,item_size,item_color";
            $col = " WHERE ".str_replace(","," LIKE '%$filter%' OR ",$col)." LIKE '%$filter%' ";
        }
        $sql = "SELECT * FROM items $col ORDER BY item_date DESC";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        return mysqli_num_rows($query);
    }

    function fetch_items($limit){
        $sql = "SELECT * FROM items i LEFT JOIN category c ON i.item_category=c.cat_id LEFT JOIN subcat s ON i.item_subcat=s.subcat_id
                 LEFT JOIN brand b ON i.item_brand=b.brand_id ORDER BY i.item_date DESC LIMIT $limit"; //.$GLOBALS['page_limit'];
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        return $query;
    }

    function JQ_fetch_items($column){
        $data = "";
        $sql = "SELECT * FROM items ORDER BY $column item_date DESC ";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die("error");
        while($row = mysqli_fetch_assoc($query)){
            $data .= "<tr class='".$row['item_id']."'>".
                            "<td>".$row['item_category']."</td>".
                            "<td>".$row['item_subcat']."</td>".
                            "<td>".$row['item_name']."</td>".
                            "<td>".$row['item_qty']."</td>".
                            "<td><span class='item-amount'>".number_format($row['item_amount'],2)."</span></td>".
                            "<td>".$row['item_size']."</td>".
                            "<td>".$row['item_color']."</td>".
                            "<td>
                                <button type='button' class='btn btn-sm btn-primary mr-2 item-edit' item-id='".$row['item_id']."'>
                                    <i class='fa fa-edit'></i>
                                    Edit
                                </button>
                                <button type='button' class='btn btn-sm btn-danger item-delete' item-id='".$row['item_id']."'>
                                    <i class='fa fa-trash'></i>
                                    Delete
                                </button>
                            </td></tr>";
        }
        return $data;
    }

    function get_item($user_id,$item_id){
        $sql = "SELECT * FROM items i LEFT JOIN item_like it ON i.item_id=it.item_id AND it.username='$user_id' WHERE i.item_id='$item_id'";
        $query = mysqli_query($GLOBALS['$con'],$sql) or die(mysqli_error());
        $row = mysqli_fetch_assoc($query);
        $data = [ 'item_name' => $row['item_name'],
                'item_qty' => $row['item_qty'],
                'item_amount' => number_format($row['item_amount'],2),
                'item_category' => $row['item_category'],
                'item_subcat' => $row['item_subcat'],
                'item_brand' => $row['item_brand'],
                'item_size' => $row['item_size'],
                'item_color' => $row['item_color'],
                'item_date' => $row['item_date'],
                'item_like' => $row['liked'] ] ;

        $jsonDATA = json_encode($data);
        return $jsonDATA;
    }


?>