<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    $cart_qty = isset($_SESSION['cart_qty']) ? $_SESSION['cart_qty'] : 0;
    
    require_once '../helper/user-config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); 
    
    if(isset($_GET['event_id'])){
        $event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';
        
        foreach ($_SESSION['cart'] as $key => $value) {
            if($value['event_id'] == $event_id){
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart_qty']--;
                header('location: cart.php');
                exit();
            }
        }
    }
    
    if(isset($_POST['cancel_order'])){
        unset($_SESSION['cart']);
        $_SESSION['cart_qty'] = 0;
        header('location: cart.php');
        exit();
    }
    
    if(isset($_POST['payment']) && !empty($_POST)){
        $_SESSION['total_price'] = isset($_POST['total_price']) ? $_POST['total_price'] : '';
           
        if(!$_SESSION['logged_in']){
            header('location: login.php');
            exit();
        }

        if(empty($_SESSION['cart'])){
            echo '<script>alert("Please select at least one event.")</script>';
        }else{
            header('location: confirm-order.php');
            exit();
        }
    }
    
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
        <title>Event Cart</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <link href="css/searchbar.css" rel="stylesheet" type="text/css"/>
        <link href="css/footer.css" rel="stylesheet" type="text/css"/>
        <style>
            body
            { 
                font: 14px sans-serif; 
            }
            .shadow{
                width: 80%;
                margin-left: auto;
                margin-right: auto;
                margin-top: 50px;
                height: 100%;
            }
            .shadow > div{
                margin-top: 15px;
                font-size: 16px;
            }
            .seperator{
                width: 100%;
                border: 1px solid #3366ff;
                margin-bottom: 30px;
                margin-left: auto;
                margin-right: auto;
            }
            .fa-trash{
                margin-right: 10px; 
                margin-top: 10px; 
                float:right;
            }
            .warning{
                text-align: center;
                font-size: 20px;
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 30px;
            }
            .amount{
                text-align: right;
                font-weight: bold;
            }
            img{
                width: 30%;
            }
            .cover{
                margin: 20px auto;
                border-radius: 15px;
                margin-right: 20px;
                padding-bottom: 20px;
            }
            .top_msg{
                background-color: #EB0000;
                padding: 16px 0px;
                border-radius: 15px;
            }
            .top_msg > div , .bot_msg > div{
                margin: 5px 20px;
                color: white;
                letter-spacing: 1px;
            }
            .bot_msg{
                border-radius: 15px;
                margin-bottom: 20px;
            }
            .bot_msg > div{
                color: black;
                margin-top: 0;
                margin-bottom: 0;
                line-height: 1.5em;
                
            }
            .br{
                width: 26%;
                height: 2px;
                border-radius: 15px;
                background-color: #EB0000;
                float: right;
                
            }
        </style>
    </head>
    <body>
        <!-- Top navigation -->
            <nav class="navbar navbar-expand-lg text-white top fixed-top bg-white">
                <a class="navbar-brand p-0" href="homePage.php">
                    <img src="pictures/japan_logo.png" alt="" style="width:90px;" class="p-0"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="" role="button" ><i class="fa fa-bars" aria-hidden="true" style="color:light-grey"></i></span>
                </button>

                <div class="collapse navbar-collapse nav-left" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link pl-5" href="homePage.php" style="font-size:20px; color: black;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pl-5" href="eventPage.php" style="font-size:20px; color: black;">Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pl-5" href="aboutUs.php" style="font-size:20px; color: black;">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pl-5" href="cart.php" style="font-size:20px; color: black;"><i class="fa fa-shopping-cart" style="font-size:20px;"></i> (<?php echo $cart_qty ?>)</a>
                        </li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse nav-right" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        
                        <?php
                            if(empty($_SESSION['logged_in']))
                            {
                                ?>    
                                <li class="nav-item">
                                    <a class="nav-link pl-5" href="register.php" style="font-size:20px; color: black;">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link ml-4 border rounded text-center" href="login.php" style="font-size:20px; color: black;">Login</a>
                                </li>
                                <?php
                            }
                            else
                            {        
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link pl-5" style="font-size:20px; color:black;" data-toggle="modal" href="#profile">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link pl-5" href="logout.php" style="font-size:20px; color: black;" data-toggle="tooltip" data-placement="bottom" title="Do you want to log out?">Log out</a>
                                </li>
                                <?php
                            }
                                ?>
                    </ul>
                </div>
            </nav>
        <br><br><br><br>
        
        
        <!------------------- Event Cart Page  ------------------->
        <div class="shadow p-3 mt-5 mb-5 bg-white rounded">       
               
            <h2>Event Cart</h2>

            <?php 
                $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                if(!empty($_SESSION['cart']))
                {
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $key => $value) 
                    {
                        $total_price += $value['order_amount'];
                        $sql = "select price, img_url from event_table where event_id = " . $value['event_id'];
                        if($result = $mysqli->query($sql))
                        {
                            if($row = $result->fetch_object())
                            { 
                                $price = $row->price;
                                $img = $row->img_url;
                            }
                        }
            ?>
            <div class="shadow p-3 mb-5 bg-white rounded seperator">     
                <a href="cart.php?event_id=<?php echo $value['event_id']; ?>" name="delete_cart" ><i class="fa fa-trash"></i></a>
                <img src="../admin/uploads/<?php echo $img?>" />
                <div class="name" >Event Name : <?php echo $value['event_name'] ?></div>
                <div class="price">Price : RM <?php echo $price ?>.00</div>
                <div class="qty" style="float: left;">Purchase Quantity : <?php echo $value['order_qty'] ?></div>
                <div class="amount">Total Amount : RM <?php echo $value['order_amount'] ?>.00</div>
                
            </div>
            
            <?php
                    }
                }
                else
                {
                    echo "<div class='warning'>You don't have any order yet ! </div>";
                }
                $mysqli->close();
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="total_price" value="<?php echo isset($total_price) ? $total_price : '' ?>"/>
                
                <?php          
                    $count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                    $_SESSION['cart_qty'] = $count;
                    $total_event_num = isset($_SESSION['total_event_num']) ? $_SESSION['total_event_num'] : '';
                    if($count != $total_event_num){
                ?>     
                <button type="button" class="btn btn-outline-primary btn-lg btn-block" onclick="location='eventPage.php'">&#43</button>
                <br/>
                
                <?php            
                    }
                ?>
 
                
                
                
                <div class="group-row">
                    <button type="submit" class="btn btn-warning " name="cancel_order" >Clear All Order</button>
                    <button type="submit" class="btn btn-primary float-right" name="payment">Proceed Payment</button>
                </div>
                
            </form>
        </div>
        
        <div class="modal fade" id="profile">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                        <h2 class="modal-title pull-left"><i class="fas fa-user-circle" style="color:rgb(128, 193, 255);"></i>  Profile</h2>
                        <ul class="nav mt-3">
                            <li class="nav-item p-0">
                                <a class="nav-link border" style="font-size:20px;">
                                    <i class="fas fa-info-circle"></i>
                                    Details
                                </a>
                            </li>
                            <li class="nav-item p-0" style="background-color:lightgrey;">
                                <a class="nav-link border" style="font-size:20px; text-decoration:none;" data-toggle="modal" href="#history" data-dismiss="modal">
                                    <i class="fas fa-history"></i>
                                    History
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-body pb-0">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" value="<?php echo $username?>" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" value="<?php echo $email?>" disabled>
                                </div>
                            </div>  
                            <div class="form-row">    
                                <div class="form-group col-md-12">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" value="12345678" disabled>
                                </div>    
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer m-0 justify-content-between">
                        <button type="button" class="btn btn-outline-primary" onclick="location= 'modify-profile.php'" name="modify">Modify Profile</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>                
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="history">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                        <h2 class="modal-title pull-left ml-3"><i class="fas fa-user-circle" style="color:rgb(128, 193, 255);"></i>  Profile</h2>
                        <ul class="nav mt-3">
                            <li class="nav-item p-0" style="background-color:lightgrey;">
                                <a class="nav-link border" style="font-size:20px;" data-toggle="modal" href="#profile" data-dismiss="modal">
                                    <i class="fas fa-info-circle"></i>
                                    Details
                                </a>
                            </li>
                            <li class="nav-item p-0">
                                <a class="nav-link border" style="font-size:20px;">
                                    <i class="fas fa-history"></i>
                                    History
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-body pb-0 ml-3" >
                        <div class="cover" >
                                <?php
                                    $got_cart = false;
                                    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                                    $sql = "select B.id, B.total_price, B.card_type, B.card_number, B.order_date, B.status, C.order_qty, C.total_amount, D.event_name 
                                            from user_member A, orders B, order_details C, event_table D
                                            where A.id = $id AND B.customer_id = A.id AND C.orders_id = B.id AND C.event_id = D.event_id  
                                            order by C.id";
                                    $count = 0;
                                    if($result = $mysqli->query($sql))
                                    {
                                       while($row = $result->fetch_object())
                                        {
                                           $cust_order_id = $row->id;
                                           $cust_order_total_price = $row->total_price;
                                           $cust_order_card_type = $row->card_type;
                                           $cust_order_card_num = $row->card_number;
                                           $cust_order_date = $row->order_date;
                                           $cust_order_status = $row->status;
                                           $cust_order_qty = $row->order_qty;
                                           $cust_order_total_amount = $row->total_amount;
                                           $cust_order_event_name = $row->event_name;
                                           
                                           
                                           $cust_order_array[$count] = array( 
                                                'event_name' => $cust_order_event_name,
                                                'qty' => $cust_order_qty,
                                                'amount' => $cust_order_total_amount,
                                           );
                                           $cust_history_array[$count] = array(
                                               'order_id' => $cust_order_id,
                                               'total_price' => $cust_order_total_price,
                                               'card_type' => $cust_order_card_type,
                                               'card_num' => $cust_order_card_num,
                                               'date' => $cust_order_date,
                                               'status' => $cust_order_status,
                                               'array' => $cust_order_array
                                            );
                                           
                                           
                                           $count++;
                                           $got_cart = true;
                                        }
                                        $result->free();
                                    }
                                    $mysqli->close();
                                ?>
                            
                            <?php if($got_cart){ 
                                $count = 0;
                                $previous = 0;
 
                                foreach($cust_history_array as $keys => $values){
                                if($values['order_id'] != $previous){
                                    
                            ?>
                            
                            <div class="top_msg" style="background-color: <?php echo $values['status']==0 ? "#EB0000" : "gray"  ?>">
                                <div style="float: left; margin-top: 5px;">Order ID : <?php echo $values['order_id'] ?> <b><?php echo $values['status']==1 ? "(Order Has Been Cancelled)" : ''?></b> </div>
                            <div style="text-align: right; margin-bottom: 0;">Order Date : <i><?php echo $values['date'] ?></i></div>
                            <div style="margin-top: 10px;">Card Type : <?php echo $values['card_type'] ?></div>
                            <div style="float:left;">Card Number : <?php echo $values['card_num'] ?></div>
                            <div style="text-align: right; margin-top: 10px; margin-bottom: 0px;"><b>Total Price: RM <?php echo $values['total_price'] ?>.00</b></div>
                            <div class="br"></div>
                            
                            </div>
                                <?php $previous = $values['order_id']; } ?>
                            <div class="bot_msg">
                            
                            <div style="margin-top: 10px;">Event Name: <?php echo $values['array'][$count]['event_name'] ?></div>
                            <div>Quantity: <?php echo $values['array'][$count]['qty'] ?></div>
                            <div>Amount: RM <?php echo $values['array'][$count]['amount'] ?>.00</div><br/>
                            
                            
                            </div>
                            <?php 
                                $count++;
                                    }
                                }else{ ?>
                            <div style="text-align:center; margin-top: 20px;"><b>You Don't Have Any Puchase History ~</b></div>
                            
                            <?php } ?>
                        </div>
                    </div>
                    <div class="modal-footer m-0">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            $(document).ready(function(){
              $('[data-toggle="tooltip"]').tooltip();   
            });
        </script>
    </body>
</html>
