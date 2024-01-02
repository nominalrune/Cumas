<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;
use App\Service\File\Save;

class Receive
{
    use Save;
    public function __construct(
        private MailAccountRepository $repo
    ) {
        echo 'Receive';
    }
    public function receiveAll()
    {
        $accounts = $this->fetchAccounts();
        return array_map( function ($account) {
            return $this->fetchMails($account);
        },$accounts);
    }
    private function fetchAccounts()
    {
        $accounts = $this->repo->findBy(['active' => true]);
        return $accounts;
    }
    /**
     * @return object{subject:string,from:string,to:string,date:string,message_id:string,references:string,in_reply_to:string,size:string,uid:string,msgno:string,recent:string,flagged:string,answered:string,deleted:string,seen:string,draft:string,udate:string} - received mail
     */
    private function fetchMails(MailAccount $account)
    {
        $server = '{' . $account->getHost() . ':993/imap/ssl/novalidate-cert}INBOX';
        $username = $account->getUsername();
        $password = $account->getPassword();
        $inbox = imap_open($server, $username, $password);

        if (! $inbox) {
            die('Failed to connect to the mail server: ' . imap_last_error());
        } // TODO accountのlastCheckedAtを元に受信するメールの期間を決める
        $sequence = imap_search($inbox, 'ON "' . date("j F Y") . '"', SE_UID);
        if (! $sequence || count($sequence) < 1) {
            die('no sequence nor new mails.');
        }
        $overviews = imap_fetch_overview($inbox, implode(",", $sequence), FT_UID);
        if (!$overviews) {
            die('no overviews.');
        }
        foreach ($overviews as $overview) {
            $body=imap_fetchbody($inbox, $overview->uid, '1', FT_UID);
            $overview->body = $body;
        }
        imap_close($inbox);
        return $overviews;
    }

}