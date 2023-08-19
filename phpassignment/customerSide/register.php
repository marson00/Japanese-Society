<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $type_of_member = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
    require_once '../helper/user-config.php';       
        
    if(!empty($_POST) || isset($_POST['user-register']))
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            
        $username = $mysqli->real_escape_string(trim($_POST['username']));
        $email = $mysqli->real_escape_string(trim($_POST['email']));
        $password = $mysqli->real_escape_string(trim($_POST['password']));
        $confirm_password = $mysqli->real_escape_string(trim($_POST['confirm_password']));
            
        $error['username'] = validateUsername($username);
        $error['email'] = validateEmail($email);
        $error['password'] = validatePassword($password);
        $error['confirm_password'] = validateConfirmPassword($password, $confirm_password);
            
        $error = array_filter($error);
            
        if(empty($error))
        {               
            if($type_of_member == 1)
            {
                $sql = "insert into user_member (username,email,password,user_type) values (?,?,?,?)";
                $stmt = $mysqli->prepare($sql);
                $user_type = 1;
                $stmt->bind_param('sssi', $username,$email,$password,$user_type);     
            }
            else
            {
                $sql = "insert into user_member (username,email,password,user_type) values (?,?,?,?)";
                $stmt = $mysqli->prepare($sql);
                $user_type = 0;
                $stmt->bind_param('sssi', $username,$email,$password,$user_type);
            }                
            $password = password_hash($password, PASSWORD_DEFAULT);
                
            if($stmt->execute())
            {
                header('location: login.php');
                $_SESSION['logged_in'] = false;
            }
            else
            {
                echo "Oops! Something went wrong. Please try again !";
            }               
             $stmt->close();                
        }           
        $mysqli->close();   
    }               
?>



<html>
    <head>
        <meta charset="UTF-8">
        <title>Register Account</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <style>
            *{
                overflow-y:hidden;
                overflow-x:hidden;
            }
            body{ 
                background-image:url('./pictures/whiteFlower.jpeg');
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-size: cover;
                font: 14px sans-serif;
            }
        </style>
    </head>
    <body>
        <div style="width:100%; height:100%;">
            <div style="width:60%; margin-left:20%; height:100%; margin-top:5%;" class="bg-white">
                <div class="pull-right bg-white" style="width:50%; height:100%;">
                    <img class="img-fluid" src="pictures/bruu.png" style="width:900px; height:600px;">
                </div>
                <div class="bg-white pull-left" style="width:50%; height:100%;">
                    <?php
                        $type_of_member = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
                        if($type_of_member == 1)
                        {
                            echo '<h2 class="text-center mt-4">Add New Admin member</h2>';
                            echo '<p class="text-center" style="font-size:16px" class="mt-3 mb-3">Please fill out this form to create an admin account.</p>';
                        }
                        else
                        {
                            echo '<h2 class="text-center mt-4">Sign Up</h2>';
                            echo '<p class="text-center" style="font-size:16px" class="mt-3 mb-3">Please fill out this form to create an account.</p>';
                        }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                        <div class="form-group">
                            <label style="margin-left:15%;">Username</label>
                            <input style="width:70%;" type="text" name="username" id="Username"class="form-control mx-auto <?php echo (!empty($error['username'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
                            <span style="margin-left:15%;" class="invalid-feedback"><?php echo $error['username']; ?></span>
                        </div>    

                        <div class="form-group">
                            <label style="margin-left:15%;">Email</label>
                            <input style="width:70%;" type="text" name="email" id="Email" class="form-control mx-auto <?php echo (!empty($error['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''  ?>">
                            <span style="margin-left:15%;" class="invalid-feedback"><?php echo $error['email']; ?></span>
                        </div>

                        <div class="form-group">
                            <label style="margin-left:15%;">Password</label>
                            <input style="width:70%;" type="password" name="password" id="Password" class="form-control mx-auto <?php echo (!empty($error['password'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''  ?>">
                            <span style="margin-left:15%;" class="invalid-feedback"><?php echo $error['password']; ?></span>
                        </div>

                        <div class="form-group">
                            <label style="margin-left:15%;">Confirm Password</label>
                            <input style="width:70%;" type="password" name="confirm_password" id="Confirm" class="form-control mx-auto <?php echo (!empty($error['confirm_password'])) ? 'is-invalid' : ''; ?>">
                            <span style="margin-left:15%;" class="invalid-feedback"><?php echo $error['confirm_password']; ?></span>
                        </div>

                        <div class="form-group">
                            <input style="margin-left:15%;" type="submit" class="btn btn-primary pull-left" value="Submit" name="user-register">
                            <input style="margin-right:15%;" type="reset" class="btn btn-secondary ml-2 pull-right" value="Reset">
                        </div>
                        <div>
                            <a href="#" onclick="location='login.php'" style="font-size:14px; margin-left:15%;" class="pull-left">Back to page</a>
                            <div class="pull-right " style="margin-right:15%;">
                                Already have an account?<br>
                                <div class="text-right">
                                    <a href="login.php">Login here</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        
        document.getElementById("Username").focus();
        <?php if(!empty($error['username'])){ ?>
            document.getElementById("Username").focus();
        <?php }else if(!empty($error['email'])){ ?>
            document.getElementById("Email").focus();
        <?php }else if(!empty($error['password'])){ ?>
            document.getElementById("Password").focus();
        <?php }else if(!empty($error['confirm_password'])){ ?>
            document.getElementById("Confirm").focus();
        <?php } ?>
    </script>
</html>

