<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Service\ArticleService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, ArticleService $articleService, CategoryRepository $categoryRepository): Response
    {
        $notification = null;
        $latestArticles = $articleService->getLastTenArticles();

        // Créez un objet Search pour le formulaire
        $searchForm = new Search();
        $form = $this->createForm(SearchType::class, $searchForm);
        $form->handleRequest($request);

        // Vérifiez si le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez l'objet Search pour filtrer les articles
            $articles = $articleService->getPaginatedArticles(null, $searchForm);
        } else {
            // Si aucune recherche n'est effectuée, utilisez la méthode par défaut
            $articles = $articleService->getPaginatedArticles();
        }

        // Créez un nouvel objet Search pour que le formulaire soit réinitialisé
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $topArticles = $articleService->getTopArticles();

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'latestArticles' => $latestArticles,
            'articles' => $articles,
            'notification' => $notification,
            'categories' => $categoryRepository->findAll(),
            'topArticles' => $topArticles,
        ]);
    }
}
