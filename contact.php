<?php
    session_start();
    if($_SESSION['captcha'] != $_POST['captcha']) {
        echo "BADCAPTCHA";
        exit;    
    }
    $to = "YOUREMAILADDRESS";
    
    
    // These four fields are for the email template. You can edit them to your liking.
    define("mail_from", "YOURNAME <noreply@YOUR.WEBSITE>");
    define("site_title", "YOUR NAME | Developer Portfolio");
    define("site_icon", "https://www.shadowed.xyz/portfolio/images/ShadowedSoul_150x150.png"); // shameless plug
    define("site_color", "orange");

    $subject = $_POST['subject'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $headers = "From: Portfolio Form <noreply@YOUR.WEBSITE>\r\n";
    $headers .= "Reply-To: " . $name . " <" . $email . ">\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html; charset=UTF-8\r\n";
    $timeZone = new DateTimeZone('America/Chicago'); // set yours here! https://www.php.net/manual/en/timezones.php
    $dateTime = new DateTime('now', $timeZone);
    $date = $dateTime->format("F j, Y, g:i A");
    
    // !!! EDIT HERE
    $footer = "<p style=\"margin-top: 50px; font-size: 10px; color: #999; text-align: center; width: 100%;\">This message was sent from the "
    +"<a href=\"https://www.mtryoutlander.github.io\" target=\"_blank\">contact form on my portfolio.</p>";
    
    // !!! HERE TOO
    $signature = "-John Smith,<br>".
    "----------------------<br>" .
    "My Title<br>" .
    "Generic Company Name<br>" .
    "----------------------<br>" .
    "Web: <a href=\"https://www.mtryoutlander.github.io\" target=\"_blank\">https://www.mtryoutlander.github.io/</a><br>" .
    "Phone: <a href=\"tel:15555555555\">+1 (555) 555-5555</a><br>" .
    "Shadowed Studios Twitter: <a href=\"https://www.twitter.com/shadowedstudios\" target=\"_blank\">https://www.shadowed.games/twitter</a><br>" . // plug
    "Unity Asset Store: <a href=\"https://www.shadowed.games/uas\" target=\"_blank\">https://www.shadowed.games/uas</a><br>" . // plug
    "My Portfolio: <a href=\"https://www.shadowed.xyz/portfolio/\" target=\"_blank\">https://www.shadowed.xyz/portfolio/</a>"; // plug
    
    // !!! HERE TOO!
    $message = "<p>" . $_POST['name']. ",<br>Thank you for reaching out! I will get back to you as soon as possible.<br>"
    +" In the mean time, make sure to check out our <a href=\"https://www.shadowed.games/twitter\" target=\"_blank\">Twitter</a> and assets on the "
    +"<a href=\"https://www.shadowed.games/uas\" target=\"_blank\">Unity Asset Store!</a><br><br>" . $signature . "<br><br><pre>Message received at: " . $date . ". <br>"
    +"Original message: <br> ".$_POST['message']."</pre>";

    // GO AWAY!

    incoming($_POST['name'], $to, "[Portfolio]: " . $_POST['subject'], "<p>A message has come through the portfolio contact form.<br> <pre><b>Date:</b> ".$date." <br>\n<b>IP Address:</b> ".$_SERVER['REMOTE_ADDR']." <br>\n<b>Visitor Name:</b> ".$name." <br>\n<b>Visitor Email:</b> ".$email." <br>\n<b>Message:</b></pre>" . $_POST['message'] . "<br><br>", $footer);

    outgoing($_POST['email'], "Contact Request Received", $message, $footer);

    echo("<p style=\"font-weight: bold; color: green; text-align: center; width: 100%;\">Mail sent!</p>");   
        
    function outgoing($email, $subject, $message, $footer)
    {
        $headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" .  'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        //$email_template = str_replace('%title%', $site_title, file_get_contents('outgoing-email-template.html'));
        $email_template = str_replace('%site_color%', site_color, file_get_contents('outgoing-email-template.html'));
        $email_template = str_replace('%icon%', site_icon, $email_template);
        //$email_template = str_replace('%site_color%', $site_color, $email_template);
        $email_template = str_replace('%subject%', $subject, $email_template);
        $email_template = str_replace('%contents%', $message, $email_template);
        $email_template = str_replace('%footer%', $footer, $email_template);
        mail($email, $subject, $email_template, $headers);
    }
    
    function incoming($from, $email, $subject, $message, $footer)
    {
        $headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" .  'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        $email_template = str_replace('%site_color%', site_color, file_get_contents('outgoing-email-template.html'));
        //$email_template = str_replace('%title%', $site_title, file_get_contents('outgoing-email-template.html'));
        $email_template = str_replace('%icon%', site_icon, $email_template);
        //$email_template = str_replace('%site_color%', $site_color, $email_template);
        $email_template = str_replace('%subject%', $subject, $email_template);
        $email_template = str_replace('%contents%', $message, $email_template);
        $email_template = str_replace('%footer%', $footer, $email_template);
        mail($email, $subject, $email_template, $headers);
    }
?>
