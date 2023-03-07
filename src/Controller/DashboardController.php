<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\EquipeRepository;
use App\Repository\LicencieRepository;
use App\Repository\SportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(
        SportRepository $sportRepository,
        EquipeRepository $equipeRepository,
        LicencieRepository $licencieRepository,
        ContactRepository $contactRepository,
        ): Response
    {
        $sports = $sportRepository->findAll();
        $equipes = $equipeRepository->findAll();
        $licencies = $licencieRepository->findAll();
        $messages = $contactRepository->findAll();

        return $this->render('dashboard/index.html.twig', [
            'countSports' => count($sports),
            'countEquipes' => count($equipes),
            'countLicencies' => count($licencies),
            'countMessages' => count($messages),
        ]);
    }
}
