<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
#[IsGranted("ROLE_ADMINISTRATOR")]
class AdminController extends AbstractDashboardController
{
    #[Route("/", name: "home")]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::linkToCrud('Utilisateur', "", User::class),

            MenuItem::section("Blog"),
            MenuItem::linkToCrud('Category', "", Category::class),
            MenuItem::linkToCrud('Article', "", Article::class),
            MenuItem::linkToCrud('Comment', "", Comment::class),
        ];
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('bundles/easyadmin/app.css');
    }
}
