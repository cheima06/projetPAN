<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository, 
    CategoryRepository $categoryRepository, 
    PaginatorInterface $paginator, 
    Request $request): Response
    {
        $articles = $paginator->paginate(
            $articleRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/

        );

        return $this->render('home/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'articles'=>$articles,
        ]);
    }

    #[Route('/search_article', name: 'app_search_articles', methods: ['GET'])]
    public function getArticleBySearch(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        //si j'ai un parametre GET search
        if($request->query->has("search")){

        $search=strtolower($request->query->get("search"));
        

        $articles = $paginator->paginate(
            $articles=$articleRepository->findArticlesBySearch($search),
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/

        );

            return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);

        } else {
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

    }


    #[Route('/search_event', name: 'app_search_event', methods: ['GET'])]
    public function getEventBySearch(EventRepository $eventRepository, PaginatorInterface $paginator, Request $request): Response
    {
        //si j'ai un parametre GET search
        if($request->query->has("search")){
            //récupère la valeur en miniscule du paramètre search de la chaîne de requête GET. 
        $search=strtolower($request->query->get("search"));
        

        $events = $paginator->paginate(
            $events=$eventRepository->findEventsBySearch($search),
            $request->query->getInt('page', 1), /*numero de la premiere page*/
            4 /*nombre d'évenement par page*/

        );

            return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);

        } else {
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

    }

    
}
