<?php

namespace App\Controller;

use App\Entity\Operateur;
use App\Entity\Groupe;

use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\OperatorAuthenticator;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
        
    /* #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, OperatorAuthenticator $authenticator): Response
    {
        $user = new Operateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $entityManager = $this->getDoctrine()->getManager();
            
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                    )
                );
            
            if(!$entityManager->getRepository(Groupe::class)->findOneById('1')){
                $groupe = new Groupe();
                
                $entityManager->persist($groupe);
                $entityManager->flush();
            }
                
            $user->setIdGroupe($entityManager->getRepository(Groupe::class)->findOneById('1'));
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('login');
        }
        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    } */    
    
    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
   }

    #[Route("/logout", name: "logout")]
    public function logout()
    {
        throw new \LogicException();
    }
}
