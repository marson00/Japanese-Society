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
        $status = isset($_GET['status']) ? ($mysqli->real_escape_string(trim($_GET['status']))) : '';
               
        $status == 0 ? $status_type = 'Active'  : $status_type = 'Banned' ;
        $status_type == 'Active' ? $status_color = 'btn btn-success' : $status_color = 'btn btn-danger';
        if($type_of_member == 1)
        {
            $status_color == 'btn btn-success' ? $status_button = 'Ban Admin Account' : $status_button = 'Activate Account';
        }
        else
        {
            $status_color == 'btn btn-success' ? $status_button = 'Ban User Account' : $status_button = 'Activate Account';
        }
        $status_button == 'Activate Account' ? $status_button_color = 'btn btn-outline-success btn-block' : $status_button_color = 'btn btn-outline-danger btn-block';
                  
        $sql = "select * from user_member where id = '$id'";
        $result = $mysqli->query($sql);
        if($row = $result->fetch_object())
        {        
        $id = $row->id;
        $username = $row->username;
        $email = $row->email;
        $status = $row->status;      
        }
        else
        {
            if($type_of_member == 1)
            {
                $error['warning'] = 'Please access from admin table! <a href="admin-table.php">Click Here</a>';
            }
            else
            {
                $error['warning'] = 'Please access from admin table! <a href="customer-table.php">Click Here</a>';
            }
            $hideForm = true;
        }
        $result->free();
               
    }
    else
    {                
        if(!empty($_POST['admin-submit']) && isset($_POST['admin-submit']))
        {    
            $id = isset($_GET['id']) ? $mysqli->real_escape_string(trim($_GET['id'])) : '';
            $status = isset($_GET['status']) ? ($mysqli->real_escape_string(trim($_GET['status']))) : '';
            $username = isset($_POST['username']) ? $mysqli->real_escape_string(trim($_POST['username'])) : '';
            $email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';
                
            $status == 0 ? $status_type = 'Active'  : $status_type = 'Banned' ;
            $status_type == 'Active' ? $status_color = 'btn btn-success' : $status_color = 'btn btn-danger';
            if($type_of_member == 1)
            {
                $status_color == 'btn btn-success' ? $status_button = 'Ban Admin Account' : $status_button = 'Activate Account';
            }
            else
            {
                $status_color == 'btn btn-success' ? $status_button = 'Ban User Account' : $status_button = 'Activate Account';
            }
            $status_button == 'Activate Account' ? $status_button_color = 'btn btn-outline-success btn-block' : $status_button_color = 'btn btn-outline-danger btn-block';
                
            $error['username'] = validateUsername($username);
            $error['email'] = validateEmail($email);
                
            $error = array_filter($error);
                if(empty($error))
                {
                    $sql = "update user_member set username = ?, email = ? where id = ? ";
                    
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('ssi', $username,$email, $id);
                    
                    if($stmt->execute())
                    {
                        if($type_of_member == 1){
                            $error['correct'] = 'Successfully <b>Update</b> Admin account details! <a href="admin-table.php">Back to see changes.</a> '; 
                        }else{
                            $error['correct'] = 'Successfully <b>Update</b> User account details! <a href="customer-table.php">Back to see changes.</a> '; 
                        }
                        
                        $hideForm = true;
                    }
                    else
                    {                      
                        $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                    }                    
                    $stmt->close();
                } 
        }
        else
        {
            $id = isset($_GET['id']) ? $mysqli->real_escape_string(trim($_GET['id'])) : '';
            $status = isset($_GET['status']) ? $mysqli->real_escape_string(trim($_GET['status'])) : '';
            $username = isset($_POST['username']) ? $mysqli->real_escape_string(trim($_POST['username'])) : '';
            $email = isset($_POST['email']) ? $mysqli->real_escape_string(trim($_POST['email'])) : '';
                
                
            $status == 0 ? $status_type = 'Active'  : $status_type = 'Banned' ;
            $status_type == 'Active' ? $status_color = 'btn btn-success' : $status_color = 'btn btn-danger';
            if($type_of_member == 1)
            {
                $status_color == 'btn btn-success' ? $status_button = 'Ban Admin Account' : $status_button = 'Activate Account';
            }
            else
            {
                $status_color == 'btn btn-success' ? $status_button = 'Ban User Account' : $status_button = 'Activate Account';
            }
            $status_button == 'Activate Account' ? $status_button_color = 'btn btn-outline-success btn-block' : $status_button_color = 'btn btn-outline-danger btn-block';
                
            if(!empty($_POST['status-submit']) && isset($_POST['status-submit']))
            {                   
                $sql = "update user_member set status = ? where id = ?";
                    
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ii',$param_status,$param_id);
                    
                $status == 0 ? ($param_status = 1) : ($param_status = 0);
                    
                $param_id = $id;
                    
                if($stmt->execute())
                {
                    if($type_of_member == 1)
                    {
                        if($param_status == 1)
                        {
                            $error['correct'] = 'Successfully <b>Banned</b> admin account ! <a href="admin-table.php">Back to see changes.</a>';
                        }
                        else
                        {
                            $error['correct'] = 'Successfully <b>Actived</b> admin account ! <a href="admin-table.php">Back to see changes.</a>';
                        }
                        $hideForm = true;
                    }
                    else
                    {
                        if($param_status == 1)
                        {
                            $error['correct'] = 'Successfully <b>Banned</b> user account ! <a href="customer-table.php">Back to see changes.</a>';
                        }
                        else
                        {
                            $error['correct'] = 'Successfully <b>Actived</b> user account ! <a href="customer-table.php">Back to see changes.</a>';
                        }
                        $hideForm = true;
                    }
                        
                }
                else
                {
                    $error['warning'] = 'Oops! Something went wrong, Please try again!';
                }                   
                $stmt->close();                   
            }               
        }
    }
    $mysqli->close();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Modify Admin Account Details</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
        <link href="sidenavi.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <style>
            body
            { 
                font: 14px sans-serif; 
                background-image: url('./images/back1.png');
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-size: cover;
            }           
            .wrapper
            { 
                width: 510px; 
                margin-top:10%; 
                border-radius:10px;
            }
            .btn-success {margin-left: 30%; margin-right:1%;}
            p > .btn-danger{margin-left: 28%; margin-right:1%;}
            
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
         

    <div class="wrapper border shadow p-4 mx-auto bg-white">
        <?php
            if($type_of_member == 1)
            {
                echo '<h2>Modify Admin Account Details</h2>';
                printf('<p class="mb-4">Click to change admin account details.
                        <input type="button" class="%s pull-right" value="%s"></p>',$status_color,$status_type);
            }
            else
            {
                echo '<h2>Modify User Account Details</h2>';
                printf('<p class="mb-4">Click to change user account details.
                        <input type="button" class="%s pull-right" value="%s"></p>',$status_color,$status_type);
            }
        ?>
        <?php
            if(!empty($error['warning']))
            {
                printf('<div class="alert alert-danger" role="alert">%s</div>',$error['warning']);
            }
            else if(!empty($error['correct']))
            {
                printf('<div class="alert alert-success" role="alert">%s</div>',$error['correct']);
            }
            $sample = 12345678;
        ?>
        <?php if(isset($hideForm) == false) : ?>
        <form action="" method="POST">

            
            <div class="form-row">   
                <div class="form-group col-md-3">
                    <label>ID</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $id ?>" disabled>
                </div>
                
                <div class="form-group col-md-9 ">
                    <label>Username</label>
                    <input type="text" name="username" id="Username" class="form-control <?php echo (!empty($error['username'])) ? 'is-invalid' : ''; ?>" value="<?php echo $username ?>" <?php echo  $status == 1 ? 'disabled' :'' ?>>
                    <span class="invalid-feedback"><?php echo $error['username']; ?></span>
                </div>      
            </div>
                
            <div class="form-group">
                <label>Email</label>
                    <input type="text" name="email" class="form-control <?php echo (!empty($error['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo $email ?>" <?php echo $status == 1 ? 'disabled' :'' ?> >
                    <span class="invalid-feedback"><?php echo $error['email']; ?></span>               
            </div>
           
            <div class="form-row">                    
                <div class="form-group col-md-6">
                    
                    <label>Password</label>
                    <input type="password" name="password" class="form-control " value="<?php echo $sample?>" disabled>
                </div>
                
                <div class="form-group col-md-6 ">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control " value="<?php echo $sample ?>" disabled>
                </div>               
            </div>
                   
            <input type="button" class="btn btn-outline-primary float-left mb-3" value="Back" onclick="location='<?php echo $type_of_member == 1 ? 'admin-table.php' : 'customer-table.php' ?>'" >
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter2" <?php echo $status == 1? 'disabled' : ''?> >OK</button> 
          
            <input type="button" class="btn btn-primary  btn-block" value="Change Password" onclick="location='change-password.php?id=<?php echo $id ?>&username=<?php echo $username ?>&email=<?php echo $email ?>'" <?php echo $status == 1 ? 'disabled' : '' ?>>
            <button type="button" class="<?php echo $status_button_color ?>" data-toggle="modal" data-target="#exampleModalCenter" ><?php echo $status_button ?></button>
            
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
                    <?php if($status == 0 && $type_of_member == 1){ ?>
                    Are you sure to <b>ban</b> this admin account ?
                    <?php }else if($status == 1 && $type_of_member == 1){ ?>
                    Are you sure to <b>activate</b> this admin account ?
                    <?php }else if($status == 0 && $type_of_member == 0){ ?>
                    Are you sure to <b>ban</b> this user account ?
                    <?php }else{ ?>
                    Are you sure to <b>activate</b> this user account ?
                    <?php } ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                  <input type="submit" class="btn btn-primary" value="Confirm" name="status-submit"/>
                </div>
              </div>
            </div>
          </div>
            
            
          <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Confirmation Message</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <?php if($type_of_member == 1){ ?>
                    Are you sure to <b> update </b> admin account details ?
                    <?php }else{ ?>
                    Are you sure to <b> update </b> user account details ?
                    <?php } ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                  <input type="submit" class="btn btn-primary"  value="OK" name="admin-submit" >  
                </div>
              </div>
            </div>
          </div>  
            
        </form>
        <?php endif; ?>
    </div>

    </body>
    <script>
        document.getElementById("Username").focus();
    </script>
</html>
