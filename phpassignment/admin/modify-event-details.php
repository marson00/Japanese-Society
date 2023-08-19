<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $event_field = isset($_SESSION['event_field']) ? $_SESSION['event_field'] : '';
    $event_id = isset($_SESSION['event_id']) ? $_SESSION['event_id'] : '';
    $event_status = isset($_SESSION['event_status']) ? $_SESSION['event_status'] : '';
    $admin_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    
    require_once '../helper/user-config.php';
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
             
        
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
            $_SESSION['srt_date'] = $event_start_date;
            $_SESSION['end_date'] = $event_end_date;
            $_SESSION['srt_time'] = $event_start_time;
            $_SESSION['end_time'] = $event_end_time;
            
        }
        $result->free();
        
    }else {
        
        if(!empty($_POST['submit_event_name']) || isset($_POST['submit_event_name'])){
            $event_name = isset($_POST['event_name']) ? $mysqli->real_escape_string(trim($_POST['event_name'])) : '';   
            $error['event_name'] = validateEventName($event_name);
            $error = array_filter($error);
            
            if(empty($error)){
                $sql = "update event_table set event_name = ? where event_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $param_event_name, $param_event_id );
                   
                $param_event_name = $event_name;
                $param_event_id = $event_id;

                if($stmt->execute())
                {
                     $success = 'Successfully update <b>event name</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();
            }
            
        }else if(!empty($_POST['submit_event_date']) || isset($_POST['submit_event_date'])){
            $event_start_date = isset($_POST['start_date']) ? $mysqli->real_escape_string(trim(strtoupper($_POST['start_date']))) : '';
            $event_end_date = isset($_POST['end_date']) ? $mysqli->real_escape_string(trim(strtoupper($_POST['end_date']))) : ''; 
            $event_start_time = $_SESSION['srt_time'];
            $event_end_time = $_SESSION['end_time'];
            $error['start_date'] = validateSrtDate($event_start_date);;
            $error['end_date'] = validateEndDate($event_end_date, $event_start_date, $event_start_time, $event_end_time);
            
            $error = array_filter($error);
            if(empty($error)){
                $sql = "update event_table set start_date = ?, end_date = ? where event_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssi', $param_start_date, $param_end_date, $param_event_id );
                
                $param_start_date = $event_start_date;
                $param_end_date = $event_end_date;
                $param_event_id = $event_id;

                if($stmt->execute())
                {
                     $success = 'Successfully update <b>event date</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();
            }
            
        }else if(!empty($_POST['submit_event_time']) || isset($_POST['submit_event_time'])){
            
            $event_start_time = isset($_POST['start_time']) ? $mysqli->real_escape_string(trim(strtoupper($_POST['start_time']))) : '';
            $event_end_time = isset($_POST['end_time']) ? $mysqli->real_escape_string(trim(strtoupper($_POST['end_time']))) : ''; 
            $event_start_date = $_SESSION['srt_date'];
            $event_end_date = $_SESSION['end_date'];
            
            $error['start_time'] = validateSrtTime($event_start_time);
            if(!empty($event_start_time)){
                $error['end_time'] = validateEndTime($event_end_time, $event_start_time, $event_start_date, $event_end_date);
            }else{
                $error['end_time'] = "Please enter a <b>End Time</b>";
            }
            
            $error = array_filter($error);
            
            if(empty($error)){
                $sql = "update event_table set start_time = ?, end_time = ? where event_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ssi', $param_start_time, $param_end_time, $param_event_id );
                
                $param_start_time = $event_start_time;
                $param_end_time = $event_end_time;
                $param_event_id = $event_id;

                if($stmt->execute())
                {
                     $success = 'Successfully update <b>event time</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();
            }
            
        }else if(!empty($_POST['submit_event_location']) || isset($_POST['submit_event_location'])){
            $event_location = isset($_POST['event_location']) ? $mysqli->real_escape_string(trim($_POST['event_location'])) : '';   
            $error['event_location'] = validateLocation($event_location);
            $error = array_filter($error);
            
            if(empty($error)){
                $sql = "update event_table set location = ? where event_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $param_location, $param_event_id );
                
                $param_location = $event_location;
                $param_event_id = $event_id;

                if($stmt->execute())
                {
                     $success = 'Successfully update <b>event location</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();
            }
            
            
        }else if(!empty($_POST['submit_event_qty_price']) || isset($_POST['submit_event_qty_price'])){
            $event_quantity = isset($_POST['quantity']) ? $mysqli->real_escape_string(trim($_POST['quantity'])) : '';   
            $event_price = isset($_POST['price']) ? $mysqli->real_escape_string(trim($_POST['price'])) : '';   
            $error['quantity'] = validateQuantity($event_quantity);
            $error['price'] = validatePrice($event_price);
            
            $error = array_filter($error);
            
            if(empty($error)){
                $sql = "update event_table set total_quantity = ?, quantity_left = ?, price = ? where event_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('iiii', $param_total_quantity, $param_quantity_left, $param_price, $param_event_id );
                
                $param_total_quantity = $event_quantity;
                $param_quantity_left = $event_quantity;
                $param_price = $event_price;
                $param_event_id = $event_id;

                if($stmt->execute())
                {
                     $success = 'Successfully update <b>total ticket quantity</b> and <b>ticket price</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();
            }
            
        }else if(!empty($_POST['submit_event_img']) || isset($_POST['submit_event_img'])){
            $fileName = isset($_FILES['file']['name']) ? basename($_FILES['file']['name']) : '';    
            $targetDir = "uploads/";
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION); 
            
            $error['file'] = validateFile($fileName,$fileType);
            $error = array_filter($error);
            
            if(empty($error)){ 
                move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath);
                $sql = "update event_table set img_url = ? where event_id = ? ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('si', $param_img_url, $param_event_id);
                
                $param_img_url = $fileName;
                $param_event_id = $event_id;
                
                if($stmt->execute())
                {
                     $success = 'Successfully update a <b>new image</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();  
                
            }
        }else if(!empty($_POST['submit_event_status']) || isset($_POST['submit_event_status'])){
            $new_event_status = isset($_POST['event_status']) ? $mysqli->real_escape_string(trim($_POST['event_status'])) : ''; 
            
            if($event_status == $new_event_status){
                $error['event_status'] = 'Please select a new event <b>status</b>. ';
                $error = array_filter($error);
            }
            
            if(empty($error)){
                $sql = "update event_table set status = ? where event_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('ii', $param_event_status, $param_event_id );
                
                $param_event_status = $new_event_status;
                $param_event_id = $event_id;

                if($stmt->execute())
                {
                     $success = 'Successfully changed event status from <b>' . eventStatus()[$event_status] . '</b> to <b>' . eventStatus()[$new_event_status] . '</b>.';      
                }
                else
                {                      
                     $error['warning'] = 'Oops! Something went wrong, Please try again!';                    
                }                    
                $stmt->close();
                
            }
            
        }
    }
    
    $mysqli->close();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Modify Event Details</title>
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
            <?php
            if(!empty($error['warning']))
            {
                printf('<div class="alert alert-danger" role="alert">%s</div>',$error['warning']);
            }
            else if(!empty($success))
            {
                printf('<div class="alert alert-success" style="text-align:center;" role="alert">%s</div>',$success);
            }
            ?>
            <!--Body Content-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="mx-auto">
                <div class="border wrapper p-3 justify-content-center" style="width:60%;margin-left:20%;">    
                <?php if($event_field == 'event_name'){?>
                    <div class="form-group" style="width:100%;">
                        <h3 class="mt-3 text-center">Event Name</h3>
                        <p style="margin-left:10%;">Enter new event name.</p>
                        <input type="text" name="event_name" id="Event_name" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['event_name'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['event_name']) ? $_POST['event_name'] : '' ?>" placeholder="<?php echo isset($event_name) ? $event_name : '' ?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['event_name']; ?></span>
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit"  name="submit_event_name" style="margin-right:10%;" class="btn btn-primary mt-3 pull-right">Submit</button>
                    </div>
                <?php }else if($event_field == 'event_date'){ ?>   
                    <div class="form-group" style="width:100%;">
                        <h3 class="mt-3 text-center">Event Date</h3>
                        <p style="margin-left:10%;">Select new event start date.</p>
                        <input type="date" name="start_date" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['start_date'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : '' ?>" placeholder="<?php echo isset($event_start_date) ? $event_start_date : '' ?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['start_date']; ?></span>
                        <p style="margin-left:10%;">Select new event end date.</p>
                        <input type="date" name="end_date" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['end_date'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : '' ?>" placeholder="<?php echo isset($event_end_date) ? $event_end_date : ''?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['end_date']; ?></span>                   
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit"  name="submit_event_date" style="margin-right:10%;" class="btn btn-primary mt-3 pull-right">Submit</button>    
                    </div>
                <?php }else if($event_field == 'event_time'){ ?>   
                    <div class="form-group" style="width:100%;">
                        <h3 class="mt-3 text-center">Event Time</h3>
                        <p style="margin-left:10%;">Select new event start time.</p>
                        <input type="time" name="start_time" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['start_time'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : '' ?>" placeholder="<?php echo isset($event_start_time) ? $event_start_time : '' ?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['start_time']; ?></span>
                        <p style="margin-left:10%;">Select new event end time.</p>
                        <input type="time" name="end_time" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['end_time'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['end_time']) ? $_POST['end_time'] : '' ?>" placeholder="<?php echo isset($event_end_time) ? $event_end_time : ''?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['end_time']; ?></span>
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit"  name="submit_event_time" style="margin-right:10%;" class="btn btn-primary pull-right mt-3">Submit</button>   
                    </div>    
                <?php }else if($event_field == 'event_location'){ ?> 
                    <div class="form-group" style="width:100%;">  
                        <h3 class="mt-3 text-center">Event Location</h3>
                        <p style="margin-left:10%;">Enter new event location.</p>
                        <input type="text" name="event_location" id="Location" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['event_location'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['event_location']) ? $_POST['event_location'] : '' ?>" placeholder="<?php echo isset($event_location) ? $event_location : '' ?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['event_location']; ?></span>
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit"  name="submit_event_location" style="margin-right:10%;" class="btn btn-primary pull-right mt-3">Submit</button>   
                    </div>    
                <?php }else if($event_field == 'event_qty_price'){ ?>   
                    <div class="form-group" style="width:100%;">    
                        <h3 class="mt-3 text-center">Event Total Ticket Quantity and Price</h3>
                        <p style="margin-left:10%;">Enter new event ticket quantity.</p>
                        <input type="text" name="quantity" id="Quantity" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['quantity'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : '' ?>" placeholder="<?php echo isset($event_total_quantity) ? $event_total_quantity : '' ?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['quantity']; ?></span>
                        <p style="margin-left:10%;">Enter new event ticket price.</p>
                        <input type="text" name="price" style="width:80%;" class="form-control mx-auto <?php echo (!empty($error['price'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['price']) ? $_POST['price'] : '' ?>" placeholder="<?php echo isset($event_price) ? $event_price : '' ?>">
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['price']; ?></span>
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit" name="submit_event_qty_price" style="margin-right:10%;" class="btn btn-primary pull-right mt-3">Submit</button>      
                    </div>    
                <?php }else if($event_field == 'event_img'){ ?>   
                    <div class="form-group" style="width:100%;">    
                        <h3 class="mt-3 text-center">Event Image</h3>
                        <p style="margin-left:10%;">Select new event image.</p>
                        <div class="custom-file">
                            <input type="file" name="file" style="width:80%;" class="custom-file-input form-control mx-auto <?php echo (!empty($error['file'])) ? 'is-invalid' : ''; ?>" id="customFile"/>
                            <label class="custom-file-label form-control mx-auto" style="width:80%;" for="customFile">Choose file</label>
                            <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['file']; ?></span>
                        </div>
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit" name="submit_event_img" style="margin-right:10%;" class="btn btn-primary pull-right mt-3">Submit</button>       
                    </div>    
                <?php }else if($event_field == 'event_status'){?>  
                    <div class="form-group" style="width:100%;">    
                        <h3 class="mt-3 text-center">Event Status</h3>
                        <p style="margin-left:10%;">Select new event status.</p>                 
                        <select class="custom-select form-control <?php echo (!empty($error['event_status'])) ? 'is-invalid' : ''; ?>" style="width:80%;margin-left:10%;" name="event_status">
                            <?php 
                            if($event_status == 0)
                            {
                                echo '<option value="0" selected >Preparing</option>';
                                echo '<option value="1">Ongoing</option><option value="2">Cancel Event</option>';
                            }
                            else if($event_status == 1)
                            {
                                echo '<option value="1" selected >Ongoing</option>';
                                echo '<option value="0">Preparing</option><option value="2">Cancel Event</option>';
                            }
                            else
                            {
                                echo '<option value="2" selected >Cancelled</option>';
                                echo '<option value="0">Preparing</option><option value="1">Ongoing</option>';
                            }
                            ?> 
                        </select>                  
                        <span class="invalid-feedback" style="margin-left:10%;"><?php echo $error['event_status']; ?></span>
                        <button type="button" style="margin-left:10%;" class="btn btn-outline-primary mt-3 pull-left"><a href="modify-event.php?event_id=<?php echo $event_id ?>">Back</a></button>
                        <button type="submit"  name="submit_event_status" style="margin-right:10%;" class="btn btn-primary pull-right mt-3">Submit</button>     
                    </div>    
                <?php } ?>
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
        $(".custom-file-input").on("change", function() 
        {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        
        <?php if($event_field == 'event_name'){ ?>
           document.getElementById('Event_name').focus();
        <?php }else if($event_field == 'event_location'){ ?>
           document.getElementById('Location').focus();
        <?php }else if($event_field == 'event_qty_price'){ ?>
           document.getElementById('Quantity').focus();
        <?php } ?>
        
    </script>
    </body>
</html>


