<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Form\SportType;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/sport')]
class SportController extends AbstractController
{
    #[Route('/', name: 'app_sport_index', methods: ['GET'])]
    public function index(SportRepository $sportRepository): Response
    {
        return $this->render('sport/index.html.twig', [
            'sports' => $sportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sport_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        SportRepository $sportRepository,
        SluggerInterface $slugger
    ): Response
    {
        $sport = new Sport();
        $form = $this->createForm(SportType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageUrl')->getData(); 
            
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move( $this->getParameter('images_directory').'/sports/', $newFilename
                );
                $sport->setImageUrl($newFilename);
            }

            $sportRepository->save($sport, true);


            return $this->redirectToRoute('app_sport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sport/new.html.twig', [
            'sport' => $sport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sport_show', methods: ['GET'])]
    public function show(Sport $sport): Response
    {
        return $this->render('sport/show.html.twig', [
            'sport' => $sport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sport_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Sport $sport, 
        SportRepository $sportRepository,
        SluggerInterface $slugger
    ): Response
    {
        $form = $this->createForm(SportType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageUrl')->getData(); 
            if ($imageFile) {

                // Supprimer l'ancienne image
                $projectDir = $this->getParameter('kernel.project_dir');
                $fileSystem = new Filesystem();

                if($fileSystem->exists($projectDir.'/public/uploads/images/sports/'.$sport->getImageUrl())) {
                    $fileSystem->remove($projectDir.'/public/uploads/images/sports/'.$sport->getImageUrl());
                }

                // Créer la nouvelle image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move( $this->getParameter('images_directory').'/sports/', $newFilename
                );
                $sport->setImageUrl($newFilename);
            }

            $sportRepository->save($sport, true);

            return $this->redirectToRoute('app_sport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sport/edit.html.twig', [
            'sport' => $sport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sport_delete', methods: ['POST'])]
    public function delete(Request $request, Sport $sport, SportRepository $sportRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sport->getId(), $request->request->get('_token'))) {
            // Supprimer l'image
            $projectDir = $this->getParameter('kernel.project_dir');
            $fileSystem = new Filesystem();

            if($fileSystem->exists($projectDir.'/public/uploads/images/sports/'.$sport->getImageUrl())) {
                $fileSystem->remove($projectDir.'/public/uploads/images/sports/'.$sport->getImageUrl());
            }

            $sportRepository->remove($sport, true);
        }

        return $this->redirectToRoute('app_sport_index', [], Response::HTTP_SEE_OTHER);
    }
}
