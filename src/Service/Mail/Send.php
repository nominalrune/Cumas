<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;
use PHPMailer\PHPMailer\PHPMailer;
use App\Service\String\Crypt;
use Symfony\Component\Config\Resource\FileResource;
use App\Service\Log\Logger;
use Doctrine\ORM\EntityManagerInterface;

/**
 */
class Send
{
	public function __construct(
		private MailAccountRepository $repo,
		private Crypt $crypt,
		private Logger $logger,
		private EntityManagerInterface $em,
	) {
	}
	/**
	 * @throws \Exception if no account found
	 */
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
	 * send a mail. 
	 * @param string $sender sender
	 * @param string $to to
	 * @param string $subject subject
	 * @param string $message message, which can be html or plain text
	 * @param ?string $plainMessage plain text message
	 * @param ?string[] $attachments paths of attachments
	 * @param ?array{string,string} $additional_headers
	 * @param ?string $additional_params
	 * @return string sent message, which includes complete headers and body.
	 */
	public function send(
		string $sender,
		string $to, // TODO 名前指定できるようにする？
		string $subject,
		string $message,
		?string $plainMessage = null,
		array $attachments = null,
		string $additional_headers = "",
		string $additional_params = null
	) : string {
		$account = $this->fetchAccount($sender);
		$mailer = $this->setUp($account);
		$mailer->addAddress($to);
		$mailer->Subject = $subject;
		$mailer->msgHTML($message); // TODO imageのinline化を活用したい
		if(isset($plainMessage)){
			$mailer->Body = $plainMessage;
		}
		if(isset($additional_headers)){
			foreach($additional_headers as $key => $value){
				$mailer->addCustomHeader($key, $value);
			}
		}
		// Attach files
		foreach ($attachments as $attachment) {
			$path = $attachment;
			$mailer->addAttachment($path, pathinfo($path,PATHINFO_FILENAME));
		}
		// $mailer->preSend();
		// send the message, check for errors
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
