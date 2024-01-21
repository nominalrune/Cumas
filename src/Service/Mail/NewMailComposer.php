<?php

namespace App\Service\Mail;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class NewMailComposer
{
public function __construct(
	Sender $sender,
	){
	
}
	function _($form)
	{
		/** @var UploadedFile[] $attachments */
		$_attachments = $form->get('attachments')->getData();
		$attachments = [];
		foreach ($_attachments as $attachment) {
            // ...

            try {
				$attachment->getContent();
                // $attachment->move(
                //     $this->getParameter('uploads_dir'),
                //     $newFilename
                // );

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
                // Use the Sender service to send the email
                $this->sender->send(
                    $form->get('from')->getData(),
                    $form->get('to')->getData(),
                    $form->get('subject')->getData(),
                    $form->get('body')->getData(),
                    
                );
        }
	}
}