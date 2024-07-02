<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $filter = $request->query->get('filter'); // Récupère le paramètre 'filter' de la requête
        
        // Récupère les événements selon le filtre, s'il est défini
        if ($filter === 'ASC') {
            $events = $eventRepository->findBy([], ['startAt' => 'ASC']);
        } elseif ($filter === 'DESC') {
            $events = $eventRepository->findBy([], ['startAt' => 'DESC']);
        } else {
            // Par défaut, affiche tous les événements
            $events = $eventRepository->findAll();
        }

        // Pagination des événements
        $events = $paginator->paginate(
            $events,
            $request->query->getInt('page', 1), // numéro de page
            4 // nombre d'éléments par page
        );

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'filter' => $filter
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('event_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception
                }

                $event->setPicture($newFilename);
            } else {
                // Utiliser une image par défaut si aucune image n'est téléchargée
                $event->setPicture('default.png');
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $originalPicture = $event->getPicture();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('event_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception
                }

                $event->setPicture($newFilename);
            } else {
                // Utiliser l'image d'origine si aucune nouvelle image n'est téléchargée
                $event->setPicture($originalPicture ?: 'default.png');
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/delete', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/category/{id_category}', name: 'app_get_event_by_category', methods: ['GET'])]
    public function getEventByCategory(EntityManagerInterface $entityManager, int $id_category): Response
    {
        $event=$entityManager->getRepository(Event::class)->findBy(array('category'=>$id_category));
        return $this->render('event/index.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/filter/{filter}', name: 'app_event_filter')]
    public function getEventByFilter(
    CategoryRepository $categoryRepository,
    EventRepository $eventRepository,
    Request $request,
    string $filter,
    SerializerInterface $serializer
    ): JsonResponse
    {
        $events = $eventRepository->findEventByFilter($filter);

        $eventsDatas = [];

        foreach ($events as $event) {

            $eventsData = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'city'=>$event->getCity(),
                'description' => $event->getDescription(),
                'picture' => $event->getPicture(),
                'startAt' => $event->getStartAt()->format('Y-m-d'),
                'endAt' => $event->getEndAt()->format('Y-m-d'),
                //'category_id' => $event->getCategory() ? $event->getCategory()->getId() : null,
                //'category_name' => $event->getCategory() ? $event->getCategory()->getTitle() : null,
            ];
        
            // Ajoutez le tableau simplifié de l'article au tableau des articles
            $eventsDatas[] = $eventsData;
        }
        
        // Utilisez JsonResponse pour retourner le tableau d'articles en JSON
        return new JsonResponse($eventsDatas);

    }

}
