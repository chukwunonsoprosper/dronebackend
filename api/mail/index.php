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
$recaptchaToken = $_POST['recaptchaToken'] ?? '';

// Verify reCAPTCHA
$recaptchaSecret = '6LfEVNUqAAAAAJkS5Lkq-69R-6x6rWXp_XxjWCEj';
$recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

$response = file_get_contents($recaptchaVerifyUrl . "?secret=$recaptchaSecret&response=$recaptchaToken");
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
    echo json_encode(['error' => 'reCAPTCHA verification failed.']);
    exit;
}

if (empty($username) || empty($email) || empty($phone) || empty($company) || empty($message)) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

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
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .email-body {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
        .email-body p {
            margin: 10px 0;
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
    $mail->Password = 'rvyf xiig spjq gqcp'; // Ensure this is stored securely!
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($email, 'Drone.bj');
    $mail->addAddress('benindronelab@gmail.com', 'Admin');
    $mail->addReplyTo($email, $username);

    $mail->isHTML(true);
    $mail->Subject = "Mail from $username";
    $mail->Body = $mailRequest;

    if ($mail->send()) {
        echo json_encode(['message' => 'Email sent successfully!']);
    } else {
        echo json_encode(['error' => 'Failed to send email.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
?>
