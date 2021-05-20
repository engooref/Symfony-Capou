<?php

namespace App\EventSubscriber;

use \DateTime;
use App\Entity\Operateur;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;
    private $mailer;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }
    
    
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['createUser'],
            //BeforeEntityUpdatedEvent::class => ['updateUser'],
        ];
    }
    


        
    public function createUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        
        if (!($entity instanceof Operateur)) {
            return;
        }

        /* G�n�ration de mot de passe */
        $password = $this->_generateRandomPassword();
        
        /* Cryptage de mot de passe */
        $entity->setPassword($this->passwordEncoder->encodePassword(
                $entity,
                $password
                ));


        //$entity->setPassword($password);
        //$entity->setPassword('Test');
        
        $entity->setVerifiedbyadmin(true);
        $entity->setIsFirstConnexion(true);
        $entity->setCreatedAt(new DateTime());
        
        
        $titreMail = "Lyc�e Capou - Votre compte a bien �tait cr�� par un administrateur";
        $message = (new \Swift_Message($titreMail))
        ->setCharset('ISO-8859-1')
        ->setFrom('inscription.lyceecapou@gmail.com')
        ->setTo($entity->getEmail())
        ->setBody(
            '<h1>Bonjour ! Un administrateur vous a cr�� un compte sur le site Lyc�e Capou - Irrigation Connect�e</h1>
            <br>
            <p>Vous avez donc re�u un email d�taillant les informations de connexion pour vous connecter � votre compte :</p>
            <p><u>Identifiant :</u> <b>'. $entity->getEmail() . '</b></p>
            <p><u>Mot de passe :</u> <b> '. $password . '</b></p>
            </p>
            <br>
            <p>Attention, vous avez re�u un mot de passe g�n�r� al�atoirement, il sera n�cessaire de le changer suite � votre premi�re connexion sur notre site.</p>
            <br>
            <p>Merci de votre compr�hension. Cordialement !</p>',
            'text/html'
            );
        
        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            throw new CustomUserMessageAuthenticationException("L'envoi du mail a echou�.");
        }
        
    }
    
    private function _generateRandomPassword() : ?string
    {
        // G�n�ration mot de passe crypt�
        $nbChar = 8;
        $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((double)microtime()*1000000);
        $generatedPassword = '';
        for($i=0; $i<$nbChar; $i++){
            $generatedPassword .= $chaine[rand()%strlen($chaine)];
        }
        return $generatedPassword;
    }
    
/*    
    public function updateUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        
        if (!($entity instanceof Operator)) {
            return;
        }
        
        $slug = $this->slugger->slugify($entity->getTitle());
        $entity->setSlug($slug);
    }
*/
}