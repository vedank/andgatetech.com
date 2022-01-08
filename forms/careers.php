<?php

    function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
       $file = $path;
       $file_size = filesize($file);
       $handle = fopen($file, "r");
       $content = fread($handle, $file_size);
       fclose($handle);
       $content = chunk_split(base64_encode($content));
       $uid = md5(uniqid(time()));
       $header = "From: ".$from_name.
       " <".$from_mail.
       ">\r\n";
       $header .= "Reply-To: ".$replyto.
       "\r\n";
       $header .= "MIME-Version: 1.0\r\n";
       $header .= "Content-Type: multipart/mixed; boundary=\"".$uid.
       "\"\r\n\r\n";
       $header .= "This is a multi-part message in MIME format.\r\n";
       $header .= "--".$uid.
       "\r\n";
       $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
       $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
       $header .= $message.
       "\r\n\r\n";
       $header .= "--".$uid.
       "\r\n";
       $header .= "Content-Type: application/octet-stream; name=\"".$filename.
       "\"\r\n"; // use different content types here
       $header .= "Content-Transfer-Encoding: base64\r\n";
       $header .= "Content-Disposition: attachment; filename=\"".$filename.
       "\"\r\n\r\n";
       $header .= $content.
       "\r\n\r\n";
       $header .= "--".$uid.
       "--";
       if (mail($mailto, $subject, "", $header)) {
          $message = "Your message has been sent. Thank you.";
          $type = "success";
          echo "OK";
       } else {
           $message = "Unable to send your message!";
           $type = "failed";
           echo "FAIL";
       }
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
       $name = $_POST['name'];
       $email = $_POST['email'];
       $mobile = $_POST['mobile'];
       $position = $_POST['position'];
       $message = $_POST['message'];
       $resume = $_FILES['file'];
       $subject = "Job Application Received for " . $position . " position.";
       $content = "You have received a job application for below position.\n\nJob Position: " . $position
                . "\n\nPlease find the details of the candidate below: \n\nName: " . $name . "\nEmail: " . $email . "\nMobile No.: " . $mobile . "\nMessage: " . $message . "\n\nPlease find the attachment for the resume." ;
        
        $attachment = chunk_split(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
        $filename = $_FILES['file']['name'];
        $path=$_FILES["file"]["tmp_name"];
        
        $toEmail = "vedank.kulshrestha6@gmail.com";
        
        mail_attachment($filename, $path, $toEmail, $email, $name, $email, $subject, $content);
        
    } else {
        
        echo "Method Not Allowed";
        
    }
	
?>
