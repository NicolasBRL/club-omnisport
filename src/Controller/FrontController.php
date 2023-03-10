<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\EquipeRepository;
use App\Repository\LicencieRepository;
use App\Repository\SportRepository;
use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FrontController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        SportRepository $sportRepository,
        EquipeRepository $equipeRepository,
        LicencieRepository $licencieRepository,
        ContactRepository $contactRepository,
        Request $request,
        SendMailService $mailer,
        ): Response
    {
        $sports = $sportRepository->findAll();
        $equipes = $equipeRepository->findAll();
        $licencies = $licencieRepository->findAll();

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $contactRepository->save($contact, true);

            $contactFormData = [
                'nom' => $contact->getNom(),
                'email' => $contact->getEmail(),
                'content' => $contact->getContent(),
                'sujet' => $contact->getSujet(),
                'urlToMessage' => $this->generateUrl('app_contact_index', ['id' => $contact->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            ];

            $subject = 'Nouvelle demande de contact sur le site clubomnisport.fr';
            $mailer->send(to: 'contact@clubomnisport.fr', subject: $subject, template: 'contact', context: compact('contactFormData'));

            $this->addFlash('success', 'Votre message a été envoyé');
            return $this->redirectToRoute('home', ['_fragment' => 'contact']);
        }

        return $this->render('front/home.html.twig', [
            'chiffres' => [
                'sport' => count($sports),
                'equipe' => count($equipes),
                'licencie' => count($licencies),
            ],
            'sports' => $sports,
            'equipes' => $equipes,
            'licencies' => $licencies,
            'contactForm' => $this->createForm(ContactType::class)->createView()
        ]);
    }

    #[Route('/activites', name: 'activites')]
    public function activites_index(
        SportRepository $sportRepository,
    ): Response
    {
        return $this->render('front/activites.html.twig', [
            'sports' => $sportRepository->findAll()
        ]);
    }

    #[Route('/equipes', name: 'equipes')]
    public function equipes_index(
        EquipeRepository $equipeRepository,
    ): Response
    {
        return $this->render('front/equipes.html.twig', [
            'equipes' => $equipeRepository->findBy([], ['id' => 'ASC'])
        ]);
    }
}
