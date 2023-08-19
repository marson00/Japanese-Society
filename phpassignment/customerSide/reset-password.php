<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    
    session_start();
    require_once '../helper/user-config.php';
    
    if(!empty($_POST) || isset($_POST['password-submit']))
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $email = isset($_SESSION['email_address']) ? ($mysqli->real_escape_string(trim($_SESSION['email_address']))) : '';

        $new_password = $mysqli->real_escape_string(trim($_POST['new_password']));
        $confirm_password = $mysqli->real_escape_string(trim($_POST['confirm_password']));

        $error['new_password'] = validatePassword($new_password);
        $error['confirm_password'] = validateConfirmPassword($new_password,$confirm_password);

        $error = array_filter($error);

        if(empty($error))
        {
            $sql = "update user_member set password = ? where email = ?";

            if($stmt = $mysqli->prepare($sql))
            {
                $stmt->bind_param('ss', $param_password,$param_email);

                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_email = $email;

                if($stmt->execute())
                {
                    $error['correct'] = 'Successfully change password. <a href="login.php">Back to login.</a>';
                    $hideForm = true;
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again !';
                }
                $stmt->close();
            }
        }
        $mysqli->close();
    }
    else
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

        $email = isset($_SESSION['email_address']) ? ($mysqli->real_escape_string(trim($_SESSION['email_address']))) : '';
        $mysqli->close();
    }
?>



<html>
    <head>
        <meta charset="UTF-8">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
        body
        { 
            background-image:url('./pictures/back9.jpg');
            background-repeat:no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font: 14px sans-serif;
        }
        .wrapper
        { 
            width: 50%; 
            border-radius:10px;
            margin-top:10%;
        }
        .invalid-feedback{
            font-size:15px;
        }
        label{
            font-size:16px;
        }
        input{
            width:70%;
        }
        
        </style>
    </head>
    <body>
        <div class="wrapper mx-auto p-3 bg-white border">
            <h2>Reset Password</h2>
            <p style="font-size:18px" >Please fill out this form to reset your password.</p>
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($error['new_password'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['new_password']) ? $_POST['new_password'] : ''?>" placeholder="Enter password">
                    <span class="invalid-feedback"><?php echo $error['new_password']; ?></span>
                </div>
            
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($error['confirm_password'])) ? 'is-invalid' : ''; ?>" value="" placeholder="Enter password">
                    <span class="invalid-feedback"><?php echo $error['confirm_password']; ?></span>
                </div>
            
                <div class="form-group pt-3 w-100">
                    <a class="btn btn-outline-danger" href="login.php">Cancel</a>
                    <input type="submit" class="btn btn-primary float-right" style="width:10%;" value="Submit" name="password-submit">
                </div> 
            </form>
             <?php endif; ?>
        </div>     
    </body>
</html>