<?php

namespace App\Controller;

use App\Entity\Article;

use App\Form\ArticleType;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,CategoryRepository $categoryRepository, 
    PaginatorInterface $paginator, 
    Request $request ): Response
    {
        $filter = $request->query->get('filter');
        //Récupère le paramètre 'filter' de la requête
        
        // Récupère les événements selon le filtre, s'il est défini
        if ($filter === 'ASC') {
            $articles = $articleRepository->findBy([],
            ['date' => 'ASC']);
        } elseif ($filter === 'DESC') {
            $articles = $articleRepository->findBy([],
            ['date' => 'DESC']);
        } else {
            // Par défaut, affiche tous les événements
            $articles = $articleRepository->findAll();
        }

        // Pagination des événements
        $articles = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1), // numéro de page
            4 // nombre d'éléments par page
        );

        
        return $this->render('article/index.html.twig', [
            'articles' => $articles, 
            'filter'=> $filter
        ]);
    }


    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           /** @var UploadedFile $pictureFile */
           $pictureFile = $form->get('photo')->getData();

           if ($pictureFile) {
            $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

            try {
                $pictureFile->move(
                    $this->getParameter('article_picture_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception
            }

            $article->setPhoto($newFilename);
        } else {
            // Utiliser une image par défaut si aucune image n'est téléchargée
            $article->setPhoto('default.png');
        }

        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET','POST'])]
    public function show(Article $article): Response
    {
   

        return $this->render('article/show.html.twig', [
            'article'=>$article,
           
        ]);
    }

   
    

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $originalPicture = $article->getPhoto();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('article_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception
                }

                $article->setPhoto($newFilename);
            } else {
                // Utiliser l'image d'origine si aucune nouvelle image n'est téléchargée
                $article->setPhoto($originalPicture ?: 'default.png');
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/category/{id_category}', name: 'app_get_article_by_category', methods: ['GET'])]
    public function getArticleByCategory(EntityManagerInterface $entityManager, int $id_category): Response
    {
        $articles=$entityManager->getRepository(Article::class)->findBy(array('category'=>$id_category));
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    
}
