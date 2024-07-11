<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function modify(Request $request,EntityManagerInterface $entityManager ): Response
    {

        $form=$this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $entityManager->persist($this->getUser());//inserer en base
                $entityManager->flush();//fermer la transaction executée par ma bdd

                $this->addFlash(
                    'succes',
                    'Votre profile a bien été mis à jour !'
                );
    
                return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('profile/modify-profile.html.twig', [
            'profileForm' => $form,
        ]);
    }




    #[Route('/profile/password/edit', name: 'app_profile_password_edit')]
    public function modifyPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {

        $user= $this->getUser();
        $form =$this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($passwordEncoder->isPasswordValid($user, $form['oldPassword']->getData())){ 

            if($form->isValid()){

                $newEncodedPassword = $passwordEncoder->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($newEncodedPassword);
                
                $entityManager->persist($user);//inserer en base
                $entityManager->flush();//fermer la transaction executée par ma bdd

                $this->addFlash(
                    'succes',
                    'Votre mot de passe a bien été mis à jour !'
                );
    
                return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
            }
        }else{
            $this->addFlash('message', 'La saisie de l\'ancien mot de passe est incorrecte');
        }
    }
        return $this->render('profile/modify-password.html.twig', [
            'PasswordForm' => $form,
        ]);
    }
    
#[Route("/profil/delete", name:"app_profile_delete")]
public function delete(EntityManagerInterface $entityManager): RedirectResponse
{
    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Supprimer l'utilisateur
    $entityManager->remove($user);
    $entityManager->flush();
    session_destroy();

    // Redirection vers une page de confirmation ou une autre page
    return $this->redirectToRoute('app_home'); // Redirige vers la page d'accueil par exemple
}
#[Route('/profile/orders', name: 'app_profile_orders')]
public function history(OrderRepository $orderRepository): Response
{
    // Récupérer toutes les commandes de l'utilisateur connecté
    $user = $this->getUser();
    $orders = $orderRepository->findBy(['user' => $user], ['date' => 'DESC']);

    return $this->render('profile/index.html.twig', [
        'orders' => $orders,
    ]);
}
}


