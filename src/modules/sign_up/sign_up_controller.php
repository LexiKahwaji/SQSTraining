<?php
/**
 * Created by PhpStorm.
 * User: connor
 * Date: 10/9/18
 * Time: 12:38 AM
 */

require_once("sign_up_model.php");

session_start();

//Checking what has been posted is a special case for this
//      module because it has 2 views that both redirect to it
//      and index also can come to here
//      Usually, you shouldn't need to do this

$_SESSION['errorMessage'] = null;


if(isset($_POST['hidden'])) {

    //error checking
    sanitized();

    //coming from sign_up_view_1
    if($_POST['hidden'] == 'sign_up_view_1'){


        noneMissing();
        passwordsChecked();

        createNewUser();

        header("Location: sign_up_view_2.php");
        exit();
    }else{
        //coming from sign_up_view_2
        if($_POST['hidden'] == 'sign_up_view_2'){

            addUserInfo();

            header("Location: ../home/home_controller.php");
            exit();

        }else{
            //shouldn't get here
            error("Error: Something went wrong", 1);
        }
    }
}else{
    //Coming from Index
    header("Location: sign_up_view_1.php");
    exit();
}

function addUserInfo(){
    $array = array(
        'gender' => $_POST['gender'],
        'dateofbirth' => $_POST['dateofbirth'],
        'phone_number' => $_POST['phone_number'],
        'street_number' => $_POST['street_number'],
        'street_name' => $_POST['street_name'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'zip' => $_POST['zip']
    );
    $returned = addInfo($array);
    if($returned != true){
        error($returned->getMessage(), 2);
    }
}

function createNewUser(){

    $_SESSION['uid'] = null;

    $array = array(
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
    );
    $returned = newUser($array);
    if(is_numeric($returned)){
        $_SESSION['uid'] = $returned;
    }else{
        error("Error: ". $returned, 1);
    }
}

function passwordsChecked(){
    if($_POST['password'] != $_POST['confirm_password']){
        error("Error: Passwords do not match", 1);
    }
}

function noneMissing(){
    foreach($_POST as $element){
        if(empty($element)){
            error("Error: One or more required fields are empty", 1);
        }
    }
}

function sanitized(){

    $array = array(

        'first_name' => FILTER_SANITIZE_STRING,
        'last_name' => FILTER_SANITIZE_STRING,
        'email' => FILTER_SANITIZE_EMAIL,
        'password' => FILTER_SANITIZE_STRING,
        'confirm_password' => FILTER_SANITIZE_STRING,
        'gender' => FILTER_SANITIZE_STRING,
        'dateofbirth' => FILTER_SANITIZE_STRING,
        'phone_number' => FILTER_SANITIZE_NUMBER_INT,
        'street_number' => FILTER_SANITIZE_NUMBER_INT,
        'street_name' => FILTER_SANITIZE_STRING,
        'city' => FILTER_SANITIZE_STRING,
        'state' => FILTER_SANITIZE_STRING,
        'zip' => FILTER_SANITIZE_NUMBER_INT
    );

    if(!filter_input_array(INPUT_POST, $array)){
        error("Error: Invalid entry.", 1);
    }

}

function error($message, $page){
    $_SESSION['errorMessage'] = $message;
    header("Location: sign_up_view_" . $page . ".php");
    exit();
}
