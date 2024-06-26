<?php

namespace App\Controller;

use App\Entity\Rubrique;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
          
            $fileName = $imageService->copyImage("image", $this->getParameter("service_image_directory"), $form);
            $service->setImage($fileName);
            $entityManager->persist($service);
            $entityManager->flush();
    
    
            $this->addFlash(
                'success',
                'Votre Service a bien été ajouté'
            );
    
            
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
         
        }
    
       
    
        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(ServiceRepository $serviceRepository, Rubrique $rubrique): Response
    {
        // Récupérer les services associés à la rubrique spécifique
        $services = $rubrique->getServices();
        
        // Déterminer le template à utiliser pour l'affichage
        $templateName = $services->count() > 0 ? $services[0]->getTemplateName() : 'show';
        
        return $this->render('service/template.html.twig', [
            'rubrique' => $rubrique,
            'services' => $services,
            'templateName' => $templateName,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
