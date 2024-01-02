<?php
namespace App\Command;

use App\Repository\ContactEmailRepository;
use App\Repository\InquiryRepository;
use App\Repository\MessageRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Mail\Receive;
use App\Entity\Message;
use App\Entity\Inquiry;
use App\Entity\Contact;
use App\Entity\ContactEmail;
use App\Repository\ContactRepository;
use App\Service\String\Crypt;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:check-mails')]
class CheckNewMailsCommand extends Command
{
    public function __construct(
        private ContactEmailRepository $contactEmailRepo,
        private ContactRepository $contactRepo,
        private InquiryRepository $inquiryRepo,
        private MessageRepository $messageRepo,
        private Receive $receiver,
        private Crypt $crypt,
    ) {
        parent::__construct('app:check-mails');
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) : int {
        $mails = $this->receiver->receiveAll();
        // 送信者の連絡先が登録されていなかったら作る
        foreach ($mails as $mail) {
            $contactEmail = $this->contactEmailRepo->findOneBy(['email' => $this->crypt->encrypt($mail->from)]);
            if (! $contactEmail) {
                $contact = new Contact();
                $contact->setName($mail->from);
                $this->contactRepo->save($contact);
                $contactEmail = new ContactEmail();
                $contactEmail->setEmail($mail->from);
                $contactEmail->setContact($contact);
                $this->contactEmailRepo->save($contactEmail);
            }
            $contact = $contactEmail->getContact();
            $inquiry = $this->messageRepo->findOneBy(['message_id' => $mail->references])?->getInquiry();
            if (! $inquiry) {
                $date = new \DateTimeImmutable();
                $inquiry = new Inquiry();
                $inquiry->setContactId($contact->id);
                $inquiry->setTitle($mail->subject);
                $inquiry->setCreatedAt($date);
                $inquiry->setUpdatedAt($date);
                $this->inquiryRepo->save($inquiry);
            }
            $file = $this->receiver->save($mail->uid . ".msg", $mail->body);
            $message = new Message();
            $message->setSenderType(0);
            $message->setSubject($mail->subject);
            $message->setInquiry($inquiry);
            $message->setMail($contactEmail);
            $message->setFile($file);
            $message->setMessageId($mail->message_id);
            $message->setReferenceId($mail->references);
            $this->messageRepo->save($message);
        }
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}