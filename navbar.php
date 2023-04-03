<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <?php 
                $loggedIn = false;
                if(isset($_SESSION['loggedIn'])){
                    $loggedIn = true;
                }
                echo '<li><a href="/LOGIN_SIGNUP_PHP/home.php">Home</a></li>';

                if(!$loggedIn){
                    echo '<li><a href="/LOGIN_SIGNUP_PHP/signup.php">Signup</a></li>
                    <li><a href="/LOGIN_SIGNUP_PHP/login.php">login</a></li>';
                }else{
                    echo '<li><a href="/LOGIN_SIGNUP_PHP/logout.php">Logout</a></li>';
                }
                
            ?>
            
        </ul>
    </div>
</body>
</html>