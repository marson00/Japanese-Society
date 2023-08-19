<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $type_of_member = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
         
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
    {
        header('location: homePage.php');
        exit;
    }
    require_once '../helper/user-config.php';
        
    if(isset($_POST['user-login']))
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            
        $username = $mysqli->real_escape_string(trim($_POST['username']));
        $password = $mysqli->real_escape_string(trim($_POST['password']));
            
        $error['username'] = checkUsername($username);
        $error['password'] = checkPassword($password);
            
        $error = array_filter($error);
            
        if(empty($error))
        {
            $sql = "select id, username, password, user_type, status from user_member where username = ? and status = ?";
                
            if($stmt = $mysqli->prepare($sql))
            {
                //Bind variables to the prepare statement
                $stmt->bind_param('si', $param_username,$param_status);
                    
                //Set parameters
                $param_username = $username;
                $param_status = 0;
                    
                if($stmt->execute())
                {
                    $stmt->store_result();
  
                    //if username existed, then only check password
                    if($stmt->num_rows == 1)
                    {
                        $stmt->bind_result($id,$username,$hash,$user_type,$status);
                            
                        if($stmt->fetch())
                        {
                            if(password_verify($password, $hash))
                            {
                                session_start();
                                    
                                $_SESSION['user_type'] = $user_type;
                                $_SESSION['logged_in'] = true;
                                $_SESSION['id'] = $id;
                                $_SESSION['username'] = $username;   
                                    
                                $type_of_member = $_SESSION['user_type'];
                                    
                                if($type_of_member == 1)
                                {
                                    //header('location: ../admin/admin-table.php');
                                    header('location: ../admin/adminPage.php');
                                    $_SESSION['user_type'] = 1;
                                }
                                else
                                {
                                    header('location: homePage.php');
                                    $_SESSION['user_type'] = 0;
                                }
                            }
                            else
                            {
                               $error['warning'] = 'Incorrect username or password.';
                            }
                        }
                    }
                    else
                    {
                        $error['warning'] = 'Incorrect username or password.';
                    }    
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            }
        }    
        $mysqli->close();
    }
?>





<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
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
            body
            { 
                background-image:url('./pictures/Backsomething-PixTeller.png');
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
                <div class="bg-white pull-left" style="width:50%; height:100%;">
                    <h1 class="ml-3 mt-3">Login Here</h1>
                    <p style="font-size:125%" class="mt-3 mb-4 ml-3">Please fill out this form to log in to our website.</p>
                    <?php
                        if (!empty($error['warning'])) 
                        {
                            printf('<div class="alert alert-danger alert-dismissible fade show w-75 ml-3">
                                        <strong>Error!</strong> %s
                                    </div>',$error['warning']);
                        }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="padding-bottom:18%;" class="ml-3">
                        <div class="form-group pb-2">
                            <label style="font-size:110%;">Username</label>
                            <input style="width:70%;" type="text" name="username" id="Username" class="form-control <?php echo (!empty($error['username'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
                            <span class="invalid-feedback"><?php echo $error['username']; ?></span>
                        </div>    

                        <div class="form-group pb-2 mb-4">
                            <label style="font-size:16px;">Password</label>
                            <input style="width:70%;" type="password" name="password" id="Password" class="form-control <?php echo (!empty($error['password'])) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $error['password']; ?></span>
                        </div>

                        <div class="form-group mt-4 pb-3">
                            <input style="width:100px; height:40px;" type="submit" class="btn btn-primary" value="Submit" name="user-login">
                            <input style="width:100px; height:40px;" type="reset" class="btn btn-secondary ml-2" value="Reset">
                        </div>

                        <p style="font-size:14px;">Don't have an account? <a href="register.php">Sign up now</a>.</p> 
                        <p>
                            <a href="forgot-password.php">Forgot Password</a>&nbsp&nbsp|&nbsp&nbsp<a href="#" onclick="location='homePage.php'">Back to page</a>
                        </p>
                    </form>  
                </div>
                <div class="pull-right" style="width:50%; height:100%;">
                    <img class="img-fluid" src="pictures/tree2.png" style="width:900px; height:600px;">
                </div>
            </div>
        </div>
    </body>
    <script>
        
        document.getElementById("Username").focus();
        <?php if(!empty($error['password'])){ ?>
            document.getElementById("Password").focus();
        <?php } ?>
    </script>
</html>