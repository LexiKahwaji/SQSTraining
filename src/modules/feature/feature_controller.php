<?php
/**
 * Created by PhpStorm.
 * User: Nick Sladic
 * Date: 11/19/18
 * Time: 20:04
 */
require_once('feature_model.php');
session_start();

if($_SESSION['role'] == "SUPERUSER" || $_SESSION['role'] == "ADMIN" || $_SESSION['role'] == "SUPERADMIN"){
    if(isset($_POST['apply'])){
        print("<pre>".print_r($_POST,true)."</pre>");
        $uid = $_POST['userid'];
        $yt = $_POST['youtubeErrors'];
        $gm = $_POST['googleMapErrors'];
        $gc = $_POST['groupCardErrors'];
        $pc = $_POST['profileCardErrors'];
        $pe = $_POST['profileEditErrors'];
        $limit = count($uid);
        for($x = 0; $x < $limit; $x++){
            $af = getAssignedId($uid[$x]);
            setFeatures($uid[$x],$yt[$x],$af[0]['id']);
            setFeatures($uid[$x],$gm[$x],$af[1]['id']);
            setFeatures($uid[$x],$gc[$x],$af[2]['id']);
            setFeatures($uid[$x],$pc[$x],$af[3]['id']);
            setFeatures($uid[$x],$pe[$x],$af[4]['id']);
        }
        $_SESSION['users'] = null;
        $_SESSION['youtube_errors'] = null;
        $_SESSION['googlemap_errors'] = null;
        $_SESSION['groupcard_errors'] = null;
        $_SESSION['profilecard_errors'] = null;
        $_SESSION['profileedit_errors'] = null;
        setup();
        header('Location: feature_view.php');
        exit();
    }
    else{
        setup();
        header('Location: feature_view.php');
        exit();
    }
}
else{
    header('Loacation: ../landing/landing_controller.php');
    exit();
}

function setup(){
    $users = getAllUsers();
    $newArray = array();
    foreach($users as $user){
        $temp = array();
        array_push($temp, $user);
        array_push($temp, getAssignFeatures($user['UID']));
        array_push($newArray,$temp);
    }
    $_SESSION['users'] = $newArray;
    $_SESSION['youtube_errors'] = getErrors("youtube");
    $_SESSION['googlemap_errors'] = getErrors("googlemap");
    $_SESSION['groupcard_errors'] = getErrors("groupcard");
    $_SESSION['profilecard_errors'] = getErrors("profilecard");
    $_SESSION['profileedit_errors'] = getErrors("profileedit");
}