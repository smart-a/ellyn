<?php
    require "connection/connect.php";
    $GLOBALS['$con'] = $con;
    class Auth {
        function getMemberByUsername($username) {
            $query = "SELECT * FROM members WHERE username = '$username'";
            $result = mysqli_query($GLOBALS['con'],$query) or die("error in getMemberByUsername()");
            return mysqli_fetch_array($result);
        }
        
        function getTokenByUsername($username) {
            $query = "SELECT * FROM e_token_auth WHERE user_hash = '$username'";
            $result = mysqli_query($GLOBALS['con'],$query) or die("error in getTokenByUsername()");
            return mysqli_fetch_array($result);
        }
        
        function discardToken($username) {
            $expired = 1;
            $query = "DELETE FROM e_token_auth WHERE user_hash = '$username'";
            $result = mysqli_query($GLOBALS['con'],$query) or die("error in discardToken()");
            return $result;
        }
        
        function insertToken($user_hash, $password_hash, $expiry_date) {
            $query = "INSERT INTO e_token_auth (user_hash, password_hash, expiry_date) 
                        VALUES ('$user_hash', '$password_hash', '$expiry_date')";
            $result = mysqli_query($GLOBALS['con'],$query) or die("error in insertToken()");
            return $result;
        }

        function getCart($user) {
            $query = "SELECT count(username) as cart_num FROM cart WHERE username='$user'";
            $result = mysqli_query($GLOBALS['con'],$query) or die("error in get cart");
            return mysqli_fetch_assoc($result)['cart_num'];
        }
    }
?>