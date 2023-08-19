<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    require_once '../helper/user-config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $admin_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        
        $event_id = isset($_GET['event_id']) ? ($mysqli->real_escape_string(trim($_GET['event_id']))) : '';
        
        $sql = "select * from event_table where event_id = '$event_id'";
        $result = $mysqli->query($sql);
        if($row = $result->fetch_object())
        {        
            $event_id = $row->event_id;
            $event_name = $row->event_name;
            $event_start_date = $row->start_date;
            $event_end_date = $row->end_date;
            $event_start_time = $row->start_time;
            $event_end_time = $row->end_time;
            $event_location = $row->location;
            $event_total_quantity = $row->total_quantity;
            $event_price = $row->price;
            $event_status = $row->status;
            $event_img = $row->img_url;
                              
            $_SESSION['event_status'] = $event_status;
        }
        $result->free();
        
    }else{
        $event_status = isset($_POST['event_status']) ? ($mysqli->real_escape_string(trim($_POST['event_status']))) : '';
        $_SESSION['event_id'] = isset($_POST['event_id']) ? ($mysqli->real_escape_string(trim($_POST['event_id']))) : '';
        
        if(!empty($_POST['event_name']) || isset($_POST['event_name'])){
            $_SESSION['event_field'] = 'event_name';
            
        }else if(!empty($_POST['event_date']) || isset($_POST['event_date'])){
            $_SESSION['event_field'] = 'event_date';
            
        }else if(!empty($_POST['event_time']) || isset($_POST['event_time'])){
            $_SESSION['event_field'] = 'event_time';
            
        }else if(!empty($_POST['event_location']) || isset($_POST['event_location'])){
            $_SESSION['event_field'] = 'event_location';
            
        }else if(!empty($_POST['event_qty_price']) || isset($_POST['event_qty_price'])){
            $_SESSION['event_field'] = 'event_qty_price';
            
        }else if(!empty($_POST['event_img']) || isset($_POST['event_img'])){
            $_SESSION['event_field'] = 'event_img';
           
        }else if(!empty($_POST['event_status']) || isset($_POST['event_status'])){
            $_SESSION['event_field'] = 'event_status';
            
        }
        
        header('location: modify-event-details.php');
        exit();
    }

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Choose Event Details</title>
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
        <link href="sidenav.css" rel="stylesheet" type="text/css"/>
        <style>
            .border
            {
                width: 100%;
                background-color: white;
                border: gray 1px solid;
                border-radius: 5px;
            }
            .line
            {
                width: 90%;
                height: 1px;
                background-color: gray;
                opacity: 0.7;
                border-radius: 2px;
                margin-left: 5%;
                margin-top: 2%;
                margin-bottom: 2%;
            }
            
        </style>
    </head>
    <body>
        <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3 class="text-center">Japanese Society</h3>
                <strong>JS</strong>
            </div>
            
            <ul class="list-unstyled mt-3">
                <li class="bg-dark" style="pointer-events:none; color:white;">
                    <a>
                        <i class="fas fa-user"></i>
                        <?php echo $admin_username?>
                    </a>
                </li>
            </ul>   

            <ul class="list-unstyled components">
                <li>
                    <a href="adminPage.php">
                        <i class="far fa-clipboard"></i>
                        Overview
                    </a>
                </li>
                <li>
                    <a href="admin-table.php">
                        <i class="fas fa-copy"></i>
                        Admin
                    </a>
                </li>
                <li>
                    <a href="customer-table.php">
                        <i class="fas fa-image"></i>
                        User Account
                    </a>
                </li>
                <li class="active">
                    <a href="event-table.php">
                        <i class="fas fa-calendar"></i>
                        Event
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-ticket-alt"></i>
                        Tickets
                    </a>
                </li>
                <li class="mt-5">
                    <a href="../customerSide/logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Log out
                    </a>
                </li>
            </ul>
        </nav>
        
        <!--Page Content-->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>Admin Page</h2>
                </div>
            </nav>
         
         
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width:100%;">
                <p style="margin-left:10%">Click the field to modify.</p>
                <div class="border mx-auto" style="width:80%;">
                    <div class="form-inline mb-2 mt-3" style="margin-left:5%; width:90%;">
                        <label class="mr-3">Event Name</label>
                        <label class="pt-0" style="color:grey; margin-left: 5%;"><?php echo $event_name ?></label>
                        <input type="hidden" name="event_id" value="<?php echo $event_id ?>"/>
                        <button type="submit" name="event_name" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>

                    <div class="line"></div>  

                    <div class="form-inline mb-2 mt-2" style="margin-left:5%; width:90%;">
                        <label class="mr-3">Start Date</label>
                        <label class="pt-0 mr-5" style="color:grey; margin-left: 7%;"><?php echo $event_start_date ?></label>
                        <label class="mr-3" style=" margin-left: 15%;">End Date</label>
                        <label class="pt-0" style="color:grey; margin-left: 5%;"><?php echo $event_end_date ?></label>
                        <button type="submit" name="event_date" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>

                    <div class="line"></div>  

                    <div class="form-inline mb-2 mt-2" style="margin-left:5%; width:90%;">
                        <label class="mr-3">Start Time</label>
                        <label class="pt-0 mr-5" style="color:grey; margin-left: 7%;"><?php echo $event_start_time ?></label>
                        <label class="mr-3" style=" margin-left: 20.7%;">End Time</label>
                        <label class="pt-0" style="color:grey; margin-left: 5%;"><?php echo $event_end_time ?></label> 
                        <button type="submit" name="event_time" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>

                    <div class="line"></div>

                    <div class="form-inline mb-2 mt-2" style="margin-left:5%; width:90%;">
                        <label class="mr-3">Location </label>
                        <label class="pt-0" style="color:grey; margin-left: 8.7%;"><?php echo $event_location ?></label>
                        <button type="submit" name="event_location" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>

                    <div class="line"></div>

                    <div class="form-inline mb-2 mt-2" style="margin-left: 5%; width:90%;">
                        <label class="mr-3">Total Quantity</label>
                        <label class="pt-0 mr-5" style="color:grey; margin-left: 4%;"><?php echo $event_total_quantity ?></label>
                        <label class="mr-3" style=" margin-left: 22%;">Price</label>
                        <label class="pt-0" style="color:grey; margin-left: 8%; "><?php echo 'RM ' . $event_price . '.00'?></label>
                        <button type="submit" name="event_qty_price" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>

                    <div class="line"></div>

                    <div class="form-inline mb-2 mt-2" style="margin-left:5%; width:90%;">
                        <label class="mr-3">Image Pathname </label>
                        <label class="pt-0" style="color:grey; margin-left: 1%;"><?php echo $event_img ?></label>
                        <button type="submit" name="event_img" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>

                    <div class="line"></div>

                    <div class="form-inline mb-3 mt-2" style="margin-left:5%; width:90%;">
                        <label class="mr-3">Status</label>
                        <label class="pt-0" style="color:grey; margin-left: 11%;"><?php echo eventStatus()[$event_status] ?></label>
                        <button type="submit" name="event_status" class="btn ml-auto" style="color: gray;">&#62;</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    <script type="text/javascript">
        $(document).ready(function () 
        {
            $('#sidebarCollapse').on('click', function () 
            {
                $('#sidebar').toggleClass('active');
            });
        });   
     </script>
    </body>
</html>
