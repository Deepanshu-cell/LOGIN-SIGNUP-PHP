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

        $sql = "SELECT * FROM `users` WHERE username=?";

        $statement = $conn->prepare($sql);
        $statement->bind_param('s',$username);
        $statement->execute();
        $rows_exists = $statement->get_result()->fetch_row();

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
            echo json_encode(array('msg'=>"<br>Email and Password validation failure(your choice is weak): $error",'error'=>true));
            die();
            }else{
            // It means user already exists in database
                if($rows_exists){
                $userExists = true;
                }

            if($userExists){
                echo json_encode(array('msg'=>"<br> User already exists",'error'=>true));
            die();

            }else{
                
                if($password == $confirm_password){
                    
                        // Use password_hash() function to
                        // create a password hash
                        $hash_password_salt = password_hash($password,PASSWORD_DEFAULT);
                        $password = $hash_password_salt;

                    $sql = "INSERT INTO `users` (`username`, `email`, `password`) VALUES (?, ?, ?)";
                    $statement = $conn->prepare($sql);
                    $statement->bind_param('sss',$username,$email,$password);
                    $statement->execute(); 
                 
                    if($statement){
                        echo json_encode(array('msg'=>"<br> Signed Up successfully",'error'=>false));

                        session_start();
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $hash_password_salt;
                        // header("location:home.php");
                        die();
                    }else{
                        echo json_encode(array('msg'=>"'failed signup due to -------> '.mysqli_error($conn)",'error'=>true));
                    die();

                    }
                }else{
                    echo json_encode(array('msg'=>"Password and confirm password does not matches",'error'=>true));
                    die();
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
    
    <h1 id="signup-heading">Please Signup..</h1>
    <h1 id="error-heading" style="display:none">Error occured</h1>
    <form method="" id="signup-form">

        <label for="username">User Name:</label>
        <input type="text" name="username" id="username"> <br>

        <label for="email">Email:</label>
        <input type="text" name="email" id="email"> <br>

        <label for="password">Password:</label>
        <input type="text" name="password" id="password"><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="text" name="confirm_password" id="confirm_password">

        <button type="submit" id="submit-btn">Submit</button>
    </form>
    <h3 style="color:red" id="result"></h3>
</body>
<script src="jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $('#signup-form').on('submit',function(e){
            
            e.preventDefault();

            $.ajax({
                url:'signup.php',
                type:'POST',
                data:{
                    username:$('#username').val(),
                    email:$('#email').val(),
                    password:$('#password').val(),
                    confirm_password:$('#confirm_password').val(),
                },
                success:function (data){
                    // if error exists
                    console.log(data);
                    data = JSON.parse(data);
                    if(data['error']){
                        $('#result').html(data['msg']);
                        $('#error-heading').show();
                        $('#signup-form').hide();
                        $('#signup-heading').hide();
                    }else{
                        window.location.href='home.php'
                    }
                }
            })
        })
    })
</script>
</html>