<?php
include '../access/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$company = $_POST['company'] ?? '';
$message = $_POST['message'] ?? '';

if (empty($username && $email && $phone && $company && $message)) {
    echo json_encode(['error' => 'Info is required']);
} else {
    // HTML email body with a proper layout and styling
    $mailRequest = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .email-container {
                background-color: #ffffff;
                width: 100%;
                max-width: 500px;
                margin: 20px auto;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .email-header {
                background-color: #333333;

                color: #ffffff;
                text-align: center;
                padding: 10px;
                border-radius: 8px 8px 0 0;
            }
            .email-header h2 {
                margin: 0;
                font-size: 24px;
            }
            .email-body {
                margin-top: 20px;
                font-size: 16px;
                color: #333333;
            }
            .email-body p {
                margin: 10px 0;
            }
            .email-footer {
                margin-top: 30px;
                font-size: 14px;
                color: #888888;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <h2>Contact Form Submission</h2>
            </div>
            <div class='email-body'>
                <p><strong>Name:</strong> $username</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Company:</strong> $company</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            </div>
        </div>
    </body>
    </html>
    ";

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'benindronelab@gmail.com';
        $mail->Password = 'rvyf xiig spjq gqcp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($email, 'Drone.bj');
        $mail->addAddress('benindronelab@gmail.com', 'Admin');

        // Add CC for the company to reply to
        $mail->addReplyTo($email, $username);

        $mail->isHTML(true);
        $mail->Subject = "Mail from $username";
        $mail->Body    = $mailRequest;
        $mail->AltBody = 'Drone.bj';

        if ($mail->send()) {
            echo json_encode(['status' => true, 'message' => 'Mail sent successfully']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => 'Code not sent, try again']);
    }
}




