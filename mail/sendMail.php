<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use controllers\InvoiceController;
use database\UserDBC;
use entities\User;

require 'mail/src/Exception.php';
require 'mail/src/OAuth.php';
require 'mail/src/PHPMailer.php';
require 'mail/src/POP3.php';
require 'mail/src/SMTP.php';



/*
  * Sends the invoice to the customer
  * Will be sent as soon as the customer books a trip.
 * 
 * @author Vanessa Cajochen
 */
$mail = new PHPMailer(TRUE);

try {
   
    $userId = $_SESSION['userId'];
    $userDBC = new UserDBC();
    $user = $userDBC->findUserById($userId);    
    if(!$user){
        exit;
    }
    
    
    // Extraction of the customer variables
    $mailAddress = $user->getEmail();
    $gender = $user->getGender();
    $lastname = $user->getLastName();
    if($gender == "male") {
        $gender = "Mr.";
    } else {
        $gender = "Mrs.";
    }
    
    
    // Creation of the content of the email
   $mail->setFrom('mail.dreamtrips@gmail.com', 'Dream Trips');
   $mail->addAddress($mailAddress);
   $mail->Subject = 'Thank you for your booking';
   $mail->Body = '<p>Dear '.$gender.' '.$lastname.', </br></br>Thank you for choosing to book your trip with Dream Trips. We hope that you thoroughly enjoy the experience.</br></br>Attached you will find the invoice for your booked trip.</br>Please transfer the amount within the next 30 days.</br></br>Sincerely,</br>Dream Trips</p>';
   
   $pdf_url = 'pdf/tempInvoices/'.$_SESSION['tripId'].'.pdf';
   $mail->addAttachment($pdf_url, 'Invoice.pdf');
   

   // Connection details to GMAIL account
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->isHTML(true);
  
    
     // Username (email address)
     $mail->Username = 'mail.dreamtrips@gmail.com';

     // Google account password
     $mail->Password = 'Wnb-6rm-qdT-ttq';
   
   $mail->send();
}
catch (Exception $e)
{
   echo $e->errorMessage();
}
catch (\Exception $e)
{
   echo $e->getMessage();
}





   
   
   
