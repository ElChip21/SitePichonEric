<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Devis;
use App\Entity\Rubrique;
use App\Entity\Service;
use App\Entity\Temoignage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ServiceCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Titre Pro Afpa');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Mes Rubriques', 'fas fa-list', Rubrique::class);
        yield MenuItem::linkToCrud('Mes Services', 'fas fa-list', Service::class);
        yield MenuItem::linkToCrud('Mes contacts', 'fas fa-list', Contact::class);
        yield MenuItem::linkToCrud('Mes Témoignages', 'fas fa-list', Temoignage::class);
        yield MenuItem::linkToCrud('Devis', 'fas fa-list', Devis::class);

    }
}
