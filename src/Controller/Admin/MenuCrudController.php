<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuCrudController extends AbstractCrudController
{
    const MENU_PAGES = 0;
    const MENU_ARTICLES = 1;
    const MENU_LINKS = 2;
    const MENU_CATEGORIES = 3;

    public function __construct(private RequestStack $requestStack, private MenuRepository $menuRepository)
    {

    }


    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $subMenuIndex = $this->getSubMenuIndex();

        return $this->menuRepository->getIndexQueryBuilder($this->getFieldNameFromSubMenuIndex($subMenuIndex));
    }

    public function configureCrud(Crud $crud): Crud
    {
        $subMenuIndex = $this->getSubMenuIndex();
        $entityLabelInSingular = 'um menu';

        $entityLabelInPlural = match ($subMenuIndex){
            self::MENU_ARTICLES => 'Artigos',
            self::MENU_LINKS => 'Links personalizados',
            self::MENU_CATEGORIES => 'Categorias',
            default => 'Pagos'
        };

        return $crud
            ->setEntityLabelInSingular($entityLabelInSingular)
            ->setEntityLabelInPlural($entityLabelInPlural);
    }

    public function configureFields(string $pageName): iterable
    {
        $subMenuIndex = $this->getSubMenuIndex();

        yield TextField::new('name', 'Título da navegação');
        yield NumberField::new('menuOrder', 'Ordem');
        yield $this->getFieldFromSubMenuIndex($subMenuIndex)
            ->setRequired(true);
        yield BooleanField::new('isVisible', 'Visível');
        yield AssociationField::new('subMenus', 'Sub-menus');
    }

    public function getFieldNameFromSubMenuIndex(int $subMenuIndex){
        return match ($subMenuIndex){
            self::MENU_ARTICLES => 'article',
            self::MENU_LINKS => 'link',
            self::MENU_CATEGORIES => 'category',
            default => 'page'
        };
    }

    private function getFieldFromSubMenuIndex(int $subMenuIndex): AssociationField|TextField
    {
        $fieldName = $this->getFieldNameFromSubMenuIndex($subMenuIndex);

        return ($fieldName === 'link') ? TextField::new($fieldName, 'link') : AssociationField::new($fieldName);
    }

    private function getSubMenuIndex(): int
    {
        $query = $this->requestStack->getMainRequest()->query;

        if ($referer = $query->get('referrer')) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $query);

            return $query['submenuIndex'] ?? 0;
        }

        return $query->getInt('submenuIndex');
    }

}
