<?php

    function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $body) {
       
       $file = $path.$filename;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $name = basename($file);
       
       $eol = PHP_EOL;

        // Basic headers
        $header = "From: ".$from_name." <".$from_mail.">".$eol;
        $header .= "Reply-To: ".$replyto.$eol;
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"";
        
        // Put everything else in $message
        $message = "--".$uid.$eol;
        $message .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
        $message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
        $message .= $body.$eol;
        $message .= "--".$uid.$eol;
        $message .= "Content-Type: application/pdf; name=\"".$filename."\"".$eol;
        $message .= "Content-Transfer-Encoding: base64".$eol;
        $message .= "Content-Disposition: attachment; filename=\"".$filename."\"".$eol;
        $message .= $content.$eol;
        $message .= "--".$uid."--";
       
       if (mail($mailto, $subject, $message, $header)) {
          $message = "Your message has been sent. Thank you.";
          $type = "success";
          echo "OK";
       } else {
           $message = "Unable to send your message!";
           $type = "failed";
           //print_r(error_get_last());
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
       $subject = "Job Application Received for " . $position . " position:";
       $content = "<p>You have received a job application for below position</p><p><strong>Job Position</strong>: ".$position."</p><div>Please find the details of the candidate below: <br/><br/><strong>Name:</strong> ".$name."<br/><strong>Email:</strong> ".$email."<br/><strong>Mobile No.:</strong> " . $mobile . "<br/><strong>Message:</strong> " . $message . "<br/><br/>Please find the attachment for the resume.</div>" ;
        
        $attachment = chunk_split(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
        $filename = $_FILES['file']['name'];
        $path=$_FILES["file"]["tmp_name"];
        
        $toEmail = "vedank.kulshrestha6@gmail.com";
        
        mail_attachment($filename, $path, $toEmail, $email, $name, $email, $subject, $content);
        
    } else {
        
        echo "Method Not Allowed";
        
    }	
?>