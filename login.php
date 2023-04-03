<?php 
     if($_SERVER['REQUEST_METHOD'] =='POST'){
        include 'C:\xampp\htdocs\LOGIN_SIGNUP_PHP\_dbconnect.php';

        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT password FROM `users` WHERE `email`='$email'";
        $statement = $conn->prepare($sql);
        $statement->execute();

        $resultSet = $statement->get_result();
        $output = $resultSet->fetch_all(MYSQLI_ASSOC);

        $hashed_password=$output[0]["password"];
        // $user_entered_pass = password_hash($password,PASSWORD_DEFAULT, array('cost' => 9));
        
        echo "<br>".$hashed_password;
        echo "<br>".$password;

        print_r(password_verify($password, $hashed_password));


        if(password_verify(trim($password),trim($hashed_password))){
            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $hashed_password;

            header('location:home.php'); //redirecting to home page after successfull authentication
        }else{
                echo "<br>Invalid credentials";
        }

        // $num = mysqli_num_rows($result);

        // if($num >0){
            // session_start();
            // $_SESSION['loggedIn'] = true;
            // $_SESSION['email'] = $email;
            // $_SESSION['password'] = $password;

            // header('location:home.php'); //redirecting to home page after successfull authentication
        // }else{
        //     echo "<br>Invalid credentials";
        // }

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
    
    <h1>Please Login..</h1>
    <form action="/LOGIN_SIGNUP_PHP/login.php" method="post">

        <label for="email">Email:</label>
        <input type="text" name="email"> <br>

        <label for="password">Password:</label>
        <input type="text" name="password"><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>