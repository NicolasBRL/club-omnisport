<?php

namespace App\Controller;

use App\Entity\Licencie;
use App\Form\LicencieType;
use App\Repository\LicencieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/licencie')]
#[IsGranted('ROLE_USER')]
class LicencieController extends AbstractController
{
    #[Route('/', name: 'app_licencie_index', methods: ['GET'])]
    public function index(LicencieRepository $licencieRepository): Response
    {
        return $this->render('licencie/index.html.twig', [
            'licencies' => $licencieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_licencie_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        LicencieRepository $licencieRepository,
        SluggerInterface $slugger): Response
    {
        $licencie = new Licencie();
        $form = $this->createForm(LicencieType::class, $licencie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageUrl')->getData(); 
            
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move( $this->getParameter('images_directory').'/licencies/', $newFilename
                );
                $licencie->setImageUrl($newFilename);
            }

            $licencieRepository->save($licencie, true);

            return $this->redirectToRoute('app_licencie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('licencie/new.html.twig', [
            'licencie' => $licencie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_licencie_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Licencie $licencie, 
        LicencieRepository $licencieRepository,
        SluggerInterface $slugger
    ): Response
    {
        $form = $this->createForm(LicencieType::class, $licencie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageUrl')->getData(); 
            if ($imageFile) {

                // Supprimer l'ancienne image
                $projectDir = $this->getParameter('kernel.project_dir');
                $fileSystem = new Filesystem();

                if($fileSystem->exists($projectDir.'/public/uploads/images/licencies/'.$licencie->getImageUrl()) && $licencie->getImageUrl()) {
                    $fileSystem->remove($projectDir.'/public/uploads/images/licencies/'.$licencie->getImageUrl());
                }

                // Créer la nouvelle image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move( $this->getParameter('images_directory').'/licencies/', $newFilename
                );
                $licencie->setImageUrl($newFilename);
            }

            $licencieRepository->save($licencie, true);

            return $this->redirectToRoute('app_licencie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('licencie/edit.html.twig', [
            'licencie' => $licencie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_licencie_delete', methods: ['POST'])]
    public function delete(Request $request, Licencie $licencie, LicencieRepository $licencieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$licencie->getId(), $request->request->get('_token'))) {
            // Supprimer l'image
            $projectDir = $this->getParameter('kernel.project_dir');
            $fileSystem = new Filesystem();

            if($fileSystem->exists($projectDir.'/public/uploads/images/licencies/'.$licencie->getImageUrl()) && $licencie->getImageUrl()) {
                $fileSystem->remove($projectDir.'/public/uploads/images/licencies/'.$licencie->getImageUrl());
            }

            $licencieRepository->remove($licencie, true);
        }

        return $this->redirectToRoute('app_licencie_index', [], Response::HTTP_SEE_OTHER);
    }
}
