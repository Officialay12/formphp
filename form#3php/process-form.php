<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);
    
    // Check that data was sent to the mailer
    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit
        http_response_code(400);
        header("Location: index.php?error=1");
        exit;
    }
    
    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Subject: $subject\n\n";
    $email_content .= "Message:\n$message\n";
    
    // Build the email headers
    $email_headers = "From: $name <$email>";
    
    // Send the email
    if (mail(EMAIL_RECIPIENT, EMAIL_SUBJECT, $email_content, $email_headers)) {
        // Set a 200 (okay) response code
        http_response_code(200);
        header("Location: index.php?success=1");
        exit;
    } else {
        // Set a 500 (internal server error) response code
        http_response_code(500);
        header("Location: index.php?error=1");
        exit;
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code
    http_response_code(403);
    header("Location: index.php?error=1");
    exit;
}
?>