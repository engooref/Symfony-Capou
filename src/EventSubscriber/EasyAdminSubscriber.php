<?php

namespace App\EventSubscriber;

use \DateTime;
use App\Entity\Operateur;
use App\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatableMessage;


class EasyAdminSubscriber implements EventSubscriberInterface 
{
    private $passwordEncoder;
    private $mailer;
    private $doctrineManager;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, EntityManagerInterface $manager, SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->doctrineManager = $manager;
        $this->session = $session;
    }
    
    
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['createUser'],
            BeforeEntityPersistedEvent::class => ['createGroup'],
            BeforeEntityUpdatedEvent::class => ['updateGroupe'],
        ];
    }
    


        
    public function createUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        
        if (!($entity instanceof Operateur)) {
            return;
        }

        /* Génération de mot de passe */
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
        
        
        $titreMail = "Lycée Capou - Votre compte a bien été créé par un administrateur";
        $message = (new \Swift_Message($titreMail))
        ->setCharset('ISO-8859-1')
        ->setFrom('inscription.lyceecapou@gmail.com')
        ->setTo($entity->getEmail())
        ->setBody(
            '<h1>Bonjour ! Un administrateur vous a créé un compte sur le site Lycée Capou - Irrigation Connectée</h1>
            <br>
            <p>Informations de connexion pour vous connecter à votre compte :</p>
            <p><u>Identifiant :</u> <b>'. $entity->getEmail() . '</b></p>
            <p><u>Mot de passe :</u> <b> '. $password . '</b></p>
            </p>
            <br>
            <p>Attention, vous avez reçu un mot de passe généré aléatoirement, toutefois, nous vous recommandons de le changer suite à votre première connexion sur notre site.</p>
            <br>
            <p>Merci de votre compréhension. Cordialement !</p>',
            'text/html'
            );
        
        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            throw new CustomUserMessageAuthenticationException("L'envoi du mail a echoué.");
        }
        
    }
    
    private function _generateRandomPassword() : ?string
    {
        // Génération mot de passe crypté
        $nbChar = 8;
        $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((double)microtime()*1000000);
        $generatedPassword = '';
        for($i=0; $i<$nbChar; $i++){
            $generatedPassword .= $chaine[rand()%strlen($chaine)];
        }
        return $generatedPassword;
    }
    
    public function createGroup(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        
        if (!($entity instanceof Groupe)) {
            return;
        }
        foreach($entity->getIdOperateur() as $idOperateur){
            $idOperateur->setIdGroupe($entity);
            $entity->addIdOperateur($idOperateur);
        }
    }
  
    public function updateGroupe(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance(); // On récupère l'instance de l'entité
        
        if (!($entity instanceof Groupe)) { // On test si l'entité est bien une instance de Groupe
            return;
        }
   
        $defaultGroup = $this->doctrineManager->getRepository(Groupe::class)->findOneById('1'); // Le groupe considéré "par défaut" est le groupe avec comme id=1
        $GroupAct = $this->doctrineManager->getRepository(Groupe::class)->findOneById($entity->getId()); // Le groupe actuel est l'id de l'entité
        
        foreach($GroupAct->getIdOperateur() as $idOperateur){ // Pour chaque opérateurs contenus dans le groupe actuel, on les transfere dans le groupe par défaut (1)
            $idOperateur->setIdGroupe($defaultGroup);
        }
        foreach($entity->getIdOperateur() as $idOperateur){ // Pour chaque opérateurs contenus dans l'entité, on assigne aux nouveaux groupes les clés étrangères
            $idOperateur->getIdGroupe()->removeIdOperateur($idOperateur);
            $idOperateur->setIdGroupe($entity);
            $entity->addIdOperateur($idOperateur);
        }
    }

}