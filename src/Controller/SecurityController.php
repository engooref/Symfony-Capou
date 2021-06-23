<?php

namespace App\Controller;

use \DateTime;
use App\Entity\Operateur;
use App\Entity\Parcelle;
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
    
    
    
    #[Route('/creerCompte', name: 'createaccount')]
    public function createAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, OperatorAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new Operateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
//             mot de passe par défaut lors de la création de compte
            $nbChar = 8;
            $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
            srand((double)microtime()*1000000);
            $password = '';
            for($i=0; $i<$nbChar; $i++){
                $password .= $chaine[rand()%strlen($chaine)];
            }
//             $password = "capou";
            // on encode le mot de passe
            $encoded = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            if(!$entityManager->getRepository(Parcelle::class)->findOneById('1')){
                $parcelle = new Parcelle();
                $parcelle->setLabel("Parcelle par défaut");
                $entityManager->persist($parcelle);
                $entityManager->flush();
            }
            $user->setIdParcelle($entityManager->getRepository(Parcelle::class)->findOneById('1'));
            $user->setVerifiedbyadmin('0');
            $user->setIsFirstConnexion('1');
            $user->setCreatedAt(new DateTime());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $titreMail = "Lycée Capou - Votre compte a bien été crée";
            $message = (new \Swift_Message($titreMail))
            ->setCharset('UTF-8')
            ->setFrom('inscription.lyceecapou@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                '<h1>Bonjour ! Votre demande de création de compte a bien été prise en compte</h1>
            <br>
            <p>Informations de connexion pour vous connecter à votre compte :</p>
            <p><u>Identifiant :</u> <b>'. $user->getEmail() . '</b></p>
            <p><u>Mot de passe :</u> <b> '. $password . '</b></p>
            </p>
            <br>
            <p>Attention, vous avez reçu un mot de passe généré aléatoirement, toutefois, nous vous recommandons de le changer suite à votre première connexion sur notre site. Vous devez attendre qu\'un administrateur confirme votre demande pour pouvoir vous connecter.</p>
            <br>
            <p>Merci de votre compréhension. Cordialement !</p>',
                'text/html'
                );
            
            try {
                $mailer->send($message);
                $this->addFlash('success', 'Votre demande de création de compte a bien été prise en compte. Veuillez consulter vos mails.');
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
        $user = $this->getUser();
         if ($user) {
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
