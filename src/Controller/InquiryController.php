<?php

namespace App\Controller;

use App\Entity\Inquiry;
use App\Entity\Message;
use App\Form\InquiryType;
use App\Form\NewEmailType;
use App\Repository\InquiryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Mail\Sender;
use eXorus\PhpMimeMailParser\Parser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
#[Route('/inquiry')]
class InquiryController extends AbstractController
{
    #[Route('/', name: 'app_inquiry_index', methods: ['GET'])]
    public function index(InquiryRepository $inquiryRepository) : Response
    {
        return $this->render('inquiry/index.html.twig', [
            'inquiries' => $inquiryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_inquiry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $inquiry = new Inquiry();
        $form = $this->createForm(InquiryType::class, $inquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inquiry);
            $entityManager->flush();

            return $this->redirectToRoute('app_inquiry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inquiry/new.html.twig', [
            'inquiry' => $inquiry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inquiry_show', methods: ['GET'])]
    public function show(Inquiry $inquiry) : Response
    {
        return $this->render('inquiry/show.html.twig', [
            'inquiry' => $inquiry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inquiry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inquiry $inquiry, EntityManagerInterface $entityManager) : Response
    {
        $form = $this->createForm(InquiryType::class, $inquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_inquiry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inquiry/edit.html.twig', [
            'inquiry' => $inquiry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{messageId}/mail', name: 'app_inquiry_send_mail', methods: ['POST'])]
    public function mail(Request $request, Inquiry $inquiry, Message $message, EntityManagerInterface $entityManager, Sender $sender, Parser $parser) : Response
    {
        $form = $this->createForm(NewEmailType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // /** @var string[]*/
            // $attachments = array_map(fn($attachment)=>($attachment->getContent()->getRealPath()),$form->get('attachments')->getData());
            /** @var UploadedFile[] $attachments */
            $attachments = $form->get('attachments')->getData();
            
            $from = $form->get('from')->getData();
            $to = $form->get('to')->getData();
            $subject = $form->get('subject')->getData();
            $body = $form->get('body')->getData();
            
            $sentMail=$sender->send(
                $from,
                $to,
                $subject,
                $body,
                attachments:$attachments,
                save: true,
            );
            
            $message = new Message();
            $message->setInquiry($inquiry);
            $message->setSubject($subject);
            $message->setFile($sentMail);
            $message->setReferenceId($message->getMessageId());
            $message->setSenderType(1);
            $entityManager->persist($message);
            $entityManager->flush();
        }

        return $this->render('inquiry/show.html.twig', [
            'inquiry' => $inquiry,
        ]);
    }

    #[Route('/{id}', name: 'app_inquiry_delete', methods: ['POST'])]
    public function delete(Request $request, Inquiry $inquiry, EntityManagerInterface $entityManager) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $inquiry->getId(), $request->request->get('_token'))) {
            $entityManager->remove($inquiry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inquiry_index', [], Response::HTTP_SEE_OTHER);
    }
}
