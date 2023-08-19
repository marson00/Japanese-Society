<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $type_of_member = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
    $admin_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    
    if($_SESSION['logged_in'] != true){
        header('location: ../customerSide/login.php');
        eixt();
    }
    
    require_once '../helper/user-config.php';
          
    $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
    $adsql = "SELECT COUNT(*) AS adminrows FROM user_member WHERE user_type = 1";
    $adresult = mysqli_query($con,$adsql);
    $adfetchresult = mysqli_fetch_array($adresult);
    $admincount = $adfetchresult['adminrows'];
    
    $cssql = "SELECT COUNT(*) AS csrows FROM user_member WHERE user_type = 0";
    $csresult = mysqli_query($con,$cssql);
    $csfetchresult = mysqli_fetch_array($csresult);
    $cscount = $csfetchresult['csrows'];
    
    $evtsql = "SELECT COUNT(*) AS eventrows FROM event_table";
    $evtresult = mysqli_query($con,$evtsql);
    $evtfetchresult = mysqli_fetch_array($evtresult);
    $evtcount = $evtfetchresult['eventrows'];
    
    $ordsql = "SELECT COUNT(*) AS ordrows FROM orders";
    $ordresult = mysqli_query($con,$ordsql);
    $ordfetchresult = mysqli_fetch_array($ordresult);
    $ordcount = $ordfetchresult['ordrows'];
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
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
        </style>
    </head>
    <body style="height:1080px; overflow-y:hidden;">
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
                
                <li class="active">
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
                <li>
                    <a href="event-table.php">
                        <i class="fas fa-calendar"></i>
                        Event
                    </a>
                </li>
                <li>
                    <a href="ticket-table.php">
                        <i class="fas fa-ticket-alt"></i>
                        Order
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

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>Overview</h2>
                </div>
            </nav>
            <div style="height:20%;" class="mb-0">
                <div class="container-fluid pull-left border shadow" style="width:16%; height:90%; background-color:rgb(230, 230, 230); border-radius:10px;">
                    <i class="fas fa-users mt-2" style="color:rgb(140, 140, 140); height:100%; margin-left:30%; width:40%; height:50%;"></i>
                    <p class="text-dark mb-0 text-center mt-2" style="font-size:24px;"><b><?php echo $cscount ?></b> Users</p>
                </div>
                <div class="container-fluid pull-left border shadow" style="width:16%; height:90%; background-color:rgb(230, 230, 230); border-radius:10px; margin-left:12%;">
                    <i class="fas fa-user-shield mt-2" style="color:rgb(140, 140, 140); height:100%; margin-left:30%; width:40%; height:50%;"></i>
                    <p class="text-dark mb-0 text-center mt-2" style="font-size:24px;"><b><?php echo $admincount ?></b> Admins</p>
                </div>
                <div class="container-fluid pull-left border shadow" style="width:16%; height:90%; background-color:rgb(230, 230, 230); border-radius:10px; margin-left:12%;">
                    <i class="fas fa-calendar-check mt-2" style="color:rgb(140, 140, 140); height:100%; margin-left:30%; width:40%; height:50%;"></i>
                    <p class="text-dark mb-0 text-center mt-2" style="font-size:24px;"><b><?php echo $evtcount ?></b> Events</p>
                </div>
                <div class="container-fluid pull-left border shadow" style="width:16%; height:90%; background-color:rgb(230, 230, 230); border-radius:10px; margin-left:12%;">
                    <i class="fas fa-receipt mt-2" style="color:rgb(140, 140, 140); height:100%; margin-left:30%; width:40%; height:50%;"></i>
                    <p class="text-dark mb-0 text-center mt-2" style="font-size:24px;"><b><?php echo $ordcount ?></b> Orders</p>
                </div>
            </div>
            <div style="height:70%;">
                <div class="container-fluid border shadow pull-left" style="width:60%; height:90%; background-color:rgb(230, 230, 230); border-radius:10px;">
                    <h2 class="text-center mt-2 mb-3">Recent Event Sales</h2>
                    <p class="mb-5 text-center">Shows 5 most recent purchase</p>
                    <?php
                        require_once '../helper/user-config.php';
                        $db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

                        $rowperpage = 5;
                        $row = 0;
                    ?>
                    <h2 class=""></h2>
                    <table class="table table-bordered table-secondary">
                        <thead class="table-dark">
                            <th class="text-center">No</th>
                            <th class="text-center">Order Quantity</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center">Event Name</th>
                        </thead>
                        <?php
                            $sql = "SELECT COUNT(*) AS cntrows FROM order_details";
                            $tresult = mysqli_query($db,$sql);
                            $fetchresult = mysqli_fetch_array($tresult);
                            $allcount = $fetchresult['cntrows'];

                            $sql = "SELECT O.id, O.order_qty, O.total_amount, E.event_name FROM order_details O, event_table E WHERE O.event_id = E.event_id ORDER BY O.id desc limit $row,".$rowperpage;
                            $gresult = mysqli_query($db,$sql);

                            $no = $row + 1;
                            while($fetch = mysqli_fetch_array($gresult))
                            {       
                                $order_qty = $fetch['order_qty'];
                                $total_amount = $fetch['total_amount'];
                                $event_name = $fetch['event_name'];
                        ?>
                            <tr>
                                <td align='center'><?php echo $no; ?></td>
                                <td align='center'><?php echo $order_qty; ?></td>
                                <td align='center'>RM <?php echo $total_amount; ?></td>
                                <td align='center'><?php echo $event_name; ?></td>
                            </tr>
                        <?php
                                $no++;
                            }
                        ?>
                    </table>
                </div>

                <div class="container-fluid pull-left border shadow" style="width:35%; height:90%; background-color:rgb(230, 230, 230);  margin-left:5%; border-radius:10px;">
                    <h2 class="text-center mt-2">To Do List</h2>
                    <p class="text-center mb-2">This list only shows 5 at once</p>
                    <form method="post" action="adminPage.php" class="input_form text-center mt-3 mb-3">
                        <input type="text" name="task" class="task_input">
                        <button type="submit" name="submit" id="add_btn" class="add_btn" onClick="window.location.href=window.location.href">Add Task</button>
                    </form>
                    <?php if (isset($errors)) { ?>
                        <p><?php echo $errors; ?></p>
                    <?php } ?>
                    <?php
                        require_once '../helper/user-config.php';
                        $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

                        // insert a quote if submit button is clicked
                        if (isset($_POST['submit'])) 
                        {
                            if (empty($_POST['task'])) {
                                $errors = "You must fill in the task";
                            }
                            else
                            {
                                $task = $_POST['task'];
                                $sql = "INSERT INTO tasks (task) VALUES ('$task')";
                                mysqli_query($con, $sql);   
                            }
                        }
                        if (isset($_GET['del_task'])) 
                        {
                            $id = $_GET['del_task'];

                            mysqli_query($con, "DELETE FROM tasks WHERE task_id=".$id);
                        }
                    ?>

                    <table class="table table-dark table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:10%;" scope="col">No.</th>
                                <th style="width:73%;" scope="col">Tasks</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="table-responsive-sm" style="height:65%; display:block;">
                        <table class="table table-striped table-bordered mb-0">
                            <tbody>
                            <?php 
                                // select all tasks if page is visited or refreshed
                                $result = mysqli_query($con, "SELECT * FROM tasks");
                                $i = 1;
                                while ($fetch = mysqli_fetch_array($result)) 
                                { 
                                    $task_id = $fetch['task_id'];
                                    $task = $fetch['task'];
                                    if($i > 5)
                                    {
                                        break;
                                    }
                                    else
                                    {
                            ?>      
                                <tr>
                                    <td align="center" class="p-2" style="width:10%;" scope="row"> <?php echo $i; ?> </td>
                                    <td class="task p-2" style="width:73%; font-size:14px" align="center"> <?php echo $task; ?> </td>
                                    <td class="delete p-2" align="center"> 
                                        <a href="adminPage.php?del_task=<?php echo $task_id;?>"><button class="btn btn-danger py-1 px-2 m-0" onClick="window.location.href=window.location.href"><i class="fas fa-trash-alt "></i></button></a>
                                    </td>
                                </tr>
                            <?php 
                                    }
                                    $i++; 
                                } 
                            ?>	
                            </tbody>
                        </table>
                    </div>
                </div>
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
