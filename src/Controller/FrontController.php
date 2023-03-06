<?php

namespace App\Controller;

use App\Repository\EquipeRepository;
use App\Repository\LicencieRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        SportRepository $sportRepository,
        EquipeRepository $equipeRepository,
        LicencieRepository $licencieRepository,
        ): Response
    {
        $sports = $sportRepository->findAll();
        $equipes = $equipeRepository->findAll();
        $licencies = $licencieRepository->findAll();

        return $this->render('front/home.html.twig', [
            'chiffres' => [
                'sport' => count($sports),
                'equipe' => count($equipes),
                'licencie' => count($licencies),
            ],
            'sports' => $sports,
            'equipes' => $equipes,
            'licencies' => $licencies,
            
        ]);
    }
}
