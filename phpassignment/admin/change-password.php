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
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $id = isset($_GET['id']) ? ($mysqli->real_escape_string(trim($_GET['id']))) : '';
               
        $username = isset($_GET['username']) ? $mysqli->real_escape_string(trim($_GET['username'])) : '';
        $email = isset($_GET['email']) ? $mysqli->real_escape_string(trim($_GET['email'])) : '';
                
        $sql = "select * from user_member where id = '$id'";
        $result = $mysqli->query($sql);
        if($row = $result->fetch_object())
        {
            $id = $row->id;
            $username = $row->username;
            $email = $row->email;
            $password = $row->password;
        }
        else
        {
            if($type_of_member == 1)
            {
                $error['warning'] = 'Please access from admin table! <a href="admin-table.php">Click Here</a>';
            }
            else
            {
            $error['warning'] = 'Please access from user table! <a href="customer-table.php">Click Here</a>';
            }
            $hideForm = true;
        }
        $result->free();
    }
    else
    {
        $id = isset($_GET['id']) ? $mysqli->real_escape_string(trim($_GET['id'])) : '';
        $username = isset($_GET['username']) ? $mysqli->real_escape_string(trim($_GET['username'])) : '';
        $email = isset($_GET['email']) ? $mysqli->real_escape_string(trim($_GET['email'])) : '';
                
        $old_password = isset($_POST['old_password']) ? $mysqli->real_escape_string(trim($_POST['old_password'])) : '';
        $new_password = isset($_POST['new_password']) ? $mysqli->real_escape_string(trim($_POST['new_password'])) : '';
        $confirm_password = isset($_POST['confirm_password']) ? $mysqli->real_escape_string(trim($_POST['confirm_password'])) : '';
        
        $error['old_password'] = checkPassword($old_password);
        $error['new_password'] = validatePassword($new_password);
        $error['confirm_password'] = validateConfirmPassword($new_password,$confirm_password);
        
        $error = array_filter($error);
        
        if(empty($error))
        {
            $sql = "select id, password from user_member where id = ?";
            $check_password = false;
                    
            if($stmt = $mysqli->prepare($sql))
            {
                //Bind variables to the prepare statement
                $stmt->bind_param('i', $param_id);
                //Set parameters
                $param_id = $id;
                if($stmt->execute())
                {
                    $stmt->store_result();
                    //if id existed, then only check password
                    if($stmt->num_rows == 1)
                    {
                        $stmt->bind_result($id,$hash);
                        if($stmt->fetch())
                        {
                            if(password_verify($old_password, $hash))
                            {
                            $check_password = true;
                            }
                            else
                            {
                                $error['warning'] = 'Incorrect old password.';
                            }
                            
                        }
                    }
                    $stmt->close();
                }
                if($check_password)
                {
                    $sql = "update user_member set password = ? where id = ?";
                    if($stmt = $mysqli->prepare($sql))
                    {
                        $stmt->bind_param('si', $param_password,$param_id);
                        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                        if($stmt->execute())
                        {
                            $_SESSION['logged_in'] = false;
                            $error['correct'] = 'Successfully <b>reset</b> password ! <a href="../customerSide/login.php">Back to test password.</a>';
                        }
                        else
                        {
                            $error['warning'] = 'Oops! Something went wrong, Please try again!';
                        }
                        $stmt->close();
                        $hideForm = true;
                    }
                }
            }
        }
        $mysqli->close();
    }
?>




<html>
    <head>
        <meta charset="UTF-8">
        <title>Change Password</title>
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
            body
            { 
                font-size: 14px; 
                background-image: url('./images/back4.png');
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-size: cover;
            }
            .wrapper
            { 
                width:35%;
                padding: 2%; 
                padding-bottom: 3%;
                margin-top:7%;
                border-radius:5px
            }
        </style>
        <script>
            $(document).ready(function(){
                $('#myModal').on('shown.bs.modal', function () {
                    $('#myInput').trigger('focus')
                })
            });
        </script>
    </head>
    <body>
        <div class="wrapper border bg-white mx-auto shadow">
            <h2>Change Account Password</h2>
            
            <?php
                if(!empty($error['warning']))
                {
                    printf('<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Error!</strong> %s
                            </div>',$error['warning']);
                }
                else if(!empty($error['correct']))
                {
                    printf('<div class="alert alert-success mt-3" role="alert">%s</div>',$error['correct']);
                }
            ?>
            <?php if(isset($hideForm) == false) : ?>
            <p class="pt-3 pb-2">Only correct old password will allow you to change the password.</p>
            <form action="" method="POST">
                <div class="form-row">   
                    <div class="form-group col-md-3">
                        <label>ID</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $id ?>" disabled>
                    </div>
                    <div class="form-group col-md-9 ">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($error['username'])) ? 'is-invalid' : ''; ?>" value="<?php echo $username ?>" disabled>
                        <span class="invalid-feedback"><?php echo $error['username']; ?></span>
                    </div>    
                </div> 
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control <?php echo (!empty($error['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo $email ?>" disabled>
                    <span class="invalid-feedback"><?php echo $error['email']; ?></span>
                </div>
            
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="text" name="old_password" id="Old_password" class="form-control <?php echo (!empty($error['old_password'])) ? 'is-invalid' : ''; ?>" value="" placeholder="Enter here">
                    <span class="invalid-feedback"><?php echo $error['old_password']; ?></span>
                </div>
                <div class="form-row">     
                    <div class="form-group col-md-6">
                        <label>New Password</label>
                        <input type="text" name="new_password" class="form-control <?php echo (!empty($error['new_password'])) ? 'is-invalid' : ''; ?>" value="" placeholder="Enter here">
                        <span class="invalid-feedback"><?php echo $error['new_password']; ?></span>
                    </div>
                    <div class="form-group col-md-6 ">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($error['confirm_password'])) ? 'is-invalid' : ''; ?>" value="" placeholder="Enter here">
                        <span class="invalid-feedback"><?php echo $error['confirm_password']; ?></span>
                    </div>
                </div>
                <input type="button" class="btn btn-outline-primary float-left" value="Back" onclick="location='<?php echo $type_of_member == 1 ? 'admin-table.php' : 'customer-table.php' ?>'" >
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter" >OK</button>
                
                
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Confirmation Message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure to <b>reset</b> your password ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                <input type="submit" class="btn btn-primary float-right" value="Yes" name="password-submit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </body>
    <script>
        document.getElementById("Old_password").focus();
    </script>
</html>
