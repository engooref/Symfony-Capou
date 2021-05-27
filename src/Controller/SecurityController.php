<?php

namespace App\Controller;

use \DateTime;
use App\Entity\Operateur;
use App\Entity\Groupe;
use App\Form\RegistrationFormType;
use App\Security\OperatorAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    
    
    
    #[Route('/createaccount', name: 'createaccount')]
    public function createAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, OperatorAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new Operateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // mot de passe par dï¿½faut lors de la crï¿½ation de compte
//             $nbChar = 8;
//             $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
//             srand((double)microtime()*1000000);
//             $password = '';
//             for($i=0; $i<$nbChar; $i++){
//                 $password .= $chaine[rand()%strlen($chaine)];
//             }
            $password = "capou";
            // on encode le mot de passe
            $encoded = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            if(!$entityManager->getRepository(Groupe::class)->findOneById('1')){
                $groupe = new Groupe();
                $entityManager->persist($groupe);
                $entityManager->flush();
            }
            $user->setIdGroupe($entityManager->getRepository(Groupe::class)->findOneById('1'));
            $user->setVerifiedbyadmin('0');
            $user->setIsFirstConnexion('1');
            $user->setCreatedAt(new DateTime());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $titreMail = "Lycée Capou - Demande de création de votre compte";
            $message = (new \Swift_Message($titreMail))
            ->setCharset('iso-8859-2')
            ->setFrom('inscription.lyceecapou@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'registration/demande_creation_email.html.twig'
                    ),
                'text/html'
                );
            try {
                $mailer->send($message);
                $this->addFlash('success', 'Votre demande de création de compte a bien été prise en compte, un administrateur va traiter votre demande.');
                return $this->redirectToRoute('login');
            } catch (TransportExceptionInterface $e) {
                throw new CustomUserMessageAuthenticationException("L'envoi du mail a echoué.");
            }
            
            //return $this->redirectToRoute('login');
        }
        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
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
