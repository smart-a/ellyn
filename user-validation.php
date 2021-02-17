<?php

    require_once('Auth.php');
    require_once('Util.php');

    $auth = new Auth();
    $util = new Util();

    // Get Current date, time
    $current_time = time();
    $current_date = date("Y-m-d H:i:s", $current_time);

    // Set Cookie expiration for 1 month
    $cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

    $isLoggedIn = false;

    if(!empty($_SESSION['e_member'])){
        $isLoggedIn = true;
    }
    else if(!empty($_COOKIE["e_username"]) && !empty($_COOKIE["e_password"])){
        $isValid = false;

        $user_data = $auth->getTokenByUsername($_COOKIE["e_username"]);

        if($user_data['password_hash'] == $_COOKIE['e_password'] && $user_data['expiry_date'] >= $current_date ){
            $isValid = true;
        }

        if(!empty($user_data['user_hash']) && $isValid){
            $isLoggedIn = true;
        }
        else {
            if(!empty($user_data["user_hash"])) {
                $auth->discardToken($user_data["user_hash"]);
            }
            // clear cookies
            $util->clearAuthCookie();
        }
    }


    extract($_POST);
    if (isset($action)) {
        $isAuthenticated = false;
        
        $user = $auth->getMemberByUsername($username);
        if ($password == $user["password"]) {
            $isAuthenticated = true;
        }
        
        if ($isAuthenticated) {
            $_SESSION["e_member"] = $user["username"];
            
            // Set Auth Cookies if 'Remember Me' checked
            if (! empty($remember)) {
                setcookie("e_username", $username, $cookie_expiration_time);
                
                $password_hash = $util->getToken(16);
                setcookie("e_password", $password_hash, $cookie_expiration_time);

                $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
                
                // discard existing token if expire
                $userToken = $auth->getTokenByUsername($username);
                if (! empty($userToken["user_hash"])) {
                    $auth->discardToken($userToken["user_hash"]);
                }
                // Insert new token
                $auth->insertToken($username, $password_hash, $expiry_date);
            } else {
                $util->clearAuthCookie();
            }
            $util->redirect("./");
        } else {
            $message = '<i class="fa fa-exclamation-triangle" style="color:red"></i> <span>Invalid Login details</span>';
        }
    }



?>