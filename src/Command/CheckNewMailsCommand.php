<?php
namespace App\Command;

use App\Entity\MailAccount;
use App\Repository\ContactItemRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\Log\Logger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Mail\Receiver;
use App\Entity\Message;
use App\Entity\Inquiry;
use App\Entity\Contact;
use App\Entity\ContactItem;
use App\Service\String\Crypt;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:check-mails')]
class CheckNewMailsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageRepository $messageRepo,
        private ContactItemRepository $contactItemRepo,
        private UserRepository $userRepo,
        private Receiver $receiver,
        private Crypt $crypt,
        private Logger $logger,
    ) {
        parent::__construct('app:check-mails');
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) : int {
        try {
            $this->logger->info("check email start.");
            $mailCollections = $this->receiver->receiveAll();

            foreach ($mailCollections as $collection) {
                $receiver = $collection['account'];
                foreach ($collection['mails'] as $mail) {
                    $this->enrollMail($mail, $receiver);
                }
            }

            $this->logger->info("check email end.");

            // (it's equivalent to returning int(0))
            return Command::SUCCESS;
        } catch (\Exception $e) {
            echo "error";
            $this->logger->error($e->getMessage());
            return Command::FAILURE;
        }
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID

    }

    private function enrollMail($mail, MailAccount $account)
    {
        /** @var array{address:string,name:string} $from  */
        $from = \mailparse_rfc822_parse_addresses($mail->from)[0];
        $contactItem = $this->getOrCreateContactItem($from);
        $inquiry = $this->getOrCreateInquiry($mail, $contactItem->getContact(), $account);

        $filePath = $this->receiver->save('mails/' . $mail->message_id . ".msg", $mail->file);
        $this->logger->info("new mail saved: {$filePath}");

        $message = $this->createMessage($mail, $contactItem, $inquiry, $filePath);
        return $message;
    }
    private function getOrCreateContactItem(array $from)
    {
        $contactItem = $this->contactItemRepo->findOneBy(['value' => $this->crypt->encrypt($from['address'])]);
        // 送信者の連絡先が登録されていなかったら作る
        if (! isset($contactItem)) {
            $contactItem = $this->createContactItem($from);
        }
        $contact = $contactItem->getContact();
        if (! isset($contact)) {
            throw new \Exception("contact not found.");
        }
        return $contactItem;
    }

    private function createContactItem(array $from)
    {
        $contact = new Contact();
        $name = $from['display'] ?? $from['address'];
        $contact->setName($name);
        $this->em->persist($contact);
        $this->em->flush();
        echo "created contact. name: {$name}", PHP_EOL;

        $contactItem = new ContactItem();
        $contactItem->setValue($from['address'])
            ->setContact($contact);
        $this->em->persist($contactItem);

        $this->em->flush();
        $this->logger->info("new contact email: {$contactItem->getValue()}");
        return $contactItem;
    }
    private function createMessage($mail, ContactItem $contactItem, Inquiry $inquiry, string $filePath)
    {
        echo "creating message: subject: {$mail->subject}, inquiry:" . json_encode($inquiry), PHP_EOL;
        $message = new Message();
        $message->setSenderType(0)
            ->setSubject($mail->subject)
            ->setInquiry($inquiry)
            ->setContact($contactItem)
            ->setFile($filePath)
            ->setMessageId($mail->message_id)
            ->setReferenceId(property_exists($mail, 'references') ? $mail->references : '');
        $this->em->persist($message);
        $this->em->flush();
        $this->logger->info("new message: {$message->getMessageId()}");
        return $message;
    }

    private function getOrCreateInquiry($mail, Contact $contact, MailAccount $account)
    {
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