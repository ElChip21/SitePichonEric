<?php

namespace App\Controller;

use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavController extends AbstractController
{

// Controller de ma nav pour l'afficher sur toutes mes pages avec ma boucle for pour mettre le nom de mes rubriques

    #[Route('/nav', name: 'app_nav')]
    public function index(RubriqueRepository $rubriqueRepository): Response
    {
        $rubriques = $rubriqueRepository->findAll();

        return $this->render('nav.html.twig', [
            'rubriques' => $rubriques,
        ]);
    }
}
