<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;
use PHPMailer\PHPMailer\PHPMailer;

class Send
{
	public function __construct()
	{
		echo 'Receive';
	}
	function fetchAccounts(MailAccountRepository $repo)
	{
		$accounts= $repo->findBy(['active'=>true]);
		return $accounts;
	}
	public function send(
		string $to,
		string $subject,
		string $message,
		string $additional_headers = "",
		string $additional_params = null
	):string {
		// $headers=var_dump($additional_headers,true);
		// file_put_contents("headers.txt", '['.date('Y-m-dTh:m').']'.$headers.PHP_EOL, FILE_APPEND);
		$mailer = $this->setUp();
		//Set who the message is to be sent to
		$mailer->addAddress($to);
		$mailer->Subject = $subject;
		// $mailer->msgHTML($message);
		$mailer->Body = $message;
		// var_dump($mailer);

		//Attach an image file
		// $mailer->addAttachment('images/phpmailer_mini.png');
		// $mailer->preSend();
		//send the message, check for errors
		if (!$mailer->send()) {
			echo 'Mailer Error: ' . $mailer->ErrorInfo;
			throw new \Exception('Mailer Error: ' . $mailer->ErrorInfo);
		} else {
			echo 'Message sent!';
			$message = $mailer->getSentMIMEMessage();
			return $message;
		}
	}
	private function setUp(MailAccount $account)
	{
		$mailer = new PHPMailer();
		$mailer->isSMTP();
		// $mailer->SMTPDebug = SMTP::DEBUG_CONNECTION;
		$mailer->Host = $account->getHost();
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mailer->Port = $account->getPort();

		$mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mailer->SMTPAuth = true;
		$mailer->Username = $account->getUsername();
		$mailer->Password = $account->getPassword();
		$mailer->CharSet = "UTF-8";

		$mailer->setFrom($account->getUsername(), $account->getName());
		// $mailer->addReplyTo('replyto@example.com', 'First Last');
		return $mailer;
	}
}
