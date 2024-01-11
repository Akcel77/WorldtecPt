<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class SearchController extends AbstractController
{
    #[Route('/search/results', name: 'search_results')]
    public function searchResults(Request $request, ArticleRepository $articleRepository): Response
    {
        $query = $request->query->get('q', '');
        $results = $articleRepository->searchByQuery($query);

        return $this->render('search/results.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}