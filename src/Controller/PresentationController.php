<?php

namespace App\Controller;

use App\Repository\PresentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PresentationController extends AbstractController
{
    #[Route('/presentation', name: 'app_presentation')]
    public function index(PresentationRepository $presentation): Response
    {
        $latestPresentation = $presentation->findOneBy([], ['id' => 'DESC']); // ici on récupère le dernier en base

        return $this->render('presentation/index.html.twig', [
            'latestPresentation' => $latestPresentation,
        ]);
    }
}
