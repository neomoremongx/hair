 <?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if form fields exist before accessing them
    $name = isset($_POST["enquiry-name"]) ? $_POST["enquiry-name"] : '';
    $email = isset($_POST["enquiry-email"]) ? $_POST["enquiry-email"] : '';
    $phone = isset($_POST["enquiry-phone"]) ? $_POST["enquiry-phone"] : '';
    $subject = isset($_POST["enquiry-subject"]) ? $_POST["enquiry-subject"] : '';
    $message = isset($_POST["enquiry-message"]) ? $_POST["enquiry-message"] : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "
        <script>
         alert('Error: Please fill in all required fields.');
         document.location.href = 'index.html';
        </script>
        ";
        exit;
    }

    try {
        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();                              //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;             //Enable SMTP authentication
        $mail->Username   = 'neomoremongx@gmail.com';   //SMTP write your email
        $mail->Password   = 'pxcosqmpbjlodmyw';      //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
        $mail->Port       = 465;                                    

        //Recipients
        $mail->setFrom($email, $name); // Sender Email and name
        $mail->addAddress('neomoremongx@gmail.com');     //Add a recipient email  
        $mail->addReplyTo($email, $name); // reply to sender email

        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = "Contact Form: " . $subject;   // email subject headings

        $formattedMessage = "
        <html>
        <head>
            <style>
                :root {
                    --primary: #e75480;
                    --primary-light: #f8b1c4;
                    --peach: #ffd8c8;
                    --peach-light: #ffe8e0;
                    --secondary: #ffffff;
                    --accent: #000000;
                    --light-pink: #fdf2f8;
                    --text: #4a4a4a;
                    --border: #fce7f3;
                }
                
                body {
                    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                    line-height: 1.6;
                    color: var(--text);
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                
                .header {
                    background: linear-gradient(135deg, var(--primary), var(--peach));
                    color: white;
                    padding: 30px;
                    text-align: center;
                    border-radius: 8px 8px 0 0;
                }
                
                .content {
                    background: var(--light-pink);
                    padding: 30px;
                    border: 1px solid var(--border);
                    border-top: none;
                }
                
                .details {
                    background: white;
                    padding: 25px;
                    border-radius: 8px;
                    margin: 20px 0;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                
                .detail-row {
                    display: flex;
                    margin-bottom: 12px;
                    padding: 8px 0;
                    border-bottom: 1px solid var(--border);
                }
                
                .detail-label {
                    font-weight: bold;
                    color: var(--primary);
                    width: 120px;
                    flex-shrink: 0;
                }
                
                .footer {
                    background: linear-gradient(135deg, var(--primary), var(--peach));
                    color: white;
                    padding: 25px;
                    text-align: center;
                    border-radius: 0 0 8px 8px;
                    margin-top: 20px;
                }
                
                .message-box {
                    background: white;
                    padding: 20px;
                    border-radius: 6px;
                    border-left: 4px solid var(--primary);
                    margin: 15px 0;
                }
            </style>
        </head>
        <body>
            <div class=\"header\">
                <h1>New Contact Inquiry</h1>
                <p>The Look Hair Gallery | Making Women Feel Beautiful</p>
            </div>
            
            <div class=\"content\">
                <div class=\"details\">
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">From:</span>
                        <span class=\"detail-value\">" . htmlspecialchars($name) . "</span>
                    </div>
                    
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Email:</span>
                        <span class=\"detail-value\">" . htmlspecialchars($email) . "</span>
                    </div>
                    
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Phone:</span>
                        <span class=\"detail-value\">" . htmlspecialchars($phone) . "</span>
                    </div>
                    
                    <div class=\"detail-row\">
                        <span class=\"detail-label\">Subject:</span>
                        <span class=\"detail-value\">" . htmlspecialchars($subject) . "</span>
                    </div>
                </div>
                
                <h3 style=\"color: var(--primary); margin-bottom: 10px;\">Message:</h3>
                <div class=\"message-box\">
                    <p style=\"margin: 0; white-space: pre-line;\">" . htmlspecialchars($message) . "</p>
                </div>
                
                <p style=\"margin-top: 20px;\">Please contact <strong>" . htmlspecialchars($name) . "</strong> at " . htmlspecialchars($email) . " or " . htmlspecialchars($phone) . " to respond to this inquiry.</p>
            </div>
            
            <div class=\"footer\">
                <p style=\"margin: 0; font-size: 14px;\">
                    <strong>The Look Hair Gallery</strong><br>
                    234 Mc Roode Avenue, Potchefstroom, South Africa<br>
                    Phone: 076 124 8895<br>
                    © " . date('Y') . " The Look Hair Gallery. All rights reserved.
                </p>
            </div>
        </body>
        </html>
        ";

        $mail->Body = $formattedMessage; //email message
        // Success sent message alert
        $mail->send();

        // Auto-reply to client
        $autoReplyMail = new PHPMailer(true);

        try {
            //Server settings - same as your main email
            $autoReplyMail->isSMTP();
            $autoReplyMail->Host       = 'smtp.gmail.com';
            $autoReplyMail->SMTPAuth   = true;
            $autoReplyMail->Username   = 'neomoremongx@gmail.com';
            $autoReplyMail->Password   = 'pxcosqmpbjlodmyw';
            $autoReplyMail->SMTPSecure = 'ssl';
            $autoReplyMail->Port       = 465;

            //Recipients
            $autoReplyMail->setFrom('neomoremongx@gmail.com', 'The Look Hair Gallery');
            $autoReplyMail->addAddress($email, $name); // Send to the client
            $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'The Look Hair Gallery');

            //Content
            $autoReplyMail->isHTML(true);
            $autoReplyMail->Subject = "Thank You for Your Inquiry - The Look Hair Gallery";
            
            $autoReplyMessage = "
            <html>
            <head>
                <style>
                    :root {
                        --primary: #e75480;
                        --primary-light: #f8b1c4;
                        --peach: #ffd8c8;
                        --peach-light: #ffe8e0;
                        --secondary: #ffffff;
                        --accent: #000000;
                        --light-pink: #fdf2f8;
                        --text: #4a4a4a;
                        --border: #fce7f3;
                    }
                    
                    body {
                        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                        line-height: 1.6;
                        color: var(--text);
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                    }
                    
                    .header {
                        background: linear-gradient(135deg, var(--primary), var(--peach));
                        color: white;
                        padding: 30px;
                        text-align: center;
                        border-radius: 8px 8px 0 0;
                    }
                    
                    .content {
                        background: var(--light-pink);
                        padding: 30px;
                        border: 1px solid var(--border);
                        border-top: none;
                    }
                    
                    .footer {
                        background: linear-gradient(135deg, var(--primary), var(--peach));
                        color: white;
                        padding: 25px;
                        text-align: center;
                        border-radius: 0 0 8px 8px;
                        margin-top: 20px;
                    }
                    
                    .thank-you {
                        font-size: 18px;
                        color: var(--primary);
                        margin-bottom: 20px;
                        font-weight: bold;
                    }
                    
                    .contact-info {
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        margin: 20px 0;
                        border-left: 4px solid var(--primary);
                    }
                </style>
            </head>
            <body>
                <div class=\"header\">
                    <h1>Thank You for Contacting The Look Hair Gallery!</h1>
                    <p>Making Women Feel Beautiful</p>
                </div>
                
                <div class=\"content\">
                    <div class=\"thank-you\">Dear " . htmlspecialchars($name) . ",</div>
                    
                    <p>Thank you for reaching out to The Look Hair Gallery. We have received your inquiry and our team will review your message promptly.</p>
                    
                    <div class=\"contact-info\">
                        <h3 style=\"color: var(--primary); margin-top: 0;\">Your Inquiry Summary</h3>
                        <p style=\"margin: 5px 0;\"><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
                        <p style=\"margin: 5px 0;\"><strong>Contact Phone:</strong> " . htmlspecialchars($phone) . "</p>
                        <p style=\"margin: 5px 0;\"><strong>Submitted:</strong> " . date('F j, Y \a\t g:i A') . "</p>
                    </div>
                    
                    <p><strong>We typically respond within 24 hours.</strong></p>
                    
                    <div class=\"contact-info\">
                        <h3 style=\"color: var(--primary); margin-top: 0;\">Salon Information</h3>
                        <p style=\"margin: 5px 0;\"><strong>Address:</strong> 234 Mc Roode Avenue, Potchefstroom</p>
                        <p style=\"margin: 5px 0;\"><strong>Phone:</strong> 076 124 8895</p>
                        <p style=\"margin: 5px 0;\"><strong>Hours:</strong> Mon-Fri: 9:00 AM - 6:00 PM, Sat: 8:00 AM - 4:00 PM</p>
                        <p style=\"margin: 5px 0;\"><strong>Email:</strong> [Your Salon Email]</p>
                    </div>
                    
                    <p>For urgent matters, please feel free to call us directly at 076 124 8895.</p>
                    
                    <p>We look forward to helping you look and feel beautiful!<br>
                    <strong>The Look Hair Gallery Team</strong></p>
                </div>
                
                <div class=\"footer\">
                    <p style=\"margin: 0; font-size: 14px;\">
                        <strong>The Look Hair Gallery</strong><br>
                        Making Women Feel Beautiful Since 2023<br>
                        © " . date('Y') . " The Look Hair Gallery. All rights reserved.
                    </p>
                </div>
            </body>
            </html>
            ";
            
            $autoReplyMail->Body = $autoReplyMessage;
            $autoReplyMail->send();
            
        } catch (Exception $e) {
            // Optional: Log error but don't show to user to avoid confusion
            error_log("Auto-reply failed: " . $autoReplyMail->ErrorInfo);
        }

        echo "
        <script> 
         alert('Thank you " . htmlspecialchars($name) . "! Your inquiry has been sent successfully. We will contact you shortly.');
         document.location.href = 'index.html';
        </script>
        ";
        
    } catch (Exception $e) {
        // Error message
        echo "
        <script> 
         alert('Sorry, there was an error sending your inquiry. Please try again or contact us directly at 076 124 8895.');
         document.location.href = 'index.html';
        </script>
        ";
    }
} else {
    // If not a POST request, redirect to home
    header("Location: index.html");
    exit;
}

?>