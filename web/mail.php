<?php
  require __DIR__ . '/../vendor/autoload.php';

  $receive_email = $_POST['email'];
  $full_name = $_POST['full_name'];

  $email = new \SendGrid\Mail\Mail(); 
  $email->setFrom("info@immigrationtrust.co.nz", "Immigration Trust");
  $email->setSubject("Confirmation of Payment");
  $email->addTo($receive_email, $full_name);
  $email->addCc("chea.reaksmey@gmail.com", "Ivy");
  $email->addDynamicTemplateData(
    new \SendGrid\Mail\Substitution("name", $full_name)
  );
  $email->addDynamicTemplateData(
    new \SendGrid\Mail\Substitution("email", $receive_email)
  );
  $email->setTemplateId(
    new \SendGrid\Mail\TemplateId("d-335893beeb7e4f60a426bc34dadaf73e")
  );
  $sendgrid = new \SendGrid("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");
  try {
      $response = $sendgrid->send($email);
      print $response->statusCode() . "\n";
      print_r($response->headers());
      print $response->body() . "\n";
  } catch (Exception $e) {
      echo 'Caught exception: '. $e->getMessage() ."\n";
  }
   // data sent in header are in JSON format
//    header('Content-Type: application/json');
//    // takes the value from variables and Post the data
//    $name = $_POST['name'];
//    $email = $_POST['email'];
//    $contact = $_POST['contact'];
//    $postmessage = $_POST['message'];  
//    $to = "chea.reaksmey@gmail.com";
//    $subject = "Contact Us";
//    // Email Template
//    $message = "<b>Name : </b>". $name ."<br>";
//    $message .= "<b>Contact Number : </b>".$contact."<br>";
//    $message .= "<b>Email Address : </b>".$email."<br>";
//    $message .= "<b>Message : </b>".$postmessage."<br>";

//    $header = "From:"+$email+" \r\n";
//    $header .= "MIME-Version: 1.0\r\n";
//    $header .= "Content-type: text/html\r\n";
//    $retval = mail ($to,$subject,$message,$header);
//    // message Notification
//    if( $retval == true ) {
//       echo json_encode(array(
//          'success'=> true,
//          'message' => 'Message sent successfully'
//       ));
//    }else {
//       echo json_encode(array(
//          'error'=> true,
//          'message' => 'Error sending message'
//       ));
//    }
?>