<?php

    require "../../connection/connect.php";
    $GLOBALS['$con'] = $con;
    extract($_POST);

   // if(isset($_GET['action'])){
   //     $action = $_GET['action'];
  //  }
    //$image_name = $_GET['action'];; //image name from form/ value of the image tag
    
    switch ($action) {
        case 'image_upload':
            image_upload($item_id);
            break;
        case 'delete_img':
            delete_image($image_name);
            break;
        case 'check_img':
            echo check_image($img_name);
            break;
        case 'remove_all_image':
            echo remove_all_image($image_name);
            break;
        case 'item_img':
            remove_dummy_image();
            break;
        
        default: 
            # code...
            break;
    }

    function remove_dummy_image(){
        $root = $_SERVER["DOCUMENT_ROOT"]."/ELLYN/image";
        $arr_files = [];
        $arr_ids = [];
        $status = 1;
        
        foreach(glob($root.'/*.*') as $file){
            $f = explode('_',basename($file))[0];
            array_push($arr_files,$f);
        }

        $query = mysqli_query($GLOBALS['con'],"SELECT item_id FROM items") or die("Error read");
        while($row = mysqli_fetch_array($query)){
            array_push($arr_ids,$row['item_id']);
        }
        
        $arr_dummy = array_diff($arr_files,$arr_ids);
        foreach ($arr_dummy as $key => $val) {
            $status = remove_all_image($val);
        }
        
        echo $status; //json_encode($arr_dommy);
    }
    
    function remove_all_image($img_name){
        $root = $_SERVER["DOCUMENT_ROOT"]."/ELLYN";
        $img_path = $root."/image/";
        $c = 1;
        $org_img = $img_path.$img_name."_".$c.".png";
        $status = 1;
        while(file_exists($org_img)){
            if(!unlink($org_img)){
                $status = 0; 
                break;
            }
            $c++;
            $org_img = $img_path.$img_name."_".$c.".png";
            
        }
        return $status;
    }

    function delete_image($img_name){
        $root = $_SERVER["DOCUMENT_ROOT"]."/ELLYN/image/";
        $img_path = $root.$img_name.".png";
        $img_n = $root.explode("_",$img_name)[0];
        $n="";
        $nn="";
        $c = 1;
        $cc = 2;
        $not = 0;
        $result = "0";
        if(unlink($img_path))
            $result = "1";
        $count=0;
        $n = $img_n."_".$c.".png";
        $nn = $img_n."_".$cc.".png";

        while($not < 2){
            if(!file_exists($n)){
                if(file_exists($nn)){
                    rename($nn,$n);
                    $c++;
                    $cc = $c+1;
                }
                else{
                    $not++;
                    $cc++;
                } 
                $nn = $img_n."_".$cc.".png";  
            }
            else{
                $c++;
                $cc = $c+1;
            }
            $n = $img_n."_".$c.".png";
            $nn = $img_n."_".$cc.".png";

            $count++;
        }
        echo $result;
    }

    function image_upload($item_id)
    {
        $valid_exts = array("jpeg", "jpg", "png");
        $path = $_SERVER["DOCUMENT_ROOT"]."/ELLYN/image/";
        $img_nam ="";
 
        $img = basename($_FILES['item_img']['name']); 
        $tmp = $_FILES['item_img']['tmp_name']; 

        //get image extension
        $nam = explode('.', $img);
        $ext = $nam[1];

        //Generate random number
        //$rnd = rand(100,100000);

        //Check if image extension is valid based on the expected extension
        if(in_array($ext, $valid_exts)){
            $img_nam = generate_img_name($item_id);

            if($img_nam != "img_exceed"){
                $path = $path.$img_nam;
                if(move_uploaded_file($tmp, $path)){
                    echo $img_nam; //return new image name;
                }
                else
                    echo "error_upload";
            }
            else
                echo "error_exceed";
        }
        else
            echo "error_ext";
    }
  
    function generate_img_name($item_id){
        $item_name = $item_id."_1.png";
        $root = $_SERVER["DOCUMENT_ROOT"]."/ELLYN";
        $img_path = $root."/image/".$item_name;
        $count = 1;
        while(file_exists($img_path)){
            $item_name = $item_id."_".$count.".png";
            $img_path = $root."/image/".$item_name;
            $count++;
            if($count > 6){
                $item_name = "img_exceed";
                break;
            }
                
        }
        return $item_name;
    }

    function check_image($img_name) {
        $img = [];
        $root = $_SERVER["DOCUMENT_ROOT"]."/ELLYN";
        $count = 1;
        while(true){
            $src = $img_name."_".$count;
            $img_path = $root."/image/".$src.".png";
            if(file_exists($img_path))
                $img[] = array($src);
            else
                break;
            $count++;
        }
        $jsonDATA = json_encode($img);
        return $jsonDATA;
    }

    function move_image($img_name){
        $root = $_SERVER["DOCUMENT_ROOT"]."/ELLYN";
        $img_path = $root."/image/".$img_name.".png";
        $img_tmp = $root."/tmp_image/".$img_name.".png";
        if(file_exists($img_tmp))
            if(rename($img_tmp, $img_path)){
                echo "1"; //return new image path;
            }
            else
                echo "0";
    }

    function delete_images($img_name){
        $root_tmp = $_SERVER["DOCUMENT_ROOT"]."/ELLYN/tmp_image/";
        $img_tmp = $root_tmp.$img_name.".png";
        $img_n = $root_tmp.explode("_",$img_name)[0];

        $root_img = $_SERVER["DOCUMENT_ROOT"]."/ELLYN/image/";
        $img_path = $root_img.$img_name.".png";
        $img_m = $root_img.explode("_",$img_name)[0];
        
        $n="";
        $nn="";
        $m="";
        $mm="";
        $c = 1;
        $cc = 2;
        $not = 0;
        $result = "0";
        if(unlink($img_tmp) || unlink($img_path))
            $result = "1";
        $count=0;
        $n = $img_n."_".$c.".png";
        $nn = $img_n."_".$cc.".png";
        $m = $img_m."_".$c.".png";
        $mm = $img_mm."_".$cc.".png";
        while($not < 2){
           
            if(!file_exists($n)){
                if(file_exists($nn)){
                    rename($nn,$n);
                    $c++;
                    $cc = $c+1;
                }
                else{
                    $not++;
                    $cc++;
                } 
                $nn = $img_n."_".$cc.".png";  
            }
            else{
                $c++;
                $cc = $c+1;
            }
            $n = $img_n."_".$c.".png";
            $nn = $img_n."_".$cc.".png";

            if(!file_exists($m)){
                if(file_exists($mm)){
                    rename($mm,$m);
                    $c++;
                    $cc = $c+1;
                }
                else{
                    $not++;
                    $cc++;
                } 
                $mm = $img_m."_".$cc.".png";  
            }
            else{
                $c++;
                $cc = $c+1;
            }
            $m = $img_m."_".$c.".png";
            $mm = $img_mm."_".$cc.".png";


            $count++;
        }
        echo $result;
    }

?>