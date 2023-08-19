<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php  
    session_start();
    require_once '../helper/user-config.php';

    
    if(!empty($_POST) || isset($_POST['email-submit']))
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $email = $mysqli->real_escape_string(trim($_POST['email']));
        $error['email'] = checkEmail($email);
        $error = array_filter($error);
        if(empty($error))
        { 
            $_SESSION['email_address'] = $email;
            
            
            require '../helper/Exception.php';
            require '../helper/PHPMailer.php';
            require '../helper/SMTP.php';
            $mail = new \PHPMailer\PHPMailer\PHPMailer;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 25;
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username = 'japanesesociety666@gmail.com';
            $mail->Password = '@dmin666';
            $mail->setFrom('japanesesociety666@gmail.com','Notification');
            $mail->addAddress($email);
            $mail->addReplyTo('japanesesociety666@gmail.com');
            $mail->isHTML(true);
            $mail->Subject = 'IMPORTANT: REQUEST OTP NUMBER ';
            $mail->Body =  '<h3>From Japanese  Society</h3>
                            <p>Hi Friend ! </p>
                            <p>Please click the link below to get an OTP number.</p>
                            <a href="http://localhost/PHPAssignment/customerSide/OTP.php">Click Here</a>
                            ';
            
            if($mail->send()){
                $error['correct'] = 'Please check your Email to get an <b>OTP</b>. ';
            }else{
                $error['warning'] = $mail->ErrorInfo;
                
            }
            

        } 
        $mysqli->close();
    }
?>



<html>
    <head>
        <meta charset="UTF-8">
        <title>Forgot Password</title>
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
            *
            {
                overflow-y:hidden;
                overflow-x:hidden;
            }
            body
            { 
                background-image:url('./pictures/back5.png');
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-size: cover;
                font: 14px sans-serif;
            }
            .wrapper 
            { 
                width: 45%;
                border-radius:10px;
                margin-top:15%;
            }
            .invalid-feedback
            {
                margin-left:15%; 
                font-size:15px;
            }
            h2
            {
                margin-top: 2%;
            }
            .alert{
                margin-left: 15%;
                width: 70%;
            }
        </style>
    </head>
    <body>
        <div class="wrapper mx-auto bg-white border">
            <h2 class="text-center pt-2">Email Validation</h2>
            <p style="font-size:18px" class="text-center mb-4">Please enter an email to get an OTP.</p>
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group">
                    <label style="margin-left:15%; font-size:16px;">Email</label>
                    <input style="width:70%;" type="text" name="email" class="form-control mx-auto <?php echo (!empty($error['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''  ?>" placeholder="Enter email address">
                    <span  class="invalid-feedback"><?php echo $error['email']; ?></span>
                </div>
                <div class="form-group mt-4 mb-3">
                    <input type="submit" class="btn btn-outline-primary pull-left" style="margin-left:15%;" value="Submit" name="email-submit">
                    <a class="btn btn-danger pull-right" style="margin-right:15%;" href="login.php" onclick="history.go(-1)">Cancel</a>
                </div>
            </form>
        </div>
    </body>
</html>
