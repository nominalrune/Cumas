<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;
use App\Service\File\Savable;
use PHPMailer\PHPMailer\PHPMailer;
use App\Service\String\Crypt;
use App\Service\Log\Logger;
use Doctrine\ORM\EntityManagerInterface;

/**
 */
class Sender
{
	use Savable;
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
	 * @param ?\Symfony\Component\HttpFoundation\File\UploadedFile[] $attachments paths of attachments
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
		?array $attachments = null,
		?string $additional_headers,
		?string $additional_params,
		?string $referenceId,
		?bool $save = false,
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
			$name = $attachment->getClientOriginalName();
			$path = $attachment->getRealPath();
			$mailer->addAttachment($path, $name, 'base64', $attachment->getMimeType());
		}
		// $mailer->preSend();
		// send the message, check for errors
		if (! $mailer->send()) {
			throw new \Exception('Mailer Error: ' . $mailer->ErrorInfo);
		} else {
			$message = $mailer->getSentMIMEMessage();
			//FIXME
			$messageId = $mailer->MessageID;
			if($save){
				$this->save(getenv('STORAGE_PATH').'/mail/'.$messageId.".msg" , $message);
			}
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
