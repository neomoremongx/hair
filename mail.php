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
    $name = isset($_POST["contact-name"]) ? $_POST["contact-name"] : '';
    $email = isset($_POST["contact-email"]) ? $_POST["contact-email"] : '';
    $phone = isset($_POST["contact-phone"]) ? $_POST["contact-phone"] : '';
    $subject = isset($_POST["contact-subject"]) ? $_POST["contact-subject"] : '';
    $message = isset($_POST["contact-message"]) ? $_POST["contact-message"] : '';

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

        $formattedMessage = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #4a4a4a; background-color: #fdf2f8;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto;">
                <!-- Header -->
                <tr>
                    <td style="background: linear-gradient(135deg, #e75480, #f5c6aa); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">New Contact Inquiry</h1>
                        <p style="margin: 10px 0 0 0; font-size: 16px;">The Look Hair & Beauty Gallery | Making Women Feel Beautiful</p>
                    </td>
                </tr>
                
                <!-- Content -->
                <tr>
                    <td style="background: #fdf2f8; padding: 30px; border: 1px solid #fce7f3; border-top: none;">
                        <!-- Details Box -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: white; padding: 25px; border-radius: 8px; margin: 0 0 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #fce7f3;">
                                    <strong style="color: #e75480; width: 120px; display: inline-block;">From:</strong>
                                    <span>' . htmlspecialchars($name) . '</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #fce7f3;">
                                    <strong style="color: #e75480; width: 120px; display: inline-block;">Email:</strong>
                                    <span>' . htmlspecialchars($email) . '</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #fce7f3;">
                                    <strong style="color: #e75480; width: 120px; display: inline-block;">Phone:</strong>
                                    <span>' . htmlspecialchars($phone) . '</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0;">
                                    <strong style="color: #e75480; width: 120px; display: inline-block;">Subject:</strong>
                                    <span>' . htmlspecialchars($subject) . '</span>
                                </td>
                            </tr>
                        </table>
                        
                        <!-- Message Section -->
                        <h3 style="color: #e75480; margin: 0 0 10px 0;">Message:</h3>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background: white; padding: 20px; border-radius: 6px; border-left: 4px solid #e75480; margin: 0 0 20px 0;">
                            <tr>
                                <td>
                                    <p style="margin: 0; white-space: pre-line;">' . htmlspecialchars($message) . '</p>
                                </td>
                            </tr>
                        </table>
                        
                        <p style="margin: 20px 0 0 0;">Please contact <strong>' . htmlspecialchars($name) . '</strong> at ' . htmlspecialchars($email) . ' or ' . htmlspecialchars($phone) . ' to respond to this inquiry.</p>
                    </td>
                </tr>
                
                <!-- Footer -->
                <tr>
                    <td style="background: linear-gradient(135deg, #e75480, #f5c6aa); color: white; padding: 25px; text-align: center; border-radius: 0 0 8px 8px; margin-top: 20px;">
                        <p style="margin: 0; font-size: 14px;">
                            <strong>The Look Hair Gallery</strong><br>
                            234 MC Roode Road, Van der Hoff Park, Potchefstroom, South Africa<br>
                            Phone: 076 124 8895<br>
                            © ' . date('Y') . ' The Look Hair & Beauty Gallery. All rights reserved.
                        </p>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ';

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
            $autoReplyMail->setFrom('neomoremongx@gmail.com', 'The Look Hair & Beauty Gallery');
            $autoReplyMail->addAddress($email, $name); // Send to the client
            $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'The Look Hair & Beauty Gallery');

            //Content
            $autoReplyMail->isHTML(true);
            $autoReplyMail->Subject = "Thank You for Your Inquiry - The Look Hair & Beauty Gallery";
            
            $autoReplyMessage = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #4a4a4a; background-color: #fdf2f8;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #e75480, #f5c6aa); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; font-size: 28px; font-weight: bold;">Thank You for Contacting The Look Hair Gallery!</h1>
                            <p style="margin: 10px 0 0 0; font-size: 16px;">Making Women Feel Beautiful</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="background: #fdf2f8; padding: 30px; border: 1px solid #fce7f3; border-top: none;">
                            <div style="font-size: 18px; color: #e75480; margin-bottom: 20px; font-weight: bold;">Dear ' . htmlspecialchars($name) . ',</div>
                            
                            <p style="margin: 0 0 20px 0;">Thank you for reaching out to The Look Hair & Beauty Gallery. We have received your inquiry and our team will review your message promptly.</p>
                            
                            <!-- Inquiry Summary -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #e75480;">
                                <tr>
                                    <td>
                                        <h3 style="color: #e75480; margin: 0 0 15px 0;">Your Inquiry Summary</h3>
                                        <p style="margin: 5px 0;"><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>
                                        <p style="margin: 5px 0;"><strong>Contact Phone:</strong> ' . htmlspecialchars($phone) . '</p>
                                        <p style="margin: 5px 0;"><strong>Submitted:</strong> ' . date('F j, Y \a\t g:i A') . '</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 20px 0;"><strong>We typically respond within 24 hours.</strong></p>
                            
                            <!-- Salon Information -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #e75480;">
                                <tr>
                                    <td>
                                        <h3 style="color: #e75480; margin: 0 0 15px 0;">Salon Information</h3>
                                        <p style="margin: 5px 0;"><strong>Address:</strong> 234 MC Roode Road, Van der Hoff Park, Potchefstroom</p>
                                        <p style="margin: 5px 0;"><strong>Phone:</strong> 076 124 8895</p>
                                        <p style="margin: 5px 0;"><strong>Hours:</strong> Mon-Fri: 9:00 AM - 6:00 PM, Sat: 8:00 AM - 4:00 PM</p>
                                        <p style="margin: 5px 0;"><strong>Email:</strong> info@lookhairgallery.co.za</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 20px 0;">For urgent matters, please feel free to call us directly at 076 124 8895.</p>
                            
                            <p style="margin: 0;">We look forward to helping you look and feel beautiful!<br>
                            <strong>The Look Hair & Beauty Gallery Team</strong></p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #e75480, #f5c6aa); color: white; padding: 25px; text-align: center; border-radius: 0 0 8px 8px; margin-top: 20px;">
                            <p style="margin: 0; font-size: 14px;">
                                <strong>The Look Hair & Beauty Gallery</strong><br>
                                Making Women Feel Beautiful<br>
                                © ' . date('Y') . ' The Look Hair & Beauty Gallery. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>
            ';
            
            $autoReplyMail->Body = $autoReplyMessage;
            $autoReplyMail->send();
            
        } catch (Exception $e) {
            // Optional: Log error but don't show to user to avoid confusion
            error_log("Auto-reply failed: " . $autoReplyMail->ErrorInfo);
        }

        echo "
        <script> 
         alert('Thank you " . htmlspecialchars($name) . "! Your inquiry has been sent successfully. We will contact you shortly.');
         document.location.href = '/contact';
        </script>
        ";
        
    } catch (Exception $e) {
        // Error message
        echo "
        <script> 
         alert('Sorry, there was an error sending your inquiry. Please try again or contact us directly at 076 124 8895.');
         document.location.href = '/contact';
        </script>
        ";
    }
} else {
    // If not a POST request, redirect to home
    header("Location: /home");
    exit;
}

?>
