<?php



namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DevisController extends AbstractController
{
    #[Route('/devis', name: 'app_devis')]
    
public function index(
    Request $request,
    EntityManagerInterface $entityManager,
    ValidatorInterface $validator,
    MailerInterface $mailer
): Response {

    $devis = new Devis();
    $form = $this->createForm(DevisType::class, $devis);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $DevisNumber = $devis->getId();
        $devis->setDate(new \DateTime);

        // Persist and flush the entity
        $entityManager->persist($devis);
        $entityManager->flush();

        // Générer le PDF du devis
        $pdfContent = $this->generatePdf($devis);

        // Envoyer l'e-mail avec le PDF en pièce jointe
        $email = (new TemplatedEmail())
            ->from($this->getParameter('app.mailAddress'))
            ->addTo($this->getParameter('app.mailAddress')) 
            ->addTo($devis->getEmail())
            ->subject('Votre devis')
            ->htmlTemplate('devis/email.html.twig')
            ->context([
                'devis' => $devis,
            ])
            ->attach($pdfContent, sprintf('devis-%s-EricPichon.pdf', $DevisNumber), 'application/pdf');

        $mailer->send($email);

        $this->addFlash(
            'success',
            'Votre devis a bien été envoyé'
        );

        return $this->redirectToRoute('app_devis');
    }

    return $this->render('devis/index.html.twig', [
        'form' => $form->createView(),
    ]);
}

    private function generatePdf(Devis $devis): string
    {
        // Générer le contenu HTML du PDF
        $html = $this->renderView('devis/pdf.html.twig', [
            'devis' => $devis,
        ]);

        // Options pour la génération du PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        // Initialisation de Dompdf
        $dompdf = new Dompdf($options);

        // Chargement du contenu HTML
        $dompdf->loadHtml($html);

        // Rendu du PDF
        $dompdf->render();

        // Récupération du contenu du PDF en tant que chaîne de caractères
        return $dompdf->output();
    }
}
