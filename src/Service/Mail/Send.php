<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;
use PHPMailer\PHPMailer\PHPMailer;
use App\Service\String\Crypt;
use Symfony\Component\Config\Resource\FileResource;
class Send
{
	public function __construct(
		private MailAccountRepository $repo,
		private Crypt $crypt,
	) {
	}
	private function fetchAccount(string $sender) : MailAccount
	{
		$account = $this->repo->findOneBy(
			['username' => $this->crypt->encrypt($sender)]
		);
		if (! $account) {
			throw new \Exception("No account found for $sender");
		}
		return $account;
	}
	/**
	 * @param FileResource[] $attachments attachments
	 */
	public function send(
		string $sender,
		string $to,
		string $subject,
		string $message = null,
		array $attachments = null,
		string $additional_headers = "",
		string $additional_params = null
	) : string {$account = $this->fetchAccount($sender);
		$mailer = $this->setUp($account);
		$mailer->addAddress($to);
		$mailer->Subject = $subject;
		$mailer->msgHTML($message); // TODO imageのinline化を活用したい
		// $mailer->Body = $plainMessage;
		//Attach an image file
		foreach ($attachments as $attachment){
			$mailer->addAttachment($attachment->getResource());
		}
		// $mailer->preSend();
		//send the message, check for errors
		if (! $mailer->send()) {
			throw new \Exception('Mailer Error: ' . $mailer->ErrorInfo);
		} else {
			$message = $mailer->getSentMIMEMessage();
			return $message;
		}
	}
	private function setUp(MailAccount $account)
	{
		$mailer = new PHPMailer();
		$mailer->isSMTP();
		// $mailer->SMTPDebug = SMTP::DEBUG_CONNECTION;
		$mailer->Host = $account->getSMTPServer();
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mailer->Port = $account->getSMTPPort();

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
