<?php
/**
 * AccountVerification class
 */


require_once(__DIR__.'/../config/config.php');


// https://code.tutsplus.com/tutorials/how-to-implement-email-verification-for-new-members--net-3824 


/**
 * Class to allow creation of a verification email to be sent to a user when they
 * register with the platform.  This is class is designed to be able to extend
 * its verification method to other types (i.e text message, phone call, carrier pigeon) but for
 * now will only be able to send an email verification.
 * @author Stephen Ritchie <stephen.ritchie@uky.edu>
 * @version GIT: $Id$
 */
class AccountVerification {
    /**
     * @var string ID number of the user.
     */
    private $id;

    /**
     * @var string Verification type (email, sms, phone), but since no other methods are currently implemented
     * it's always overridden to be an email.
     */
    private $verificationType;

    /**
     * @var string Email address of the user.
     */
    private $email;

    /**
     * Constructor that should be used.
     * @param string $id ID number of the user. 
     * @param string $type Verification method (currently always overridden to 'email')
     * @return void
     */
    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->verificationType = "email"; //overriding verification type to email since that's the only method currently implemented
        
        $this->init();
    }

    /**
     * Initializes the object
     * @return void
     */
    private function init(){
        $this->email = $this->getEmail($this->id); // getting the email address from the database  
    }


    public function sendVerification()
    {
    }

    public function sendVerification_TEST()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $to = $this->email;
        $subject = "[TEST] Verify your email address";
        $url = $this->createURL($this->email);
        $message = $this->createMessage($url);

        echo '<h1>Account Verification Email</h1><hr>';
        echo '<p><b>to:</b> '.$to.'</p>';
        echo '<p><b>subject:</b> '.$subject.'</p>';
        echo '<p><b>url:</b> '.$url.'</p>';
        

        /*if (!mail($to, $subject, $message, $headers)){
            echo "<h1>EMAIL SENDING FAILED</h1>";
        }*/

        echo '<p>Email preview will be populated below...</p><br>';
        echo $message;
    }

    /**
     * Description
     * @param type $url 
     * @return type
     */
    private function createMessage($url){

        $msg = "<!DOCTYPE html><html><body>";
        $msg .= "<h2>Welcome to SQS Training!</h2>";
        $msg .= "<p>To finish setting up your account, we just need to make sure this email address is yours.</p>";
        $msg .= "<a href=\"".$url."\">Verify Email</a>";
        $msg .= "<p>Thanks,</p>";
        $msg .= "<p>The SQS Training Team</p>";
        $msg .= "</body></html>";

        return (string)$msg;
    }

    /**
     * Retrieves a user's email address from the database.
     * @param string $id user id number 
     * @return string email address of user
     * @todo Add database support.
     */
    private function getEmail($id){
        
        // TODO: Get the user's email from the database instead of hard-coding.
        $email = "stephenfritchie@gmail.com";

        return (string)$email;    
    }

    /**
     * Creates a unique url that is associated with the user via the database
     * @param string $email user's email address
     * @return string unique url that has been associated with the user
     * @todo Add database support.
     */
    private function createURL($email){

        $filename = "src/modules/verify/verify_controller.php"; // TODO: Figure out how to better define the URL handler than a hard-coded value.
        $hash = md5( rand(0,1000) ); // TODO: Get the user's hash from the database based instead of hard-coding.

        // TODO: Verify that the HTTP_POST server variable creates the right value for the url.
        return 'http://'.$_SERVER['HTTP_HOST'].'/'.$filename.'?email='.$email.'&hash='.$hash;
    }
}

$verification = new AccountVerification("1234", 'email');
$verification->sendVerification_TEST();

?>