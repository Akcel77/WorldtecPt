<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Menu;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ){

    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();

        return $this->redirect($url);


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Worldtecpt Admin Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Voltar ao site', 'fa fa-home', 'app_home');

        if($this->isGranted('ROLE_AUTHOR')){
            yield MenuItem::subMenu('Artigos', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('Todos os artigos', 'fas fa-newspaper' ,Article::class),
                MenuItem::linkToCrud('Adicionar', 'fas fa-plus' ,Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Categorias', 'fas fa-list' ,Category::class),
            ]);

            yield MenuItem::subMenu('Media', 'fas fa-photo-video')->setSubItems([
                MenuItem::linkToCrud('Mediateca', 'fas fa-photo-video' ,Media::class),
                MenuItem::linkToCrud('Adicionar', 'fas fa-plus' ,Media::class)->setAction(Crud::PAGE_NEW),
            ]);
        }


        if($this->isGranted('ROLE_ADMIN')){
            yield MenuItem::subMenu('Menus','fas fa-list')->setSubItems([
                MenuItem::linkToCrud('Páginas', 'fas fa-file' ,Menu::class)
                    ->setQueryParameter('submenuIndex', 0),
                MenuItem::linkToCrud('Artigos', 'fas fa-newspaper' ,Menu::class)
                    ->setQueryParameter('submenuIndex', 1),
                MenuItem::linkToCrud('Links personalizados', 'fas fa-link' ,Menu::class)
                    ->setQueryParameter('submenuIndex', 2),
                MenuItem::linkToCrud('Categorias', 'fab fa-delicious' ,Menu::class)
                    ->setQueryParameter('submenuIndex', 3),
            ]);

            yield MenuItem::subMenu('UserRec', 'fas fa-user')->setSubItems([
                MenuItem::linkToCrud('Todos os usuários', 'fas fa-user-friends' ,User::class),
                MenuItem::linkToCrud('Adicionar um usuário', 'fas fa-plus' ,User::class)->setAction(Crud::PAGE_NEW),
            ]);

            yield MenuItem::linkToCrud('Comentários', 'fas fa-comment' , Comment::class);
        }
    }
}
