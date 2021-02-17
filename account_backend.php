<?php
    session_start();
    require "connection/connect.php";
    $GLOBALS['$con'] = $con;
    extract($_POST);

    switch ($action) {
        case 'check-user':
            echo check_user($value);
            break;
        case 'create':
            $fullname = mysqli_real_escape_string($GLOBALS['$con'],$fullname);
            $address = mysqli_real_escape_string($GLOBALS['$con'],$address);
            $email = mysqli_real_escape_string($GLOBALS['$con'],$email);
            $username = mysqli_real_escape_string($GLOBALS['$con'],$username);
            $sql= "INSERT INTO members(fullname,country,state,city,address,phone,email,username,password)
                    VALUE('$fullname','$country','$state','$city','$address','$phone','$email','$username','$password')";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die('error');
            $_SESSION['success'] = $username;
            header('location:success.php');
            break;
        case 'update':
            $fullname = mysqli_real_escape_string($GLOBALS['$con'],$fullname);
            $address = mysqli_real_escape_string($GLOBALS['$con'],$address);
            $email = mysqli_real_escape_string($GLOBALS['$con'],$email);
            $username = mysqli_real_escape_string($GLOBALS['$con'],$username);
            $sql= "UPDATE members SET fullname='$fullname',country='$country',state='$state',city='$city',
                    address='$address',phone='$phone',email='$email' WHERE username='$username'";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die('error');
            $_SESSION['success'] = $username;
            echo 1;
            break;
        case 'check-details':
            echo check_user_details($user,$email,$phone);
            break;
        case 'reset-password':
            $sql= "UPDATE members SET password='$password' WHERE username='$username'";
            $query = mysqli_query($GLOBALS['$con'],$sql) or die('error');
            $_SESSION['success'] = 'success';
            header('location:login.php?reset-pass=success');
            break;
        
        default:
            # code...
            break;
    }



    //functions
    function check_user($user){
        $query = "SELECT * FROM members WHERE username = '$user'";
        $result = mysqli_query($GLOBALS['con'],$query) or die("error");
        return mysqli_num_rows($result);
    }

    function check_user_details($user,$email,$phone){
        $query = "SELECT * FROM members WHERE username = '$user' AND email='$email' AND phone='$phone'";
        $result = mysqli_query($GLOBALS['con'],$query) or die("error");
        return mysqli_num_rows($result);
    }

    function user_details($user){
        $query = "SELECT * FROM members WHERE username = '$user'";
        $result = mysqli_query($GLOBALS['con'],$query) or die("error");
        return mysqli_fetch_assoc($result);
    }

?>