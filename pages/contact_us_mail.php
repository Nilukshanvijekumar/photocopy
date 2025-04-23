<?php
    // My modifications to mailer script from:
    // http://blog.teamtreehouse.com/create-ajax-contact-form
    // Added input sanitizing to prevent injection

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $phone = trim($_POST["phone"]);
        $subject = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($phone) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo '<div class="alert alert-danger">Oops! There was a problem with your submission. Please complete the form and try again.</div>';
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $to = "info@globallinksolution.com";

		$message = "
		<html>
		<head>
			<title>New Contact Form Message</title>
			<style>
			
			</style>
		</head>
		<body>
			<table>
				<tr>
					<th>Name</th>
					<td>$name</td>
				</tr>
				<tr>
					<th>Email</th>
					<td>$email</td>
				</tr>
				<tr>
					<th>Phone</th>
					<td>$phone</td>
				</tr>
				<tr>
					<th>Message</th>
					<td>$message</td>
				</tr>
			</table>
		</body>
		</html>
		";
        // Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= "From: $name <$email>" . "\r\n";


        // Send the email.
        if (mail($to,$subject,$message,$headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo '<div class="alert alert-success">Thank You! Your message has been sent.</div>';
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo '<div class="alert alert-danger">Oops! Something went wrong and we couldn\'t send your message.</div>';
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo '<div class="alert alert-warning">There was a problem with your submission, please try again.</div>';
    }

?>
