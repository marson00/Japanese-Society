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
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $event_id = isset($_GET['event_id']) ? ($mysqli->real_escape_string(trim($_GET['event_id']))) : '';
        
        $sql = "select E.event_name, E.start_date, E.end_date, E.start_time, E.end_time, E.location, E.quantity_left, E.price, E.img_url
                from event_table E
                where E.event_id = $event_id ";
        if($result = $mysqli->query($sql)){
            if($row = $result->fetch_object()){  
                $event_name = $row->event_name;
                $event_start_date = $row->start_date;
                $event_end_date = $row->end_date;
                $event_start_time = $row->start_time;
                $event_end_time = $row->end_time;
                $event_location = $row->location;
                $event_qty_left = $row->quantity_left;
                $event_price = $row->price;
                $event_img = $row->img_url;   
            }
        }
        $result->free();    
        
    }else if(!empty($_POST['order_quantity']) && isset($_POST['submit_qty'])){
        
        $event_id = isset($_POST['hidden_event_id']) ? $_POST['hidden_event_id'] : '' ;
        $event_name = isset($_POST['hidden_event_name']) ? $_POST['hidden_event_name'] : '' ;
        $order_qty = isset($_POST['order_quantity']) ? $_POST['order_quantity'] : '' ;
        $order_amount = isset($_POST['amount']) ? $_POST['amount'] : '' ;

        
        if(isset($_SESSION['cart'])){
            $item_array_id = array_column($_SESSION['cart'], 'event_id');
            if(!in_array($event_id, $item_array_id)){
                $count = count($_SESSION['cart']);
                $item_array = array(
                    'event_id'     =>  $event_id,  
                    'event_name'   =>  $event_name,  
                    'order_qty'    =>  $order_qty,  
                    'order_amount' =>  $order_amount  
                  );
                $_SESSION['cart'][$count] = $item_array;
                $_SESSION['cart_qty']++;
                header('location: cart.php');
                exit();
            }else{
                echo '<script>alert("Item Already Added)</script>';
                header('location: cart.php');
                exit();
            }
        }else{
            $item_array = array(
                    'event_id'     =>  $event_id,  
                    'event_name'   =>  $event_name,  
                    'order_qty'    =>  $order_qty,  
                    'order_amount' =>  $order_amount  
                  );
            $_SESSION['cart'][0] = $item_array;
            $_SESSION['cart_qty']++;    
            header('location: cart.php');
            exit();
        }
        
        
    }
    
    $mysqli->close();
    
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
        <title>Order Page</title>
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
            .shadow > p{
                letter-spacing: 1px;
                word-spacing: 2px;
                text-align:justify;
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
        <script type="text/javascript">
            $(document).ready(function(){
                
                $("#order_qty").change(function(){
          
                    var total = 0;
                    var qty = $("#order_qty").val();
                    var price = "<?php echo $event_price; ?>";      
                    var total = qty * price;
                    
                    $("#total_amount").val(total);
                });

            });
        
        </script>  
        
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
        
        
        <!------------------- Order Event Page  ------------------->
        <div class="shadow p-3 mb-5 bg-white rounded">
          
            <h2>Order Event</h2>
            
            <img src="../admin/uploads/<?php echo $event_img?>" style="width: 60%; float: left; margin-right: 50px; margin-bottom: 150px;"/>
            <p style="font-size: 25px;"><b><?php echo $event_name ?></b></p>
            
            <p>DATE: <?php echo  $event_start_date . ' to '. $event_end_date ?></p>
            <p>TIME: <?php echo  $event_start_time . ' to '. $event_end_time  ?></p>
            <p><?php echo  'RM ' . $event_price . '.00 (per unit)' ?></p>
            <p><?php echo  $event_location ?></p>
            <p><b><?php echo $event_qty_left ?></b> tickets available</p>
            
            
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            
              <label>Quantity &nbsp&nbsp</label>
              <input type="number" value="0" min="1" max='<?php echo ($event_qty_left <= 10) ? "$event_qty_left" : "10" ?>' name="order_quantity"  style="width: 50px; border: 0; outline: none;" id="order_qty"/>     
              
              <p><i style="color: red;">one account can only purchase 10 tickets *</i></p>
              
              <p float: left;">Total Amount : RM <input type="number" id="total_amount" name="amount" value="0" readonly style="border: 0; outline: none; "/><span style="margin-left: -150px;">. 00</span></p>
              
              <input type="hidden" name="hidden_event_id" value="<?php echo $event_id; ?>"/>
              <input type="hidden" name="hidden_event_name" value="<?php echo $event_name; ?>"/>
              <button type="button" class="btn btn-outline-primary" onclick="location='eventPage.php'">Cancel</button>
              <button type="submit" class="btn btn-primary float-right" name="submit_qty" >Add to Cart</button>
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
            
            document.getElementById("order_qty").focus();
        </script>
    </body>
</html>
