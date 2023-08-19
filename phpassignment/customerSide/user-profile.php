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
    
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $sql = "select * from user_member where id = '$id'";
        $result = $mysqli->query($sql);
        if($row = $result->fetch_object())
        {        
            $id = $row->id;
            $username = $row->username;
            $email = $row->email;
        }
        $result->free();
        
    }
    $mysqli->close();
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>User Profile</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body{ font: 14px sans-serif; }
            .wrapper{ width: 700px; padding: 20px; margin: 50px auto;}
            .btn-outline-primary{
                width: 92%;
            }
        </style>
    </head>
    <body>
        
        
      <div class="wrapper border shadow p-4 mx-auto bg-white">
        <h2>Profile</h2>
        <div class="wrapper">
        
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username?>" disabled>
                    </div>
                </div>
                   
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email?>" disabled>
                    </div>
                </div>  
                    
                <div class="form-row">    
                    <div class="form-group col-md-11">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" value="12345678" disabled>
                    </div>    
                </div>
                
                <button type="button" class="btn btn-outline-primary btn-lg btn-block" onclick="location= 'modify-profile.php'" name="modify">Modify Profile</button>
                
            </form>
            
        </div>
      </div>
    </body>
</html>
