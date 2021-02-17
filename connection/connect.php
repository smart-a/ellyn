<?php
      //session_start();
    
        $server = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "ellyn";
        $con = mysqli_connect($server, $user, $pass, $dbname);
        //$mysql = new mysqli($server, $user, $pass, $dbname);
        if(!$con)
          echo $mysqli->connect_error;

?>