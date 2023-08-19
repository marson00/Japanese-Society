<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    require_once '../helper/user-config.php'; 
    
    if(!empty($_POST['otp-submit']) || isset($_POST['otp-submit']))
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        
        $user_otp = $mysqli->real_escape_string(trim($_POST['user-otp']));
        
        $error['user-otp'] = validateOTP($user_otp);
        
        $error = array_filter($error);
        
        if(empty($error))
        {
            $matching = checkExistOTP($user_otp);
            if($matching)
            {
                header("location: reset-password.php"); 
            }
            else
            {
                $error['warning'] = 'Wrong OTP. Please try again !';
            }
        }
        $mysqli->close();
    }
    else
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        
        $email = isset($_SESSION['email_address']) ? ($mysqli->real_escape_string(trim($_SESSION['email_address']))) : ''; 
        
        $otp = rand(100000,999999);
        
        $sql = "update user_member set otp_code = ? where email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('is', $param_otp,$param_email);
        
        $param_otp = $otp;
        $param_email = $email;
        
        if(!$stmt->execute())
        {
            $error['warning'] = 'Oops! Something went wrong, Please try again !';
        }
        $_SESSION['otp'] = $otp;

        $stmt->close();
        $mysqli->close();
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>OTP FORM</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>

        body
        { 
            background-image:url('./pictures/back8.png');
            background-repeat:no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font: 14px sans-serif;
        }
        .wrapper
        { 
            width: 700px; 
            height: 400px;
            border-radius:10px;
            margin-top:13%;
        }
        .invalid-feedback{
            font-size:15px;
        }
        h2{
            margin-top: 5%;
        }

        </style>
    </head>
    <body>
        <div class="wrapper mx-auto pr-3 pl-3 pt-3 pb-2 border bg-white">
            <h2 class="text-center pb-2">OTP Form</h2>
            <p class="text-center border mb-4" style="font-size: 40px;"><?php echo $_SESSION['otp'] ?></p>
            <?php
                if(!empty($error['warning']))
                {
                printf('<div class="alert alert-danger">%s</div>',$error['warning']);
                }    
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 
                <div class="form-group">
                    <label style="font-size:16px;">OTP</label>
                    <input type="text" name="user-otp" class="form-control <?php echo (!empty($error['user-otp'])) ? 'is-invalid' : ''; ?>" placeholder="123456">
                    <span class="invalid-feedback"><?php echo $error['user-otp']; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit" name="otp-submit">
                    <a class="btn btn-link ml-2" href="login.php">Cancel</a>
                </div>

                <!--Set countdown timer-->
                <script>
                    function countDown(secs,elem) 
                    {
                        var element = document.getElementById(elem);
                        element.innerHTML =  "This session will end in "+secs+" seconds";
                        if(secs < 1) 
                        {
                            clearTimeout(timer);
                            element.innerHTML = '<h2>Ended</h2>';
                            element.innerHTML += '<a href=" ">Reset</a>';
                        }   
                        secs--;
                        var timer = setTimeout('countDown('+secs+',"'+elem+'")',1000);
                    }
                </script>

                <div class="text-center" id="status" style="font-size:16px;"></div>
                <script>countDown(60,"status");</script>     
            </form>
        </div>
    </body>
    <script type="text/javascript">
        setTimeout(function()
        {
            window.location.href = "login.php";
        }, 60000);  //5000 = 5 sec

        setTimeout(function()
        {
            alert('This session is expired. Please try again!');
        }, 60000);
    </script>
</html>
