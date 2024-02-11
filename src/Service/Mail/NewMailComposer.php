<?php

namespace App\Service\Mail;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormInterface;

class NewMailComposer
{
    public function __construct(
        private Sender $sender,
    ) {
    }
    function send(FormInterface $form)
    {
        /** @var array{from:string,to:string,subject:string,body:string,attachments:string[]} $result  */
        $result = $this->composeFromForm($form);

        $this->sender->send(
            $result['from'],
            $result['to'],
            $result['subject'],
            $result['body'],
            attachments: $result['attachments'],
            save: true
        );
    }
    private function composeFromForm(FormInterface $form)
    {
        /** @var UploadedFile[] $attachments */
        $attachments = [];
        $attachmentFiles = $form->get('attachments')->getData();
        foreach ($attachmentFiles as $attachment) {
            try {
                $attachments[] = ($attachment->getContent());
                // $attachment->move(
                //     $this->getParameter('uploads_dir'),
                //     $newFilename
                // );

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            return [
                'from' => $form->get('from')->getData(),
                'to' => $form->get('to')->getData(),
                'subject' => $form->get('subject')->getData(),
                'attachments' => $attachments,
            ];
        }
    }
}