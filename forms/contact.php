<?php
	$name = $_POST["name"];
	$email = $_POST["email"];
	$subject = $_POST["subject"];
	$content = $_POST["message"];

	$toEmail = "vedank.kulshrestha6@gmail.com";
	$mailHeaders = "From: " . $name . "<". $email .">\r\n";
	if(mail($toEmail, $subject, $content, $mailHeaders)) {
	    $message = "Your message has been sent. Thank you.";
	    $type = "success";
        echo "OK";
	}else{
        $message = "Unable to send your message!";
	    $type = "failed";
        echo "FAIL";
    }
?>