<?php

namespace App\Controller;

use App\Entity\Home;
use App\Form\HomeType;
use App\Repository\HomeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HomeRepository $homeRepository): Response
    {
        $latestHome = $homeRepository->findOneBy([], ['id' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'latestHome' => $latestHome,
        ]);
    }

    #[Route('/home/edit', name: 'app_edit_home')]
    public function edit(Request $request, Home $home, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
        $form = $this->createForm(HomeType::class, $home);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de l'image
            $fileName = $imageService->copyImage("image", $this->getParameter("home_image_directory"), $form);
            $home->setImage($fileName);
            $entityManager->persist($home);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre page d\'accueil a bien été modifiée'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/edit.html.twig', [
            'home' => $home,
            'form' => $form->createView(),
        ]);
    }
}
