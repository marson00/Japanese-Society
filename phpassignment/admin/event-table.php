<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $_SESSION['user_type'] = 1;
    $type_of_member = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
    $admin_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    
    if($_SESSION['logged_in'] != true){
        header('location: ../customerSide/login.php');
        eixt();
    }
    
    require_once '../helper/user-config.php';
    
    $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
    if (!$con) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $rowperpage = 5;
    $row = 0;

    // Previous Button
    if(isset($_POST['prev']))
    {
        $row = $_POST['row'];
        $row -= $rowperpage;
        if( $row < 0 )
        {
            $row = 0;
        }
    }

    // Next Button
    if(isset($_POST['next']))
    {
        $row = $_POST['row'];
        $allcount = $_POST['count'];

        $val = $row + $rowperpage;
        if( $val < $allcount )
        {
            $row = $val;
        }
    }

    function sortorder($fieldname)
    {
        $sorturl = "?order_by=".$fieldname."&sort=";
        $sorttype = "desc";
        if(isset($_GET['order_by']) && $_GET['order_by'] == $fieldname)
        {
            if(isset($_GET['sort']) && $_GET['sort'] == "desc")
            {
                $sorttype = "asc";
            }
        }
        $sorturl .= $sorttype;
        return $sorturl;
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Account Table</title>
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
            .smallsize
            {
                width:11%;
            }
            th,td
            {
                font-size:14px;
                
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
                    <h2>Event Table</h2>
                </div>
            </nav>
        
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-info">
                    <th class="text-center">No</th>
                    <th class="text-center"><a href="<?php echo sortorder('event_id'); ?>" class="sort">Id</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('event_name'); ?>" class="sort">Event Name</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('created_time'); ?>" class="sort">Created Time</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('start_date'); ?>" class="sort">Start & End Date</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('start_time'); ?>" class="sort">Start & End Time</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('location'); ?>" class="sort">Location</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('total_quantity'); ?>" class="sort">Total Quantity</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('price'); ?>" class="sort">Price</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('img_url'); ?>" class="sort">Image Name</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('status'); ?>" class="sort">Status</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('person_in_charge'); ?>" class="sort">Person-In-Charge</a></th>
                    <th class="text-center"><a href="#" class="sort">&nbsp</a></th>
                </thead>
                <?php
                    // count total number of rows
                    $sql = "SELECT COUNT(*) AS cntrows FROM event_table";
                    $result = mysqli_query($con,$sql);
                    $fetchresult = mysqli_fetch_array($result);
                    $allcount = $fetchresult['cntrows'];
                    
                    // selecting rows
                    $orderby = " ORDER BY event_id asc ";
                    if(isset($_GET['order_by']) && isset($_GET['sort']))
                    {
                        $orderby = ' order by '.$_GET['order_by'].' '.$_GET['sort'];
                    }
        
                    // fetch rows
                    $sql = "SELECT E.event_id, E.event_name, E.created_time, E.start_date, E.end_date, E.start_time, E.end_time, E.location, E.total_quantity, E.quantity_left, E.price, E.img_url, E.status, A.username 
                    from event_table E, user_member A ".
                    "where E.person_in_charge = A.id" .$orderby." limit $row,".$rowperpage  ;
                    $result = mysqli_query($con,$sql);
                    $sno = $row + 1;
                    while($fetch = mysqli_fetch_array($result))
                    {       
                        $event_id = $fetch['event_id'];
                        $event_name = $fetch['event_name'];
                        $event_createdTime = $fetch['created_time'];
                        $event_start_date = $fetch['start_date'];
                        $event_end_date = $fetch['end_date'];
                        $event_start_time = $fetch['start_time'];
                        $event_end_time = $fetch['end_time'];
                        $event_location = $fetch['location'];
                        $event_total_quantity = $fetch['total_quantity'];
                        $event_quantity_left = $fetch['quantity_left'];
                        $event_price = $fetch['price'];
                        $event_img = $fetch['img_url'];
                        $event_status = eventStatus()[$fetch['status']];
                        $person_in_charge = $fetch['username'];
                ?>
                <tr>
                    <td align='center' class="p-2"><?php echo $sno; ?></td>
                    <td align='center' class="p-2"><?php echo $event_id; ?></td>
                    <td align='center' class="p-2"><?php echo $event_name; ?></td>
                    <td align='center' class="p-2"><?php echo $event_createdTime; ?></td>
                    <td align='center' class="p-2"><?php echo $event_start_date; ?> to <?php echo $event_end_date; ?></td>
                    <td align='center' class="p-2"><?php echo $event_start_time; ?><br> to <br><?php echo $event_end_time; ?></td>
                    <td align='center' class="p-2"><?php echo $event_location; ?></td>
                    <td align='center' class="p-2"><?php echo $event_total_quantity; ?>/<?php echo $event_quantity_left; ?></td> 
                    <td align='center' class="p-2"><?php echo $event_price; ?></td>
                    <td align='center' class="p-2"><?php echo $event_img; ?></td>
                    <td align='center' class="p-2" style="font-weight: <?php echo (($event_status)=="Cancelled") ? "bold" : "" ?>"><?php echo $event_status; ?></td>
                    <td align='center' class="p-2"><?php echo $person_in_charge; ?></td>
                    <?php
                         printf('<td class="smallsize text-center p-2"><a href="modify-event.php?event_id=%d"><button type="button" style="font-size:13px;" class="btn btn-info p-1 m-0">Modify</button></a></td>', $event_id);
                    ?>
                </tr>
                <?php
                    $sno++;
                    }
                ?>
                <tr>
                    <td colspan="12" align='center' class="align-middle">Showing <?php echo $sno = $sno - 1; ?> out of <?php echo $allcount ?> records</td>
                    <td><a href="add-event.php" ><button type="button" class="btn btn-primary" style="font-size:13px;">Add new event</button></a></td>
                </tr>
            </table>
            <form method="post" action="">
                <input type="hidden" name="row" value="<?php echo $row; ?>">
                <input type="hidden" name="count" value="<?php echo $allcount; ?>">
                <div class="text-center">
                    <input type="submit" style="font-size:13px;" class="btn btn-primary mr-3" name="prev" value="<<">
                    <input type="submit" style="font-size:13px;"  class="btn btn-primary ml-3" name="next" value=">>">
                </div>
            </form>
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
