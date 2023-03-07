<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\Contact1Type;
use App\Repository\ContactRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/contact')]
#[IsGranted('ROLE_USER')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_index', methods: ['GET', 'POST'])]
    public function index(
        ContactRepository $contactRepository,
        Request $request,
    ): Response
    {
        if($request->get('mark-as-read')){
            $contact = $contactRepository->find($request->get('id'));
            if ($this->isCsrfTokenValid('index'.$contact->getId(), $request->request->get('_token'))) {
                $contact->setReaded(!$contact->isReaded());
                $contactRepository->save($contact, true);

                return $this->redirectToRoute(('app_contact_index'));
            }
        }

        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(
        Contact $contact,
        ContactRepository $contactRepository
    ): Response
    {
        if(!$contact->isReaded()){
            $contact->setReaded(true);
            $contactRepository->save($contact, true);
        }

        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $contactRepository->remove($contact, true);
        }

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
