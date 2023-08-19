<?php

define("DB_HOST", 'localhost');
define("DB_USER", 'root');
define("DB_PASS", '');
define("DB_NAME", 'admin');

/******************User register validation and matching*****************/
function validateUsername($username)
{
    if($username == null || empty($username))
    {
        return 'Please enter an <b>Username</b>.';
    }
    else if(strlen($username) > 50 )
    {
        return '<b>Username</b> is too long, Please try again.';
    }
    else if(strlen($username) < 6 )
    {
        return '<b>Username</b> must be at least 6 characters.';
    }
    else if(!preg_match('/^[a-zA-Z0-9]+$/',$username))
    {
        return '<b>Username</b> can only contain letters and numbers!.';
    }
    else if(checkExistUsername($username))
    {
        return '<b>Username</b> already existed Please try another.';
    }
}

function checkExistUsername($username)
{
    $exist = false;
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $sql = "select * from user_member where username = '$username'";
    $result = $mysqli->query($sql);
    if($result->num_rows > 0 )
    {
        $exist = true;
    }
    
    $result->free();
    $mysqli->close();
    return $exist;
}

function validateEmail($email)
{
    if($email == null || empty($email))
    {
        return 'Please enter an <b>Email</b>.';
    }
    else if(strlen($email) > 50)
    {
        return '<b>Email</b> is too long, Please try again.';
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return 'Invalid <b>Email</b> format.';
    }
}

function validatePassword($password)
{
    $alphabet = preg_match('@[A-Za-z]@', $password);
    $integer = preg_match('@[0-9]@', $password);
    
    if($password == null || empty($password))
    {
        return 'Please enter a <b>Password</b>.';
    }
    else if(!$alphabet || !$integer )
    {
        return '<b>Password</b> should contain at least one letter and number.';
    }
    else if(strlen($password) < 8)
    {
        return '<b>Password</b> must be at least 8 characters.';
    }
}

function validateConfirmPassword($password,$confirm_password)
{
    if($confirm_password == null || empty($confirm_password))
    {
        return '<b>Confirm password</b> is missing Please enter.';
    }
    else if($password != $confirm_password)
    {
        return '<b>Password</b> did not match Please try again.';
    }
}

/******************User login validation and matching*****************/

function checkUsername($username)
{
    if($username == null || empty($username))
    {
        return 'Please enter an <b>Username</b>.';
    }
}

function checkPassword($password)
{
    if($password == null || empty($password))
    {
        return 'Please enter a <b>Password</b>.';
    }
}

/******************Set Header in Column*****************/
function getHeader()
{
    return array
    (
        'id' => 'ID',
        'username' => 'Username',
        'email' => 'Email Address',
        'acc_created_at' => 'Created Time',
        'status' => 'Status'
    );
}

function getStatus()
{
    return array
    (
      '0' => 'Active',
      '1' => 'Banned'
    );
}

/******************Forget Password validation*****************/
function checkEmail($email)
{
    if($email == null || empty($email))
    {
        return 'Please enter an <b>Email</b>.';
    }
    else if(!checkExistEmail($email))
    {
        return '<b>Email</b> not found in our record.';
    }
}

function checkExistEmail($email)
{
    $exist = false;
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $sql = "select * from user_member where email = '$email' and status = 0";
    $result = $mysqli->query($sql);
    if($result->num_rows > 0 )
    {
        $exist = true;
    }
    $result->free();
    $mysqli->close();
    return $exist;
}

/******************Details Valdation*****************/
function validateName($admin_name)
{
    if($admin_name == null || empty($admin_name))
    {
        return 'Please enter an <b>Name</b>.';
    }
    else if(strlen($admin_name) < 2)
    {
        return '<b>Name</b> should more than one character.';
    }
    else if(!preg_match('/^[A-Za-z @,\'\.\-\/]+$/', $admin_name))
    {
        return '<b>Name</b> cannot include special character(s) and number.';
    }
}

function validateIC($ic)
{
    if($ic == null || empty($ic))
    {
        return 'Please enter an <b>IC</b>.';
    }
    else if(strlen($ic) > 14)
    {
        return ('<b>IC</b> must less than 14 characters.');
    }
    else if(!preg_match('/^(([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))-([0-9]{2})-([0-9]{4})$/', $ic))
    {
        return ('Invalid <b>IC</b> format.');
    }
    else if(validateExistIC($ic))
    {
        return ('<b>IC</b> already existed. Please try another.');
    }
}

function validateExistIC($ic)
{
    $exist = false;
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $sql = "select * from details where ic = '$ic'";
    $result = $mysqli->query($sql);
    if($result->num_rows > 0 )
    {
        $exist = true;
    }
    $result->free();
    $mysqli->close();
    return $exist;
}

function validateContact($contact)
{
    if($contact == null || empty($contact))
    {
        return 'Please enter a <b>Contact</b>';
    }
    else if(strlen($contact) > 12 )
    {
        return '<b>Contact No</b> is too long.';
    }
    else if(!preg_match('/^(01)[0-46-9]\-[0-9]{7,8}$/', $contact))
    {
        return 'Invalid <b>Contact No</b> format.';
    }
}

function validateBirth($birthdate)
{
    if($birthdate == null || empty($birthdate))
    {
        return 'Please enter a <b>Date of Birth</b>';
    }
    else if(!preg_match('/^[0-9]{4}\-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/', $birthdate))
    {
        return 'Invalid <b>Birthdate</b> format.';
    }
    
}

function validateAge($age)
{
    if($age == null || empty($age))
    {
        return 'Please enter an <b>Age</b>';
    }
    else if(strlen($age) > 3)
    {
        return 'Invalid <b>Age</b>';
    }
    else if(!preg_match("/^[0-9]+$/", $age))
    {
        return 'Invalid <b>Age</b>format.';
    }
    
}

/********************OTP Form******************/
function validateOTP($user_otp)
{
    if($user_otp == null || empty($user_otp))
    {
        return 'Please enter an <b>OTP</b>';
    }
    else if(!preg_match('/^[0-9]+$/',$user_otp))
    {
        return 'Invalid <b>OTP</b> format.';
    }
}

function checkExistOTP($user_otp)
{
    $exist = false;
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $sql = "select * from user_member where otp_code = '$user_otp'";
    $result = $mysqli->query($sql);
    if($result->num_rows > 0 )
    {
        $exist = true;
    }
    $result->free();
    $mysqli->close();
    return $exist;
}

/********************Event Validate******************/
function validateEventName($event_name){
    if($event_name == null || empty($event_name)){
        return 'Please enter an <b>Event Name</b>.';
    }else if(strlen($event_name) > 50){
        return '<b>Event name</b> is too long, please try again.';
    }else if(strlen($event_name) < 5){
        return '<b>Event name</b> must be at least 5 characters.';
    }else if(!preg_match('/^[a-zA-Z -]+/', $event_name)){
        return '<b>Event name</b> can only contain letters, space and dash!.';
    }
    
}

function validateSrtDate($event_start_date){ 
    if($event_start_date == null || empty($event_start_date)){
        return 'Please enter a <b>Start Date</b>.';
    }
}

function validateEndDate($event_end_date, $event_start_date, $event_start_time, $event_end_time){ 
    if($event_end_date == null || empty($event_end_date)){
        return 'Please enter a <b>End Date</b>.';
    }else if($event_end_date == $event_start_date){
        if($event_start_time == $event_end_time){
            return 'Please choose another <b>End date</b>.';
        }
    }else if($event_end_date < $event_start_date){
        return '<b>End Date</b> must after start date.';
    }
}

function validateSrtTime($event_start_time){ 
    if($event_start_time == null || empty($event_start_time)){
        return 'Please enter a <b>Start Time</b>.';
    }
}

function validateEndTime($event_end_time, $event_start_time, $event_start_date, $event_end_date){ 
    if($event_end_time == null || empty($event_end_time)){
        return 'Please enter a <b>End Time</b>.';
    }else if($event_start_date == $event_end_date){
        if($event_start_time == $event_end_time){
            return 'Please choose another <b>End Time</b>.';
        }else if($event_end_time < $event_start_time){
            return '<b>End Time</b> should longer than start time.';
        }else if($event_end_time - $event_start_time < 5){
            return '<b>Event Time</b> must at least 5 hours.';
        }
    }
}

function validateLocation($event_location){
    if($event_location == null || empty($event_location)){
        return 'Please enter a <b>Location</b>.';
    }else if(strlen($event_location) < 5){
        return '<b>Location</b> entered is too short. Please try another.';
    }
}

function validateQuantity($event_total_quantity){ 
    if($event_total_quantity == null || empty($event_total_quantity)){
        return 'Please enter the <b>quantity</b> of ticket .';
    }else if(!is_numeric($event_total_quantity)){
        return 'Please enter <b>numeric value only !</b>';
    }else if($event_total_quantity < 50){
        return '<b>Ticket</b> sold should more than 50.';
    }
}

function validatePrice($event_price){ 
    if($event_price == null || empty($event_price)){
        return 'Please enter a <b>Price</b>.';
    }else if(!is_numeric($event_price)){
        return 'Please enter <b>numeric value only !</b>';
    }
}

function validateFile($fileName, $fileType){
    $validFileType = array('jpg','jpeg','png','gif','pdf');
    if($fileName == null || empty($fileName)){
        return 'Please select one <b>Image</b>.';
    }
    if(!in_array($fileType, $validFileType)){
        return 'Only jpg, jpeg, pdf, png and gif allow !';
    }
}

/********************Event Header******************/
function getEventHeader(){
    return array(
        'event_id' => 'ID',
        'event_name' => 'Event Name',
        'created_time' => 'Created Time',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'location' => 'Location',
        'total_quantity' => 'Total Quantity',
        'price' => 'Price (RM)',
        'img_url' => 'Image Name',
        'status' => 'Status',
        'person_in_charge' => 'Created By Admin ID'
    );
}

/********************Event Status Array******************/
function eventStatus(){
    return array(
        '0' => 'Preparing',
        '1' => 'Ongoing',
        '2' => 'Cancelled'
    );
}

/********************Validate Card******************/


function validateCvv($cvv, $card_number, $card_type, $id){
    $pattern = '/^[0-9]{3}$/';
    
    if($cvv == null || empty($cvv)){
        return 'Please enter your card <b>CVV Number</b> !';
    }else if(!is_numeric($cvv)){
        return '<b>CVV</b> can only be number.';
    }else if(!preg_match($pattern, $cvv)){ 
        return '<b>CVV</b> number should be 3 digit.';
    }else if(!empty($card_number)){
        if(!checkExistCvv($cvv, $card_number, $card_type, $id)){
            return 'Wrong <b>CVV</b> number.';
        }
    } 
}

function checkExistCvv($cvv, $card_number, $card_type, $id){
    $repeat_card_number = false;
    $exist = false;
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
    $sql = "select card_number from orders where card_number = '$card_number'";
    $result = $mysqli->query($sql);
    if($result->num_rows > 0){
        $repeat_card_number = true;
    }else{
        $repeat_card_number = false;
        $exist = true;
    }
    $result->free();
    
    if($repeat_card_number){
        $sql = "select * from orders where card_type = '$card_type' and card_number = '$card_number' and cvv = '$cvv' and customer_id = '$id'";
        $result = $mysqli->query($sql);

        if($result->num_rows > 0 )
        {
            $exist = true;
        }  
        $result->free();
    }
    
    $mysqli->close();
    return $exist;
}

function validateCard($cardNum, $cardType) {

    if ($cardNum == null || empty($cardNum)) {
        return 'Please enter a <b>Card Number</b>';
    }else if (strlen($cardNum) < 19) {
        return '<b>Card Number</b> must have 16 digits.';
    }else if($cardType == "Visa"){
        if(!preg_match('/^4\d{3}\\s\d{4}\\s\d{4}\\s\d{4}$/', $cardNum)){
            return 'Invalid <b>Visa</b> card number.';
        }
    }else if($cardType == "Master_Card"){
        if(!preg_match('/^5[1-5]\d{2}\\s\d{4}\\s\d{4}\\s\d{4}$/', $cardNum)){
            return 'Invalid <b>Master Card</b> number.';
        }
    }
}

function orderStatus(){
    return array(
      '0' => "Successful",
      '1' => "Cancelled"
    );
}