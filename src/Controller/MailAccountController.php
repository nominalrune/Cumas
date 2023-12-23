<?php

namespace App\Controller;

use App\Entity\MailAccount;
use App\Form\MailAccountType;
use App\Repository\MailAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mail/account')]
class MailAccountController extends AbstractController
{
    #[Route('/', name: 'app_mail_account_index', methods: ['GET'])]
    public function index(MailAccountRepository $mailAccountRepository): Response
    {
        return $this->render('mail_account/index.html.twig', [
            'mail_accounts' => $mailAccountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mail_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mailAccount = new MailAccount();
        $form = $this->createForm(MailAccountType::class, $mailAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mailAccount);
            $entityManager->flush();

            return $this->redirectToRoute('app_mail_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mail_account/new.html.twig', [
            'mail_account' => $mailAccount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mail_account_show', methods: ['GET'])]
    public function show(MailAccount $mailAccount): Response
    {
        return $this->render('mail_account/show.html.twig', [
            'mail_account' => $mailAccount,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mail_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MailAccount $mailAccount, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MailAccountType::class, $mailAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mail_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mail_account/edit.html.twig', [
            'mail_account' => $mailAccount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mail_account_delete', methods: ['POST'])]
    public function delete(Request $request, MailAccount $mailAccount, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mailAccount->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mailAccount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mail_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
