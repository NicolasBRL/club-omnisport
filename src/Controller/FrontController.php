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
        return $this->render('front/home.html.twig', [
            'sports' => $sportRepository->findBy(array(), array('id' => 'DESC'), 8, 0),
            'equipes' => $equipeRepository->findBy(array(), array('id' => 'DESC'), 8, 0),
            'licencies' => $licencieRepository->findBy(array(), array('id' => 'DESC'), 8, 0),
        ]);
    }
}
