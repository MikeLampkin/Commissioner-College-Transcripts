<?php  // ** CommissionerCollege Lampkin 2024 ** // ?>
<?php // ** CONFIG 8.0 ** // ?>

<?php
	include_once "config/config.php";
	$today_date   = date('m/d/Y - h:i A');
	$mailme_dump  = '<h4> MailMe Dump ' . $today_date . '</h4>';
// REQUIRED
// *********************
// $subject
// $message

// $replyto_name
// $replyto_address

// $recipient_name
// $recipient_address

// OPTIONAL
// *********************

// $cc_name
// $cc_address

// $bcc_name
// $bcc_address

	$post_email_response = 'error';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	// Load Composer's autoloader
	require_once $aws_autoload;

	if ( !isset($subject) ) 		{ $subject 			= '[Contact Us ' . $today_date; }


	// Is the OS Windows or Mac or Linux
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
	  $eol="\r\n";
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
	  $eol="\r";
	} else {
	  $eol="\n";
	}

	// HTML Version
	$message_html  	= '<html><body>' . $eol;
	$message_html  .= $message . $eol;
	$message_html  .= '</body></html>' . $eol . $eol;

	$message_text 	= strip_tags($message);

	// TEXT Version
	$message_text   = '' .  $eol;
	$message_text  .= $message_text .  $eol;
	$message_text  .= '' . $eol . $eol;

	$attachment 		= '';
	$attachment_name 	= '';

	$mailme_dump .= '<h5> Message:</h5><div class="alert alert-secondary"><pre>' . $message . '</pre></div><br />';

	// $mail			= new PHPMailer\PHPMailer\PHPMailer();
	$mail 			= new PHPMailer(true);

	// SEND THE EMAIL
	try
	{
		$mail->isSMTP();
		// Specify the SMTP settings.
		// debugging: 1 = errors and messages, 2 = messages only, 0 = off (for production use
		$mail->SMTPDebug    = 0;
		$mail->Host 		= $config_smtp_host;
		$mail->SMTPAuth     = true; // authentication enabled
		$mail->Username 	= $config_smtp_username;
		$mail->Password 	= $config_smtp_password;
		$mail->SMTPSecure   = 'tls';
		$mail->Port 		= $config_smtp_port;

		$mailme_dump .= 'config_smtp_username: ' . $config_smtp_username . '<br />';
		$mailme_dump .= 'config_smtp_password: ' . $config_smtp_password . '<br />';
		$mailme_dump .= 'config_smtp_host: ' . $config_smtp_host . '<br />';
		$mailme_dump .= 'config_smtp_port: ' . $config_smtp_port . '<br />';

		//Set who the message is to be sent from
		$mail->setFrom($config_sender_address, $config_sender_name);

		//Set an alternative reply-to address
		$mail->addReplyTo($replyto_address, $replyto_name);

		$mailme_dump .= 'config_sender_name: ' . $config_sender_name . '<br />';
		$mailme_dump .= 'config_sender_address: ' . $config_sender_address . '<br />';

		$mailme_dump .= 'replyto_name: ' . $replyto_name . '<br />';
		$mailme_dump .= 'replyto_address: ' . $replyto_address . '<br />';
		//! MULTIPLE recipient_address
		if ( isset($recipient_array) )
		{
			foreach ( $recipient_array AS $recipient_address => $recipient_name )
			{
				if( !empty($recipient_address) )
				{
					$mailme_dump .= 'recipient_name: ' . $recipient_name . '<br />';
					$mailme_dump .= 'recipient_address: ' . $recipient_address . '<br />';
					//Set to address
					$mail->addAddress($recipient_address, $recipient_name);
				}
			}
		} else {
			$mailme_dump .= 'recipient_name: ' . $recipient_name . '<br />';
			$mailme_dump .= 'recipient_address: ' . $recipient_address . '<br />';
			//Set to address
			$mail->addAddress($recipient_address, $recipient_name);
		}

		//! MULTIPLE cc_address
		if ( isset($cc_array) )
		{
			foreach ( $cc_array AS $cc_address => $cc_name )
			{
				if( !empty($cc_address) )
				{
					$mailme_dump .= 'cc_name: ' . $cc_name . '<br />';
					$mailme_dump .= 'cc_address: ' . $cc_address . '<br />';
					//Set CC address
					$mail->addCC($cc_address, $cc_name);
				}
			}
		} else {
			if( !empty($cc_address) )
			{
				$mailme_dump .= 'cc_name: ' . $cc_name . '<br />';
				$mailme_dump .= 'cc_address: ' . $cc_address . '<br />';
				//Set CC address
				$mail->addBCC($cc_address, $cc_name);
			}
		}

		//! MULTIPLE bcc_address
		if ( isset($bcc_array) )
		{
			foreach ( $bcc_array AS $bcc_address => $bcc_name )
			{
				if( !empty($bcc_address) )
				{
					$mailme_dump .= 'BCC: ' . $bcc_name . ' ' . $bcc_address . '<br />';
					//Set BCC address
					$mail->addBCC($bcc_address, $bcc_name);
				}
			}
		} else {
			if( !empty($bcc_address) )
			{
				$mailme_dump .= 'BCC: ' . $bcc_name . ' ' . $bcc_address . '<br />';
				//Set BCC address
				$mail->addBCC($bcc_address, $bcc_name);
			}
		}


		$configurationSet = '';
		// $headers .= "Return-Path: ".($config_sender_address) . "\r\n";;
		// $headers .= "MIME-Version: 1.0\r\n";
		// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		// $headers .= "X-Priority: 3\r\n";
		// $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

		$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

		// You can also add CC, BCC, and additional To recipients here.

		// Specify the content of the message.
		$mail->IsHTML(true);
		$mail->Subject 		= $subject;
		$mail->Body 		= $message_html;
		$mail->AltBody 		= $message_text;
		$mail->Send();

		$post_email_response = 'success';
	}

	catch (Exception $mail)
	{
		$post_email_response = 'error|' . $mail->errorMessage(); //Catch errors from Amazon SES.
	}

		$mailme_dump .= $post_email_response;
?>
