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
