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
    
    if(isset($_POST['delete_submit'])){
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $check = isset($_POST['delete']) ? $_POST['delete'] : '';
        
        if(!empty($check)){
         
            foreach($check as $value){
                $delete_array[] = isset($value) ? $mysqli->real_escape_string(trim($value)) : '';
            }
                $sql = "update orders set status = 1 where id in ('" . implode("','",$delete_array) ."' ) ";
                if($mysqli->query($sql)){
                    echo '<script>alert("Succesfully delete order(s).")</script>';
                }
        }else{
            echo '<script>alert("Please select at least one order to delete.")</script>';
        }
        $mysqli->close();
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
            input[type=checkbox]
	    {
  		-ms-transform: scale(1.5); /* IE */
  		-moz-transform: scale(1.5); /* FF */
  		-webkit-transform: scale(1.5); /* Safari and Chrome */
  		-o-transform: scale(1.5); /* Opera */
  		transform: scale(1.5);
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
                <li class="active">
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
                    <h2>Order Table</h2>
                </div>
            </nav>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table class="table table-bordered" >
                <thead class="table-info">
                    <th class="text-center">No</th>
                    <th class="text-center"><a href="<?php echo sortorder('id'); ?>" class="sort">Id</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('username'); ?>" class="sort">Username</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('order_date'); ?>" class="sort">Purchase Date</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('event_name'); ?>" class="sort">Event Name</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('order_qty'); ?>" class="sort">Order QTY</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('total_amount'); ?>" class="sort">Total Amount</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('total_price'); ?>" class="sort">Total Price</a></th>
                    <th class="text-center"><a href="<?php echo sortorder('status'); ?>" class="sort">Status</a></th>
                    <th class="text-center"><a href="#" class="sort">Delete</a></th>
                </thead>
                <?php
                    // count total number of rows
                    $sql = "SELECT COUNT(*) AS cntrows FROM order_details";
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
                    $sql = "SELECT U.username, O.id, O.order_date, O.total_price, O.status, D.order_qty, D.total_amount, E.event_name from user_member U, orders O, order_details D, event_table E WHERE O.customer_id = U.id AND D.orders_id = O.id AND D.event_id = E.event_id".$orderby." limit $row,".$rowperpage;
                    
                    $records = "SELECT COUNT(*) AS records FROM orders";
                    $result = mysqli_query($con, $records);
                    $fetchresult = mysqli_fetch_array($result);
                    $totalID = $fetchresult['records'];
                    
                    $result = mysqli_query($con,$sql);
                    $sno = $row + 1;
                    $previous = 0;
                    while($fetch = mysqli_fetch_array($result))
                    {       
                        $id = $fetch['id'];
                        $username = $fetch['username'];
                        $order_date = $fetch['order_date'];
                        $event_name = $fetch['event_name'];
                        $order_qty = $fetch['order_qty'];
                        $total_amount = $fetch['total_amount'];
                        $total_price = $fetch['total_price'];
                        $status = $fetch['status'];
                        
                ?>
                <tr>
                    
                    <?php
                        
                        if($previous != $id)
                        {   
                    ?>      
                        <td  align='center' class="p-2" style="border-width: 0; border-top-width: 1px;" ><?php echo $id; ?></td>
                        <td  align='center' class="p-2" style="border-width: 0; border-top-width: 1px;"><?php echo $id; ?></td>
                        <td  align='center' class="p-2" style="border-width: 0; border-top-width: 1px;"><?php echo $username; ?></td>
                        <td  align='center' class="p-2" style="border-width: 0; border-top-width: 1px;"><?php echo $order_date; ?></td>   
                            
                    <?php
                            $sno++;
                        }else{
                    ?>
                            <td class="empty_border" style="border: 0;">&nbsp</td> 
                            <td class="empty_border" style="border: 0;">&nbsp</td> 
                            <td class="empty_border" style="border: 0;">&nbsp</td> 
                            <td class="empty_border" style="border: 0">&nbsp</td> 
                            
                    <?php } ?>
                            <td  align='center' class="p-2" style="border-width: 0px; border-left-width: 1px; border-top-width: 1px; text-align:left;"><?php echo $event_name; ?></td>
                            <td  align='center' class="p-2" style="border-width: 0px; border-top-width: 1px;"><?php echo $order_qty; ?></td>
                            <td  align='center' class="p-2" style="border-width: 0px; border-right-width: 1px; border-top-width: 1px;"><?php echo $total_amount; ?></td>
                    <?php
                        if($previous != $id)
                        {   
                    ?>
                            <td align='center' class="p-2" style="border-width: 0; border-top-width: 1px;"><?php echo $total_price; ?></td>
                            <td align='center' class="p-2" style="border-width: 0; border-top-width: 1px; font-weight: <?php echo (($status)==1) ? "bold" : "" ?>">  <?php echo orderStatus()[$status]; ?> </td>
                            <td align='center' style="border-bottom-width: 0px;"><input type="checkbox" style="width:30px;" name="delete[]" value="<?php echo $id ?>" <?php echo ($status == 1) ? "disabled" : ''  ?> /></td>
                    <?php
                          }else{ 
                    ?>
                            <td class="empty_border" style="border: 0;">&nbsp</td> 
                            <td class="empty_border" style="border: 0;">&nbsp</td> 
                            <td class="empty_border" style="border-bottom-width: 0px; border-top-width: 0px;">&nbsp</td> 
                    <?php } ?>
                </tr>
                <?php
                    $previous = $id;
                    }                    
                ?>
                <tr>
                    <td colspan="9" align='center' class="align-middle">Showing <?php echo isset($id) ? $id : ''; ?> out of <?php echo $totalID ?> records</td>
                    <td align="center"><button type="button" class="btn btn-danger p-1 m-0" data-toggle="modal" data-target="#deleteModal">Cancel Order</button></td>
                </tr>
            </table>
            
                <input type="hidden" name="row" value="<?php echo $row; ?>">
                <input type="hidden" name="count" value="<?php echo $allcount; ?>">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary mr-3" style="font-size:13px;" name="prev" value="<<">
                    <input type="submit" class="btn btn-primary ml-3" style="font-size:13px;" name="next" value=">>">
                    
                    
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLongTitle">Confirmation Message</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">Are you sure to <b>delete</b> selected order(s) ?</p>
                                <p style="font-size: 11px;" class="text-center"><i>*You cannot undo this action</i></p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">No</button>
                              <input type="submit" class="btn btn-primary"  value="Yes" name="delete_submit" >  
                            </div>
                          </div>
                        </div>
                      </div>  
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
