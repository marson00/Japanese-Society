<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    
    require_once '../helper/user-config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){   
        $sql = "select * from user_member where id = '$id'";
        $result = $mysqli->query($sql);
        if($row = $result->fetch_object())
        {        
            $username = $row->username;
            $email = $row->email;
        }
        $result->free();
        
        
    }else if(!empty($_POST['submit']) || isset($_POST['submit'])){
        
        if(empty($_POST['username']) && empty($_POST['email']) && empty($_POST['new_password']) && empty($_POST['confirm_password'])){
            $error['warning'] = "Please select at least one field to modify.";
            
        }else if(!empty($_POST['username']) && !empty($_POST['email']) && (!empty($_POST['new_password']) || !empty($_POST['confirm_password']))){
            
            $username = isset($_POST['username']) ? $mysqli->real_escape_string(trim($_POST['username'])) : '';
            $email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';
            $new_password = isset($_POST['new_password']) ? $mysqli->real_escape_string(trim($_POST['new_password'])) : '';
            $confirm_password = isset($_POST['confirm_password']) ? $mysqli->real_escape_string(trim($_POST['confirm_password'])) : '';
        
            $error['username'] = validateUsername($username);     
            $error['email'] = validateEmail($email);
            $error['new_password'] = validatePassword($new_password);
            $error['confirm_password'] = validateConfirmPassword($new_password,$confirm_password);
            $error = array_filter($error);
            
            if(empty($error['new_password']) && empty($error['confirm_password'])){
                $sql = "update user_member set username = ?, email = ?, password = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('sssi', $param_username, $param_email, $param_password, $param_id);
            
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);

                $param_id = $id;
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>all profile details</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            
            }
            
        }else if(!empty($_POST['username']) && !empty($_POST['email'])){
            
            $username = isset($_POST['username']) ? $mysqli->real_escape_string(trim($_POST['username'])) : '';
            $email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';  
 
            $error['username'] = validateUsername($username);        
            $error['email'] = validateEmail($email);
            $error = array_filter($error);
        
            if(empty($error['username']) && empty($error['email'])){
                $sql = "update user_member set username = ?, email = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssi', $param_username, $param_email, $param_id);
            
                $param_username = $username;
                $param_email = $email;
                $param_id = $id;
                
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>username</b> and <b>email</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            
            } 
            
        }else if(!empty($_POST['username']) && (!empty($_POST['new_password']) || !empty($_POST['confirm_password']))){
            
            $username = isset($_POST['username']) ? $mysqli->real_escape_string(trim($_POST['username'])) : '';
            $new_password = isset($_POST['new_password']) ? $mysqli->real_escape_string(trim($_POST['new_password'])) : '';
            $confirm_password = isset($_POST['confirm_password']) ? $mysqli->real_escape_string(trim($_POST['confirm_password'])) : '';
        
            $error['username'] = validateUsername($username);      
            $error['new_password'] = validatePassword($new_password);
            $error['confirm_password'] = validateConfirmPassword($new_password,$confirm_password);
            $error = array_filter($error);
        
            if(empty($error['username']) && empty($error['new_password']) && empty($error['confirm_password'])){
                $sql = "update user_member set username = ?, password = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssi', $param_username, $param_password, $param_id);
            
                $param_username = $username;
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $id;
                
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>username</b> and <b>password</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            
            } 
            
        }else if(!empty($_POST['email']) && (!empty($_POST['new_password']) || !empty($_POST['confirm_password']))){
            
            $email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';  
            $new_password = isset($_POST['new_password']) ? $mysqli->real_escape_string(trim($_POST['new_password'])) : '';
            $confirm_password = isset($_POST['confirm_password']) ? $mysqli->real_escape_string(trim($_POST['confirm_password'])) : '';
        
            $error['email'] = validateEmail($email);    
            $error['new_password'] = validatePassword($new_password);
            $error['confirm_password'] = validateConfirmPassword($new_password,$confirm_password);
            $error = array_filter($error);
        
            if(empty($error['email']) && empty($error['new_password']) && empty($error['confirm_password'])){
                $sql = "update user_member set email = ?, password = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssi', $param_email, $param_password, $param_id);
            
                $param_email = $email;
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $id;
                
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>email</b> and <b>password</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            
            } 
            
        }else if(!empty($_POST['username'])){
        
            $username = isset($_POST['username']) ? $mysqli->real_escape_string(trim($_POST['username'])) : '';
        
            $error['username'] = validateUsername($username);        
            $error = array_filter($error);
        
            if(empty($error['username'])){
                $sql = "update user_member set username = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $param_username, $param_id);
            
                $param_username = $username;
                $param_id = $id;
                
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>username</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            
            } 
        
        }else if(!empty($_POST['email'])){
 
            $email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';  
 
            $error['email'] = validateEmail($email);
            $error = array_filter($error);
        
            if(empty($error['email'])){
                $sql = "update user_member set email = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $param_email, $param_id);
            
                $param_email = $email;
                $param_id = $id;
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>email</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
            
            }

        }else if(!empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
        
            $new_password = isset($_POST['new_password']) ? $mysqli->real_escape_string(trim($_POST['new_password'])) : '';
            $confirm_password = isset($_POST['confirm_password']) ? $mysqli->real_escape_string(trim($_POST['confirm_password'])) : '';
        
            $error['new_password'] = validatePassword($new_password);
            $error['confirm_password'] = validateConfirmPassword($new_password,$confirm_password);
            $error = array_filter($error);
            if(empty($error['new_password']) && empty($error['confirm_password'])){
                $sql = "update user_member set password = ? where id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $param_password, $param_id);
            
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $id;
                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change <b>password</b>. <a href="homePage.php">Back to Home Page.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }
                $stmt->close();
                
            }
    
    
        }

    }else if(isset($_POST['delete'])){
            
        $sql = "update user_member set status = ? where id = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ii',$param_status, $param_id);
        
        $param_status = 1;
        $param_id = $id;
        if($stmt->execute())
            {
                $_SESSION['logged_in'] = false;
                header('location: homePage.php');
                
            }
            else
            {
                $error['warning'] = 'Oops! Something went wrong, Please try again!';
            }
            $stmt->close();
    }
    
    
    $mysqli->close();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Modify User Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body
            {
                font: 14px sans-serif; 
                background-image: url('./pictures/back6.png');
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-size: cover;
            }
            .wrapper{ width: 600px;}
            
        </style>
    </head>
    <body>
        
        <div class="wrapper border shadow p-4 mx-auto bg-white" style="border-radius:10px; margin-top:10%;">
            <h2>Modify your profile details</h2>
        <p>Click the field you want to change.</p>
        <?php
            if(!empty($error['warning']))
            {
                printf('<div class="alert alert-danger" role="alert">%s</div>',$error['warning']);
            }
            else if(!empty($error['correct']))
            {
                printf('<div class="alert alert-success" role="alert">%s</div>',$error['correct']);
            }
            
        ?>
        <?php if(isset($hideForm) == false) : ?>
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <label>Username</label>
                        <input type="text" name="username" id="Username" class="form-control <?php echo (!empty($error['username'])) ? 'is-invalid' : ''; ?>" value="" placeholder=" new username">
                        <span class="invalid-feedback"><?php echo $error['username']; ?></span>
                    </div>
                </div>
                   
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <label>Email</label>
                        <input type="text" name="email" id="Email" class="form-control <?php echo (!empty($error['email'])) ? 'is-invalid' : ''; ?>" value="" placeholder=" new email ">
                        <span class="invalid-feedback"><?php echo $error['email']; ?></span>
                    </div>
                </div>  
                    
                <div class="form-row">    
                    <div class="form-group col-md-11">
                        <label>New Password</label>
                        <input type="password" name="new_password" id="New_pass" class="form-control <?php echo (!empty($error['new_password'])) ? 'is-invalid' : ''; ?>" placeholder=" new password">
                        <span class="invalid-feedback"><?php echo $error['new_password']; ?></span>
                    </div>    
                </div>
                
                <div class="form-row">    
                    <div class="form-group col-md-11">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" id="Confirm" class="form-control <?php echo (!empty($error['confirm_password'])) ? 'is-invalid' : ''; ?>" >
                        <span class="invalid-feedback"><?php echo $error['confirm_password']; ?></span>
                    </div>    
                </div>
                
                    <div class="justify-content-between">
                        <button type="submit" class="btn btn-danger" style="margin-right:49%;" name="delete">Delete Account</button>
                        <button type="button" class="btn btn-outline-primary" name="back" onclick="location='homePage.php'" >Back</button>
                        <button type="submit" class="btn btn-outline-primary" name="submit">OK</button>
                    </div>
            </form>
            <?php endif; ?>
        </div>
      </div>
    
    </body>
    <script>
        document.getElementById("Username").focus();
        <?php if(!empty($error['username'])){ ?> 
            document.getElementById("Username").focus();
        <?php }else if(!empty($error['email'])){ ?>
            document.getElementById("Email").focus();
        <?php }else if(!empty($error['new_password'])){ ?>
            document.getElementById("New_pass").focus();
        <?php }else if(!empty($error['confirm_password'])){ ?>    
            document.getElementById("Confirm").focus();
        <?php } ?>
    </script>
</html>
