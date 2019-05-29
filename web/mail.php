<?php
  require __DIR__ . '/../vendor/autoload.php';

  $email = new \SendGrid\Mail\Mail(); 
  $email->setFrom("chea.reaksmey@gmail.com", "Example User");
  $email->setSubject("Sending with Twilio SendGrid is Fun");
  $email->addTo("chea.reaksmey@gmail.com", "Example User");
  $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
  $email->addContent(
      "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
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