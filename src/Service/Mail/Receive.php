<?php

namespace App\Service\Mail;

use App\Repository\MailAccountRepository;
use App\Entity\MailAccount;

class Receive
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
function fetchMails(MailAccount $account)
{
    $server = '{' . $account->getHost() . ':993/imap/ssl/novalidate-cert}INBOX';
    $username = $account->getUsername();
    $password = $account->getPassword();

    // Connect to the mail server
    $inbox = imap_open($server, $username, $password);

    if (!$inbox) {
        die('Failed to connect to the mail server: ' . imap_last_error());
    }// TODO accountのlastCheckedAtを元に受信するメールの期間を決める
    $sequence = imap_search($inbox, 'ON "' . date("j F Y") . '"', SE_UID);
    if (!$sequence || count($sequence)<1) {
        die('no sequence nor new mails.');
    }
    $overviews = imap_fetch_overview($inbox, implode(",", $sequence), FT_UID);
    // print_r($overviews);
    foreach($overviews as $view){
        print_r($view);
    }
    $newMailIds = array_map(fn($item) => $item->uid, array_filter($overviews, fn($item) => !$item->seen));
    if (count($newMailIds) < 1) {
        die('no new emails. (emails are all seen.)');
    } else {
        echo count($newMailIds) . ' new mails. ids:' . implode(",", $newMailIds) . PHP_EOL;
    }
	
    // imap_setflag_full($inbox, implode(",", $overviews), "\\Seen \$label1", SE_UID);
    imap_setflag_full($inbox, implode(",", $newMailIds), "\\Seen \$label1", SE_UID);
    print_r(imap_fetch_overview($inbox, implode(",", $newMailIds), FT_UID));
    imap_close($inbox);
}

}