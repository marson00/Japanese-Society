<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    $total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : '';
    $cart_qty = isset($_SESSION['cart_qty']) ? $_SESSION['cart_qty'] : 0;
        
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
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
        }
        $result->free();
        
    }
    $mysqli->close();

    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
    if(isset($_POST['cancel'])){
        unset($_SESSION['cart']);
        unset($_SESSION['cart_qty']);
        header('location: homePage.php');
        exit();
    }else if(isset($_POST['confirm']) && !empty($_POST)){
        $card_type = isset($_POST['card_type']) ? ($mysqli->real_escape_string(trim($_POST['card_type']))) : '';
        $card_number = isset($_POST['card_number']) ? ($mysqli->real_escape_string(trim($_POST['card_number']))) : '';
        $cvv = isset($_POST['cvv']) ? ($mysqli->real_escape_string(trim($_POST['cvv']))) : '';
        $error['card_number'] = validateCard($card_number, $card_type);
        $error['cvv'] = validateCvv($cvv, $card_number, $card_type, $id);
        
        
        $error = array_filter($error);
      
        if(empty($error)){
            $query_status = false;
            
            $sql = "insert into orders (total_price, card_type, card_number, cvv, status, customer_id) values (?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('issiii', $param_total_price, $param_card_type, $param_card_number, $param_cvv, $param_order_status, $param_id);
            
            $param_total_price = $total_price;
            $param_card_type = $card_type;
            $param_card_number = $card_number;
            $param_cvv = $cvv;
            $param_order_status = 0;
            $param_id = $id;
            
            if($stmt->execute()){
                $query_status = true;
            }else{
                echo $mysqli->error;
                $query_status = false;
            }
           $stmt->close();
            
            if($query_status){
                $sql = "select id from orders where customer_id = $id order by id desc";
                if($result = $mysqli->query($sql)){
                    if($row = $result->fetch_object()){
                        $orders_id = $row->id;
                        $_SESSION['orders_id'] = $orders_id;
                    }
                }else{
                    $query_status = false;
                }
                $result->free();
                
                foreach ($_SESSION['cart'] as $key => $value) {
                    $sql = "insert into order_details (order_qty, total_amount, event_id, orders_id) values (?,?,?,?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('iiii', $param_order_qty, $param_total_amount, $param_event_id, $param_orders_id);
                    
                    $param_order_qty = $value['order_qty'];
                    $param_total_amount = $value['order_amount'];
                    $param_event_id = $value['event_id'];
                    $param_orders_id = $_SESSION['orders_id'];
                    
                    if(!$stmt->execute()){
                        echo $mysqli->error;
                        $query_status = false;
                    }
                    $stmt->close();
                    
                    $sql = "select quantity_left from event_table where event_id = " . $value['event_id'];
                    if($result = $mysqli->query($sql)){
                        if($row = $result->fetch_object()){
                            $event_total_quantity = $row->quantity_left; 
                        }
                    }else{
                        $query_status = false;
                    }
                    $result->free();
                    
                    $sql = "update event_table set quantity_left = ? where event_id = ? ";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('ii', $param_quantity_left, $param_event_id);
                    
                    $param_quantity_left = $event_total_quantity - $value['order_qty'];
                    if($param_quantity_left == 0){
                        $event_id_array = array($value['event_id']);
                    }
                    $param_event_id = $value['event_id'];
                    
                    if(!$stmt->execute()){
                        echo $mysqli->error;
                        $query_status = false;
                    }
                    $stmt->close();
                    
                }
                if($param_quantity_left == 0){
                    foreach($event_id_array as $value){
                        $sql = "update event_table set status = 2 where event_id = '$value' ";
                        $result = $mysqli->query($sql);
                        if($result){
                            $query_status = true;
                        }else{
                            $query_status = false;
                        }
                    }
                    
                }
                
                
                if($query_status){
                    echo "<script>
                            alert('You Have Successfully Book Event Tickets.');
                            window.location.href = 'homePage.php';
                          </script>";
                    unset($_SESSION['cart']);
                    unset($_SESSION['cart_qty']);
                    
                }
            }
        }
        
    }
    
    
    $mysqli->close();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Confirm order</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <link href="css/homePage.css" rel="stylesheet" type="text/css"/>
        <link href="css/searchbar.css" rel="stylesheet" type="text/css"/>
        <link href="css/footer.css" rel="stylesheet" type="text/css"/>
        <style>
            body
            { 
                font: 14px sans-serif; 
            }
            .shadow{
                width: 50%;
                margin-left: auto;
                margin-right: auto;
                margin-top: 50px;
                height: 100%;
            }
            button{
                margin-top: 50px;
            }
            .modal .cover{
                margin: 20px auto;
                border-radius: 15px;
                margin-right: 20px;
                padding-bottom: 20px;
            }
            .modal .top_msg{
                background-color: #EB0000;
                padding: 16px 0px;
                border-radius: 15px;
            }
            .modal .top_msg > div , .modal .bot_msg > div{
                margin: 5px 20px;
                color: white;
                letter-spacing: 1px;
            }
            .modal .bot_msg{
                border-radius: 15px;
                margin-bottom: 20px;
            }
            .modal .bot_msg > div{
                color: black;
                margin-top: 0;
                margin-bottom: 0;
                line-height: 1.5em;
                
            }
            .modal .br{
                width: 26%;
                height: 2px;
                border-radius: 15px;
                background-color: #EB0000;
                float: right;
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
                            <a class="nav-link pl-5" href="cart.php" style="font-size:20px; color: black;"><i class="fa fa-shopping-cart" style="font-size:20px"></i> (<?php echo $cart_qty ?>)</a>
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
        
        <!-------Last Order Confirmation------>
        <div class="shadow p-3 mb-5 bg-white rounded checking">  
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h3>Total Price: RM <?php echo $total_price; ?>.00 </h3><br/>
                
                
                <p>Card Type</p>
                <select class="custom-select mb-5" name="card_type">
                    <option value="Visa">Visa</option>
                    <option value="Master_Card">Master Card</option>
                </select>    

                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" name="card_number" class="form-control mx-auto <?php echo (!empty($error['card_number'])) ? 'is-invalid' : ''; ?>" maxlength="19" value="<?php echo isset($card_number) ? $card_number : '' ?>" id="card-number"/>
                    <span class="invalid-feedback"><?php echo $error['card_number']; ?></span>
                </div>

                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" name="cvv" class="form-control mx-auto <?php echo (!empty($error['cvv'])) ? 'is-invalid' : ''; ?>" maxlength="3" value="<?php echo isset($cvv) ? $cvv : '' ?>" id="CVV"/>
                    <span class="invalid-feedback"><?php echo $error['cvv']; ?></span>
                </div>
                
                <button type="submit" class="btn btn-warning" name="cancel" >Cancel Order</button>
                <button type="button" class="btn btn-outline-success float-right mb-0" data-toggle="modal" data-target="#exampleModalCenter">Confirm Order</button>
                <div class="mt-1 pb-4">
                    <p style="font-size: 11px;" class="pull-right"><i>*Once confirm the order cannot be cancel</i></p>
                </div>
                
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
                                <p style="font-size:24px;" class="text-center">Are you sure you want to proceed with the payment process?</p>
                                <p style="font-size: 11px; color: red;" class="text-center"><i>*Once confirm the order cannot be cancel</i></p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger mr-auto" data-dismiss="modal" name="cancel">Cancel Order</button>
                                <input type="submit" class="btn btn-success" name="confirm" value="Continue">
                            </div>
                        </div>
                    </div>
                </div> 
                
                
                
            </form>
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
                                    <input type="text" name="username" class="form-control" value="<?php echo $_SESSION['username'];?>" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" value="<?php echo $_SESSION['email'];?>" disabled>
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
        
        
        
        
    </body>
    <script>
        document.getElementById('card-number').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
          });
          $(document).ready(function(){
              $('[data-toggle="tooltip"]').tooltip();   
            });
            
        document.getElementById("card-number").focus();    
        <?php if(!empty($error['card_number'])){ ?>
            document.getElementById("card-number").focus(); 
        <?php }else if(!empty($error['cvv'])){ ?>
            document.getElementById("CVV").focus(); 
        <?php } ?>
            
    </script>
</html>
