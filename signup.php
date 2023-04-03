<?php 
     if($_SERVER['REQUEST_METHOD'] =='POST'){
        include 'C:\xampp\htdocs\LOGIN_SIGNUP_PHP\_dbconnect.php';

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $userExists = false;

        // prevention of sql injection
        $username = stripcslashes($username);
        $password = stripcslashes($password);
        $username = mysqli_real_escape_string($conn , $username);
        $password= mysqli_real_escape_string($conn , $password);

        $sql = "SELECT * FROM `users` WHERE username='$username'";
        $result = mysqli_query($conn,$sql);
        $numRows = mysqli_num_rows($result);
        $error="";

        if($email=="" || $password==""){
            $error.="Email and password required";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error .= "<br>Please use @ and . in email";
            }if( strlen($password ) < 8 ) {
                $error .= "<br>Password too short!
                ";
                }
                if( strlen($password ) > 20 ) {
                $error .= "<br>Password too long!
                ";
                }
                if( !preg_match("#[0-9]+#", $password ) ) {
                $error .= "<br>Password must include at least one number!
                ";
                }
                if( !preg_match("#[a-z]+#", $password ) ) {
                $error .= "<br>Password must include at least one letter!
                ";
                }
                if( !preg_match("#[A-Z]+#", $password ) ) {
                $error .= "<br>Password must include at least one CAPS!
                ";
                }
        }

            if($error){
            echo "<br>Email and Password validation failure(your choice is weak): $error";
            }else{
            // It means user already exists in database
                    if($numRows > 0){
                $userExists = true;
                 }

            if($userExists){
                echo "<br> User already exists";
            }else{
                
                if($password == $confirm_password){
                    
                        // Use password_hash() function to
                        // create a password hash
                        $hash_password_salt = password_hash($password,PASSWORD_DEFAULT);
                        $password = $hash_password_salt;

                    $sql = "INSERT INTO `users` (`username`, `email`, `password`) VALUES ('$username', '$email', '$password')";

                    $result = mysqli_query($conn , $sql);

                    if($result){
                        echo "<br>Signed up successfully";

                        session_start();
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $hash_password_salt;
                        header("location:home.php");
                    }else{
                        echo "failed signup due to -------> ".mysqli_error($conn);
                    }
                }else{
                    echo "<br>Password and confirm password doesn't matches";
                }
            }
                        } 
                    
     }


    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php require 'navbar.php ';
     
    ?>
    
    <h1>Please Signup..</h1>
    <form action="/LOGIN_SIGNUP_PHP/signup.php" method="post">

        <label for="username">User Name:</label>
        <input type="text" name="username"> <br>

        <label for="email">Email:</label>
        <input type="text" name="email"> <br>

        <label for="password">Password:</label>
        <input type="text" name="password"><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="text" name="confirm_password">

        <button type="submit">Submit</button>
    </form>
</body>
</html>