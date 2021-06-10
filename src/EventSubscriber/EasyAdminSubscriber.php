<?php

namespace App\EventSubscriber;

use \DateTime;
use App\Entity\Operateur;
use App\Entity\Piquet;
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
        
        
        $titreMail = "Lyc�e Capou - Votre compte a bien �t� cr�� par un administrateur";
        $message = (new \Swift_Message($titreMail))
        ->setCharset('ISO-8859-1')
        ->setFrom('inscription.lyceecapou@gmail.com')
        ->setTo($entity->getEmail())
        ->setBody(
            '<h1>Bonjour ! Un administrateur vous a cr�� un compte sur le site Lyc�e Capou - Irrigation Connect�e</h1>
            <br>
            <p>Informations de connexion pour vous connecter � votre compte :</p>
            <p><u>Identifiant :</u> <b>'. $entity->getEmail() . '</b></p>
            <p><u>Mot de passe :</u> <b> '. $password . '</b></p>
            </p>
            <br>
            <p>Attention, vous avez re�u un mot de passe g�n�r� al�atoirement, toutefois, nous vous recommandons de le changer suite � votre premi�re connexion sur notre site.</p>
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
        
        foreach($entity->getIdPiquets() as $idPiquet){
            $idPiquet->setIdGroupe($entity);
            $entity->addIdPiquet($idPiquet);
        }
    }
  
    public function updateGroupe(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance(); // On r�cup�re l'instance de l'entit�
        if (!($entity instanceof Groupe)) { // On test si l'entit� est bien une instance de Groupe
            return;
        }
        
        //dump($entity);die();    
        
        $defaultGroup = $this->doctrineManager->getRepository(Groupe::class)->findOneById('1'); // Le groupe consid�r� "par d�faut" est le groupe avec comme id=1
        
        //gestion des opérateurs
        $GroupAct = $this->doctrineManager->getRepository(Groupe::class)->findOneById($entity->getId()); // Le groupe actuel est l'id de l'entit�
        
        $operatorTab = $this->doctrineManager->getRepository(Operateur::class)->findBy(['idGroupe'=>$GroupAct->getId()]); 
        
        foreach($operatorTab as $Operator){ // Pour chaque op�rateurs contenus dans le groupe actuel, on les transfere dans le groupe par d�faut (1)
            $Operator->setIdGroupe($defaultGroup);
            $this->doctrineManager->flush();
        }
       
        foreach($entity->getIdOperateur() as $idOperateur){ // Pour chaque op�rateurs contenus dans l'entit�, on assigne aux nouveaux groupes les cl�s �trang�res
            $idOperateur->setIdGroupe($entity);
            $entity->addIdOperateur($idOperateur);
        }
        
        
        //gestion des piquets
        $piquetTab = $this->doctrineManager->getRepository(Piquet::class)->findBy(['idGroupe'=>$GroupAct->getId()]);
        
        foreach($piquetTab as $Piquet){ // Pour chaque op�rateurs contenus dans le groupe actuel, on les transfere dans le groupe par d�faut (1)
            $Piquet->setIdGroupe(null);
            $this->doctrineManager->flush();
        }
        
        foreach($entity->getIdPiquets() as $idPiquet){ // Pour chaque op�rateurs contenus dans l'entit�, on assigne aux nouveaux groupes les cl�s �trang�res
            $idPiquet->setIdGroupe($entity);
            $entity->addIdPiquet($idPiquet);
        }
    }
}