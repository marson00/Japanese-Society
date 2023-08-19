<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    $person_IC = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    
    require_once '../helper/user-config.php';
    
    if(!empty($_POST['event-submit']) || isset($_POST['event-submit'])){
        
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        
        $event_name = isset($_POST['event_name']) ? $mysqli->real_escape_string(trim($_POST['event_name'])) : '';
        $event_start_date = isset($_POST['start_date']) ? $mysqli->real_escape_string(trim(strtoupper($_POST['start_date']))) : '';
        $event_end_date = isset($_POST['end_date']) ? $mysqli->real_escape_string(trim(strtoupper($_POST['end_date']))) : '';
        $event_start_time = isset($_POST['start_time']) ? $mysqli->real_escape_string(trim($_POST['start_time'])) : '';
        $event_end_time = isset($_POST['end_time']) ? $mysqli->real_escape_string(trim($_POST['end_time'])) : '';
        $event_location = isset($_POST['location']) ? $mysqli->real_escape_string(trim($_POST['location'])) : ''; 
        $event_total_quantity = isset($_POST['quantity']) ? $mysqli->real_escape_string(trim($_POST['quantity'])) : '';
        $event_price = isset($_POST['price']) ? $mysqli->real_escape_string(trim($_POST['price'])) : '';
        
        $fileName = isset($_FILES['file']['name']) ? basename($_FILES['file']['name']) : '';    
        $targetDir = "uploads/";
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);    
        
        $error['event_name'] = validateEventName($event_name);
        $error['start_date'] = validateSrtDate($event_start_date);;
        $error['end_date'] = validateEndDate($event_end_date, $event_start_date, $event_start_time, $event_end_time);
        $error['start_time'] = validateSrtTime($event_start_time);
        $error['end_time'] = validateEndTime($event_end_time, $event_start_time, $event_start_date, $event_end_date);
        $error['location'] = validateLocation($event_location);
        $error['quantity'] = validateQuantity($event_total_quantity);
        $error['price'] = validatePrice($event_price);
        $error['file'] = validateFile($fileName,$fileType);
        
        $error = array_filter($error);
        
        if(empty($error)){
            
            move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath);
            
            $sql = "insert into event_table (event_name, start_date, end_date, start_time, end_time, location, total_quantity, quantity_left, price, img_url, status, person_in_charge) values (?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('ssssssiiisii', $param_event_name, $param_start_date, $param_end_date, $param_start_time, $param_end_time, $param_location, $param_total_quantity, $param_qty_left, $param_price, $param_img_url, $param_event_status, $param_person_ic);
            
            $param_event_name = $event_name;
            $param_start_date = $event_start_date;
            $param_end_date = $event_end_date;
            $param_start_time = $event_start_time;
            $param_end_time = $event_end_time;
            $param_location = $event_location;
            $param_total_quantity = $event_total_quantity;
            $param_qty_left = $event_total_quantity;
            $param_price = $event_price;
            $param_img_url = $fileName;
            $param_event_status = 0;
            $param_person_ic = $person_IC;
            
            if($stmt->execute()){
                $error['correct'] = 'Successfully <b>add</b> event ! <a href="event-table.php">Back to event table.</a>';
            }else{
                $error['warning'] = 'Oops! Something went wrong. <a href="event-table.php">Please try again</a> !';
            }
            $stmt->close();
            $hideForm = true;
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
            body
            { 
                font: 14px sans-serif; 
                background-image: url('./images/Cat.jpeg');
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-size: cover;
                width:100%;
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
        <div class="wrapper border shadow p-4 bg-white mx-auto" style=" border-radius: 10px; margin-top:2%; width:35%;">
                <?php
                    if(!empty($error['warning']))
                    {
                        printf('<div class="alert alert-danger w-100" role="alert">%s</div>',$error['warning']);
                    }
                    if(!empty($error['correct']))
                    {
                        printf('<div class="alert alert-success w-100" role="alert">%s</div>',$error['correct']);
                    }
                ?>
                <?php if(isset($hideForm) == false) : ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-auto w-100" enctype="multipart/form-data">                
                <h2 class="text-center mb-4 ">Add New Event</h2>
                <div class="form-group">
                    <label>Event Name</label>
                    <input type="text" name="event_name" class="form-control mx-auto <?php echo (!empty($error['event_name'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['event_name']) ? $_POST['event_name'] : '' ?>">
                    <span class="invalid-feedback"><?php echo $error['event_name']; ?></span>
                </div>
            
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control <?php echo (!empty($error['start_date'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['start_date']; ?></span>
                    </div>
                
                    <div class="form-group col-md-6">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control <?php echo (!empty($error['end_date'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['end_date']; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control mx-auto <?php echo (!empty($error['location'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['location']; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control <?php echo (!empty($error['start_time'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['start_time']; ?></span>
                    </div>
                
                    <div class="form-group col-md-6">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control <?php echo (!empty($error['end_time'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['end_time']) ? $_POST['end_time'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['end_time']; ?></span>
                    </div>
                </div>
            
                <div class="form-row">
                    <div class="form-group col-md-6" >
                        <label>Total Quantity</label>
                        <input type="text" name="quantity" class="form-control <?php echo (!empty($error['quantity'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['quantity']; ?></span>
                    </div>
                
                    <div class="form-group col-md-6">
                        <label>Price </label>
                        <input type="text" name="price" class="form-control <?php echo (!empty($error['price'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($_POST['price']) ? $_POST['price'] : '' ?>">
                        <span class="invalid-feedback"><?php echo $error['price']; ?></span>
                    </div>
                </div>
            
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Upload Image</label>
                        <input type="file" class="custom-file-input form-control mx-auto <?php echo (!empty($error['file'])) ? 'is-invalid' : ''; ?>" id="customFile" name="file"/>
                        <label class="custom-file-label form-control mt-4 mx-auto" for="customFile">Choose file</label>
                        <span class="invalid-feedback"><?php echo $error['file']; ?></span>
                    </div>
                </div>
            
                
                <input type="button" class="btn btn-danger pull-left mt-2" value="Back" onclick="window.location.href='event-table.php';">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter" >ADD</button>
                
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
                                Are you sure to <b>add</b> new event ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                <input type="submit" class="btn btn-primary float-right" value="Yes" name="event-submit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div>  
        <script>
            $(".custom-file-input").on("change", function() 
            {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });   
        </script>
    </body>
</html>
