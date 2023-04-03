<?php 
    $server ='localhost';
    $username ='root';
    $password = '';
    $database = 'employee';

    $conn = mysqli_connect($server , $username , $password , $database);

    if(!$conn){
        die('Connection failed due to '.mysqli_error());

    }

?>