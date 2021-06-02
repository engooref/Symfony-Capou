<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ForgottenPasswordFormType;
use App\Repository\OperateurRepository;
use App\Entity\Operateur;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgottenPasswordController extends AbstractController
{
    #[Route('/motDePasseOublie', name: 'forgottenpassword')]
    public function forgottenPassword(Request $request,  OperateurRepository $users, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ForgottenPasswordFormType::class);
        
        // On traite le formulaire
        $form->handleRequest($request);
        
        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On r�cup�re les donn�es
            $donnees = $form->getData();
            
            // On cherche un utilisateur ayant cet e-mail
            $user = $users->findOneByEmail($donnees['email']);
            
            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
                
                // On retourne sur la page de connexion
                return $this->redirectToRoute('forgottenpassword');
            }
            
            // On g�n�re un token
            $token = $tokenGenerator->generateToken();
            
            // On essaie d'�crire le token en base de donn�es
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('login');
            }
            
            // On g�n�re l'URL de r�initialisation de mot de passe
            $url = $this->generateUrl('resetpassword', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            
            // On g�n�re l'e-mail
            $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('inscription.lyceecapou@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'registration/email.html.twig',
                    ['user'=>$user->getEmail(),'path'=>$url ]
                    ),
                'text/html'
                )
                ;
            
            // On envoie l'e-mail
            $mailer->send($message);
            
            // On cr�e le message flash de confirmation
            $this->addFlash('message', 'Merci de consulter vos mails. Un mail permettant de modifier votre mot de passe vient de vous être envoyé');
            
            // On redirige vers la page de login
            return $this->redirectToRoute('login');
        }
        return $this->render('forgotten_password/index.html.twig', [
            'current_menu' => 'active_forgotten_password',
            'forgottenPassForm' => $form->createView()
        ]);
    }
}
