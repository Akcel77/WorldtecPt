<?php

namespace App\Twig;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    const ADMIN_NAMESPACE = 'App\Controller\Admin';

    public function __construct(private RouterInterface $router, private AdminUrlGenerator $adminUrlGenerator)
    {

    }

    public function getFilters(): array
    {
        return [
           new TwigFilter('menuLink', [$this, 'menuLink']),
        ];
    }

    public function getAdminUrl(string $controller, ?string $action = null): string
    {
        $adminUrlGenerator = $this->adminUrlGenerator
            ->setController(self::ADMIN_NAMESPACE . DIRECTORY_SEPARATOR . $controller);

        if($action){
            $adminUrlGenerator->setAction($action);
        }

        return $adminUrlGenerator->generateUrl();
    }


    public function getFunctions(): array
    {
        return [
            new TwigFunction('ea_gen_url', [$this, 'getAdminUrl']),
        ];
    }

    public function menuLink(Menu $menu): string
    {
        $article = $menu->getArticle();
        $category = $menu->getCategory();
        $page = $menu->getPage();

        $url = $menu->getLink() ?: '#';

        if ($url != '#'){
            return $url;
        }

        $name = null;
        $slug = null;

        if ($article){
            $name = 'article_show';
            $slug = $article->getSlug();
        } elseif ($category){
            $name = 'category_show';
            $slug = $category->getSlug();
        } elseif ($page){
            $name = 'page_show';
            $slug = $page->getSlug();
        }

        if ($name && $slug){
            return $this->router->generate($name, [
                'slug' => $slug
            ]);
        }

        return $url;
    }
}