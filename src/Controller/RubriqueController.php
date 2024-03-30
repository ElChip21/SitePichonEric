<?php

namespace App\Controller;

use App\Entity\Rubrique;
use App\Form\RubriqueType;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rubrique')]
class RubriqueController extends AbstractController
{

    
    

    #[Route('/new', name: 'app_rubrique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
        $rubrique = new Rubrique();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
          
            $fileName = $imageService->copyImage("image", $this->getParameter("rubrique_image_directory"), $form);
            $rubrique->setImage($fileName);
            $entityManager->persist($rubrique);
            $entityManager->flush();
    
    
            $this->addFlash(
                'success',
                'Votre Rubrique a bien été ajouté'
            );
    
            
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
         
        }
    
       
    
        return $this->render('rubrique/new.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_rubrique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rubrique $rubrique, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle picture upload
            
        $fileName = $imageService->copyImage("image", $this->getParameter("rubrique_image_directory"), $form);
        $rubrique->setImage($fileName);
        $entityManager->persist($rubrique);
        $entityManager->flush();


        $this->addFlash(
            'success',
            'Votre rubrique a bien été modifiée'
        );

        
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    
          
        }
    
        return $this->render('rubrique/edit.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_rubrique_delete', methods: ['POST'])]
    public function delete(Request $request, Rubrique $rubrique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rubrique->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($rubrique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
