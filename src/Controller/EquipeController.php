<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/equipes')]
#[IsGranted('ROLE_USER')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository): Response
    {
        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EquipeRepository $equipeRepository,
        SluggerInterface $slugger
    ): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageUrl')->getData(); 
            
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move( $this->getParameter('images_directory').'/equipes/', $newFilename);
                $equipe->setImageUrl($newFilename);
            }

            $equipeRepository->save($equipe, true);

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe): Response
    {
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Equipe $equipe, 
        EquipeRepository $equipeRepository,
        SluggerInterface $slugger
    ): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageUrl')->getData(); 
            if ($imageFile) {

                // Supprimer l'ancienne image
                $projectDir = $this->getParameter('kernel.project_dir');
                $fileSystem = new Filesystem();

                if($fileSystem->exists($projectDir.'/public/uploads/images/equipes/'.$equipe->getImageUrl()) && $equipe->getImageUrl()) {
                    $fileSystem->remove($projectDir.'/public/uploads/images/equipes/'.$equipe->getImageUrl());
                }

                // Créer la nouvelle image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move( $this->getParameter('images_directory').'/equipes/', $newFilename
                );
                $equipe->setImageUrl($newFilename);
            }

            $equipeRepository->save($equipe, true);

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            // Supprimer l'image
            $projectDir = $this->getParameter('kernel.project_dir');
            $fileSystem = new Filesystem();

            if($fileSystem->exists($projectDir.'/public/uploads/images/equipes/'.$equipe->getImageUrl()) && $equipe->getImageUrl()) {
                $fileSystem->remove($projectDir.'/public/uploads/images/equipes/'.$equipe->getImageUrl());
            }
            $equipeRepository->remove($equipe, true);
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
