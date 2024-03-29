<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;
use App\Service\File\Savable;
use App\Service\Log\Logger;
use Doctrine\ORM\EntityManagerInterface;
use eXorus\PhpMimeMailParser\Parser;

class Receiver
{
    use Savable;
    public function __construct(
        private MailAccountRepository $repo,
        private Logger $logger,
        private EntityManagerInterface $em,
    ) {
    }
    /**
     * check new mails with all active-marked mail accounts
     * @return array{account:MailAccount,mails:object{subject:string,from:string,to:string,date:string,message_id:string,references:string,in_reply_to:string,size:string,uid:string,msgno:string,recent:string,flagged:string,answered:string,deleted:string,seen:string,draft:string,udate:string}[]} - received mails
     */
    public function receiveAll()
    {
        $accounts = $this->fetchAccounts();
        return array_map(function ($account) {
            try {
                $mails = $this->fetchMails($account);
                $account->setLastCheckedAt(new \DateTimeImmutable());
                $this->em->persist($account);
                $this->em->flush();
                
                return ["account" => $account, "mails" => $mails];
            } catch (\Exception $e) {
                $this->logger->error($e);
            }
        }, $accounts);
    }
    
    private function fetchAccounts()
    {
        $accounts = $this->repo->findBy(['active' => true]);
        return $accounts;
    }
    /**
     * @return array{object{subject:string,from:string,to:string,date:string,message_id:string,references:string,in_reply_to:string,size:string,uid:string,msgno:string,recent:string,flagged:string,answered:string,deleted:string,seen:string,draft:string,udate:string}} - received mail
     */
    private function fetchMails(MailAccount $account)
    {
        echo "[" . date("Y-m-d h:i:s") . "] " . "fetching mails from {$account->getName()}...", PHP_EOL;
        $server = "{{$account->getPOPServer()}:{$account->getPOPPort()}/pop3/ssl/novalidate-cert}INBOX";
        $username = $account->getUsername();
        $password = $account->getPassword();
        $inbox = \imap_open($server, $username, $password);

        if (! $inbox) {
            die('Failed to connect to the mail server: ' . imap_last_error());
        } // TODO accountのlastCheckedAtを元に受信するメールの期間を決める
        echo "[" . date("Y-m-d h:i:s") . "] " . "opened inbox.", PHP_EOL;

        $sequence = \imap_search($inbox, 'ON "' . date("j F Y") . '"', SE_UID);
        if (! $sequence || count($sequence) < 1) {
            die('no sequence nor new mails.');
        }
        $overviews = \imap_fetch_overview($inbox, implode(",", $sequence), FT_UID);
        if (! $overviews) {
            die('no overviews.');
        }

        echo "[" . date("Y-m-d h:i:s") . "] " . count($overviews) . " mails fetched.", PHP_EOL;
        echo "[" . date("Y-m-d h:i:s") . "] " . "fetching bodies...", PHP_EOL;
        foreach ($overviews as $overview) {
            // echo "[" . date("Y-m-d h:i:s") . "] " . "fetching body of {$overview->uid}...", PHP_EOL;
            $mailFile = $this->fetchWholeMail($overview->uid, $inbox);
            $overview->file = $mailFile;
            $overview->message_id = str_replace(["<", ">"], "", $overview->message_id);
            $overview->reference_id = str_replace(["<", ">"], "", $overview->reference_id);
            $parser = new Parser();
            $parser->setText($mailFile);
            $overview->subject = (string)$parser->getHeader('subject');
        }
        \imap_close($inbox);
        echo "[" . date("Y-m-d h:i:s") . "] " . "closed inbox.", PHP_EOL;
        return $overviews;
    }
    private function fetchWholeMail($uid, $inbox)
    {
        $header = \imap_fetchheader($inbox, $uid, FT_UID);
        $body = str_replace('This is a multi-part message in MIME format.','',\imap_body($inbox, $uid, FT_UID));
        $mail = $header . "\n\n" . $body;
        return $mail;
    }

}