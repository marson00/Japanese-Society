<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $_SESSION['user_type'] = 0;
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
    
    $rowperpage = 10;
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
            <body>
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
                <li class="active">
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
                    <h2>User Table</h2>
                </div>
            </nav>
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-info">
                    <th class="text-center">No</th>
                    <th class="text-center"><a href="<?php echo sortorder('id'); ?>" class="sort">Id</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('username'); ?>" class="sort">Username</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('email'); ?>" class="sort">Email</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('acc_created_at'); ?>" class="sort">Created Time</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('status'); ?>" class="sort">Status</a></th>
                    <th class="text-center"><a href="#" class="sort">&nbsp</a></th>
                </thead>
                <?php
                    // count total number of rows
                    $sql = "SELECT COUNT(*) AS cntrows FROM user_member WHERE user_type = 0";
                    $result = mysqli_query($con,$sql);
                    $fetchresult = mysqli_fetch_array($result);
                    $allcount = $fetchresult['cntrows'];
                    
                    // selecting rows
                    $orderby = " ORDER BY id asc ";
                    if(isset($_GET['order_by']) && isset($_GET['sort']))
                    {
                        $orderby = ' order by '.$_GET['order_by'].' '.$_GET['sort'];
                    }
        
                    // fetch rows
                    $sql = "SELECT * FROM user_member WHERE user_type = 0 ".$orderby." limit $row,".$rowperpage;
                    $result = mysqli_query($con,$sql);
                    $sno = $row + 1;
                    while($fetch = mysqli_fetch_array($result))
                    {       
                        $id = $fetch['id'];
                        $username = $fetch['username'];
                        $email = $fetch['email'];
                        $createdTime = $fetch['acc_created_at'];
                        $status = $fetch['status'];
                ?>
                <tr>
                    <td align='center' class="p-2"><?php echo $sno; ?></td>
                    <td align='center' class="p-2"><?php echo $id; ?></td>
                    <td align='center' class="p-2"><?php echo $username; ?></td>
                    <td align='center' class="p-2"><?php echo $email; ?></td>
                    <td align='center' class="p-2"><?php echo $createdTime; ?></td>
                    <td align='center' class="p-2" style="font-weight: <?php echo (($status)==1) ? "bold" : "" ?>"><?php echo getStatus()[$status]; ?></td>
                    <?php
                        printf('<td class="smallsize text-center p-2"><a href="admin-modify.php?id=%d&status=%d"><button type="button" class="btn btn-info p-1 m-0" style="font-size:14px">Modify</button></a></td>', $id, $status)
                    ?>
                </tr>
                <?php
                    $sno++;
                    }
                ?>
                <tr>
                    <td colspan="6" align='center' class="align-middle">Showing <?php echo $sno = $sno - 1; ?> out of <?php echo $allcount ?> records</td>
                    <td class="p-2"><a href="../customerSide/register.php" ><button type="button" class="btn btn-primary p-1 m-0" style="font-size:13px;">Add new member</button></a></td>
                </tr>
            </table>
            <form method="post" action="">
                <input type="hidden" name="row" value="<?php echo $row; ?>">
                <input type="hidden" name="count" value="<?php echo $allcount; ?>">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary mr-3" style="font-size:13px;" name="prev" value="<<">
                    <input type="submit" class="btn btn-primary ml-3" style="font-size:13px;" name="next" value=">>">
                </div>
            </form>
        </div>     
        
        <script type="text/javascript">
        $(document).ready(function() 
        {
            $('#sidebarCollapse').on('click', function () 
            {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
    </body>
</html>
