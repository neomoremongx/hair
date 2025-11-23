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
   $name = isset($_POST["booking-name"]) ? $_POST["booking-name"] : '';
   $subject = "Appointment Booking - The Look Hair Gallery";
   $email = isset($_POST["booking-email"]) ? $_POST["booking-email"] : '';
   $phone = isset($_POST["booking-phone"]) ? $_POST["booking-phone"] : '';
   $service_type = isset($_POST["service-type"]) ? $_POST["service-type"] : '';
   $preferred_date = isset($_POST["preferred-date"]) ? $_POST["preferred-date"] : '';
   $preferred_time = isset($_POST["preferred-time"]) ? $_POST["preferred-time"] : '';
   $special_requests = isset($_POST["special-requests"]) ? $_POST["special-requests"] : '';

   // Validate required fields
   if (empty($name) || empty($email) || empty($phone) || empty($service_type) || empty($preferred_date) || empty($preferred_time)) {
       echo "
       <script>
        alert('Error: Please fill in all required fields.');
        document.location.href = 'home.html';
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
       $mail->Subject = $subject;   // email subject headings
       
       // Map service values to readable text
       $service_types = [
           'haircut' => 'Haircut & Styling',
           'coloring' => 'Hair Coloring',
           'treatment' => 'Hair Treatment',
           'special' => 'Special Occasion',
           'keratin' => 'Keratin Treatment',
           'extensions' => 'Extensions',
           'consultation' => 'Consultation'
       ];
       
       $service_text = isset($service_types[$service_type]) ? $service_types[$service_type] : $service_type;

       $booking_message = "
       <html>
       <head>
           <style>
               body {
                   font-family: 'Arial', sans-serif;
                   line-height: 1.6;
                   color: #333;
                   max-width: 600px;
                   margin: 0 auto;
                   padding: 20px;
               }
               .header {
                   background: linear-gradient(135deg, #e75480, #f5c6aa);
                   color: white;
                   padding: 30px;
                   text-align: center;
                   border-radius: 8px 8px 0 0;
               }
               .content {
                   background: #fdf2f8;
                   padding: 30px;
                   border: 1px solid #fce7f3;
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
                   border-bottom: 1px solid #f0f0f0;
               }
               .detail-label {
                   font-weight: bold;
                   color: #e75480;
                   width: 180px;
                   flex-shrink: 0;
               }
               .detail-value {
                   color: #555;
               }
               .footer {
                   background: linear-gradient(135deg, #e75480, #f5c6aa);
                   color: white;
                   padding: 25px;
                   text-align: center;
                   border-radius: 0 0 8px 8px;
                   margin-top: 20px;
               }
               .special-requests {
                   background: #ffe8e0;
                   padding: 20px;
                   border-left: 4px solid #e75480;
                   margin: 15px 0;
                   border-radius: 4px;
               }
               .thank-you {
                   font-size: 18px;
                   color: #e75480;
                   text-align: center;
                   margin-bottom: 25px;
                   font-weight: bold;
               }
               .salon-info {
                   background: #f8f8f8;
                   padding: 20px;
                   border-radius: 6px;
                   margin: 20px 0;
               }
           </style>
       </head>
       <body>
           <div class=\"header\">
               <h1>Appointment Booking Request</h1>
               <p>The Look Hair & Beauty Gallery | Making Women Feel Beautiful</p>
           </div>
           
           <div class=\"content\">
               <div class=\"thank-you\">
                   New Appointment Request Received!
               </div>
               
               <p>You have received a new appointment booking request from <strong>{$name}</strong>.</p>
               
               <div class=\"details\">
                   <h3 style=\"color: #e75480; margin-top: 0; text-align: center;\">Appointment Details</h3>
                   
                   <div class=\"detail-row\">
                       <span class=\"detail-label\">Client Name:</span>
                       <span class=\"detail-value\">{$name}</span>
                   </div>
                   
                   <div class=\"detail-row\">
                       <span class=\"detail-label\">Service Type:</span>
                       <span class=\"detail-value\">{$service_text}</span>
                   </div>
                   
                   <div class=\"detail-row\">
                       <span class=\"detail-label\">Preferred Date:</span>
                       <span class=\"detail-value\">" . date('F j, Y', strtotime($preferred_date)) . "</span>
                   </div>
                   
                   <div class=\"detail-row\">
                       <span class=\"detail-label\">Preferred Time:</span>
                       <span class=\"detail-value\">{$preferred_time}</span>
                   </div>
                   
                   <div class=\"detail-row\">
                       <span class=\"detail-label\">Contact Email:</span>
                       <span class=\"detail-value\">{$email}</span>
                   </div>
                   
                   <div class=\"detail-row\">
                       <span class=\"detail-label\">Contact Phone:</span>
                       <span class=\"detail-value\">{$phone}</span>
                   </div>
                   
 
               </div>
               
               " . (!empty($special_requests) ? "
               <div class=\"special-requests\">
                   <h4 style=\"color: #e75480; margin-top: 0;\">Special Requests & Notes</h4>
                   <p style=\"margin: 0; font-style: italic; white-space: pre-line;\">{$special_requests}</p>
               </div>
               " : "") . "

               <p>Please contact <strong>{$name}</strong> at {$email} or {$phone} to confirm this appointment.</p>
           </div>
           
           <div class=\"footer\">
               <p style=\"margin: 0; font-size: 14px;\">
                   <strong>The Look Hair & Beauty Gallery</strong>
                   <br>
                   234 Mc Roode Avenue, Potchefstroom, South Africa
                   <br>
                   Phone: 076 124 8895
                   <br>
                   © " . date('Y') . " The Look Hair Gallery. All rights reserved.
               </p>
           </div>
       </body>
       </html>
       ";

       // Date validation
       $today = date('Y-m-d');
       if ($preferred_date < $today) {
           echo "
           <script>
            alert('Error: Please select a date that is today or in the future. You cannot book appointments for past dates.');
            document.location.href = '/home';
           </script>
           ";
           exit;
       }

       // Success sent message alert
       $mail->Body = $booking_message;
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
           $autoReplyMail->Subject = "Appointment Request Received - The Look Hair & Beauty Gallery";
           
           $autoReplyMessage = "
           <html>
           <head>
               <style>
                   body { 
                       font-family: Arial, sans-serif; 
                       line-height: 1.6; 
                       color: #333; 
                       max-width: 600px;
                       margin: 0 auto;
                       padding: 20px;
                   }
                   .header { 
                       background: linear-gradient(135deg, #e75480, #f5c6aa);
                       color: white; 
                       padding: 30px; 
                       text-align: center; 
                       border-radius: 8px 8px 0 0;
                   }
                   .content { 
                       padding: 30px; 
                       background: #fdf2f8; 
                       border: 1px solid #fce7f3;
                       border-top: none;
                   }
                   .footer { 
                       background: linear-gradient(135deg, #e75480, #f5c6aa);
                       color: white; 
                       padding: 25px; 
                       text-align: center; 
                       border-radius: 0 0 8px 8px;
                       margin-top: 20px;
                   }
                   .appointment-details {
                       background: white;
                       padding: 20px;
                       border-radius: 8px;
                       margin: 20px 0;
                       border-left: 4px solid #e75480;
                   }
                   .thank-you {
                       font-size: 18px;
                       color: #e75480;
                       margin-bottom: 20px;
                       font-weight: bold;
                   }
                   .contact-info {
                       background: white;
                       padding: 20px;
                       border-radius: 8px;
                       margin: 20px 0;
                       border-left: 4px solid #e75480;
                   }
               </style>
           </head>
           <body>
               <div class=\"header\">
                   <h2>Thank You for Your Appointment Request!</h2>
               </div>
               <div class=\"content\">
                   <div class=\"thank-you\">Dear {$name},</div>
                   
                   <p>Thank you for choosing The Look Hair & Beauty Gallery! We have received your appointment request for <strong>{$service_text}</strong> on <strong>" . date('F j, Y', strtotime($preferred_date)) . "</strong> at <strong>{$preferred_time}</strong>.</p>
                   
                   <div class=\"appointment-details\">
                       <h3 style=\"color: #e75480; margin-top: 0;\">Your Appointment Request</h3>
                       <p style=\"margin: 5px 0;\"><strong>Service:</strong> {$service_text}</p>
                       <p style=\"margin: 5px 0;\"><strong>Date:</strong> " . date('F j, Y', strtotime($preferred_date)) . "</p>
                       <p style=\"margin: 5px 0;\"><strong>Time:</strong> {$preferred_time}</p>
                       <p style=\"margin: 5px 0;\"><strong>Phone:</strong> {$phone}</p>
                       <p style=\"margin: 5px 0;\"><strong>Submitted:</strong> " . date('F j, Y \a\t g:i A') . "</p>
                   </div>
                   
                   <p><strong>We will contact you within 24 hours to confirm your appointment.</strong></p>
                   
                   <div class=\"contact-info\">
                       <h3 style=\"color: #e75480; margin-top: 0;\">Salon Information</h3>
                       <p style=\"margin: 5px 0;\"><strong>Address:</strong> 234 Mc Roode Avenue, Potchefstroom</p>
                       <p style=\"margin: 5px 0;\"><strong>Phone:</strong> 076 124 8895</p>
                       <p style=\"margin: 5px 0;\"><strong>Hours:</strong> Mon-Fri: 9:00 AM - 6:00 PM, Sat: 8:00 AM - 4:00 PM</p>
                   </div>
                   
                   <p>For any questions or to make changes to your appointment, please call us at 076 124 8895.</p>
                   
                   <p>We look forward to making you feel beautiful!<br>
                   <strong>The Look Hair & Beauty Gallery Team</strong></p>
               </div>
               <div class=\"footer\">
                   <p style=\"margin: 0; font-size: 14px;\">
                       <strong>The Look Hair Gallery</strong><br>
                       Making Women Feel Beautiful<br>
                       © " . date('Y') . " The Look Hair & Beauty Gallery. All rights reserved.
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

       // Success message
       echo "
       <script> 
        alert('Thank you {$name}! Your appointment request for {$service_text} has been received. We will contact you shortly to confirm your booking.');
        document.location.href = '/home';
       </script>
       ";
       
   } catch (Exception $e) {
       // Error message
       echo "
       <script> 
        alert('Sorry, there was an error sending your appointment request. Please try again or call us directly at 076 124 8895.');
        document.location.href = '/home';
       </script>
       ";
   }
} else {
   // If not a POST request, redirect to home
   header("Location: /home");
   exit;
}


?>
