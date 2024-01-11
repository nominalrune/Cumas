<?php
namespace App\Command;

use App\Entity\MailAccount;
use App\Repository\ContactEmailRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\Log\Logger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Mail\Receive;
use App\Entity\Message;
use App\Entity\Inquiry;
use App\Entity\Contact;
use App\Entity\ContactEmail;
use App\Service\String\Crypt;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:check-mails')]
class CheckNewMailsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageRepository $messageRepo,
        private ContactEmailRepository $contactEmailRepo,
        private UserRepository $userRepo,
        private Receive $receiver,
        private Crypt $crypt,
        private Logger $logger,
    ) {
        parent::__construct('app:check-mails');
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) : int {
        $this->logger->log("check email start.");
        $tuples = $this->receiver->receiveAll();

        foreach ($tuples as $tuple) {
            foreach ($tuple['mails'] as $mail) {
                $this->enrollMail($mail, $tuple['account']);
            }
        }
        
        $this->logger->log("check email end.");

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;
        // or return this if some error happened during the executio
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID

    }

    private function enrollMail($mail, MailAccount $account)
    {
        $from = \mailparse_rfc822_parse_addresses($mail->from)[0];
        $contactEmail = $this->contactEmailRepo->findOneBy(['email' => $this->crypt->encrypt($from["address"])]);
        
        // 送信者の連絡先が登録されていなかったら作る
        if (! isset($contactEmail)) {
            $contactEmail = $this->createContactEmail($from);
        }
        $contact = $contactEmail->getContact();
        if(! isset($contact)){
            throw new \Exception("contact not found.");
        }
        $inquiry = $this->getOrCreateInquiry($mail, $contact, $account);
        
        $filePath = $this->receiver->save('mails/' . $mail->message_id . ".msg", $mail->file);
        $this->logger->log("new mail saved: {$filePath}");

        $message = $this->createMessage($mail, $contactEmail, $inquiry, $filePath);
    }
    
    private function createMessage($mail, ContactEmail $contactEmail, Inquiry $inquiry, string $filePath)
    {
        echo "creating message: subject: {$mail->subject}, inquiry:".json_encode($inquiry), PHP_EOL;
        $message = new Message();
        $message->setSenderType(0)
            ->setSubject($mail->subject)
            ->setInquiry($inquiry)
            ->setMail($contactEmail)
            ->setFile($filePath)
            ->setMessageId($mail->message_id)
            ->setReferenceId(property_exists($mail, 'references') ? $mail->references : '');
        $this->em->persist($message);
        $this->em->flush();
        $this->logger->log("new message: {$message->getMessageId()}");
        return $message;
    }
    /**
     * @param array{display?:string,address:string} $from
     */
    private function createContactEmail(array $from){
        $contact = new Contact();
        $name = $from['display'] ?? $from['address'];
        $contact->setName($name);
        $contact->setNotes('');
        $this->em->persist($contact);
        $this->em->flush();
        echo "created contact. name: {$name}", PHP_EOL;

        $contactEmail = new ContactEmail();
        $contactEmail->setNotes('')
            ->setEmail($from['address'])
            ->setContact($contact);
        $this->em->persist($contactEmail);

        $this->em->flush();
        $this->logger->log("new contact email: {$contactEmail->getEmail()}");
        return $contactEmail;
    }
    
    private function getOrCreateInquiry($mail, Contact $contact, MailAccount $account){
        if (property_exists($mail, 'references')) {
            $inquiry = $this->messageRepo->findOneBy(['message_id' => $mail->references])?->getInquiry();
            echo "inquiry found by references: {$inquiry->getId()}", PHP_EOL;
        }
        if (! isset($inquiry)) {
            echo "inquiry not found by references. creating new one: contactId:{$contact->getId()}", PHP_EOL;
            $date = new \DateTimeImmutable();
            $inquiry = new Inquiry();
            $inquiry->setContact($contact)
                ->setTitle($mail->subject)
                ->setStatus('open')
                ->setNotes('')
                ->setDepartment($account->getGroup())
                ->setCreatedAt($date)
                ->setUpdatedAt($date);
            
            $this->em->persist($inquiry);
            $this->em->flush();
        }
        echo "inquiry created: {$inquiry->getId()}", PHP_EOL;
        return $inquiry;
    }
}