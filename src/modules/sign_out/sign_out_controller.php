<?php
/**
 * Created by PhpStorm.
 * User: luke
 * Date: 10/24/18
 * Time: 8:02 PM
 */

session_start();

// destroy session variable
session_destroy();

// reroute to index
header("Location: ../../../index.php");
exit();