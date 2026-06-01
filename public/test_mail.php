<?php
// Place this file at: C:\xampp\htdocs\learning_management\public\test_mail.php
// Then visit: localhost/learning_management/public/test_mail.php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<pre>";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'echo';

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rogelioamoyan123@gmail.com';
    $mail->Password = 'yinf tvlf cnxs nfld';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('rogelioamoyan123@gmail.com', 'iLearnSystem');
    $mail->addAddress('rogelioamoyan123@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email from iLearn';
    $mail->Body = '<h1>Test working!</h1>';

    $mail->send();
    echo "</pre>";
    echo '<h2 style="color:green">✅ Email sent successfully! Check your inbox.</h2>';

} catch (Exception $e) {
    echo "</pre>";
    echo '<h2 style="color:red">❌ FAILED</h2>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>PHPMailer Info:</strong> ' . htmlspecialchars($mail->ErrorInfo) . '</p>';
}