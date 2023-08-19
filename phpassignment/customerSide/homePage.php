<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $type_of_member = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
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
        }
        $result->free();
        
    }
    $mysqli->close();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page</title>
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
        <link href="css/footer.css" rel="stylesheet" type="text/css"/>
        <style>
            body
            { 
                font: 14px sans-serif;
            }
            .bookbtn
            {
                text-decoration: none;
                font-size: 17px;
                font-family: monospace;
                color: black;
                border: 1px solid gray;
                border-radius: 30px;
                padding:12px 18px;
            }
            .bookbtn:hover
            {
                border: 1px solid red;
                color: red;
            }
            .bookbtn:hover > span{
                padding-right: 25px;
            }
            .bookbtn:hover > span::after
            {
                opacity: 1;
                right: 0;
            }
            /* arrow setting*/
            .bookbtn > span
            {
                display: inline-block;
                position: relative;
                transition: 0.3s;     
            }
            .bookbtn > span::after
            {
                content: '\00bb';
                position: absolute;
                opacity: 0;
                top: 0;
                right: -20px;
                transition: 0.4s;
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
                float: right;
                
            }
            .pl-5{
                font-size: 18px;
                text-align: justify;
                line-height: 1.5em;
                word-spacing: 1px;
            }
            h4{
                margin-top: 20px;
            }
            .row > div{
                font-size: 16px;
                margin-top: 10px;
                font-family: system-ui;
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
                                    <a class="nav-link ml-4 border rounded text-center "  href="login.php" style="font-size:20px; color: black;">Login</a>
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
               
        <!-- Event Slideshow -->
        <div id="slideshow" class="carousel slide" data-ride="carousel" style="background-color:black;">
            <?php
                $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                
                $sql = "SELECT img_url from event_table";
                $count = 0;
                if($result = $mysqli->query($sql))
                {
                    echo "<ul class='carousel-indicators'>";
                    while($row = $result->fetch_object())
                    {
                        if ($count == 0) 
                        {
                            echo "<li class='active' data-target='#slideshow' data-slide-to='$count'></li>";
                        }
                        else
                        {
                            echo "<li data-target='#slideshow' data-slide-to='$count'></li>";
                        }
                        
                        $count++;
                    }
                    echo "</ul>";
                }
                $result->free();
            ?>
            
            <?php
                $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                
                $sql = "SELECT img_url from event_table";
                $count_img = 0;
                if($result = $mysqli->query($sql))
                {
                    echo "<div class='carousel-inner'>";
                    while($row = $result->fetch_object())
                    {
                        $event_img = $row->img_url;
                        if ($count_img == 0) 
                        {
                            echo "<div class='carousel-item active'>";
                        }
                        else
                        {
                            echo "<div class='carousel-item'>";
                        }
                        
                        echo "<a href='eventPage.php'><img src='../admin/uploads/$event_img' style='width:70%; margin-left: 15%;'/></a>";
                        
                        echo "</div>";
                        
                        $count_img++;
                    }
                    echo "</div>";
                }
                $result->free();
            ?>
  
            <a class="carousel-control-prev" href="#slideshow" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#slideshow" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
        
        <div class="container-fluid mt-2 p-5 content1">
            <div class="border border-right-0 border-top-0 border-bottom-0 border-dark">
                <h2 class="pl-5" style="font-size: 18px;">About Japan</h2>
                <div class="embed-responsive embed-responsive-16by9 w-50 ml-5">
                    <iframe src="https://www.youtube.com/embed/p-I3LhCrsq0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <p class="pl-5 pr-5" style="font-family: system-ui;">
                    <br>
                    Japan (Japanese: Nippon <i>[ɲippoꜜɴ]</i> (About this soundlisten) or Nihon <i>[ɲippoꜜɴ]</i>) is an island country in East Asia, located in the northwest Pacific Ocean. 
                    It is bordered on the west by the Sea of Japan, and extends from the Sea of Okhotsk in the north toward the East China Sea and Taiwan in the south. 
                    Part of the Ring of Fire, Japan spans an archipelago of 6852 islands covering 377,975 square kilometers (145,937 sq mi); the five main islands are Hokkaido, Honshu, Shikoku, Kyushu, and Okinawa. 
                    Tokyo is Japan's capital and largest city; other major cities include Yokohama, Osaka, Nagoya, Sapporo, Fukuoka, Kobe, and Kyoto.
                    <br><br>
                    Japan is the eleventh-most populous country in the world, as well as one of the most densely populated and urbanized. 
                    About three-fourths of the country's terrain is mountainous, concentrating its population of 125.36 million on narrow coastal plains. 
                    Japan is divided into 47 administrative prefectures and eight traditional regions. The Greater Tokyo Area is the most populous metropolitan area in the world, with more than 37.4 million residents.
                </p>
            </div>
        </div>
        
        <div class="container-fluid p-5 content2">
            <div class="border border-right-0 border-top-0 border-bottom-0 border-dark">
                <h2 class="pl-5">Japan's Famous Landmarks</h2>
                <br>
                <div class="text-center">
                    <div class="row">
                        <div class="col pl-5"><h4>Shibuya Crossing</h4></div>
                        <div class="col pl-5"><h4>Arashiyama Bamboo Crossing</h4></div>
                        <div class="col pl-5"><h4>Mount Fuji</h4></div>
                    </div>
                    <div class="row">
                        <div class="col img-fluid pl-5"><img src="pictures/shibuya.png" style="width:100%;"></div>
                        <div class="col img-fluid pl-5"><img src="pictures/arashiyama.png" style="width:100%;"></div>
                        <div class="col img-fluid pl-5"><img src="pictures/fuji.png" style="width:100%;"></div>
                    </div>
                    <div class="row">
                        <div class="col pl-5">Often called the world’s busiest pedestrian scramble, Shibuya Crossing has become a symbol of modern Tokyo.</div>
                        <div class="col pl-5">The beauty and mystery of the Arashiyama Bamboo Grove in Kyoto have never been replicated anywhere else on Earth.</div>
                        <div class="col pl-5">With its wide stature and snow-capped peak, Mount Fuji is immediately recognizable at a glance. This beautiful mountain near Tokyo has become a symbol of Japan.</div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col pl-5"><h4>Ashikaga Flower Park</h4></div>
                        <div class="col pl-5"><h4>Dotonbori</h4></div>
                        <div class="col pl-5"><h4>Himeji Castle</h4></div>
                    </div>
                    <div class="row">
                        <div class="col img-fluid pl-5"><img src="pictures/ashikaga.png" style="width:100%;"></div>
                        <div class="col img-fluid pl-5"><img src="pictures/dotonbori.png" style="width:100%;"></div>
                        <div class="col img-fluid pl-5"><img src="pictures/himeji.png" style="width:100%;"></div>
                    </div>
                    <div class="row">
                        <div class="col pl-5">Ashikaga Flower Park’s stunning purple wisterias attract thousands of visitors each year. The wisteria bloom in late April to early May.</div>
                        <div class="col pl-5">Osaka’s downtown Dotonbori district is possibly its most visited attraction, famous for its bright neon signboards and tasty delicacies.</div>
                        <div class="col pl-5">Himeji Castle is Japan’s most famous castle and one of the best surviving examples of feudal Edo architecture.</div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col pl-5"><h4>Shirakawa-go</h4></div>
                        <div class="col pl-5"><h4>Nara Park</h4></div>
                        <div class="col pl-5"><h4>Fushimi Inari Taisha</h4></div>
                    </div>
                    <div class="row">
                        <div class="col img-fluid pl-5"><img src="pictures/shirakawa.png" style="width:100%;"></div>
                        <div class="col img-fluid pl-5"><img src="pictures/nara.png" style="width:100%;"></div>
                        <div class="col img-fluid pl-5"><img src="pictures/fushimi.png" style="width:100%;"></div>
                    </div>
                    <div class="row">
                        <div class="col pl-5">A UNESCO World Heritage Site, the picturesque village of Shirakawa-go is one of Japan’s top winter destinations. With the village lit up and covered with a blanket of snow.</div>
                        <div class="col pl-5">Once thought to be messengers of the gods, the local sika deer now roam free in Nara Park and have become an icon of the city.</div>
                        <div class="col pl-5">Fushimi Inari Taisha is a shrine in Kyoto. The long path of brightly colored torii leading up to the shrine has been featured in countless films.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid p-5 content3">
            <div class="border border-right-0 border-top-0 border-bottom-0 border-dark">
                <h2 class="pl-5">Book Now For The Omatsuri Event</h2>
                <div class="img-fluid pl-5"><img src="pictures/omatsuri2.png" style="width:60%;"></div>
                <p class="pl-5" style="font-size: 18px;">
                    <br>
                    Come and experience the Japanese festival without the need to travel to Japan.
                    <br><br>
                    Book now for early seats available for the event before it runs out!!
                    <br><br>
                    <a href="eventPage.php" class="bookbtn">
                        <span>Click to Book Ticket</span>
                    </a>
                </p>
            </div>
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
                            <div class="br" style="background-color: white" ></div>
                            
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
        
        <div>
            <footer class="text-center justify-content-center">
                <ul style="list-style:none; display:flex;" class="justify-content-center pr-0 pl-0 pb-3 pt-5 m-0">
                    <li><a style="text-decoration:none;" class="footer" href="homePage.php">Home</a></li>
                    <li>
                        <?php
                            if(empty($_SESSION['logged_in']))
                            {
                                ?>    
                                    <a style="text-decoration:none;" class="footer" href="login.php">Login</a>
                                <?php
                            }
                            else
                            {        
                                ?>
                                    <a style="text-decoration:none;" class="footer" href="logout.php">Log out</a>
                                <?php
                            }
                                ?>
                    </li>
                    <li><a style="text-decoration:none;" class="footer" href="cart.php">Booking</a></li>
                    <li><a style="text-decoration:none;" class="footer" href="aboutUs.php">About</a></li>
                </ul>
                <div class="p-0 m-0">&copy 2021 Cookies by <b><i>Japanese Society</i></b> | All rights reserved</div>
            </footer>
        </div>
        
        <script>
            $(document).ready(function(){
              $('[data-toggle="tooltip"]').tooltip();   
            });
        </script>
        
    </body>
</html>
