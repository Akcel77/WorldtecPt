<?php

namespace App\Service;

use App\Classe\Search;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleService
{
    public function __construct(
        private RequestStack $requestStack,
        private ArticleRepository $articleRepository,
        private PaginatorInterface $paginator,
        private OptionService $optionService)
    {

    }

    public function getPaginatedArticles(?Category $category = null, ?Search $search = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = $this->optionService->getValue('blog_article_limit');

        if ($search && ($search->string || !empty($search->categories))) {
            $articlesQuery = $this->articleRepository->findWithSearch($search);
        } else {
            $articlesQuery = $this->articleRepository->findForPagination($category);
        }

        return $this->paginator->paginate($articlesQuery, $page, $limit);
    }

    /**
     * Get the last 10 articles published.
     *
     * @return array
     */
    public function getLastTenArticles(): array
    {
        return $this->articleRepository->findBy(
            [], // Critères
            ['createdAt' => 'DESC'], // Trier par date de publication en ordre décroissant
            10 // Limiter le nombre de résultats à 10
        );
    }

    /**
     * Get the last 5 articles published.
     *
     * @return array
     */
    public function getTopArticles(): array
    {
        return $this->articleRepository->findBy(
            [], // Critères
            ['createdAt' => 'DESC'], // Trier par date de publication en ordre décroissant
            7 // Limiter le nombre de résultats à 10
        );
    }
}